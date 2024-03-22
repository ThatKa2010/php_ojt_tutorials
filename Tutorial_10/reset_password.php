<?php
session_start();
include ("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset ($_GET["token"])) {
        $token = $_GET["token"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        $current_email = $_POST["current_email"];

        if ($password !== $confirm_password) {
            $_SESSION["error_message"] = "Passwords do not match.";
            header("Location: reset_password.php?token=" . urlencode($token));
            exit();
        }

        $check_token_sql = "SELECT * FROM users WHERE reset_token = ?";
        $check_token_stmt = $conn->prepare($check_token_sql);
        $check_token_stmt->bind_param("s", $token);
        $check_token_stmt->execute();
        $check_token_result = $check_token_stmt->get_result();

        if ($check_token_result->num_rows > 0) {
            $user = $check_token_result->fetch_assoc();
            if ($user['email'] !== $current_email) {
                $_SESSION["error_message"] = "Email does not match, please check your email.";
                header("Location: reset_password.php?token=" . urlencode($token));
                exit();
            }
            // Validate password length
            if (strlen($password) < 8 || strlen($password) > 16) {
                $_SESSION["error_message"] = "Password must be between 8 and 16 characters.";
                header("Location: reset_password.php?token=" . urlencode($token));
                exit();
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $update_password_sql = "UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ? AND email = ?";
            $update_password_stmt = $conn->prepare($update_password_sql);
            $update_password_stmt->bind_param("sss", $hashed_password, $token, $current_email);
            if ($update_password_stmt->execute()) {
                $_SESSION["success_message"] = "Password reset successfully. You can now log in with your new password.";
                header("Location: login.php");
                exit();
            } else {
                $_SESSION["error_message"] = "An error occurred while resetting your password.";
            }
        } else {
            $_SESSION["error_message"] = "Invalid or expired token.";
        }
    } else {
        $_SESSION["error_message"] = "Token not provided.";
    }
    header("Location: forget_password.php");
    exit();
}

// Token is not provided or invalid
if (!isset ($_GET["token"])) {
    $_SESSION["error_message"] = "Token not provided.";
    header("Location: forget_password.php");
    exit();
}

$token = $_GET["token"];
$check_token_sql = "SELECT * FROM users WHERE reset_token = ?";
$check_token_stmt = $conn->prepare($check_token_sql);
$check_token_stmt->bind_param("s", $token);
$check_token_stmt->execute();
$check_token_result = $check_token_stmt->get_result();

if ($check_token_result->num_rows <= 0) {
    $_SESSION["error_message"] = "Invalid or expired token.";
    header("Location: forget_password.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container mt-5">
        <div class="col-6 offset-md-3">
            <h2>Reset Password</h2>
            <?php
            if (isset ($_SESSION["error_message"])) {
                echo '<div class="alert alert-danger">' . $_SESSION["error_message"] . '</div>';
                unset($_SESSION["error_message"]);
            }
            ?>
            <form action="reset_password.php?token=<?php echo urlencode($token); ?>" method="POST">
                <div class="mb-3 px-3">
                    <label for="current_email" class="form-label">Current Email</label>
                    <input type="email" class="form-control" id="current_email" name="current_email" required>
                </div>
                <div class="mb-3 px-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3 px-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="text-end p-3" style="background:#e3e3e3;">
                <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>
