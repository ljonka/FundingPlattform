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
                      max: {{$calculation->complete}}
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
