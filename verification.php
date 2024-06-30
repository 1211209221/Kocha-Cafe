
<?php
    session_start();
    var_dump($_SESSION);
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

<script>
    // Pass the remaining time to JavaScript
    var remainingTime = <?php echo $remaining_time; ?>;
    var remainingResendTime = <?php echo $remaining_resend_time; ?>;
</script>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="icon" href="register/logo_icon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="Favicon.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <title>Verification</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="#">Verification Account</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<main class="login-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Verification Account</div>
                    <div class="card-body">
                        <form action="#" method="POST">
                            <div class="form-group row">
                                <label for="otp" class="col-md-4 col-form-label text-md-right">OTP Code</label>
                                <div class="col-md-6">
                                    <input type="text" id="otp" class="form-control" name="otp_code" required autofocus>
                                </div>
                            </div>

                            <div class="col-md-6 offset-md-4">
                                <input type="submit" value="Verify" name="verify">
                            </div>
                        </form>
                        <form action="#" method="POST">
                            <div class="col-md-6 offset-md-4 d-flex">
                                <input type="submit" value="Resend OTP" name="resend_otp" id="resendButton">
                                <p id="resendCountdown" class="my-0 ml-1" style="line-height: 1.7;"></p>
                            </div>
                        </form>
                        <div class="col-md-6 offset-md-4 mt-3">
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
