<?php
include ("db_connection.php");

$start_date = "2024-03-01";
$end_date = "2024-03-31";

$labels = array();
$current_date = new DateTime($start_date);

// Loop for each day in March 2024
while ($current_date <= new DateTime($end_date)) {
    $labels[] = $current_date->format("m-d-Y");
    $current_date->modify("+1 day");
}

// Query to get post count for each date in March 2024
$sql = "SELECT DATE_FORMAT(created_datetime, '%m-%d-%Y') AS date_formatted, COUNT(*) AS post_count 
        FROM posts 
        WHERE created_datetime >= '$start_date'
        AND created_datetime <= '$end_date'
        GROUP BY DATE(created_datetime)";
$result = $conn->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[$row['date_formatted']] = $row['post_count'];
}

// Fill in the missing dates with zero post count
foreach ($labels as $label) {
    if (!isset ($data[$label])) {
        $data[$label] = 0;
    }
}

// Sort the labels by date
ksort($data);
$data = array_values($data);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Monthly Posts</title>
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
                <a href="monthly.php" class="btn btn-outline-success bg-success text-white">Monthly</a>
                <a href="yearly.php" class="btn btn-outline-success">Yearly</a>
            </div>
            <div>
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // JavaScript to render the bar graph
        var ctx = document.getElementById('dailyChart').getContext('2d');
        var dailyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: '#Monthly Created posts',
                    data: <?php echo json_encode($data); ?>,
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
// Close the database connection
$conn->close();
?>
