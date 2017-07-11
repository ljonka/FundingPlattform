<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supporter;
use App\Events\SupporterUpdated;

//a23498rcnwnhcfksn
class FundingController extends Controller
{

    public function share(){
      return view('funding.share');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('funding.create');
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
        return view('funding.show',[
          'supporter' => Supporter::where('uuid', $id)->first()
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
        if($id == 'create'){ //create new entry
          $binUuid = random_bytes(12);
          $hexUuid = bin2hex($binUuid);
          $supporter = new Supporter($request->all());
          $supporter->uuid = $hexUuid;
        }else{
          $hexUuid = $id;
          $supporter = Supporter::where('uuid', $id)->first();
          $supporter->update($request->all());
        }
        $supporter->save();

        broadcast(new SupporterUpdated($supporter));

        return redirect()->action('FundingController@show', ['id' => $hexUuid]);
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

    public static function getCurrentCalculation($supporters){
      $complete = 600;
      $funded = 0;
      $factor = 1;
      foreach($supporters as $supporter){
        $funded += $supporter->beitrag;
      }
      if($funded > $complete){
        $diff = $funded - $complete;
        $firstFund = (count($supporters) > 0) ? $supporters[0]->beitrag : 0;
        $firstFactor = $firstFund / $funded;
        $firstDiff = $diff * $firstFactor;
        $factor = 1 - (1 * ($firstDiff/$firstFund));
      }

      return (object)[
        'funded' => (100*($funded * $factor)) / $complete,
        'factor' => $factor,
        'singlesupports' => 0,
        'complete' => $complete
      ];
    }
}
