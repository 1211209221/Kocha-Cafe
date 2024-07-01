<?php
session_start();
include('connect.php');
?>

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="Favicon.png">
    <title>Password Recovery</title>
    <style>
        html {
            height: 100%;
        }
        body {
            background-image: url('images/otp.jpg'); /* Update the path to your actual background image */
            background-size: cover;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .card {
            font-family: 'Afacad', sans-serif !important;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
            padding-top: 40px;
            padding-bottom: 25px;
            font-family: 'Afacad', sans-serif !important;
            background-color: #ffffff; /* White background for header */
            border-bottom: none;
            font-weight: bold;
            font-size: 1.25rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center; /* Center align the text */
        }
        .card-header img {
            max-height: 60px; /* Adjust as needed */
            width: auto;
            max-width: 100%;
            margin-bottom: 10px; /* Space between image and text */
        }
        .card-body {
            padding: 2rem;
            background-color: #ffffff;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
        }
        .btn-primary {
            height: 100%;
            width: 100%;
            border-radius: 5px;
            border: none;
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            letter-spacing: 1px;
            cursor: pointer;
            background: linear-gradient(135deg, #ff5784, #0290a5);
            background-size: 200% auto;
            transition: background-position 0.5s ease;
        }
        .btn-primary:hover { 
            background-position: right center;
        }
        .login-form {
            justify-content: center;
            height: 100%;
            display: flex;
            align-items: center;
        }
        input[type="password"] {
            background-color: #EDEDED !important;
            border: #EDEDED 1px solid !important;
            margin: 3px;
            width: 100%;
            border-radius: 6px;
            padding: 22px 10px;
            font-size: 19px;
            outline: none !important;
            font-family: 'Afacad', sans-serif !important;
        }
        input[name="reset"] {
            margin: 3px;
            width: 100%;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 18px;
        }
        input:disabled {
            color: #c3c3c3 !important;
            cursor: auto;
            pointer-events: none;
        }
        .container-verification {
            width: 400px;
        }
        @media (max-width: 768px) {
            .container-verification {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<main class="login-form">
    <div class="container-verification">
        <div class="card">
            <div class="card-header"><img src="logo_1.png" alt="Logo">Reset Your Password</div>
            <div class="card-body">
                <form action="#" method="POST" name="login">
                    <div class="form-group">
                        <input type="password" id="password" class="form-control" name="password" placeholder="New Password" required autofocus>
                        <i class="bi bi-eye-slash" id="togglePassword"></i>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Reset" name="reset">
                </form>
            </div>
        </div>
    </div>
</main>
</body>
</html>
<?php
    if(isset($_POST["reset"])){
        include('connect.php');
        $psw = $_POST["password"];

        if(strlen($psw) < 8) {
            ?>
            <script>
                alert("Password must be at least 8 characters long.");
            </script>
            <?php
            exit; // Stop further execution
        }

        if(isset($_GET['token'])) {
            // Get the value of the 'token' parameter
            $token = $_GET['token'];
            
        } else {
            // Handle the case where the 'token' parameter is not present
            echo "Token not found in the URL.";
        }
        if(isset($_GET['email'])) {
            // Get the value of the 'token' parameter
            $email = $_GET['email'];
            
        } else {
            // Handle the case where the 'token' parameter is not present
            echo "Email not found in the URL.";
        }

        $hash = password_hash($psw, PASSWORD_DEFAULT);

        $sql = mysqli_query($conn, "SELECT * FROM customer WHERE cust_email='$email'");
        $query = mysqli_num_rows($sql);
  	    $fetch = mysqli_fetch_assoc($sql);

        if($query > 0){
            // Check if the entered password is different from the current one
            if(password_verify($psw, $fetch['cust_pass'])) {
                ?>
                <script>
                    alert("Please choose a different password. You cannot use the same password as the current one.");
                </script>
                <?php
            } else {
                // Proceed with password reset if the entered password is different
                $new_pass = $hash;
                mysqli_query($conn, "UPDATE customer SET cust_pass='$new_pass' WHERE cust_email='$email'");
                ?>
                <script>
                    window.location.replace("login.php");
                    alert("Your password has been successfully reset.");
                </script>
                <?php
            }
        } else {
            ?>
            <script>
                alert("Please try again.");
            </script>
            <?php
        }
    }
?>
<script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    toggle.addEventListener('click', function(){
        if(password.type === "password"){
            password.type = 'text';
        }else{
            password.type = 'password';
        }
        this.classList.toggle('bi-eye');
    });
</script>
