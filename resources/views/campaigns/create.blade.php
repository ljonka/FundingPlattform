@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Kampagne hinzufügen</div>

                <div class="panel-body">
                  @if(empty($campaign->id))
                    {{Form::model($campaign, ['action' => ['CampaignController@create'], 'files' => true, 'method' => 'PUT'])}}
                  @else
                    {{Form::model($campaign, ['action' => ['CampaignController@update', $campaign->id], 'files' => true, 'method' => 'PATCH'])}}
                  @endif
                    <div class="form-group">
                      <label for="name">Bezeichnung:</label>
                      <input type="text" class="form-control" id="name" name="name" value="{{$campaign->name}}" required autofocus>
                    </div>
                    <div class="form-group">
                      <label for="description">Beschreibung:</label>
                      <input type="text" class="form-control" id="description" name="description" value="{{$campaign->description}}" required>
                    </div>
                    <div class="form-group">
                      <label for="image_path">Bild:</label>
                      <input type="file" class="form-control" id="image_path" name="image_path" value="{{$campaign->image_path}}">
                    </div>
                    <div class="checkbox">
                      @if($campaign->repeated_campaign)
                      <label><input type="checkbox" name="repeated_campaign" value="true" checked> Regelmäßiger Beitrag</label>
                      @else
                      <label><input type="checkbox" name="repeated_campaign" value="true"> Regelmäßiger Beitrag</label>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="repeat_interval">Intervall der Wiederholungen in Tagen:</label>
                      <input type="number" class="form-control" id="repeat_interval" name="repeat_interval" value="{{$campaign->repeat_interval}}" required>
                    </div>
                    <div class="form-group">
                      <label for="amount">Gesamtbetrag pro Interval in Euro:</label>
                      <input type="number" class="form-control" id="amount" name="amount" value="{{$campaign->amount}}" required>
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                  {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
