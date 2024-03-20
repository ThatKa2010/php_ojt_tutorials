<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Data with Pagination</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="create.php" class="btn btn-primary my-3">Create</a>
        <div class="list">
            <?php
            error_reporting(1);
            if ($_GET['success']) {
                $successEdit = $_GET['success'];
                echo "<div class='container mt-3'><div class='alert alert-success' role='alert'>$successEdit</div></div>";
            }
            ?>
            <h2>Data from Database</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Is Published</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    error_reporting(1);
                    include 'db_connection.php';
                    // Pagination
                    $per_page = 5;
                    $page = isset ($_GET['page']) ? intval($_GET['page']) : 1;
                    $start = ($page - 1) * $per_page;
                    // Handle deletion
                    if (isset ($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
                        $delete_id = $_GET['delete_id'];
                        // JavaScript confirmation dialog box
                        echo "<script>
                        if(confirm('Are you sure you want to delete?')) {
                            window.location.href = 'index.php?confirmed_delete_id=$delete_id';
                        } else {
                            window.location.href = 'index.php';
                        }
                    </script>";
                    }

                    //  perform deletion from database
                    if (isset ($_GET['confirmed_delete_id']) && is_numeric($_GET['confirmed_delete_id'])) {
                        $confirmed_delete_id = $_GET['confirmed_delete_id'];
                        $sql_delete = "DELETE FROM posts WHERE id = $confirmed_delete_id";
                        if ($conn->query($sql_delete) === TRUE) {
                            echo "<div class='alert alert-success' role='alert'>Successfully has been deleted.</div>";
                        } else {
                            echo "<div class='alert alert-danger' role='alert'>Error deleting entry: " . $conn->error . "</div>";
                        }
                    }

                    // Query to retrieve data
                    $sql = "SELECT * FROM posts LIMIT $start, $per_page";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // output data of each row
                        while ($row = $result->fetch_assoc()) {
                            if ($row['is_published'] == 1) {
                                $status = "Published";
                            } else {
                                $status = "Unpublished";
                            }
                            echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["title"] . "</td>
                        <td>" . substr_replace($row["content"], "...", 50) . "</td>
                        <td>" . $status . "</td>
                        <td>" . date("M d, Y", strtotime($row["created_datetime"])) . "</td>
                        <td>
                        <a class='btn btn-info' href='detail.php?id=" . $row["id"] . "'>View</a>
                        <a class='btn btn-success' href='edit.php?id=" . $row["id"] . "'>Edit</a>
                        <a class='btn btn-danger' href='index.php?delete_id=" . $row["id"] . "'>Delete</a>
                        </td>
                        </tr>";
                        }

                    } else {
                        echo "<tr><td colspan='3'>No records found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <?php
        // Pagination links
        include 'db_connection.php';
        $sql = "SELECT COUNT(*) AS total FROM posts";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $total_pages = ceil($row["total"] / $per_page);
        ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination offset-5">
                <!-- Left arrow -->
                <li class="page-item <?php echo ($_GET['page'] <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?page=<?php echo ($_GET['page'] <= 1) ? 1 : ($_GET['page'] - 1); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    // Adding active class to the current page
                    $active_class = ($i == $_GET['page']) ? 'active' : '';
                    echo "<li class='page-item $active_class'><a class='page-link' href='index.php?page=$i'>$i</a></li>";
                }
                ?>

                <!-- Right arrow -->
                <li class="page-item <?php echo ($_GET['page'] >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?page=<?php echo ($_GET['page'] >= $total_pages) ? $total_pages : ($_GET['page'] + 1); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</body>
</html>
