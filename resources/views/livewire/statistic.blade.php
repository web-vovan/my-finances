<div>
    <canvas id="myChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: JSON.parse('{!! $labels !!}'),
            datasets: [{
                data: JSON.parse('{!! $data !!}'),
                borderWidth: 1
            }]
        },
    });
</script>
