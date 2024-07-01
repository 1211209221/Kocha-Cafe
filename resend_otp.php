<?php
session_start();
include('connect.php');

if (isset($_POST)) {
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
        echo json_encode(['message' => 'Register Failed, Invalid Email']);
    } else {
        echo json_encode(['message' => 'Register Successfully, OTP sent to ' . $email]);
    }
}
?>
