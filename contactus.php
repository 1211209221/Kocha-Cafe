<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Contact Us | Kocha Café</title>
        <link rel="stylesheet" type="text/css" href="contact.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
        <link rel="icon" href="images/logo/logo_icon.png">
        <script src="script.js"></script>
        <script src="gototop.js"></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>
    <style>
        .w100{
            width: 100%;
        }    
        
    </style>
    <body>
        <?php
            include 'connect.php';
            include 'top.php';

            //for mail and data storing part
            require_once 'PHPMailer src/PHPMailer.php';
            require_once 'PHPMailer src/Exception.php';
            require_once 'PHPMailer src/SMTP.php';

            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;
            use PHPMailer\PHPMailer\SMTP;

            $name =  $phno = $email = $subject = $message = "";

            if($_SERVER["REQUEST_METHOD"] == "POST"){
                if(!empty($_POST["name"]) && !empty($_POST["phno"]) && !empty($_POST["email"]) && !empty($_POST["subject"]) && !empty($_POST["message"])){
                    $name = $_POST['name'];
                    $phno = $_POST['phno'];
                    $email = $_POST['email'];
                    $subject = $_POST['subject'];
                    $message = $_POST['message'];
                    $message_line_breaks = nl2br($message);

                    if(!empty($_FILES['attachment']['tmp_name'][0]) && is_uploaded_file($_FILES['attachment']['tmp_name'][0])){
                        $filecount = count($_FILES['attachment']['tmp_name']);
                    }

                    if($_POST["g-recaptcha-response"]){
                        $recaptcha_response = $_POST["g-recaptcha-response"];
                        $recaptcha_secret = "6Lf506cpAAAAAK_euIDA9CphEiXmC3LSk0fQILPT";
                        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response";
                        $response = file_get_contents($url);
                        $response_data = json_decode($response, true);

                    }

                    if (!$response_data["success"]) {
                        $_SESSION['captcharesponse'] = true;
                        echo '<script>';
                        echo 'window.location.href = "contactus.php";';
                        echo '</script>';
                        exit();
                    }
                    else{
                        date_default_timezone_set('Asia/Kuala_Lumpur');
                        $cfdate = date("Y-m-d H:i:s");
                        $sql = "INSERT INTO contact_message (CF_name, CF_phno, CF_email, CF_subject, CF_message, CF_time) VALUES 
                        ('$name', '$phno', '$email', '$subject', '$message', '$cfdate')";
                        $result = mysqli_query($conn, $sql);

                        $mail = new PHPMailer(true);
                        try{
                            // SMTP configuration
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'kochacafe8@gmail.com';
                            $mail->Password = 'bktz mine wgfr ayis';
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = 587;

                            $recipient = "kochacafe8@gmail.com"; 

                            $mail->From = $email;
                            $mail->FromName = $name;
                            $mail->addReplyTo($email, $name);
                            $mail->addAddress($recipient, 'Kocha Cafe');

                            // Email content
                            $mail->isHTML(true);
                            $mail->Subject = $subject;
                            $mail->Body = '<table style = "max-width:500px; display:flex; margin:5px; padding: 10px; border: 0.5px solid #5e5e5e;"><h3 style = "color:#50A596;">&#128236;  CONTACT FORM MESSAGE RECEIVED FROM <span style = "color: #36676A; text-transform: uppercase;"> '.$name.'<span></h3><hr style="color:#838383;">
                            <p><b>Email:</b> '. $email .' <br><br><b>Phone Number:</b> '. $phno .'<br><br><b>Message:</b><br> '. $message_line_breaks.'</p></table>';

                            if(!empty($_FILES['attachment']['tmp_name'][0]) && is_uploaded_file($_FILES['attachment']['tmp_name'][0])){
                                $cfid = mysqli_insert_id($conn);
                                for($i=0;$i<$filecount;$i++){
                                    $file_tmp = $_FILES['attachment']['tmp_name'][$i];
                                    $filename = $_FILES['attachment']['name'][$i];
                                    $filetype = $_FILES['attachment']['type'][$i];
                                    $filesize = $_FILES['attachment']['size'][$i];

                                    $content = file_get_contents($file_tmp);
                                    $mail->addStringAttachment($content, $filename, 'base64', $filetype);

                                    $escaped_content = mysqli_real_escape_string($conn, $content);
                                    date_default_timezone_set('Asia/Kuala_Lumpur');
                                    $uploaddate = date("Y-m-d H:i:s");

                                    $sqlfile = "INSERT INTO cf_files (filename, filesize, filetype, file_content, upload_date, CF_ID) VALUES 
                                    ('$filename', $filesize, '$filetype', '$escaped_content', '$uploaddate', '$cfid')";
                                    $fileresult =  mysqli_query($conn, $sqlfile);
                                }
                                if($result === false ||$fileresult === false){
                                    $_SESSION['databasepart'] = true;
                                    echo '<script>';
                                    echo 'window.location.href = "contactus.php";';
                                    echo '</script>';
                                    exit();
                                }
                                
                            }

                            if($mail->send()){
                                $_SESSION['success'] = true;
                                echo '<script>';
                                echo 'window.location.href = "contactus.php";';
                                echo '</script>';
                                exit();
                            }

                        }
                        catch(Exception $e){
                            $_SESSION['error'] = true;
                            echo '<script>';
                            echo 'window.location.href = "contactus.php";';
                            echo '</script>';
                            exit();
                        }
                    }

                }
            }
            if(isset($_SESSION['captcharesponse']) &&  $_SESSION['captcharesponse'] === true){
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>You are spammer! Get out...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';
                unset($_SESSION['captcharesponse']);
            }
            if(isset($_SESSION['databasepart']) && $_SESSION['databasepart'] === true){
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Couldn\'t connect to database. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';
                unset($_SESSION['databasepart']);
            }
            if(isset($_SESSION['success']) && $_SESSION['success'] === true){
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast true fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Your message is sent successfully!
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';
                unset($_SESSION['success']);
            }
            if(isset($_SESSION['error']) && $_SESSION['error'] === true){
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Failed to send.('. $mail->ErrorInfo .') Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';
                unset($_SESSION['error']);
            }
            include 'sidebar.php';
            include 'gototopbtn.php'
        ;?>

        <div class="container-fluid container">
            <div class="col-12 m-auto">
                
                <div class="breadcrumbs">
                        <a href="index.php">Home</a> > <a class="active">Contact Us</a>
                    </div>
                    <section class="contact-banner">
                    <img src="banner background/1.png" id = "leftimg">
                    <img src="banner background/2.png" id = "rightimg">
                    <div class="banner-text">
                        <h2>CONTACT US &#128238;</h2>
                        <div class="wrap-text">
                            <p>If you have any </p>
                            <ul class="dynamic-text">
                                <li><span>feedback.</span></li>
                                <li><span>questions.</span></li>
                                <li><span>suggestions.</span></li>
                                <li><span>comments.</span></li>
                            </ul>
                        </div>
                        <p class="line-2"><i class="far fa-hand-point-right"></i> Any other inquiries are also welcome! We will strive to respond to you <u>within 24 hours</u> .</p>
                        <p class="line-2"><i class="far fa-hand-point-right"></i> If you think we did a great job &#128077; we'd also love to hear from you.</p>
                    </div>
                </section>
                <div class="contact-form">
                    <div class="maparea">
                        <h3>Location Map  <i class="fas fa-map-marked-alt"></i> </h3>
                        <iframe class="fade_in" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.9944164785484!2d101.6781645!3d3.0961430000000014!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc4a3936458433%3A0xdd49188c6bcec09f!
                        2s4%2C%2044%2C%20Jalan%20Desa%2C%20Taman%20Desa%2C%2058100%20Kuala%20Lumpur%2C%20Wilayah%20Persekutuan%20Kuala%20Lumpur!5e0!3m2!1sen!2smy!4v1710597219405!5m2!1sen!2smy" 
                        style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" ></iframe>
                        
                    </div>
                    <div class="form-box fade_in">
                        <div class="contact-info">
                            <h4>CONTACT INFO</h4>
                            <div class="box">
                                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="info-text">
                                    <h5>Address</h5>
                                    <p>No. 44, Jalan Desa Melur 4/1, Taman Bandar Connaught, 56000 Cheras, Kuala Lumpur, Malaysia</p>
                                </div>
                            </div>
                            <div class="box">
                                <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                                <div class="info-text">
                                    <h5>Phone</h5>
                                    <p><a href = "tel:+6017 412 4250">+6017 412 4250</a></p>
                                </div>
                            </div>
                            <div class="box">
                                <div class="info-icon"><i class="fas fa-envelope"></i></div>
                                <div class="info-text">
                                    <h5>Email</h5>
                                    <p><a href="mailto:info@kochacafe.com">info@kochacafe.com</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="contactForm">
                            <form id = "Contactform" action ="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" autocomplete="off" method ="POST" enctype ="multipart/form-data">
                                <h4>SEND MESSAGE</h4>
                                <div class="form">
                                    <div class="inputbox w50">
                                        <label for="name">Your Full Name:</label>
                                        <input type="text" id="name" name="name" class="name" placeholder="e.g. Eric Lim Ming" required>
                                        <small class="error-input hidden"></small>
                                        
                                    </div>
                                    <div class="inputbox w50">
                                        <label for="phno">Phone Number:</label>
                                        <input type="tel" id="phno" name="phno" class="phno" placeholder="e.g. +6012345678" required>
                                        <small class="error-input hidden"></small>
                                    </div>
                                    <div class="inputbox w50">
                                        <label for="email">Email Address:</label>
                                        <input type="email" id="email" name="email" class="email" placeholder="e.g. eric123@gmail.com" required>
                                        <small class="error-input hidden"></small>
                                    </div>
                                    <div class="inputbox w50">
                                        <label for="subject">Subject:</label>
                                        <input type="text" id="subject" name="subject" class="subject" placeholder="e.g. Feedback" required>
                                        <small class="error-input hidden"></small>
                                    </div>
                                    <div class="inputbox w100">
                                        <label for="attachment">File: <span>(optional, total max size: 10MB)</span></label>
                                        <input type="file" id="attachment" name="attachment[]" multiple>
                                        <small class="error-input hidden"></small>
                                    </div>
                                    <div class="inputbox w100">
                                        <label for="message">Message:</label>
                                        <textarea id="message" name="message" class="message" placeholder="Write your message here..." rows="4" required></textarea>
                                        <small class="error-input hidden"></small>
                                    </div>
                                    <div class="inputbox cap">
                                        <div id="captcha" class="g-recaptcha" data-sitekey="6Lf506cpAAAAALCC8XRrEmC5-LqhuH3m0_s_9Mck" style="transform:scale(0.8);-webkit-transform:scale(0.8);transform-origin:0 0;-webkit-transform-origin:0 0;">
                                        </div>
                                        <small class="error-input hidden"></small>
                                    </div>
                                    
                                    <div class="inputbox w50 sbtn">
                                        <input type="submit" id = "submitbtn" name="submit" value="Submit">
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'footer.php'; ?>
                        
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Attach event listener to the form elements
                const formElements = document.querySelectorAll("#name, #phno, #email, #subject, #attachment, #message, #captcha");
                formElements.forEach(element => {
                    element.addEventListener("input", function() {
                        validateContactForm();
                    });
                });

                // Attach event listener to the submit button
                document.getElementById("submitbtn").addEventListener("click", function(event) {
                    // Validate form fields
                    if (!validateContactForm()) {
                        // Prevent form submission if validation fails
                        event.preventDefault();
                    }
                });
            });

            function isValidEmail(email) {
                const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email).toLowerCase());
            }

            function isValidPhone(phone) {
                const re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;
                return re.test(String(phone).toLowerCase());
            }

            //function to validate
            function validateContactForm() {
                const name = document.getElementById("name").value;
                const phno = document.getElementById("phno").value;
                const email = document.getElementById("email").value;
                const subject = document.getElementById("subject").value;
                const attachment = document.getElementById("attachment");
                const f = attachment.files;
                const message = document.getElementById("message").value;
                var letters = /^[a-zA-Z-' ]*$/;
                let valid = true; 

                // Validate name
                if (name.trim() === "") {
                    errorDisplay(document.getElementById("name"), "*Please enter your name.*");
                    valid = false;
                }
                else if(!name.match(letters)){
                    errorDisplay(document.getElementById("name"), "*Only letters and white space allowed.*");
                }
                else{
                    clearError(document.getElementById("name"));
                }

                // Validate phone number
                if (phno.trim() === "") {
                    errorDisplay(document.getElementById("phno"), "*Please enter your phone number.*");
                    valid = false;
                } else if (!isValidPhone(phno.trim())) {
                    errorDisplay(document.getElementById("phno"), "*Invalid phone number format*");
                    valid = false;
                }
                else{
                    clearError(document.getElementById("phno"));
                }

                // Validate email
                if (email.trim() === "") {
                    errorDisplay(document.getElementById("email"), "*Please enter your email address.*");
                    valid = false;
                } else if (!isValidEmail(email.trim())) {
                    errorDisplay(document.getElementById("email"), "*Invalid email format*");
                    valid = false;
                }
                else{
                    clearError(document.getElementById("email"));
                }

                // Validate subject
                if (subject.trim() === "") {
                    errorDisplay(document.getElementById("subject"), "*Please enter a subject.*");
                    valid = false;
                }
                else{
                    clearError(document.getElementById("subject"));
                }

                //Validate file
                if(f.length > 0){//not empty
                    let totalsize = 0;
                    const allfile = 10 * 1024 * 1024 //10mb

                    for(let i = 0; i < f.length; i++){
                        const fsize = f[i].size;
                        totalsize += fsize;
                    }
                    if(totalsize > allfile){
                        errorDisplay(document.getElementById("attachment"), "*Oops, the file(s) exceed total size limit.*");
                        valid = false;
                    }
                    else{
                        clearError(document.getElementById("attachment"));
                    }

                } 
                else{
                    clearError(document.getElementById("attachment"));
                }   

                // Validate message
                if (message.trim() === "") {
                    errorDisplay(document.getElementById("message"), "*Please fill your message.*");
                    valid = false;
                }
                else{
                    clearError(document.getElementById("message"));
                }

                //validate recaptcha
                if (window.grecaptcha.getResponse().length === 0) { 
                    errorDisplay(document.getElementById("captcha"), "*Please tick the recaptcha.*"); 
                    valid = false;
                }
                else{
                    clearError(document.getElementById("captcha"));
                }

                // Return the overall validity of the form
                return valid;
            }

            //function to display error message
            function errorDisplay(input, message) {
                const errorElement = input.nextElementSibling;
                errorElement.innerText = message;
                errorElement.classList.remove('hidden');
                input.classList.add('error-color');
            }
            // Function to clear error message
            function clearError(input) {
                const errorElement = input.nextElementSibling;
                errorElement.innerText = " ";
                errorElement.classList.add('hidden');
                input.classList.remove('error-color');
            }
        </script>

        <script>
            if(window.history.replaceState){
                window.history.replaceState(null,null,window.location.href);
            }
        </script>

    </body>
    
</html>