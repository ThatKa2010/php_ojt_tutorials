<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container mt-5">
        <div class="col-6 offset-3">
            <h2>Register</h2>
            <form action="register.php" class="p-3" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?php echo isset ($_POST['name']) ? $_POST['name'] : ""; ?>">
                    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['name'] == "") {
                        echo "<span class='text-danger'>Name field is require!</span>";
                    } ?>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="email" id="email" class="form-control" value="<?php echo isset ($_POST['email']) ? $_POST['email'] : ""; ?>">
                    <?php
                    if (isset ($_GET['already-email'])) {
                        echo "<span class='text-danger'>Email have already exists!</span>";
                    }
                    ?>
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $email = $_POST['email'];
                        if ($email == "") {
                            echo "<span class='text-danger'>Email field is require!</span>";
                        } else {
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                echo "<span class='text-danger'>Invalid email format!</span>";
                            }
                        }
                    }
                    ?>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="number" name="phone" id="phone" class="form-control" value="<?php echo isset ($_POST['phone']) ? $_POST['phone'] : ""; ?>">
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $phone = $_POST['phone'];
                        if ($phone == "") {
                            echo "<span class='text-danger'>Phone field is require!</span>";
                        } else {
                            if (strlen($phone) > 11) {
                                echo "<span class='text-danger'>Phone number cannot exceed 11 digits.</span>";
                            }
                        }
                    }
                    ?>
                </div>
                <div class="mb-3">
                    <label for="pass" class="form-label">Password</label>
                    <input type="password" name="password" id="pass" class="form-control">
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $password = $_POST['password'];
                        if ($password == "") {
                            echo "<span class='text-danger'>Password field is require!</span>";
                        } else {
                            if (strlen($password) < 8 || strlen($password) > 16) {
                                echo "<span class='text-danger'>Password must be between 8 and 16 characters.</span>";
                            }
                        }
                    }
                    ?>
                </div>
                <div class="mb-4">
                    <label for="add" class="form-label">Address</label>
                    <input type="text" name="address" id="add" class="form-control" value="<?php echo isset ($_POST['address']) ? $_POST['address'] : ""; ?>">
                    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['address'] == "") {
                        echo "<span class='text-danger'>Address field is require!</span>";
                    } ?>
                </div>
                <div class="mb-3"><button class="btn btn-primary w-100" name="register">Register</button></div>
                <div class="text-center"><a href="login.php" class="text-decoration-none">Already have an account?</a></div>
            </form>
        </div>
    </div>

    <?php
    session_start(); // Start session
    include ("db.php");

    if (isset ($_POST['register'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $address = $_POST['address'];

        // Validate if any field is empty
        if ($name == "" || $email == "" || $phone == "" || $password == "" || $address == "") {
            exit();
        } else {
            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                exit();
            } else {
                // Check if email is already registered
                $check_email_sql = "SELECT * FROM users WHERE email = ?";
                $check_email_stmt = $conn->prepare($check_email_sql);
                $check_email_stmt->bind_param("s", $email);
                $check_email_stmt->execute();
                $check_email_result = $check_email_stmt->get_result();

                if ($check_email_result->num_rows > 0) {
                    header("Location: register.php?already-email");
                    exit();
                } else {
                    // Validate password length
                    if (strlen($password) < 8 || strlen($password) > 16) {
                        exit();
                    } else {
                        // Validate phone number length
                        if (strlen($phone) > 11) {
                            exit();
                        } else {
                            // Hash the password
                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                            // Insert new user into database
                            $insert_sql = "INSERT INTO users (name, email, phone, password, address) VALUES (?, ?, ?, ?, ?)";
                            $insert_stmt = $conn->prepare($insert_sql);
                            $insert_stmt->bind_param("sssss", $name, $email, $phone, $hashed_password, $address);

                            if ($insert_stmt->execute()) {
                                $_SESSION['user_id'] = $insert_stmt->insert_id; // Store user ID in session
                                $_SESSION['username'] = $name; // Store username in session
                                header("Location: index.php"); // Redirect to index.php
                                exit;
                            } else {
                                echo "<div class='col-5 offset-4 text-danger bg-warning p-3 mb-5'>Registration failed: " . $conn->error . "</div>";
                            }
                        }
                    }
                }
            }
        }
    }
    ?>
</body>
</html>
