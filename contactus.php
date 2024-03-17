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
    
    </head>

    <body>
        <?php
            include 'connect.php';
            include 'top.php';
            include 'sidebar.php'
        ;?>

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
                            <p>+6017 412 4250</p>
                        </div>
                    </div>
                    <div class="box">
                        <div class="info-icon"><i class="fas fa-envelope"></i></div>
                        <div class="info-text">
                            <h5>Email</h5>
                            <p>info@kochacafe.com</p>
                        </div>
                    </div>
                </div>
                <div class="contactForm">
                    <form id="Contactform" action="/">
                        <h4>SEND MESSAGE</h4>
                        <div class="form">
                            <div class="inputbox w50">
                                <label for="name">Your Full Name:</label>
                                <input type="text" name="name" id="name" placeholder="e.g. Eric Lim Ming" required>
                                <small class="error-input hidden-error">Your name is required.</small>
                            </div>
                            <div class="inputbox w50">
                                <label for="phone">Phone Number:</label>
                                <input type="tel" id="phno" placeholder="e.g. +6012345678" required>
                                <small class="error-input"></small>
                            </div>
                            <div class="inputbox w50">
                                <label for="email">Email Address:</label>
                                <input type="email" id="email" placeholder="e.g. eric123@gmail.com" required>
                                <small class="error-input"></small>
                            </div>
                            <div class="inputbox w50">
                                <label for="subject">Subject:</label>
                                <input type="text" id="subject" placeholder="e.g. Feedback" required>
                                <small class="error-input"></small>
                            </div>
                            <div class="inputbox w50">
                                <label for="file">File: <span>(optional)</span></label>
                                <input type="file">
                                <small class="error-input"></small>
                            </div>
                            <div class="inputbox w80">
                                <label for="message">Message:</label>
                                <textarea id="msg" placeholder="Write your message here..." rows="4" required></textarea>
                                <small class="error-input"></small>
                            </div>
                            <div class="inputbox w80">
                                <input type="submit" value="Submit">
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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

            const isValidEmail(email){
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
            };

            const isValidPhone(phone){
            const re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;
            return re.test(String(phone).toLowerCase());
            };

            //const name = document.querySelector('input[name="name"]');

            const Contactform = document.getElementById('Contactform');
            const name = document.getElementById('name');
            const phno = document.getElementById('phno');
            const email = document.getElementById('email');
            const msg = document.getElementById('msg');
            
            Contactform.addEventListener('submit', e => {
                e.preventDefault();
                validateInputs();
            });

            const seterror = (element, message) => {
                const inputcontrol = element.parentElement;
                const errorDisplay = inputcontrol.querySelector('.error-input');

                errorDisplay.innertext = message;
                inputcontrol.classList.add('error');
                inputcontrol.classList.remove('hidden');
            }
            const setsuccess = element => {
                const inputcontrol = element.parentElement;
                const errorDisplay = inputcontrol.querySelector('.error-input');

                errorDisplay.innertext = '';
                inputcontrol.classList.remove('error');
                inputcontrol.classList.add('hidden');
            }
            const validateInputs = () => {
                /*if(!name.value){
                    name.nextElementSibling.classList.add("invalid");
                    name.nextElementSibling.classList.remove("hidden");
                }*/
                const namevalue = name.value.trim();
                const phnovalue = phno.value.trim();
                const emailvalue = email.value.trim();
                const msgvalue = msg.value.trim();

                if(emailvalue === ''){
                    seterror(email,'Email is required');
                }
                else if(!isValidEmail(emailvalue)){
                    seterror(email,'Provide a valid email address.');
                }
                else{
                    setsuccess(email);
                }
            }
        </script>
    </body>
    
</html>