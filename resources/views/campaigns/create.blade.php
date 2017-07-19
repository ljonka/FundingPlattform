@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Kampagne hinzufügen</div>

                <div class="panel-body">
                  {{Form::model($campaign, ['action' => 'CampaignController@create', 'files' => true, 'method' => 'PUT'])}}
                    <div class="form-group">
                      <label for="name">Bezeichnung:</label>
                      <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                      <label for="description">Beschreibung:</label>
                      <input type="text" class="form-control" id="description" name="description">
                    </div>
                    <div class="form-group">
                      <label for="image_path">Bild:</label>
                      <input type="file" class="form-control" id="image_path" name="image_path">
                    </div>
                    <div class="checkbox">
                      <label><input type="checkbox" name="repeated_campaign" checked> Regelmäßiger Beitrag</label>
                    </div>
                    <div class="form-group">
                      <label for="repeat_interval">Intervall der Wiederholungen in Tagen:</label>
                      <input type="number" class="form-control" id="repeat_interval" name="repeat_interval">
                    </div>
                    <div class="form-group">
                      <label for="amount">Gesamtbetrag pro Interval in Euro:</label>
                      <input type="number" class="form-control" id="amount" name="amount">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                  {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
