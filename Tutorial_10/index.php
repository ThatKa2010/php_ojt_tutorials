<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Authentication And Middleware</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <?php
    session_start();
    error_reporting(1);
    // Check if user is logged in
    if (isset ($_SESSION['user_id'])) {
        // User is logged in, show authenticated content
        echo '
                <nav class="navbar navbar-light bg-light">
                <div class="container-fluid d-flex justify-content-between px-5">
                    <a class="navbar-brand" href="#">Home</a>
                    <div class="dropdown me-5">
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="' . ($_SESSION['user_img'] != null ? $_SESSION['user_img'] : 'img/user.png') . '" alt="Dropdown Icon" style="width:60px;">
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                    </div>
                </div>
                </nav>

                <div class="container mt-5">
                <div class="col-6 offset-4">
                    <h1>Welcome ' . $_SESSION['username'] . '!</h1>
                </div>
                </div>';
    } else {
        // User is not logged in, show default content
        echo '
                <nav class="navbar navbar-light bg-light">
                <div class="container-fluid d-flex justify-content-between px-5">
                    <a class="navbar-brand" href="#">Home</a>
                    <div>
                    <a href="login.php" class="btn btn-primary">Login</a>
                    <a href="register.php" class="btn btn-primary">Register</a>
                    </div>
                </div>
                </nav>

                <div class="container mt-5">
                <div class="col-6 offset-4">
                    <h1>Welcome From My Website</h1>
                </div>
                </div>';
    }
    ?>
    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>
</html>
