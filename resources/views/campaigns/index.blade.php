@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Kampagnen</div>
                <div class="panel-body">
                  <div class="list-group">
                      <a href="{{action('CampaignController@create')}}"
                        class="list-group-item active"
                        >Kampagne hinzufügen</a>
                    @foreach ($campaigns as $campaign)
                        <a href="{{action('CampaignController@show', $campaign->id)}}"
                          class="list-group-item list-group-item-action">
                          {{$campaign->name}}                          
                        </a>
                    @endforeach
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
