<?php
include ("db_connection.php");

$sql = "SELECT DATE_FORMAT(created_datetime, '%m-%d-%Y') AS date_formatted, COUNT(*) AS post_count 
        FROM post 
        GROUP BY DATE_FORMAT(created_datetime, '%m-%d-%Y')";
$result = $conn->query($sql);

$data = array();
$labels = array(); // to store labels for x-axis

// Get the start date as the earliest date in the dataset
$startDate = date('m-d-Y');
while ($row = $result->fetch_assoc()) {
    $labels[] = $row['date_formatted'];
    $data[] = $row['post_count'];
}
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
        <div class="col-9 offset-1 mt-5">
            <a href="index.php" class="btn btn-success">back</a>
            <div class="float-right">
                <a href="weekly.php" class="btn btn-outline-success">Weekly</a>
                <a href="monthly.php" class="btn btn-outline-success">Monthly</a>
                <a href="yearly.php" class="btn btn-outline-success">Yearly</a>
            </div>
            <div style="width: 800px; height: 400px;">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Step 5: JavaScript to render the bar graph
        var ctx = document.getElementById('dailyChart').getContext('2d');
        var dailyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: '#Monthly created post',
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
// Step 6: Close the database connection
$conn->close();
?>
