<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//23498rcnwnhcfksn
/*
$cryptBaseUrl = 'a23498rcnwnhcfksn';
Route::prefix($cryptBaseUrl)->group(function () {
  Route::get('share', 'FundingController@share');
});

Route::resource($cryptBaseUrl, 'FundingController');
*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/kampagnen/{campaign}/einladen', 'CampaignController@invite');
Route::get('/kampagnen/{campaign}/sepa', 'CampaignController@sepa');

Route::resource( '/kampagnen', 'CampaignController');

Route::get( '/foerdern/{campaign_uuid}/{invitation_uuid}',  'SupporterController@support');

Route::resource( '/foerdern', 'SupporterController');
