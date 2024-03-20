<?php
include ("db_connection.php");
// Pagination
$per_page = 5;
$page = isset ($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $per_page;
// Search functionality
if (isset ($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM posts WHERE title LIKE '%$search%' OR content LIKE '%$search%'";
}
if (isset ($_GET['search']) == "" || empty ($_GET['search'])) {
    $sql = "SELECT * FROM posts LIMIT $start, $per_page";
}

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

// Retrieve data from the database
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Posts</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-between">
            <div>
                <a href="create.php" class="btn btn-primary my-3">Create</a>
                <a href="weekly.php" class="btn btn-primary my-3">Graph</a>
            </div>
            <form action="index.php" method="GET" class="col-4 mt-3 d-flex">
                <input type="text" name="search" class="form-control" placeholder="Type your keyword Here" value="<?php echo isset ($_GET['search']) ? $_GET['search'] : ""; ?>">
                <div><button type="submit" class="btn btn-primary m-0">Search</button></div>
            </form>
        </div>
        <div class="list">
            <?php
            // Perform deletion from database
            if (isset ($_GET['confirmed_delete_id']) && is_numeric($_GET['confirmed_delete_id'])) {
                $confirmed_delete_id = $_GET['confirmed_delete_id'];
                $sql_delete = "DELETE FROM posts WHERE id = $confirmed_delete_id";
                if ($conn->query($sql_delete) === TRUE) {
                    echo "<div class='alert alert-success' role='alert'>Successfully has been deleted.</div>";
                } else {
                    echo "<div class='alert alert-danger' role='alert'>Error deleting entry: " . $conn->error . "</div>";
                }
            }
            if (isset ($_GET['success'])) {
                echo "<div class='alert alert-success' role='alert'>" . $_GET['success'] . "</div>";
            }
            if ($result->num_rows > 0) {
                echo "<h2>Post Lists</h2>";
                echo "<table class='table'>";
                echo "<thead><tr><th>ID</th><th>Title</th><th>Content</th><th>Is Published</th><th>Created Date</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    $status = ($row['is_published'] == 1) ? "Published" : "Unpublished";
                    echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["title"] . "</td>
                        <td>" . substr_replace($row["content"], "...", 50) . "</td>
                        <td>$status</td>
                        <td>" . date("M d, Y", strtotime($row["created_datetime"])) . "</td>
                        <td>
                            <a class='btn btn-info' href='detail.php?id=" . $row["id"] . "'>View</a>
                            <a class='btn btn-success' href='edit.php?id=" . $row["id"] . "'>Edit</a>
                            <a class='btn btn-danger' href='index.php?delete_id=" . $row["id"] . "'>Delete</a>
                        </td>
                    </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<h2>No records found</h2>";
            }
            ?>
        </div>
        <?php
        // Pagination links
        $sql = "SELECT COUNT(*) AS total FROM posts";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $total_pages = ceil($row["total"] / $per_page);

        // Initialize $page variable to 1 if 'page' parameter is not set or is invalid
        $page = (isset ($_GET['page']) && is_numeric($_GET['page'])) ? intval($_GET['page']) : 1;
        $page = max(1, min($page, $total_pages)); // Ensure $page is within valid range
        
        $start = ($page - 1) * $per_page;
        ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination offset-5">
                <!-- Left arrow -->
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?page=<?php echo ($page <= 1) ? 1 : ($page - 1); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    // Adding active class to the current page
                    $active_class = ($i == $page) ? 'active' : '';
                    echo "<li class='page-item $active_class'><a class='page-link' href='index.php?page=$i'>$i</a></li>";
                }
                ?>

                <!-- Right arrow -->
                <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?page=<?php echo ($page >= $total_pages) ? $total_pages : ($page + 1); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

    </div>
</body>
</html>
<?php
// Close the database connection
$conn->close();
?>
