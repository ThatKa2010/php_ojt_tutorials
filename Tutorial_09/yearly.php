<?php
include ("db_connection.php");
$sql = "SELECT DATE_FORMAT(created_datetime, '%M') AS month_name, COUNT(*) AS post_count 
        FROM post 
        GROUP BY DATE_FORMAT(created_datetime, '%M')";
$result = $conn->query($sql);

$data = array();
$labels = array(); // to store labels for x-axis

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['month_name'];
    $data[] = $row['post_count'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Yearly Posts</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
        <div class="container-md">
            <div class="col-9 offset-1 mt-5">
                <a href="index.php" class="btn btn-success">back</a>
                <div class="float-right">
                    <a href="weekly.php" class="btn btn-outline-success">Weekly</a>
                    <a href="monthly.php" class="btn btn-outline-success">Monthly</a>
                    <a href="yearly.php" class="btn btn-outline-success">Yearly</a>
                </div>
                <div style="width: 800px; height: 400px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>

        <script>
            var ctx = document.getElementById('monthlyChart').getContext('2d');
            var monthlyChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: [{
                        label: '#Yearly created Posts',
                        data: <?php echo json_encode($data); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
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
</body>
</html>
<?php
$conn->close();
?>
