
<?php
    session_start();
    include('connect.php');

    // Pass the OTP expiration time to JavaScript
    $otp_time = isset($_SESSION['otp_time']) ? $_SESSION['otp_time'] : time();
    $current_time = time();
    $remaining_time = max(0, 180 - ($current_time - $otp_time));
    $remaining_resend_time = max(0, 60 - ($current_time - $otp_time));

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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <title>Verification</title>
    <style>
        body {
            background-image: url('images/otp.jpg'); /* Corrected syntax for background image */
            background-size: cover; /* Ensures the image covers the entire background */
        }
        .card {
            font-family: 'Afacad' !important;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
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
            max-height: 60px; /* Adjusted height for the logo */
            margin-bottom: 30px; /* Space between the logo and text */
        }
        .login-form {
            height: 100%;
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>


<main class="login-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <img src="logo_1.png" alt="Logo"> 
                        Verification Account
                    </div>
                    <div class="card-body text-center">
                        <form action="#" method="POST">
                            <div class="form-group row">
                                <label for="otp" class="col-md-4 col-form-label text-md-right">OTP Code</label>
                                <div class="col-md-6">
                                    <input type="text" id="otp" class="form-control text-center" name="otp_code" required autofocus>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <input type="submit" value="Verify" name="verify" class="btn btn-primary btn-block">
                                </div>
                            </div>
                        </form>
                        <form action="#" method="POST">
                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4 d-flex">
                                    <input type="submit" value="Resend OTP" name="resend_otp" id="resendButton" class="btn-primary btn-block">
                                    <p id="resendCountdown" class="my-0 ml-1" ></p>
                                </div>
                            </div>
                        </form>
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4 mt-3">
                                <p id="countdown"></p>
                            </div>
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
                countdownElement.textContent = ""; // Remove any text when countdown ends
            }
        }

        updateCountdown();
    }

    // Start the countdown when the page loads with the remaining time
    window.onload = function() {
        // Remaining time for main OTP countdown
        var remainingTime = <?php echo $remaining_time; ?>;
        startCountdown(remainingTime, 'countdown', 'Time left:');

        var remainingResendTime = <?php echo $remaining_resend_time; ?>;
        startCountdown(remainingResendTime, 'resendCountdown', ':');

        // Disable resend button if resend timer is active
        if (remainingResendTime > 0) {
            document.getElementById('resendButton').disabled = true;
            setTimeout(function() {
                document.getElementById('resendButton').disabled = false;
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

        $mail->Username = 'kochacafe1@gmail.com';
        $mail->Password = 'nstarhdtdgrzznze';

        $mail->setFrom('kochacafe1@gmail.com', 'OTP Verification');
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
