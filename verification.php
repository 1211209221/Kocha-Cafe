
<?php
    session_start();
    include('connect.php');

    // Pass the OTP expiration time to JavaScript
    $otp_time = isset($_SESSION['otp_time']) ? $_SESSION['otp_time'] : time();
    $current_time = time();
    $remaining_time = max(0, 95 - ($current_time - $otp_time));
    $remaining_resend_time = max(0, 35 - ($current_time - $otp_time));

    if(!empty($_SESSION)){
        $email = $_SESSION['mail'];

        $check_status = mysqli_query($conn, "SELECT * FROM customer where cust_email ='$email' AND status = 1");
        $rowCount = mysqli_num_rows($check_status);

        if ($rowCount > 0) {
            session_destroy();
            echo '<script>';
            echo 'window.location.href = "login.php";';
            echo '</script>';

        }
    }else if(empty($_SESSION)){
        echo '<script>';
        echo 'window.location.href = "login.php";';
        echo '</script>';
    }
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="icon" href="register/logo_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <title>Verification</title>
    <style>
        html{
            height: 100%;
        }
        body {
            background-image: url('images/otp.jpg');
            background-size: cover;
            height: 100%;
        }
        .card {
            font-family: 'Afacad' !important;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
            padding-top: 40px;
            padding-bottom: 25px;
            font-family: 'Afacad' !important;
            background-color: #ffffff; /* White background for header */
            border-bottom: none;
            font-weight: bold;
            font-size: 1.25rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
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
            font-size: 16px; /* Adjusted font size */
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
        .card-header img {
            max-height: 60px;
            bottom: -7px;
            position: relative;
        }
        .login-form {
            height: 100%;
            display: flex;
            align-items: center;
        }
        input[type="text"]{
            background-color: #EDEDED !important;
            border: #EDEDED 1px solid !important;
            margin: 3px;
            width: 100%;
            border-radius: 6px;
            padding: 22px 10px;
            font-size: 22px;
            outline: none !important;
        }
        input[name="verify"]{
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
        input.resendButton{
            background-color: transparent;
            color: #666666;
            border: none;
            transition: 0.3s;
            cursor: pointer;
        }
        input:focus{
            border-color: #EDEDED !important;
            outline: none !important;
            box-shadow: none !important;
        }
        .container-verification{
            width: 400px;
        }

        @media (max-width: 768px){
            .container-verification{
                width: 90%;
            }
        }
    </style>
</head>
<body>


<main class="login-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="container-verification">
                <div class="card">
                    <div class="card-header text-center">
                        <img src="logo_1.png" alt="Logo"> 
                        Account Verification
                    </div>
                    <div class="card-body text-center">
                        <form action="#" method="POST">
                            <div class="form-group row justify-content-center mb-1">
                                <div class="col-md-11">
                                    <input type="text" id="otp" class="form-control text-center" name="otp_code" placeholder="OTP code" required>
                                </div>
                            </div>
                            <div class="form-group row justify-content-center mb-2">
                                <div class="col-md-11">
                                    <input type="submit" value="Verify" name="verify" class="btn btn-primary btn-block">
                                </div>
                            </div>
                        </form>
                        <form action="#" method="POST">
                            <div class="form-group row justify-content-center">
                                <div class="col-md-11 d-flex justify-content-center">
                                    <input type="submit" value="Resend OTP" name="resend_otp" id="resendButton" class="resendButton">
                                    <p id="resendCountdown" class="my-0 ml-1" ></p>
                                </div>
                            </div>
                        </form>
                        <div class="form-group row justify-content-center mt-4 mb-1">
                            <p id="countdown"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    // Function to start the countdown
    function startCountdown(remainingTime, elementId, message) {
        const countdownElement = document.getElementById(elementId);
        const endTime = Date.now() + remainingTime * 1000;

        function updateCountdown() {
            const now = Date.now();
            const timeLeft = Math.max(0, Math.floor((endTime - now) / 1000));

            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;

            if (timeLeft > 0) {
                countdownElement.textContent = `${message} ${minutes}m ${seconds}s`;
                requestAnimationFrame(updateCountdown);
            } else {
                countdownElement.textContent = ""; // Clear countdown text
            }
        }

        updateCountdown();
    }

    // Start the countdown when the page loads with the remaining time
    window.onload = function() {
        // Remaining time for main OTP countdown
        var remainingTime = <?php echo $remaining_time; ?>;
        startCountdown(remainingTime, 'countdown', 'Code expires in:');

        var remainingResendTime = <?php echo $remaining_resend_time; ?>;
        startCountdown(remainingResendTime, 'resendCountdown', ':');

        // Disable resend button if resend timer is active
        if (remainingResendTime > 0) {
            document.getElementById('resendButton').disabled = true;
            setTimeout(function() {
                document.getElementById('resendButton').disabled = false;
                document.getElementById('resendCountdown').textContent = ""; // Clear countdown text
            }, remainingResendTime * 1000);
        }
    };

    document.getElementById('resendButton').addEventListener('click', function() {
        // Handle click event for Resend OTP button
        // You can add logic here to resend OTP
        setTimeout(function() {
            document.getElementById('resendButton').disabled = true;
        }, 20); // This is a very short delay, adjust as needed
    });
</script>
<?php
    if (isset($_POST['verify'])) {
        $otp = $_SESSION['otp'];
        $email = $_SESSION['mail'];
        $otp_code = $_POST['otp_code'];
        $otp_time = $_SESSION['otp_time'];
        $current_time = time();

        // Check if OTP has expired (3 minutes = 180 seconds)
        if (($current_time - $otp_time) > 180) {
            echo "<script>alert('OTP code has expired');</script>";
        } elseif ($otp != $otp_code) {
            echo "<script>alert('Invalid OTP code');</script>";
        } else {
            mysqli_query($conn, "UPDATE customer SET status = 1 WHERE cust_email = '$email'");
            echo "<script>
                    alert('Verify account done, you may sign in now');
                    window.location.replace('login.php');
                  </script>";
        }
    }

    if (isset($_POST['resend_otp'])) {
        // Generate a new OTP code
        $new_otp = rand(100000, 999999);
        $_SESSION['otp'] = $new_otp;
        $_SESSION['otp_time'] = time(); // Store the current time
        $email = $_SESSION['mail'];

        require "phpmailer/PHPMailerAutoload.php";
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';

        $mail->Username = 'kochacafe8@gmail.com';
        $mail->Password = 'bktz mine wgfr ayis';

        $mail->setFrom('kochacafe8@gmail.com', 'Kocha Cafe');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Your verification code";
        $mail->Body = "<p>Dear user, </p> <h3>Your verification OTP code is $new_otp <br></h3>
        <br><br>
        <p>With regards,</p>
        <b>Kocha Cafe</b>";

        if (!$mail->send()) {
            echo "<script>alert('Register Failed, Invalid Email');</script>";
        } else {
            echo "<script>
                    alert('Register Successfully, OTP sent to " . $email . "');
                    window.location.replace('verification.php');
                  </script>";
        }
    }
?>
</body>
</html>
