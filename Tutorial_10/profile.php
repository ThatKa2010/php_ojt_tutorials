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
    <title>User Profile</title>
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
            <?php
            if (isset ($_GET['successUpdated'])) {
                echo "<div class='alert-success p-3' role='alert'>User data updated successfully.</div>";
            }
            ?>

            <h2>My Profile Setting</h2>
            <form action="profile.php" method="POST" enctype="multipart/form-data" class="p-3">
                <div class="mb-3">
                    <img src="<?php echo (($user_data['img']) != null) ? $user_data['img'] : 'img/user.png'; ?>" alt="User Image" style="width:100px;">
                    <a href="image_upload.php" class="btn btn-outline-success rounded-pill px-4 ms-5">Upload</a>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo isset ($_POST['name']) ? $_POST['name'] : $user_data['name']; ?>">
                    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && empty ($_POST['name'])) {
                        echo "<span class='text-danger'>Name is required.</span>";
                    } ?>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset ($_POST['email']) ? $_POST['email'] : $user_data['email']; ?>">
                    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && empty ($_POST['email'])) {
                        echo "<span class='text-danger'>Email is required.</span>";
                    } ?>
                    <?php if (isset ($_GET['already-email'])) {
                        echo "<span class='text-danger'>Email have already exists!</span>";
                    } ?>
                </div>
                <div class="text-end"><button type="submit" class="btn btn-primary">Update</button></div>
            </form>
        </div>
    </div>

    <?php
    include ("db.php");
    // Update user email and name if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_name = $_POST['name'];
        $new_email = $_POST['email'];
        if ($new_name == "" || $new_email == "") {
            exit();
        } else {
            if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                exit();
            } else {
                if ($new_email != $user_data['email']) {
                    $check_email_sql = "SELECT * FROM users WHERE email = ?";
                    $check_email_stmt = $conn->prepare($check_email_sql);
                    $check_email_stmt->bind_param("s", $new_email);
                    $check_email_stmt->execute();
                    $check_email_result = $check_email_stmt->get_result();

                    if ($check_email_result->num_rows > 0) {
                        header("Location: profile.php?already-email");
                        exit();
                    } else {
                        $update_sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
                        $update_stmt = $conn->prepare($update_sql);
                        $update_stmt->bind_param("ssi", $new_name, $new_email, $user_id);
                        $update_stmt->execute();

                        header("Location: profile.php?successUpdated");
                        exit();
                    }
                } else {
                    $update_sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("ssi", $new_name, $new_email, $user_id);
                    $update_stmt->execute();

                    header("Location: profile.php?successUpdated");
                    exit();
                }
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
