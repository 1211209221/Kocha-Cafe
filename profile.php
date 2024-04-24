<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Profile | Kocha Café</title>
        <link rel="stylesheet" type="text/css" href="contact.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
        <link rel="icon" href="images/logo/logo_icon.png">
        <script src="gototop.js"></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>

    <body>
        <?php
            include 'connect.php';
            include 'top.php';

            if(empty($user)){
                header("Location: login.php");
                exit();
            }

            include 'sidebar.php';
            include 'gototopbtn.php'
        ?>
        <div class="container-fluid container">
            <div class="col-12 m-auto">
                
                <div class="breadcrumbs">
                        <a href="index.php">Home</a> > <a class="active">Profile</a>
                </div>
                <div id="id01" class="modal">
                    <form class="profile-edit-content animate" action="" method="post">
                        <div class="xcontainer">
                            <span class="txt">Profile</span>
                            <span onclick="document.getElementById('id01').style.display='none'" class="closeedit" title="Close Modal">&times;</span>
                          </div>
                      <div class="pcontainer">
                        <label for="uname"><b><i class="fas fa-user-edit"></i>Username</b></label>
                        <input type="text" id="uname" name="uname" required>
                  
                        <label for="pn"><b><i class="fas fa-phone-alt"></i> Phone Number</b></label>
                        <input type="tel" id="pn" name="pn" required>
                          
                        <button type="submit" name="update-profile" class="edit-profile-btn" style="outline: none;">Update</button>
                        
                      </div>
                  
                      <div class="pcontainer" style="background-color:#f3f3f3; height: 80px;">
                        <button type="button" class="edit-profile-btn cancelbtn" style="outline: none;" onclick="document.getElementById('id01').style.display='none'">Cancel</button>
                      </div>
                    </form>
                  </div>
                  <div id="id02" class="modal">
                    <form class="profile-edit-content animate" action="" method="post">
                        <div class="xcontainer">
                            <span class="txt">Address</span>
                            <span onclick="document.getElementById('id02').style.display='none'" class="closeedit" title="Close Modal">&times;</span>
                          </div>
                      <div class="pcontainer">
                        <label for="Label"><b><i class="fas fa-hashtag"></i>Label as:</b></label>
                        <input type="text" id="Label" name="uname" required>
                  
                        <label for="location"><b><i class="fas fa-map-marker-alt"></i>House No. Building, Street Name</b></label>
                        <input type="text" id="location" name="pn" required>
                          
                        <button type="submit" name="update-profile" class="edit-profile-btn" style="outline: none;">Update</button>
                        
                      </div>
                  
                      <div class="pcontainer" style="background-color:#f3f3f3; height: 80px;">
                        <button type="button" class="edit-profile-btn cancelbtn" style="outline: none;" onclick="document.getElementById('id02').style.display='none'">Cancel</button>
                      </div>
                    </form>
                  </div>
        
                  <script>
                    // Get the modal
                    var modal = document.getElementById('id01');
                    var modal2 = document.getElementById('id02');
                    
                    // When the user clicks anywhere outside of the modal, close it
                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = "none";
                        }
                        if (event.target == modal2) {
                            modal2.style.display = "none";
                        }
                    }
                    </script>
                
                <div class="bottom-info">
                    <div class="left-opt">
                        <div class="bottom-user-info">
                            <p>GUEST</p>
                            <div class="btm-pt">
                                <img src="images/coin.png" width="22px">
                                <div class="btm-pt-prt">
                                    <p>Points Owned:</p>
                                    <span>625 pts</span>
                                </div>
                                <div id="tooltip">
                                    <i class="far fa-question-circle"></i>
                                    <span class="tooltip-content">1 pt = 1 sen (RM 0.01), you can earn each point every up to RM10 purchases.</span>
                                </div>
                                
                            </div>
                        </div>
                        <div class="opt-container">
                            <ul>
                            <li class="tablink active" onclick="opencontent(event, 'profile')"><i class="fas fa-address-card"></i> <span>My Profile</span></li>
                            <li class="tablink" onclick="opencontent(event, 'history')"><i class="fas fa-history"></i> <span>Order History</span></li>
                            <li class="tablink" onclick="opencontent(event, 'voucher')"><i class="fas fa-tags"></i> <span>My Vouchers</span></li>
                            </ul>
                        </div>
                        <div class="logout-opt">
                            <i class="fas fa-sign-out-alt"></i> Log Out
                        </div>
                    </div>
                    <div class="right-detail">
                        <div id="profile" class="tabcontent">
                            <h5 class="title"><i class="fas fa-address-card"></i> My Profile</h5>
                            <p>Edit your profile details and delivery address(es).</p>
                            <div class="profile-container">
                                <div class="profile-container-one">
                                    <div class="profile-detail-container">
                                        <button style="outline: none;" onclick="document.getElementById('id01').style.display='flex'">Edit Profile</button>
                                        <div class="pro-detail-part">
                                            <h6>Personal Info</h6>
                                            <p><i class="fas fa-user-edit"></i>Username:</p>
                                            <?php
                                                $get_username = "SELECT cust_username FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
                                                $username_result = $conn->query($get_username);
                                                $username_row = mysqli_fetch_assoc($username_result);
                                                $username = $username_row['cust_username'];
                                                echo '<span class="box-witdh">'.htmlspecialchars($username).'</span>'
                                            ?>
                                            
                                            <p><i class="fas fa-phone-alt"></i> Phone Number:</p>
                                            <div class="inner-box">
                                            <?php
                                                $get_phno = "SELECT cust_phone FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
                                                $phno_result = $conn->query($get_phno);
                                                $phno_row = $phno_result->fetch_assoc();
                                                if($phno_row && !empty($phno_row['cust_phone'])){
                                                    $phone_number = $phno_row['cust_phone'];
                                                    $phone_number = ltrim($phone_number, '0');
                                                    echo '<span>+60</span> <span class="box-witdh2">'.htmlspecialchars($phone_number) .'</span>';
                                                }
                                                else{
                                                    echo '<span>+60</span> <span class="box-witdh2">No phone number found.</span>';
                                                }
                                            ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="account-container">
                                        <button class="changepwdbtn" style="outline: none;">Change Password</button>
                                        <h6>Account</h6>
                                            <p><i class="fas fa-at"></i>Email:</p>
                                            <div class="email-box">
                                            <?php
                                                $get_email = "SELECT cust_email FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
                                                $email_result = $conn->query($get_email);
                                                $email_row = $email_result->fetch_assoc();
                                                if($email_row && !empty($email_row['cust_email'])){
                                                    $email_address = $email_row['cust_email'];
                                                    echo '<span>'.htmlspecialchars($email_address ) .'</span>';
                                                }
                                                else{
                                                    echo '<span>There is a problem on database</span>';
                                                }
                                            ?>
                                            </div>
                                            <button style="border: none; outline: none;"><i class="fas fa-trash-alt" style="font-size: 13px;margin-right: 5px;"></i>Delete Account</button>
                                    </div>
                                </div>
                                <div class="profile-container-two">
                                    <h6>Delivery Address</h6>
                                    <div class="delivery-box">
                                        <button class="accordion">Delivery 1</button>
                                        <div class="panel">
                                            <div class="btn-inside-address">
                                                <button title="Edit" style="outline: none;" onclick="document.getElementById('id02').style.display='flex'"><i class="fas fa-pen"></i></button>  
                                                <button title="Delete" class="bin" style="outline: none;"><i class="fas fa-trash"></i></button>
                                            </div>
                                            <div class="address-detail">
                                                <span>No.02, Ambience Apartment, Jalan Kim Ho, 75300 Melaka</span>
                                            </div> 
                                        </div>

                                        <button class="accordion">Delivery 2</button>
                                        <div class="panel">
                                            <div class="btn-inside-address">
                                                <button style="outline: none;"><i class="fas fa-pen"></i></button>  
                                                <button class="bin" style="outline: none;"><i class="fas fa-trash"></i></button>
                                            </div>
                                            <div class="address-detail">
                                                <span>No.02, Ambience Apartment, Jalan Kim Ho, 75300 Melaka</span>
                                            </div> 
                                        </div>

                                        <button class="accordion">Delivery 3</button>
                                        <div class="panel">
                                            <div class="btn-inside-address">
                                                <button style="outline: none;"><i class="fas fa-pen"></i></button>  
                                                <button class="bin" style="outline: none;"><i class="fas fa-trash"></i></button>
                                            </div>
                                            <div class="address-detail">
                                                <span>No.02, Ambience Apartment, Jalan Kim Ho, 75300 Melaka</span>
                                            </div>                                      
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="history" class="tabcontent" style="display: none;">
                            <h5 class="title"><i class="fas fa-history"></i> Order History</h5>
                        </div>
                        <div id="voucher" class="tabcontent" style="display: none;">
                            <h5 class="title"><i class="fas fa-tags"></i> My Vouchers</h5>
                        </div>
                    </div>
                </div>
             
               
            </div>
        </div>
        <?php include 'footer.php'; ?>

        <script>
            window.addEventListener('DOMContentLoaded', (event) => {
                // Hide all tab contents except the first one
                var tabContents = document.querySelectorAll('.tabcontent');
                for (var i = 1; i < tabContents.length; i++) {
                    tabContents[i].style.display = 'none';
                }
            });
            function opencontent(evt, optname){
                var i, tabcontent, tablink;

                tabcontent = document.getElementsByClassName("tabcontent");
                for(i = 0; i < tabcontent.length; i++){
                    tabcontent[i].style.display = "none";
                }

                tablink = document.getElementsByClassName("tablink");
                for(i = 0; i < tablink.length; i++){
                    tablink[i].classList.remove("active");
                }

                document.getElementById(optname).style.display = "block";
                evt.currentTarget.classList.add("active");

            }
        </script>
        <script>
            // Add event listeners to accordions
            var acc = document.getElementsByClassName("accordion");
            for (var i = 0; i < acc.length; i++) {
                acc[i].addEventListener("click", accordionClick);
            }
            //if click then expand
            function accordionClick() {
                this.classList.toggle("expandacc");
                var panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                }

                // Store the state of the accordion in localStorage
                var accordionState = {};
                var acc = document.getElementsByClassName("accordion");
                for (var i = 0; i < acc.length; i++) {
                    accordionState[i] = acc[i].classList.contains("expandacc");
                }
                localStorage.setItem("accordionState", JSON.stringify(accordionState));
            }

            // Retrieve accordion state from localStorage
            function retrieveAccordionState() {
                var accordionState = JSON.parse(localStorage.getItem("accordionState"));
                if (accordionState) {
                    var acc = document.getElementsByClassName("accordion");
                    for (var i = 0; i < acc.length; i++) {
                        if (accordionState[i]) {
                            acc[i].classList.add("expandacc");
                            var panel = acc[i].nextElementSibling;
                            panel.style.maxHeight = panel.scrollHeight + "px";
                        }
                    }
                }
            }

            // Retrieve accordion state everytime the page reload
            window.addEventListener("load", retrieveAccordionState);
            </script>
    </body>
</html>