<style>
    .alert.alert-danger {
        padding: 5px 10px;
        margin-bottom: 0px;
        border-bottom-right-radius: 0px;
        border-bottom-left-radius: 0px;
        height: 30px;
    }
    input:placeholder-shown{
        opacity: 0;
    }
    input:placeholder-shown + .focus-input100 + .alert.alert-danger {
        display: none;
    }
    input:placeholder-shown:not(:focus) + .focus-input100 + .alert.alert-danger {
        display: block;
    }
    .btn-show-pass:has(+ input:placeholder-shown:not(:focus)){
        display: none;
    }
    .alert.alert-danger {
        font-size: 14px;
    }

</style>
<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
   exit(); // terminate script execution after redirect
}

$disabled_account = false;

// Initialize variables for input errors
$email_error = $password_error = "";

if (isset($_POST["login"])) {
    // Validate email
    if (empty($_POST["email"])) {
        $email_error = "Email is required";
    } else {
        $email = $_POST["email"];
        // Check if email is valid format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_error = "Invalid email format";
        }
    }

    // Validate password
    if (empty($_POST["password"])) {
        $password_error = "Password is required";
    }

    // If no errors, proceed with login
    if (empty($email_error) && empty($password_error)) {
        $email = $_POST["email"];
        require_once "connect.php";
        $sql = "SELECT * FROM customer WHERE cust_email = '$email'";
        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if ($user) {
            if (password_verify($_POST["password"], $user["cust_pass"])) {
                if ($user['trash'] == 1) {
                    $disabled_account = true;
                }
                else{
                    $_SESSION["user"] = $user["cust_ID"];
                    header("Location: index.php");
                    exit(); // terminate script execution after redirect
                }
            } else {
                $password_error = "Password does not match";
            }
        } else {
            $email_error = "Email does not exist";
        }
    }
}else{
    echo '<style>
        .alert.alert-danger {
            display:none !important;
        }
    </style>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kocha Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="login/css/logo_icon.png"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login/fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login/vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login/vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login/vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="login/css/util.css">
    <link rel="stylesheet" type="text/css" href="login/css/main.css">
    <!--===============================================================================================-->
</head>
<body>

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <form class="login100-form validate-form" method="POST" action="">
                <span class="login100-form-title p-b-26">
                    <img src="login/css/logo_1.png" alt="" style="width: 150px; height: auto;"> Login
                </span>
                <span class="login100-form-title p-b-48">
                </span>

                <div class="wrap-input100 validate-input" data-validate="Valid email is: a@b.c">
                    <input class="input100" type="text" name="email" required oninput="hideError('email_error')" placeholder="Type your email">
                    <span class="focus-input100" data-placeholder="Email"></span>
                    <?php if(isset($email_error) && empty($_POST["login"])) { echo "<div id='email_error' class='alert alert-danger'>$email_error</div>"; } ?>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Enter password">
                    <span class="btn-show-pass">
                        <i class="zmdi zmdi-eye"></i>
                    </span>
                    <input class="input100" type="password" name="password" required oninput="hideError('password_error')" placeholder="Type your password">
                    <span class="focus-input100" data-placeholder="Password"></span>
                    <?php if(isset($password_error) && empty($_POST["login"])) { echo "<div id='password_error' class='alert alert-danger'>$password_error</div>"; } ?>
                </div>
                <div class="text-center">
                    <a class="txt1" href="recover_psw.php">
                        Forgot Password
                    </a>
                </div>

                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn" name="login">
                            Login
                        </button>
                    </div>
                </div>

                <div class="text-center p-t-115">
                    <span class="txt1">
                        Don’t have an account?
                    </span>

                    <a class="txt2" href="registration.php">
                        Sign Up
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="dropDownSelect1"></div>

<!--===============================================================================================-->
<script src="login/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="login/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
<script src="login/vendor/bootstrap/js/popper.js"></script>
<script src="login/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="login/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
<script src="login/vendor/daterangepicker/moment.min.js"></script>
<script src="login/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
<script src="login/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
<script src="login/js/main.js"></script>

<script>
    function hideError(errorId) {
        var errorElement = document.getElementById(errorId);
        if (errorElement) {
            errorElement.style.display = "none";
        }
    }
</script>
<?php if ($disabled_account): ?>
    <script type="text/javascript">
        alert("Your account has been disabled. Please contact our customer service if there's a mistake.");
        window.location.href = "login.php";
    </script>
    <?php
    exit();
    ?>
<?php endif; ?>


</body>
</html>