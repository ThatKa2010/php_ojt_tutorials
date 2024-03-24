<?php
include ("db_connection.php");

$sql = "SELECT DAYNAME(created_datetime) AS day_name, COUNT(*) AS post_count FROM posts GROUP BY DAYNAME(created_datetime)";
$result = $conn->query($sql);

$data = array();
$daysOfWeek = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
// Initialize data array with 0 count for each day of the week
foreach ($daysOfWeek as $day) {
    $data[$day] = 0;
}

// Populate data array with counts from the database
while ($row = $result->fetch_assoc()) {
    $data[$row['day_name']] = $row['post_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weekly Posts</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!--Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container-md">
        <div class="col-9 offset-1 mt-5">
            <a href="index.php" class="btn btn-success">back</a>
            <div class="float-right">
                <a href="weekly.php" class="btn btn-outline-success bg-success text-white">Weekly</a>
                <a href="monthly.php" class="btn btn-outline-success">Monthly</a>
                <a href="yearly.php" class="btn btn-outline-success">Yearly</a>
            </div>
            <div style="width: 800px; height: 400px;">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('weeklyChart').getContext('2d');
        var weeklyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($daysOfWeek); ?>,
                datasets: [{
                    label: '#Weekly Created Posts',
                    data: <?php echo json_encode(array_values($data)); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 2,
                        }
                    }]
                }
            }
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
</body>
</html>
