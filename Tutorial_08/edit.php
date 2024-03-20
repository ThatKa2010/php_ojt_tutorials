<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Entry</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="col-6 offset-3">
            <div class="border">
                <h2>Edit Post</h2>
                <?php
                // Check if ID parameter is passed
                if (isset ($_GET['id'])) {
                    // Database connection
                    include ("db_connection.php");

                    // Get post data based on ID
                    $id = $_GET['id'];
                    $sql = "SELECT * FROM posts WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows === 1) {
                        $row = $result->fetch_assoc();
                        $title = $row['title'];
                        $content = $row['content'];
                        $publish = $row['is_published'];

                        // Display edit form
                        ?>
                        <form action='edit.php?id=<?php echo $id; ?>' method='POST' class='p-3'>
                            <div class='form-group'>
                                <label for='title'>Title:</label>
                                <input type='text' class='form-control' id='title' name='title' value='<?php echo isset($_POST["title"])?$_POST["title"]:$title; ?>'>
                                <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && empty ($_POST["title"])) {
                                    echo "<small class='text-danger'>Title is required</small>";
                                } ?>

                            </div>
                            <div class='form-group'>
                                <label for='content'>Content:</label>
                                <textarea class='form-control' id='content' name='content' rows='5'><?php echo isset($_POST["content"])?$_POST["content"]:$content; ?></textarea>
                                <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && empty ($_POST["content"])) {
                                    echo "<small class='text-danger'>Content is required</small>";
                                } ?>
                            </div>
                            <div class='form-group form-check'>
                                <input type='checkbox' class='form-check-input' id='publish' name='publish' <?php echo ($publish ? 'checked' : ''); ?>>
                                <label class='form-check-label' for='publish'>Publish</label>
                            </div>
                            <a href='index.php' class='btn btn-success'>Back</a>
                            <button type='submit' class='btn btn-primary float-right'>Save</button>
                        </form>
                        <?php
                    } else {
                        echo "<div class='alert alert-danger' role='alert'>Post not found.</div>";
                    }

                    // Check if form is submitted
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Get form data
                        $title = $_POST['title'];
                        $content = $_POST['content'];
                        $publish = isset ($_POST['publish']) ? 1 : 0;

                        if (empty ($title)) {
                            exit();
                        }

                        if (empty ($content)) {
                            exit();
                        }

                        // Update SQL statement
                        $sql_update = "UPDATE posts SET title=?, content=?, is_published=? WHERE id=?";
                        $stmt_update = $conn->prepare($sql_update);
                        $stmt_update->bind_param("ssii", $title, $content, $publish, $id);

                        if ($stmt_update->execute() === TRUE) {
                            header("Location: index.php?success=updated data successfully.");
                            exit();
                        } else {
                            echo "<div class='container mt-3'><div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div></div>";
                        }

                        $stmt_update->close();
                    }

                    // Close statement and connection
                    $stmt->close();
                    $conn->close();
                } else {
                    echo "<div class='alert alert-danger' role='alert'>No ID parameter provided.</div>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
