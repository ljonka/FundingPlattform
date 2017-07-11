<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supporter;
use App\Events\SupporterUpdated;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $supporters = Supporter::all();
      return view('home', [
        'supporters' => $supporters,
        'calculation' => FundingController::getCurrentCalculation($supporters)
      ]);
    }
}
