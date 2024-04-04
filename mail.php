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
        $mail->setFrom($email, $name);
        $mail->addReplyTo($recipient,'Kocha Cafe');
        $mail->addAddress($recipient,'Kocha Cafe');
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "<h2>This is a message received from $name</h2><hr>
                        <h3>Email: $email <br>Phone Number: $phno <br>Message: $message</h3>";

        //check whether got attachment
        if(isset($_FILES['attachment']) && !empty($_FILES['attachment']['tmp_name']) && $_FILES['attachment']['error'] == 0) {
            //for multiple attachment
            if(is_array($_FILES['attachment']['tmp_name'])) {
                foreach($_FILES['attachment']['tmp_name'] as $key => $value) {
                    $tmp_name = $_FILES['attachment']['tmp_name'][$key];
                    $filename = $_FILES['attachment']['name'][$key];
                    $filetype = $_FILES['attachment']['type'][$key];
                    $filesize = $_FILES['attachment']['size'][$key];

                    $content = file_get_contents($tmp_name);

                    $mail->addStringAttachment($content, $filename, 'base64', $filetype);
                }
            } else {
                // for single attachment
                $tmp_name = $_FILES['attachment']['tmp_name'];
                $filename = $_FILES['attachment']['name'];
                $filetype = $_FILES['attachment']['type'];
                $filesize = $_FILES['attachment']['size'];

                $content = file_get_contents($tmp_name);

                $mail->addStringAttachment($content, $filename, 'base64', $filetype);
            }
        }
        $mail->send();
        
        $hint=good;
    }
    catch(Exception $e){
        $errormail = $mail->ErrorInfo;
        echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Failed to send. $errormail Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';
    }


    //below part is for saving those info in database
    //already connected to the databse so directly insert the info

    $sql = "INSERT INTO contact_message (CF_name, CF_phno, CF_email, CF_subject, CF_message) VALUES 
    ('$name', '$phno', '$email', '$subject', '$message')";
    $result = mysqli_query($conn, $sql);

    if($result && $hint == "good") {
        //retreive id
        $cfid = mysqli_insert_id($conn);

        //now store file
        //check whether got attachment
        if(isset($_FILES['attachment']) && !empty($_FILES['attachment']['tmp_name']) && $_FILES['attachment']['error'] == 0){

            if((is_array($_FILES['attachment']['tmp_name']) && isset($_FILES['attachment']['tmp_name']))) {
                //for multiple attachment
                foreach($_FILES['attachment']['tmp_name'] as $key => $value) {
                    $uploaddate = date("Y-m-d H:i:s");

                    $sqlfile = "INSERT INTO cf_files (filename, filesize, filetype, upload_date, CF_ID) VALUES 
                    ('$filename', $filesize, '$filetype', '$uploaddate', '$cfid')";
                    mysqli_query($conn, $sqlfile);
                }
            }else{
                // for single attachment
                $uploaddate = date("Y-m-d H:i:s");

                $sqlfile = "INSERT INTO cf_files (filename, filesize, filetype, upload_date, CF_ID) VALUES 
                ('$filename', $filesize, '$filetype', '$uploaddate', '$cfid')";
                mysqli_query($conn, $sqlfile);
            }
            
        } 
    }
    else {
        echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Failed to send. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';
    }

    if($hint == "good")
    {
        echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Your message is sent succssfully!
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';
    }
    
}
?>
