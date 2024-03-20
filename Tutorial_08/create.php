<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Entry</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="col-6 offset-3">
            <div class="border">
                <h2>Create Post</h2>
                <form action="create.php" method="POST" class="p-3">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo isset($_POST['title'])?$_POST['title']:"";?>">
                        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && empty ($_POST["title"])) {
                            echo "<small class='text-danger'>Title is required</small>";
                        } ?>
                    </div>
                    <div class="form-group">
                        <label for="content">Content:</label>
                        <textarea class="form-control" id="content" name="content" rows="5"><?php echo isset($_POST['content'])?$_POST['content']:"";?></textarea>
                        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && empty ($_POST["content"])) {
                            echo "<small class='text-danger'>Content is required</small>";
                        } ?>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="publish" name="publish">
                        <label class="form-check-label" for="publish">Publish</label>
                    </div>
                    <a href="index.php" class="btn btn-success">back</a>
                    <button type="submit" class="btn btn-primary float-right" name="submit">Create</button>
                </form>
            </div>
        </div>
    </div>

    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Database connection
        include ("db_connection.php");

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

        // Prepare SQL statement
        $sql = "INSERT INTO posts (title, content, is_published) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $publish);

        // Execute SQL statement
        if ($stmt->execute() === TRUE) {
            echo "<div class='container mt-3'><div class='alert alert-success' role='alert'>New entry created successfully.</div></div>";
        } else {
            echo "<div class='container mt-3'><div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div></div>";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
