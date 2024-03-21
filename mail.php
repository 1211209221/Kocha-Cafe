<?php
include 'connect.php';

require_once 'PHPMailer src/PHPMailer.php';
require_once 'PHPMailer src/Exception.php';
require_once 'PHPMailer src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$alert ='';

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $phno = $_POST['phno'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $mail = new PHPMailer();
    try{

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kochacafe8@gmail.com'; 
        $mail->Password = 'bktz mine wgfr ayis'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        
        $recipient = "kochacafe8@gmail.com";

        $mail->addReplyTo($recipient,'Kocha Cafe');
        $mail->setFrom($email, $name);
        echo $email;

        $mail->addAddress($recipient,'Kocha Cafe');
        echo $recipient;
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "<h2>This is a message received from $name</h2><hr>
                        <h3>Email: $email <br>Phone Number: $phno <br>Message: $message</h3>";

        if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['attachment']['tmp_name'];
            $filename = $_FILES['attachment']['name'];
            $filetype = $_FILES['attachment']['type'];

            $content = file_get_contents($tmp_name);

            $mail->addStringAttachment($content, $filename, 'base64', $filetype);
        }
        $mail->send();
        $alert = '<div class="alert-success">
        <span>Your message is sent successfully! &#128238; </span>
        </div>';
    }
    catch(Exception $e){
        $alert = '<div class="alert-failed">
        <span>Something went wrong! Please try again. &#128550; </span>
        </div>' . $mail->ErrorInfo;
    }
    //echo $alert; // Output the alert message

    //below part is for saving those info in database
    //already connected to the databse so directly insert the info

    $sql = "INSERT INTO contact_message (CF_name, CF_phno, CF_email, CF_subject, CF_message) VALUES 
    ('$name', '$phno', '$email', '$subject', '$message')";
    $result = mysqli_query($conn, $sql);

    if($result){
        //retreive id
        $cfid = mysqli_insert_id($conn);

        if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK){
            $filename = $_FILES['attachment']['name'];
            $filetype = $_FILES['attachment']['type'];
            $filesize = $_FILES['attachment']['size'];
            $uploaddate = date("Y-m-d H:i:s");
    
            //now store file
            $sqlfile = "INSERT INTO cf_files (filename, filesize, filetype, upload_date, CF_ID) VALUES 
            ('$filename', $filesize, '$filetype', '$uploaddate', '$cfid')";
        }
    }
    
}
?>
