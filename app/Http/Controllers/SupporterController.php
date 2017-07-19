<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campaign;
use App\Invitation;
use App\Supporter;

class SupporterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * /foerdern/{campaign_uuid}/{invitation}
     *
     * @return \Illuminate\Http\Response
     */
    public function support(Request $request, $campaign_uuid, $invitation_uuid)
    {

        $campaign = Campaign::where('uuid', $campaign_uuid)->first();
        $invitation = Invitation::where('uuid', $invitation_uuid)->first();
        if($campaign == null || $invitation == null){
          return abort(403, 'Unauthorized action.');
        }

        $tmpSupporter = Supporter::where('uuid', $invitation_uuid)->first();

        if($tmpSupporter == null){
          $supporter = new Supporter();
          $supporter->uuid = $invitation_uuid;
          $supporter->campaign_id = $campaign->id;
          $supporter->mail = $invitation->mail;
          $supporter->land = "Deutschland";
          $supporter->ort = "Regensburg";
          $supporter->save();
        }else{
          $supporter = $tmpSupporter;
        }

        return view('supporter.index', [
          'campaign' => $campaign,
          'calculation' => $campaign->calculation(),
          'invitation' => $invitation,
          'supporter' => $supporter
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $supporter = Supporter::find($id);
        $supporter->update($request->all());
        $supporter->save();
        $campaign = Campaign::find($supporter->campaign_id);

        return redirect()->action('SupporterController@support', [
          'campaign_uuid' => $campaign->uuid,
          'invitation_uuid' => $supporter->uuid
        ]);
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
