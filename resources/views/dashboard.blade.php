<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Dashboard</h1>
        <canvas id="myChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch data from the API endpoint
            fetch('/data')
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.topic);
                    const intensities = data.map(item => item.intensity);

                    const ctx = document.getElementById('myChart').getContext('2d');
                    const myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Intensity',
                                data: intensities,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
	});
        });
    </script>
</body>
</html>

