@extends('layouts.app')
@section('chart')
{{ Form::open(['action' => ['FundingController@update', $supporter->uuid], 'method' => 'PATCH']) }}
<h3>Patenschaft bearbeiten</h3>
<div class="row">
  <div class="col-lg-6">
    <div class="form-group">
      <label for="inputVorname">Vorname</label>
      <input name="vorname" value="{{$supporter->vorname}}" type="text" class="form-control" id="inputVorname" placeholder="Vorname eingeben" autofocus required>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="form-group">
      <label for="inputNachname">Nachname</label>
      <input name="nachname" value="{{$supporter->nachname}}" type="text" class="form-control" id="inputNachname" placeholder="Nachname eingeben" required>
    </div>
  </div>
</div>
<div class="form-group">
  <label for="inputMail">E-Mail</label>
  <input name="mail" value="{{$supporter->mail}}" type="email" class="form-control" id="inputMail" placeholder="E-Mail Adresse eingeben" required>
</div>
<div class="form-group">
  <label for="inputStreet">Straße und Hausnummer</label>
  <input name="strasse" value="{{$supporter->strasse}}" type="text" class="form-control" id="inputStreet" placeholder="Straße und Hausnummer eingeben" required>
</div>
<div class="row">
  <div class="col-lg-4">
    <div class="form-group">
      <label for="inputZip">PLZ</label>
      <input name="plz" value="{{$supporter->plz}}" type="text" class="form-control" id="inputZip" placeholder="PLZ eingeben" required>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="form-group">
      <label for="inputCity">Ort</label>
      <input name="ort" value="{{$supporter->ort}}" type="text" class="form-control" id="inputCity" placeholder="Ort eingeben" required>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="form-group">
      <label for="inputCountry">Land</label>
      <input name="land" value="{{$supporter->land}}" type="text" class="form-control" id="inputCountry" placeholder="Land eingeben" required>
    </div>
  </div>
</div>
<div class="form-group">
  <label for="inputAmount">Maximaler Beitrag pro Monat in €</label>
  <input name="beitrag" value="{{$supporter->beitrag}}" type="number"
    class="form-control" id="inputAmount"
    placeholder="Maximalen Beitrag festlegen"
    min="3" max="80" step="any" required>
</div>

<button type="submit" class="btn btn-primary">Submit</button>
{{ Form::close() }}
@endsection
