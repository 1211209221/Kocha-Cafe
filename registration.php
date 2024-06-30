<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Kocha Registration</title>
    <link rel="icon" href="register/logo_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="register/register.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .title {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        .title img {
            height: 60px;
            margin-bottom: 10px;
        }

        .alert {
            display: none;
            padding: 15px;
            border: 1px solid transparent;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        body {
            background-image: url('register/register-bg.jpg');
            background-size: cover;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="title">
            <img src="register/logo_1.png" alt="Logo" class="logo">Registration
        </div>
        <div class="content">
            <?php
            require_once "connect.php";
            session_start();

            $errors = array();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $fullName = $_POST["fullname"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $passwordRepeat = $_POST["repeat_password"];

                $check_name_query = mysqli_query($conn, "SELECT * FROM customer WHERE cust_username ='$fullName'");
                $check_query = mysqli_query($conn, "SELECT * FROM customer where cust_email ='$email'");
                $rowCount = mysqli_num_rows($check_query);
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat)) {
                    array_push($errors, "All fields are required");
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    array_push($errors, "Email is not valid");
                }
                if (strlen($password) < 8) {
                    array_push($errors, "Password must be at least 8 characters long");
                }
                if ($password != $passwordRepeat) {
                    array_push($errors, "Password does not match");
                }

                // Check if the username already exists in the database
                if (mysqli_num_rows($check_name_query) > 0) {
                    array_push($errors, "Username already exists");
                }

                $sql = "SELECT * FROM customer WHERE cust_email = ?";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        array_push($errors, "Email already exists!");
                    }
                } else {
                    array_push($errors, "Database error");
                }

                if (count($errors) == 0) {
                    $sql = "INSERT INTO customer (cust_username, cust_email, cust_pass) VALUES (?, ?, ?)";
                    $stmt = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt, $sql)) {
                        mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
                        mysqli_stmt_execute($stmt);
                        echo "<div id='success-message' class='alert alert-success'>You have registered successfully.</div>";

                        // Send verification email
                        $otp = rand(100000, 999999);
                        $_SESSION['otp'] = $otp;
                        $_SESSION['mail'] = $email;
                        $_SESSION['otp_time'] = time();

                        require "phpmailer/PHPMailerAutoload.php";
                        $mail = new PHPMailer;

                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->Port = 587;
                        $mail->SMTPAuth = true;
                        $mail->SMTPSecure = 'tls';

                        $mail->Username = 'kochacafe8@gmail.com';
                        $mail->Password = 'bktz mine wgfr ayis';

                        $mail->setFrom('kochacafe8@gmail.com', 'OTP Verification');
                        $mail->addAddress($_POST["email"]);

                        $mail->isHTML(true);
                        $mail->Subject = "Your verify code";
                        $mail->Body = "<p>Dear user, </p> <h3>Your verify OTP code is $otp <br></h3>
                        <br><br>
                        <p>With regards,</p>
                        <b>Kocha Cafe</b>";

                        if (!$mail->send()) {
                            ?>
                            <script>
                                alert("<?php echo "Register Failed, Invalid Email " ?>");
                            </script>
                            <?php
                        } else {
                            ?>
                            <script>
                                alert("<?php echo "Register Successfully, OTP sent to " . $email ?>");
                                window.location.replace('verification.php');
                            </script>
                            <?php
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Something went wrong</div>";
                    }
                }
            }
            ?>

            <?php
            // Display error messages if there are any
            if ($_SERVER["REQUEST_METHOD"] == "POST" && count($errors) > 0) {
                $errorMessage = implode("\\n", $errors);
                echo "<script>alert('$errorMessage');</script>";
            }
            ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">Username</span>
                        <input type="text" name="fullname" placeholder="Enter your username"
                            value="<?php echo isset($_POST['fullname']) ? $_POST['fullname'] : ''; ?>" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Email</span>
                        <input type="email" name="email" placeholder="Enter your email"
                            value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Password</span>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Confirm Password</span>
                        <input type="password" name="repeat_password" placeholder="Confirm your password" required>
                    </div>
                </div>
                <div class="button">
                    <input type="submit" name="submit" value="Register">
                </div>
            </form>
            <div>
                <p>Already Registered? <a href="login.php">Login Here</a></p>
            </div>
        </div>
    </div>
</body>

</html>