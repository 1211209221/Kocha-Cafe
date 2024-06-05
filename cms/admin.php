<?php
session_start();
$error_message = ""; // Initialize error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["admin_username"]) && isset($_POST["admin_pass"])) {
        $username = $_POST["admin_username"];

        // Using prepared statements to prevent SQL injection
        require_once "connect.php";
        $sql = "SELECT * FROM admin WHERE admin_username = '$username'";
        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
        // Check if any rows were returned
        if (password_verify( $_POST["admin_pass"], $user["admin_pass"])) {
            // Fetch the row
            $row = mysqli_fetch_assoc($result);
                
                $_SESSION["admin"] = $user["admin_ID"];
                header("location:dashboard.php");
                
                exit(); // Terminate script execution after redirection

        } else {
            $error_message = "Username or password incorrect";
        }
    } else {
        $error_message = "Please enter both username and password";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="adminlogin/css/logo_icon.png" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="adminlogin/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="adminlogin/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="adminlogin/fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="adminlogin/vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="adminlogin/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="adminlogin/vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="adminlogin/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="adminlogin/vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="adminlogin/css/util.css">
    <link rel="stylesheet" type="text/css" href="adminlogin/css/main.css">
    <!--===============================================================================================-->
</head>

<body>

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form validate-form" method="POST" action="">
                    <span class="login100-form-title p-b-26">
                        <img src="adminlogin/css/logo_1.png" alt="" style="width: 150px; height: auto;">
                    </span>
                    <span class="login100-form-title p-b-48">
                        Admin Login
                    </span>

                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="text" name="admin_username" required>
                        <span class="focus-input100" data-placeholder="Username"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <span class="btn-show-pass">
                            <i class="zmdi zmdi-eye"></i>
                        </span>
                        <input class="input100" type="password" name="admin_pass" required oninput="hideError('password_error')">
                        <span class="focus-input100" data-placeholder="Password"></span>
                    </div>

                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn" name="login">
                                Login
                            </button>
                        </div>
                    </div>
                    <?php
                    if (!empty($error_message)) {
                        echo "<div class='alert alert-danger'>$error_message</div>";
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>

    <div id="dropDownSelect1"></div>

    <!--===============================================================================================-->
    <script src="adminlogin/vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="adminlogin/vendor/animsition/js/animsition.min.js"></script>
    <!--===============================================================================================-->
    <script src="adminlogin/vendor/bootstrap/js/popper.js"></script>
    <script src="adminlogin/vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="adminlogin/vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="adminlogin/vendor/daterangepicker/moment.min.js"></script>
    <script src="adminlogin/vendor/daterangepicker/daterangepicker.js"></script>
    <!--===============================================================================================-->
    <script src="adminlogin/vendor/countdowntime/countdowntime.js"></script>
    <!--===============================================================================================-->
    <script src="adminlogin/js/main.js"></script>

    <script>
        function hideError(errorId) {
            var errorElement = document.getElementById(errorId);
            if (errorElement) {
                errorElement.style.display = "none";
            }
        }
    </script>

</body>

</html>
