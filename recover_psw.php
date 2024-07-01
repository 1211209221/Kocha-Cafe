<?php session_start() ?>
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
        input[type="email"] {
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
        input[name="recover"] {
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
            <div class="card-header"><img src="logo_1.png" alt="Logo">Password Recovery</div>
            <div class="card-body">
                <form action="#" method="POST" name="recover_psw">
                    <div class="form-group">
                        <input type="email" id="email_address" class="form-control" name="email" placeholder="E-mail Address" required autofocus>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Recover" name="recover">
                </form>
            </div>
        </div>
    </div>
</main>
</body>
</html>

<?php 
    if(isset($_POST["recover"])){
        include('connect.php');
        $email = $_POST["email"];

        $sql = mysqli_query($conn, "SELECT * FROM customer WHERE cust_email='$email'");
        $query = mysqli_num_rows($sql);
  	    $fetch = mysqli_fetch_assoc($sql);

        if(mysqli_num_rows($sql) <= 0){
            ?>
            <script>
                alert("<?php  echo "Sorry, no emails exists "?>");
            </script>
            <?php
        }else if($fetch["status"] == 0){
            ?>
               <script>
                   alert("Sorry, your account must verify first, before you recover your password !");
                   window.location.replace("login.php");
               </script>
           <?php
        }   
            require "phpmailer/PHPMailerAutoload.php";
            $mail = new PHPMailer;

            $mail->isSMTP();
            $mail->Host='smtp.gmail.com';
            $mail->Port=587;
            $mail->SMTPAuth=true;
            $mail->SMTPSecure='tls';

            // h-hotel account
            $mail->Username = 'kochacafe8@gmail.com';
            $mail->Password = 'bktz mine wgfr ayis';

            // send by h-hotel email
            $mail->setFrom('kochacafe8@gmail.com', 'Kocha Cafe');
            // get email from input
            $mail->addAddress($_POST["email"]);
            //$mail->addReplyTo('lamkaizhe16@gmail.com');

            // HTML body
            $mail->isHTML(true);
            $mail->Subject = "Recover your password";
            $mail->Body = "<html>
                            <body>
                                <b>Dear User,</b>
                                <h3>We received a request to reset your password.</h3>
                                <p>Kindly click the below link to reset your password:</p>
                                <a href='http://localhost/Kocha-Cafe/reset_psw.php?token=$token&email=$email'>Reset Password</a>
                                <br><br>
                                <p>With regards,</p>
                                <b>Kocha Cafe</b>
                            </body>
                          </html>";                    
            if(!$mail->send()){
                ?>
                    <script>
                        alert("<?php echo " Invalid Email "?>");
                    </script>
                <?php
            }else{
                ?>
                    <script>
                        window.location.replace("notification.html");
                    </script>
                <?php
            }
        }
?>
