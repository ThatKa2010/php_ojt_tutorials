<?php
session_start(); // Start session

if (isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to index.php if already logged in
    exit;
}

$conn = new mysqli("localhost", "root", "", "tutorial-10", "3307");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input fields
    $errors = array();
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

    if (empty($errors)) {
        // Check if user exists with the provided email
        $check_user_sql = "SELECT * FROM users WHERE email = ?";
        $check_user_stmt = $conn->prepare($check_user_sql);
        $check_user_stmt->bind_param("s", $email);
        $check_user_stmt->execute();
        $check_user_result = $check_user_stmt->get_result();

        if ($check_user_result->num_rows == 1) {
            $user = $check_user_result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id']; 
                $_SESSION['username'] = $user['name'];
                $_SESSION['user_img'] = $user['img'];
                header("Location: index.php");
                exit;
            } else {
                $errors['general'] = "Invalid email or password.";
            }
        } else {
            $errors['general'] = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-6">
           <h2 class="text-center mb-4">Login</h2>
            <?php if (isset($errors['general'])): ?>
                <div class="alert alert-danger"><?php echo $errors['general']; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" placeholder="name@gmail.com">
                    <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="password">
                    <?php if (isset($errors['password'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                    <?php endif; ?>
                    <a href="forget_password.php" class="text-decoration-none">forget password?</a>
                </div>
                <button type="submit" class="btn btn-primary w-100" name="login">Login</button>
            </form>
            <div class="text-center mt-3">Not a member? <a href="register.php" class="text-decoration-none">Sign Up</a></div>
        </div>
    </div>
</div>
</body>
</html>
