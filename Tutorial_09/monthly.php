<?php
include ("db_connection.php");

// Query to get the minimum date when the first data was created
$sql_min_date = "SELECT MIN(created_datetime) AS min_date FROM posts";
$result_min_date = $conn->query($sql_min_date);
$row_min_date = $result_min_date->fetch_assoc();
$start_date = $row_min_date['min_date'];

$labels = array();
$current_date = new DateTime($start_date);
for ($i = 0; $i < 30; $i++) {
    $labels[] = $current_date->format("m-d-Y");
    $current_date->modify("+1 day");
}

// Query to get post count for each date
$sql = "SELECT DATE_FORMAT(created_datetime, '%m-%d-%Y') AS date_formatted, COUNT(*) AS post_count 
        FROM posts 
        WHERE created_datetime >= '$start_date'
        GROUP BY DATE_FORMAT(created_datetime, '%m-%d-%Y')";
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
