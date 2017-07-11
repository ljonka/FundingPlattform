<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supporter;
use App\Events\SupporterUpdated;

//a23498rcnwnhcfksn
class FundingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supporters = Supporter::all();
        return view('funding.index', [
          'supporters' => $supporters,
          'calculation' => self::getCurrentCalculation($supporters),
          'key' => config('broadcasting.connections.pusher.key')
        ]);

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

        event(new SupporterUpdated($supporter));

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
      foreach($supporters as $supporter){
        $funded += $supporter->beitrag;
      }
      return (object)[
        'funded' => (100*$funded) / $complete,
        'singlesupports' => 100,
        'complete' => $complete
      ];
    }
}
