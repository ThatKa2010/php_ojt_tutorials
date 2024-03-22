<?php
session_start();
include ("db.php");

// Check if the user is logged in
if (!isset ($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user data from the database based on user ID
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $_SESSION['user_img'] = $user_data['img'];
} else {
    // If user ID not found, redirect to login page
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload profile image</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid d-flex justify-content-between px-5">
            <a class="navbar-brand" href="index.php">Home</a>
            <div class="dropdown me-5">
                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo (($user_data['img']) != null) ? $user_data['img'] : 'img/user.png'; ?>" alt="User Image" style="width:60px;">
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="col-6 offset-3">
            <!--<h1>Welcome <?php echo $_SESSION['username']; ?>!</h1>-->
            <h2>Upload profile image</h2>
            <form action="image_upload.php" method="POST" enctype="multipart/form-data" class="p-3">
                <div class="mb-3">
                    <img src="<?php echo (($user_data['img']) != null) ? $user_data['img'] : 'img/user.png'; ?>" alt="User Image" style="width:150px;">
                </div>
                <div class="mb-3">
                    <label for="img" class="form-label">Upload photo</label>
                    <input type="file" name="image" id="img" class="form-control">
                    <?php
                    if (isset ($_GET['falsetype'])) {
                        echo "<span class='text-danger'>" . $_GET['falsetype'] . "</span>";
                    }
                    if (isset ($_GET['oversize'])) {
                        echo "<span class='text-danger'>" . $_GET['oversize'] . "</span>";
                    }
                    ?>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $user_data['name']; ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $user_data['email']; ?>" disabled>
                </div>
                <a href="profile.php" class="btn btn-success">back</a>
                <button type="submit" class="btn btn-primary float-end">Update</button>
            </form>
        </div>
    </div>

    <?php
    include ("db.php");

    // Handle image upload if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset ($_FILES["image"]) && !empty ($_FILES["image"]["name"])) {
        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            header("Location: image_upload.php?falsetype=Sorry, only JPG, JPEG, PNG files are allowed.");
            $uploadOk = 0;
            exit();
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            header("Location: image_upload.php?oversize=Sorry, your file is too large. Image file must not be greater than 5MB.");
            $uploadOk = 0;
            exit();
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Update the user's profile image in the database
                $update_img_sql = "UPDATE users SET img = ? WHERE id = ?";
                $update_img_stmt = $conn->prepare($update_img_sql);
                $update_img_stmt->bind_param("si", $target_file, $user_id);
                $update_img_stmt->execute();

                // Redirect to profile page to display updated image
                header("Location: image_upload.php");
                exit();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    ?>

    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
