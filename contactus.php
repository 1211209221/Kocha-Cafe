<?php include 'mail.php';?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Contact Us</title>
        <link rel="stylesheet" type="text/css" href="contact.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
        <script src="gototop.js"></script>
        <script src="contactvalidation.js"></script>
    </head>

    <body>
        <?php
            include 'connect.php';
            include 'top.php';
            include 'sidebar.php'
        ;?>

        <?php include 'gototopbtn.php'; ?>

        <section class="contact-banner">
            <img src="banner background/3.png" id="clouds">
            <img src="banner background/4.png" id="text">
            <img src="banner background/2.png" id="mountain">
            <img src="banner background/1.png" id="jungle">
        </section>

        <div class="contact-form">
            <h3>Welcome to Reach Us &#129303;</h3> 
            <p>Here at <b>Kocha Cafe</b>, we always appreciate your feedback to improve our products and services. <br>If you are experiencing any problems or have questions, suggestions or other comments about our products and services, kindly contact us. 
                <br>If you think we did a great job we'd also love to hear from you.</p>
            <br>

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
                    <form id="Contactform" action="mail.php" method="POST" enctype="multipart/form-data">
                        <h4>SEND MESSAGE</h4>
                        <div class="form">
                            <div class="inputbox w50 error">
                                <label for="name">Your Full Name:</label>
                                <input type="text" name="name" class="name" placeholder="e.g. Eric Lim Ming" required>
                                
                            </div>
                            <div class="inputbox w50 error">
                                <label for="phone">Phone Number:</label>
                                <input type="tel" name="phno" class="phno" placeholder="e.g. +6012345678" required>
                            </div>
                            <div class="inputbox w50 error">
                                <label for="email">Email Address:</label>
                                <input type="email" name="email" class="email" placeholder="e.g. eric123@gmail.com" required>
                            </div>
                            <div class="inputbox w50 error">
                                <label for="subject">Subject:</label>
                                <input type="text" name="subject" class="subject" placeholder="e.g. Feedback" required>
                            </div>
                            <div class="inputbox w50">
                                <label for="file">File: <span>(optional)</span></label>
                                <input type="file" name="attachment">
                            </div>
                            <div class="inputbox w80 error">
                                <label for="message">Message:</label>
                                <textarea name="message" class="message" placeholder="Write your message here..." rows="4" required></textarea>
                                <small class="error-input"></small>
                            </div>
                            <div class="inputbox w80">
                                <input type="submit" name="submit" value="Submit">
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!--alert messaage for contact form-->
            <?php echo $alert; ?>

            <div class="maparea">
                <h3>Location Map  <i class="fas fa-map-marked-alt"></i> </h3>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.9944164785484!2d101.6781645!3d3.0961430000000014!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc4a3936458433%3A0xdd49188c6bcec09f!
                2s4%2C%2044%2C%20Jalan%20Desa%2C%20Taman%20Desa%2C%2058100%20Kuala%20Lumpur%2C%20Wilayah%20Persekutuan%20Kuala%20Lumpur!5e0!3m2!1sen!2smy!4v1710597219405!5m2!1sen!2smy" 
                style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                
            </div>
        </div>
        

        <?php include 'footer.php'; ?>

        <script>
            let clouds = document.getElementById('clouds');
            let text = document.getElementById('text');
            let mountain = document.getElementById('mountain');
            let jungle = document.getElementById('jungle');

            window.addEventListener('scroll',function(){
                let value = window.scrollY;
                clouds.style.left = value * 0.25 +  'px';
                text.style.top= value * 1.05 +  'px';
                mountain.style.top = value * 0.30 +  'px';
                jungle.style.top = value * 0 +  'px';
            })

            
        </script>
        <script>
            if(window.history.replaceState){
                window.history.replaceState(null,null,window.location.href);
            }
        </script>
    </body>
    
</html>