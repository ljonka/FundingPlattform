@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">Kampagne</div>

                <div class="panel-body">
                  <h3 class="text-center">Finanzierung für {{$campaign->name}} <a href="{{action('CampaignController@edit', $campaign->id)}}" class="pull-right" title="Kampagne bearbeiten"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></h3>
                  <h5 class="text-center">{{$campaign->description}}</h5>
                  <h4 class="text-center">
                    Gesamtbedarf: <b>{{$calculation->complete}} €</b>
                    @if($campaign->repeated_campaign)
                      <b>/ {{$campaign->repeat_interval}} Tage</b>
                    @endif
                    , Aktuell finanziert: <b>{{$calculation->funded_round}} % bzw. {{($calculation->complete * $calculation->funded) / 100}} € von {{$calculation->complete}} €</b>
                  </h4>
                  <div id="fundingchart">
                      <canvas id="canvas"></canvas>
                  </div>

                  <!-- Trigger the modal with a button -->
                  <button type="button" class="btn btn-info col-md-3 col-md-offset-3" data-toggle="modal" data-target="#myModal">Einladungen</button>
                  <!-- Trigger the modal with a button -->
                  <button type="button" class="btn btn-info col-md-3" data-toggle="modal" data-target="#sepa-export">Sepa-XML Export</button>

                  <!-- Modal -->
                  <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                      <!-- Modal content-->
                      <div class="modal-content">
                        <form id="invite-form">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Einladungen</h4>
                          </div>
                          <div class="modal-body">
                              <div class="form-group">
                                <label for="supporters">Teilnehmende</label>
                                <input type="text" data-role="tagsinput" id="supporters" class="form-control">
                              </div>
                          </div>
                          <div class="modal-footer">
                              <button type="submit" class="btn btn-default">Speichern</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                          </div>
                        </form>
                      </div>

                    </div>
                  </div>

                  <!-- Modal -->
                  <div id="sepa-export" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                      <!-- Modal content-->
                      <div class="modal-content">
                        <form id="sepa-export-form">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Sepa-XML Export</h4>
                          </div>
                          <div class="modal-body">
                              Es wird ein aktueller Abzug der Patenschaften für eine Sammel-Lastschrift erzeugt.
                          </div>
                          <div class="modal-footer">
                              <button type="submit" class="btn btn-default">XML Datei herunterladen</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                </div>
            </div>

        </div>
    </div>
</div>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#myModal').on('shown.bs.modal', function () {
    $('#supporters').tagsinput('focus');
});

$('#sepa-export-form').submit(function(event){
  /*
  $.post({
    url: '{{action("CampaignController@sepa", $campaign->id)}}',
    data: {},
    success: function(data, status){

    },
    dataType: 'json'
  });
  */
  window.open('{{action("CampaignController@sepa", $campaign->id)}}');
  $('#sepa-export').modal('hide');
  event.preventDefault();
});

$('#invite-form').submit(function(event){
  var mails = $('#supporters').val();
  $.post({
    url: '{{action("CampaignController@invite", $campaign->id)}}',
    data: {'mails': mails },
    success: function(data, status){

    },
    dataType: 'json'
  });
  $('#myModal').modal('hide');
  $('#supporters').tagsinput('removeAll');
  event.preventDefault();
});
var names = []; //name
var namesData = []; //beitrag
var namesDataSecond = []; //berechnetter beitrag
var namesDataThird = []; //tellerspenden
var namesDataExtra = []; //gesamtfinanzierung in %
@foreach($supporters as $supporter)
  names.push('{{$supporter->vorname}}');
  namesData.push({{$supporter->beitrag}});
  namesDataSecond.push({{$supporter->beitrag * $calculation->factor}});
  namesDataThird.push({{$calculation->singlesupports}});
  namesDataExtra.push({{$calculation->funded}});
