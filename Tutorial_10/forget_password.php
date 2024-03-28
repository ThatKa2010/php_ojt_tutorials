<?php
session_start();
include ("db.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset ($_POST["email"])) {
    // Retrieve email from the form
    $email = $_POST["email"];

    // Check if the email exists in the database
    $check_email_sql = "SELECT * FROM users WHERE email = ?";
    $check_email_stmt = $conn->prepare($check_email_sql);
    $check_email_stmt->bind_param("s", $email);
    $check_email_stmt->execute();
    $check_email_result = $check_email_stmt->get_result();

    if ($check_email_result->num_rows > 0) {
        // Generate a unique token for password reset
        $token = rand(9999, 1111);

        // Store the token in the database for the user
        $store_token_sql = "UPDATE users SET reset_token = ? WHERE email = ?";
        $store_token_stmt = $conn->prepare($store_token_sql);
        $store_token_stmt->bind_param("ss", $token, $email);
        $store_token_stmt->execute();

        // Send an email to the user with the password reset link
        $reset_link = "http://".$_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset_password.php?token=$token";
        $to = $email;
        $subject = "Password Reset";
        $message = "Please click the following link to reset your password: $reset_link";
        $headers = "From: thatka920@gmail.com";

        mail($to, $subject, $message, $headers);

        // Set a success message
        $_SESSION["success_message"] = "Password reset link has been sent to your email.";
        header("Location: forget_password.php");
        exit();
    } else {
        // Set an error message
        $_SESSION["error_message"] = "Email not found.";
    }
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
    <div class="container mt-5">
        <div class="col-6 offset-3">
            <?php
            if (isset ($_SESSION["success_message"])) {
                echo "<div class='alert-success p-2' role='alert'>" . $_SESSION["success_message"] . "</div>";
                unset($_SESSION["success_message"]);
            }
            ?>
            <h2>Forget Password</h2>
            <form action="forget_password.php" method="post">
                <div class="p-3 mb-5">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" required>
                    <?php
                    if (isset ($_SESSION["error_message"])) {
                        echo "<span class='text-danger'>" . $_SESSION["error_message"] . "</span>";
                        unset($_SESSION["error_message"]);
                    }
                    ?>
                </div>
                <div class="p-4" style="background:#e3e3e3">
                    <a href="login.php" class="text-decoration-none">login</a>
                    <button type="submit" class="btn btn-primary float-end">Send</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
