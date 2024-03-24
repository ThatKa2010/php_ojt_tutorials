<?php
include ("db_connection.php");
// Generate an array of all 12 months
$months = array(
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
);

$sql = "SELECT DATE_FORMAT(created_datetime, '%M') AS month_name, COUNT(*) AS post_count 
        FROM posts 
        GROUP BY month_name";

$result = $conn->query($sql);

$data = array();
$labels = array(); // to store labels for x-axis

// Initialize counts for all months to 0
foreach ($months as $month) {
    $data[$month] = 0;
}

while ($row = $result->fetch_assoc()) {
    $month_name = $row['month_name'];
    $post_count = $row['post_count'];
    $data[$month_name] = $post_count;
}

// Populate labels and data array
foreach ($months as $month) {
    $labels[] = $month;
    $data[] = $data[$month];
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
        <div class="col-12 mt-5">
            <a href="index.php" class="btn btn-success">back</a>
            <div class="float-right">
                <a href="weekly.php" class="btn btn-outline-success">Weekly</a>
                <a href="monthly.php" class="btn btn-outline-success">Monthly</a>
                <a href="yearly.php" class="btn btn-outline-success bg-success text-white">Yearly</a>
            </div>
            <div class="">
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
                    label: '#Yearly Created Posts',
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
