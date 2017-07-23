<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campaign;
use App\Invitation;
use App\Notifications\CampaignInvited;
use App\Supporter;
use Digitick\Sepa\TransferFile\Factory\TransferFileFacadeFactory;
use Digitick\Sepa\PaymentInformation;
use Carbon\Carbon;
use Shapecode\Bundle\IbanBundle\Iban\IbanGenerator;
use Shapecode\Bundle\IbanBundle\Iban\IbanApiDeApi;

class CampaignController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function sepa(Request $request, $id){

      //get all supporter for given campaign
      $campaign = Campaign::find($id);
      $supporters = Supporter::where([
        ['campaign_id', $campaign->id],
        ['beitrag', '>', 0]
        ])->get();

      //Set the initial information
      $directDebit = TransferFileFacadeFactory::createDirectDebit(
        'Transition Regensburg', $campaign->name
      );
      // create a payment, it's possible to create multiple payments,
      // "firstPayment" is the identifier for the transactions
      $transitionIban = 'DE68430609678217364100';
      $ibanValidator = new IbanGenerator(new IbanApiDeApi());
      $bic = $ibanValidator->generateBic($transitionIban);

      if($bic == "" || !$ibanValidator->validateIban($transitionIban)){
        return "BIC is Empty or IBAN incorrect";
      }
      $directDebit->addPaymentInfo('patenschaften', array(
          'id'                    => 'patenschaften',
          'creditorName'          => 'Transition Regensburg e.V.',
          'creditorAccountIBAN'   => $transitionIban,
          'creditorAgentBIC'      => $bic,//'GENODEM1GLS',
          'seqType'               => PaymentInformation::S_ONEOFF,
          'creditorId'            => 'DE17ZZZ00000441401'
      ));

      $calculation = $campaign->calculation();

      Carbon::setToStringFormat('d.m.Y');
      foreach($supporters as $supporter){
        // Add a Single Transaction to the named payment

        if(empty($supporter->iban)){
          //return "IBAN Empty: " . $supporter->vorname;
          continue;
        }
        $iban = str_replace(' ', '', $supporter->iban);

        $bic = $ibanValidator->generateBic($iban);

        if($bic == "" || !$ibanValidator->validateIban($iban)){
          //return "Error with BIC or IBAN: " . $supporter->vorname;
          continue;
        }

        $directDebit->addTransfer('patenschaften', array(
            'amount'                => $supporter->beitrag * $calculation->factor,
            'debtorIban'            => $iban,
            'debtorBic'             => $bic,
            'debtorName'            => $supporter->vorname . " " . $supporter->nachname,
            'debtorMandate'         => substr($supporter->uuid, 0, 7),
            'debtorMandateSignDate' => (string)$supporter->updated_at,
            'remittanceInformation' => 'Transition Regensburg Patenschaft ' . $campaign->name,
            //s'otherIdentification'   => '>>NOTPROVIDED<<'
        ));
      }
      Carbon::resetToStringFormat();

      $dt = Carbon::now();
      return response($directDebit->asXML())
          ->withHeaders([
              'Content-Type' => 'text/xml',
              'Content-Disposition' =>
                'attachment; filename="sepa-transition-puerkelgut-patenschaften-'.$dt->toDateString().'.xml"'
          ]);;
    }

    public function invite(Request $request, $id){

        //lookup mails in invitation list
        $mails = explode(',', $request->input('mails'));
        $campaign = Campaign::find($id);
        foreach($mails as $mail){
          $invitation = new Invitation();
          $invitation->campaign_id = $id;
          $invitation->mail = $mail;
          $binUuid = random_bytes(12);
          $hexUuid = bin2hex($binUuid);
          $invitation->uuid = $hexUuid;
          if($invitation->notify(new CampaignInvited($campaign))){
            $invitation->invitation_sent = Carbon\Carbon::now();
          }
          $invitation->save();
        }
        return ["status" => "success", "data" => [], "message" => count($mails) . ' Einladung(en) wurde(n) verschickt.'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaigns = Campaign::all();
        return view('campaigns.index', ['campaigns' => $campaigns]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $campaign = new Campaign($request->all());
        $campaign->repeated_campaign = true;
        return view('campaigns.create', ['campaign' => $campaign]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $campaign = Campaign::find($id);
        $supporters = Supporter::where([
          ['campaign_id', $campaign->id],
          ['beitrag', '>', 0]
          ])->get();
        $calculation = $campaign->calculation();
        return view('campaigns.show', [
          'campaign' => $campaign,
          'supporters' => $supporters,
          'calculation' => $calculation,
          'key' => env('PUSHER_APP_KEY')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $campaign = Campaign::find($id);
        return view('campaigns.create', ['campaign' => $campaign]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($id == 'create'){
          $campaign = new Campaign($request->all());
          $binUuid = random_bytes(12);
          $hexUuid = bin2hex($binUuid);
          $campaign->uuid = $hexUuid;
        }else{
          $campaign = Campaign::find($id);
          $campaign->update($request->all());
        }
        if($request->input('repeated_campaign') == "true"){
          $campaign->repeated_campaign = true;
        }else{
          $campaign->repeated_campaign = false;
        }
        $campaign->save();
        return redirect()->action('CampaignController@show', $campaign->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
