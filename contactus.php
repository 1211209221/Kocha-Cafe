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
        <script src="gototop.js"></script>
        <script src="contactvalidation.js" defer></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>

    <body>
        <?php
            include 'connect.php';
            include 'top.php';
            include 'sidebar.php';
            include 'mail.php';
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
                        <p class="line-2"><i class="far fa-hand-point-right"></i> If you think we did a great job we'd also love to hear from you.</p>
                    </div>
                </section>
                <div class="contact-form">
                    <div class="form-box">
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
                            <form id = "Contactform" action ="mail.php" autocomplete="off" method ="POST" enctype ="multipart/form-data">
                                <h4>SEND MESSAGE</h4>
                                <div class="form">
                                    <div class="inputbox w50">
                                        <label for="name">Your Full Name:</label>
                                        <input type="text" id="name" name="name" class="name" placeholder="e.g. Eric Lim Ming" required>
                                        <small class="error-input"></small>
                                        
                                    </div>
                                    <div class="inputbox w50">
                                        <label for="phno">Phone Number:</label>
                                        <input type="tel" id="phno" name="phno" class="phno" placeholder="e.g. +6012345678" required>
                                        <small class="error-input"></small>
                                    </div>
                                    <div class="inputbox w50">
                                        <label for="email">Email Address:</label>
                                        <input type="email" id="email" name="email" class="email" placeholder="e.g. eric123@gmail.com" required>
                                        <small class="error-input"></small>
                                    </div>
                                    <div class="inputbox w50">
                                        <label for="subject">Subject:</label>
                                        <input type="text" id="subject" name="subject" class="subject" placeholder="e.g. Feedback" required>
                                        <small class="error-input"></small>
                                    </div>
                                    <div class="inputbox w50">
                                        <label for="attachment">File: <span>(optional)</span></label>
                                        <input type="file" id="attachment" name="attachment">
                                        <small class="error-input"></small>
                                    </div>
                                    <div class="inputbox w80">
                                        <label for="message">Message:</label>
                                        <textarea id="message" name="message" class="message" placeholder="Write your message here..." rows="4" required></textarea>
                                        <small class="error-input"></small>
                                    </div>
                                    <div class="g-recaptcha w50" data-sitekey="6Lf506cpAAAAALCC8XRrEmC5-LqhuH3m0_s_9Mck" style="transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;">
                                    </div>
                                    <div class="inputbox w50">
                                        <input type="submit" name="submit" value="Submit">
                                        
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>

                    <!--alert messaage for contact form-->

                    <div class="maparea">
                        <h3>Location Map  <i class="fas fa-map-marked-alt"></i> </h3>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.9944164785484!2d101.6781645!3d3.0961430000000014!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc4a3936458433%3A0xdd49188c6bcec09f!
                        2s4%2C%2044%2C%20Jalan%20Desa%2C%20Taman%20Desa%2C%2058100%20Kuala%20Lumpur%2C%20Wilayah%20Persekutuan%20Kuala%20Lumpur!5e0!3m2!1sen!2smy!4v1710597219405!5m2!1sen!2smy" 
                        style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        
                    </div>
                </div>
            </div>
        </div>
        
        

        <?php include 'footer.php'; ?>

        
        <script>
            if(window.history.replaceState){
                window.history.replaceState(null,null,window.location.href);
            }
        </script>
    </body>
    
</html>