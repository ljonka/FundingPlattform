@extends('layouts.app')

@section('chart')
<h3 class="text-center">Finanzierung für kleinen Workshop-Raum auf Schloss Pürkelgut</h3>
<h4 class="text-center">Zur Teilnahme bitte den QR-Code scannen oder drauf clicken</h4>
<div class="row">
    <div class="col-md-2 col-md-offset-4">
      <a href="{{action('FundingController@create')}}" class="qrinvite">
        {!! QrCode::size(300)->generate(action('FundingController@create')); !!}
      </a>
    </div>
</div>
<h4 class="text-center">QR-Code Scanner gibt es in jedem App-Store</h4>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
      <a href='https://play.google.com/store/apps/details?id=com.google.zxing.client.android&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1' target="_blank" style="margin-top: -9px">
        <img alt='Jetzt bei Google Play' src='https://play.google.com/intl/en_us/badges/images/generic/de_badge_web_generic.png' height="59px"/>
      </a>
      <a href="https://itunes.apple.com/de/app/qr-code-scanner/id1200318119?mt=8" target="_blank"
        style="display:inline-block;overflow:hidden;background:url(//linkmaker.itunes.apple.com/assets/shared/badges/de-de/appstore-lrg.svg) no-repeat;width:135px;height:40px;background-size:contain;">
      </a>
    </div>
</div>
@endsection
