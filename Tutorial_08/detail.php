<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entry Details</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">

        <?php
        // Database connection
        include 'db_connection.php';

        // Check if ID parameter is passed in URL
        if (isset ($_GET['id']) && is_numeric($_GET['id'])) {
            $id = $_GET['id'];

            // Prepare SQL statement
            $stmt = $conn->prepare("SELECT title, content, created_datetime, is_published FROM post WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                ?>
                <div class="col-6 offset-3">
                    <div class="border">
                        <h2>Post Detail</h2>
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo $row["title"]; ?>
                            </h5>
                            <p class="card-text">
                                <?php echo $row["is_published"] ? '<i class="mr-3">Publish at</i>' : '<i class="mr-3">not Publish</i>';
                                echo date("M d, Y", strtotime($row["created_datetime"])); ?>
                            </p>
                            <p class="card-text">
                                <?php echo $row["content"]; ?>
                            </p>
                        </div>
                        <a href="index.php" class="btn btn-success col-2 m-3">back</a>
                    </div>
                </div>
                <?php
            } else {
                echo "No entry found";
            }

            // Close statement and connection
            $stmt->close();
        } else {
            echo "Invalid ID";
        }

        // Close connection
        $conn->close();
        ?>
    </div>
</body>
</html>
