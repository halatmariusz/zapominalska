@extends('app')

@section('content')
<div class="content">
    <canvas id="currenciesChart"></canvas>
</div>
<script>
    var ctx = document.getElementById('currenciesChart');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! $labels !!},
            datasets: {!! json_encode($dataSets) !!}
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    </script>
</script>

@endsection
