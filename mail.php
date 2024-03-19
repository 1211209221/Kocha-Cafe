<?php

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $phno = $_POST['phno'];
    $email= $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $message .= $phno;

    $recipient = "tanyansan123@gmail.com"; // example email
    
    // File attachment handling
    if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['attachment']['tmp_name'];
        $filename = $_FILES['attachment']['name'];
        $filesize = $_FILES['attachment']['size'];
        $filetype = $_FILES['attachment']['type'];
        
        $content = file_get_contents($tmp_name);
        $encoded_content = chunk_split(base64_encode($content));
        
        $boundary = md5("random");
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "From: " . $name . " <" . $email . ">\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=" . $boundary . "\r\n";
        
        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= chunk_split(base64_encode($message));

        $body .= "--$boundary\r\n";
        $body .="Content-Type: $filetype; name=" . $filename . "\r\n";
        $body .="Content-Disposition: attachment; filename=" . $filename . "\r\n";
        $body .="Content-Transfer-Encoding: base64\r\n";
        $body .="X-Attachment-Id: " . rand(1000, 99999) . "\r\n\r\n";
        $body .= $encoded_content;
    } else {
        // If no file is uploaded
        $headers = "From: " . $name . " <" . $email . ">\r\n";
        $body = $message;
    }
    
    $sentmail = mail($recipient, $subject, $body, $headers);
    
    // Send mail
    if ($sentmail) {
        echo "Message is sent successfully!";
    } else {
        die("Failed to send this message.");
    }
}
?>;
