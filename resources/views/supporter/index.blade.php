@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">Kampagne</div>

                <div class="panel-body">
                  <h3 class="text-center">Finanzierung für {{$campaign->name}}</h3>
                  <h5 class="text-center">{{$campaign->description}}</h5>
                  <h4 class="text-center">
                    Gesamtbedarf: <b>{{$calculation->complete}} €</b>
                    @if($campaign->repeated_campaign)
                      <b>/ {{$campaign->repeat_interval}} Tage</b>
                    @endif
                    , Aktuell finanziert: <b>{{$calculation->funded_round}} % bzw. {{($calculation->complete * $calculation->funded) / 100}} € von {{$calculation->complete}} €</b>
                  </h4>
                  {{Form::model($supporter, ['action' => ['SupporterController@update', $supporter->id], 'files' => true, 'method' => 'PATCH'])}}

                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="vorname">Vorname:</label>
                        @if($supporter->vorname == null)
                        <input type="text" class="form-control" id="vorname" name="vorname" value="{{$supporter->vorname}}" required autofocus>
                        @else
                        <input type="text" class="form-control" id="vorname" name="vorname" value="{{$supporter->vorname}}" required>
                        @endif
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="nachname">Nachname:</label>
                        <input type="text" class="form-control" id="nachname" name="nachname" value="{{$supporter->nachname}}" required>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="mail">E-Mail:</label>
                        <input type="mail" class="form-control" id="mail" name="mail" value="{{$supporter->mail}}" required>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="strasse">Straße und Hausnummer:</label>
                        <input type="text" class="form-control" id="strasse" name="strasse" value="{{$supporter->strasse}}" required>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="plz">Plz:</label>
                        <input type="text" class="form-control" id="plz" name="plz" value="{{$supporter->plz}}" required>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="ort">Ort:</label>
                        <input type="text" class="form-control" id="ort" name="ort" value="{{$supporter->ort}}" required>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="land">Land:</label>
                        <input type="text" class="form-control" id="land" name="land" value="{{$supporter->land}}" required>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="beitrag">Mein Beitrag:</label>
                        @if($supporter->beitrag !== null)
                        <input type="text" class="form-control select-amount" id="beitrag" name="beitrag" value="{{$supporter->beitrag}}" required autofocus>
                        @else
                        <input type="text" class="form-control" id="beitrag" name="beitrag" value="{{$supporter->beitrag}}" required>
                        @endif
                      </div>
                    </div>
                  </div>

                  <button type="submit" class="btn btn-default">Speichern</button>
                  {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$('.select-amount').select();
</script>
@endsection