@endforeach
var lineChartData = {
    labels: names,
    datasets: [{
        label: "Patenschaft: maximaler Beitrag",
        borderColor: window.chartColors.purple,
        backgroundColor: window.chartColors.purple,
        fill: false,
        data: namesData,
        yAxisID: "y-axis-1",
    }, {
        label: "Patenschaft: tatsächlich verwendeter Beitrag",
        borderColor: window.chartColors.gray,
        backgroundColor: window.chartColors.gray,
        fill: false,
        data: namesDataSecond,
        yAxisID: "y-axis-1"
    }, {
        label: "Tellerspenden",
        borderColor: window.chartColors.orange,
        backgroundColor: window.chartColors.orange,
        fill: false,
        data: namesDataThird,
        yAxisID: "y-axis-1",
        pointRadius: 0,
        pointHoverRadius: 0,
        pointHitRadius: 0
    },{
        label: "Aktuelle Finanzierung in %",
        borderColor: window.chartColors.green,
        backgroundColor: window.chartColors.green,
        fill: true,
        data: namesDataExtra,
        yAxisID: "y-axis-2",
        pointRadius: 0,
        pointHoverRadius: 0,
        pointHitRadius: 0
    }]
};

window.onload = function() {
    var ctx = document.getElementById("canvas").getContext("2d");
    window.myLine = Chart.Line(ctx, {
        data: lineChartData,
        options: {
            responsive: true,
            hoverMode: 'index',
            stacked: false,
            title:{
                display: false,
                text:'Finanzierung für kleinen Workshop-Raum auf Schloss Pürkelgut'
            },
            scales: {
                yAxes: [{
                    type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                    scaleLabel: {
                      labelString: "Beiträge in €",
                      display: true
                    },
                    ticks:{
                      min: 0,
                      //max: {{$calculation->complete}}
                    },
                }, {
                    type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    position: "right",
                    id: "y-axis-2",
                    scaleLabel: {
                      labelString: "Gesamtbetrag in %",
                      display: true
                    },
                    ticks:{
                      min: 0,
                      max: 110
                    },
                    // grid line settings
                    gridLines: {
                        drawOnChartArea: false, // only want the grid lines for one axis to show up
                    },
                }],
            }
        }
    });
};
window.Echo = new window.EchoBase({
   broadcaster: 'pusher',
   key: '{{$key}}',
   cluster: 'eu',
   encrypted: true
});
window.Echo.channel('supporter.updated')
    .listen('SupporterUpdated', (e) => {
        var updatePosition = -1;
        for (var k in window.myLine.data.labels){
            if (window.myLine.data.labels[k] === e.supporter.vorname) {
                 updatePosition = k;
            }
            //update real sum for all existing entries based on return factor
            window.myLine.data.datasets[1].data.splice(k, 1, window.myLine.data.datasets[0].data[k]  * e.calculation.factor);
        }
        if(updatePosition >= 0){
          window.myLine.data.datasets[0].data.splice(updatePosition, 1, e.supporter.beitrag);
          window.myLine.data.datasets[1].data.splice(updatePosition, 1, e.supporter.beitrag  * e.calculation.factor);
        }else{
          window.myLine.data.labels.push(e.supporter.vorname);
          window.myLine.data.datasets[0].data.push(e.supporter.beitrag);
          window.myLine.data.datasets[1].data.push(e.supporter.beitrag * e.calculation.factor);
          window.myLine.data.datasets[2].data.push(e.calculation.singlesupports);
          window.myLine.data.datasets[3].data.push(e.calculation.funded);
        }
        window.myLine.data.datasets[2].data.forEach(function(element, index, array){
        window.myLine.data.datasets[2].data.splice(index, 1, e.calculation.singlesupports);
        window.myLine.data.datasets[3].data.splice(index, 1, e.calculation.funded);
      });
      window.myLine.update();
    });

    function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }

    //validate input e-mails
    $('#supporters').on('beforeItemAdd', function(event) {
       var tag = event.item;
       if(!isEmail(tag)){
         event.cancel = true;
       }
       return true;
    });

</script>
@endsection
