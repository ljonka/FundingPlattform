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
                  </h4>
                  <div id="fundingchart">
                      <canvas id="canvas"></canvas>
                  </div>
                  <div class="btn-group-wrap">
                    <div class="btn-group" role="group" aria-label="Basic example">
                      <!--<button type="button" class="btn btn-secondary">vorheriger Monat</button>-->
                      <!--
                      <a href="{{url('a23498rcnwnhcfksn/create')}}" target="_blank">
                        <button type="button" class="btn btn-secondary">neue Patenschaft eintragen</button>
                      </a>
                      <a href="{{url('a23498rcnwnhcfksn/create_singlesupport')}}" target="_blank">
                        <button type="button" class="btn btn-secondary">Einmalbetrag setzen</button>
                      </a>
                      -->
                      <!--
                      <a href="#" target="_blank">
                        <button type="button" class="btn btn-secondary">Teilnehmende einladen</button>
                      </a>
                    --><!--
                      <button type="button" class="btn btn-secondary">nächster Monat</button>
                    -->
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var names = [];
var namesData = [];
var namesDataSecond = [];
var namesDataThird = [];
var namesDataExtra = [];
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

Echo.channel('supporter.updated')
    .listen('SupporterUpdated', (e) => {
      /*
        console.log(e.supporter.uuid);
        console.log(e.supporter.vorname);
        console.log(e.supporter.nachname);
        console.log(e.supporter.beitrag);
        */
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

</script>
@endsection
