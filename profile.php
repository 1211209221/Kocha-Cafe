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

            
            if(isset($_POST['edit-address'])){
                $address_id = $_POST['edit-address'];
                $get_address = "SELECT cust_address FROM customer WHERE cust_ID = $cust_ID AND trash = 0"; // Modify this query to retrieve the specific address based on the address ID
                $address_result = $conn->query($get_address);
                $address_row = mysqli_fetch_assoc($address_result);
                                
                $addresses = explode("},{", $address_row['cust_address']);
                $addresses = array_filter($addresses, 'strlen');
                $numAddresses = count($addresses);
                            
                for($i = 0; $i < $numAddresses; $i++){
                    // Get the address components for the current iteration
                    $address = trim($addresses[$i], "{}");
                    $address_components = explode(",", $address);
                    // Check if matches the edit-address
                        if($address_id == ($i + 1)){ // Address ID starts from 1
                            // Assign the extracted address components to variables
                            $address_label = trim($address_components[0], "()");
                            $address_no = trim($address_components[1], "()");
                            $address_building = trim($address_components[2], "()");
                            $address_street = trim($address_components[3], "()");
                            $address_postcode = trim($address_components[4], "()");
                            $address_state = trim($address_components[5], "()");
                            break; // Exit the loop once the address is found
                        }
                                    
                }
                
            
                                  
            }                                
                        

            if(isset($_POST['add-address'])){
                $add_label = $_POST['newLabel'];
                $add_no = $_POST['newhouseno'];
                $add_building = $_POST['newaddline'];
                $add_street = $_POST['newaddline2'];
                $add_postcode = $_POST['newpostcode'];
                $add_state = $_POST['newstate'];

                // Retrieve all addresses from the database
                $get_addresses_query = "SELECT cust_address FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
                $addresses_result = $conn->query($get_addresses_query);
                $address_row = mysqli_fetch_assoc($addresses_result);
                $addresses_string = $address_row['cust_address'];

                // Append the new address to the existing addresses
                $new_address = "($add_label),($add_no),($add_building),($add_street),($add_postcode),($add_state)";
                if (!empty($addresses_string)) {
                    // Append the new address with a comma before it
                    $updated_addresses_string = $addresses_string . ",{" . $new_address . "}";
                } else {
                    // If no existing addresses, simply use the new address
                    $updated_addresses_string = "{" . $new_address . "}";
                }

                // Update the database with the new addresses
                $update_query = "UPDATE customer SET cust_address = ? WHERE cust_ID = $cust_ID AND trash = 0";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("s", $updated_addresses_string);
                $stmt->execute();

                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
            if(isset($_POST['update-address'])){
                $add_id = $_POST['edit-address'];
                $add_label = $_POST['uname'];
                $add_no = $_POST['houseno'];
                $add_building = $_POST['addline'];
                $add_street = $_POST['addline2'];
                $add_postcode = $_POST['postcode'];
                $add_state = $_POST['state'];

                // Retrieve all addresses from the database
                $get_addresses_query = "SELECT cust_address FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
                $addresses_result = $conn->query($get_addresses_query);
                $address_row = mysqli_fetch_assoc($addresses_result);
                $addresses_string = $address_row['cust_address'];

                // Split the addresses string into an array
                $addresses = explode("},{", $addresses_string);
                $addresses = array_filter($addresses, 'strlen');
                $numAddresses = count($addresses);

                    for($i = 0; $i < $numAddresses; $i++){
                        // Get the address components for the current iteration
                        $address = trim($addresses[$i], "{}");
                        $address_components = explode(",", $address);

                        // Assign the extracted address components to variables
                        $address_label = trim($address_components[0], "()");
                        $address_no = trim($address_components[1], "()");
                        $address_building = trim($address_components[2], "()");
                        $address_street = trim($address_components[3], "()");
                        $address_postcode = trim($address_components[4], "()");
                        $address_state = trim($address_components[5], "()");
                
                        // Check if matches the edit-address
                        if($add_id == ($i + 1)){ 
                            // Update the address with new values
                            $addresses[$i] = "($add_label),($add_no),($add_building),($add_street),($add_postcode),($add_state)";
                        }
                        else{
                            $addresses[$i] = "($address_label),($address_no),($address_building),($address_street),($address_postcode),($address_state)";

                        }
                        
                    }
                
                // Concatenate all addresses back into a single string
                $updated_addresses_string = implode("},{", $addresses);
                // Update the database 
                $update_query = "UPDATE customer SET cust_address = ? WHERE cust_ID = $cust_ID AND trash = 0";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("s", $updated_addresses_string);
                $stmt->execute();    
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
                    <form id="user-profile-form" class="profile-edit-content animate" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <div class="xcontainer">
                            <span class="txt">Address</span>
                            <span onclick="document.getElementById('id02').style.display='none'" class="closeedit" title="Close Modal">&times;</span>
                          </div>
                      <div class="pcontainer">
                        <div class="pcontainer-height">
                        <input type="hidden" name="index" value="<?php echo $address_id; ?>">
                        <label for="Label"><b><i class="fas fa-hashtag"></i>Label as:</b></label>
                        <input type="text" id="Label" name="uname" value="<?php echo $address_label; ?>" required> 
                        <span class="address-error"></span>

                        <label for="houseno"><b>House / Unit No.:</b></label>
                        <input type="text" id="houseno" name="houseno" value="<?php echo $address_no; ?>" required>
                        <span class="address-error"></span>

                        <label for="addline"><b>Buiding name / District:</b></label>
                        <input type="text" id="addline" name="addline" value="<?php echo $address_building; ?>" required>
                        <span class="address-error"></span>
                          
                        <label for="addline2"><b>Street Name:</b></label>
                        <input type="text" id="addline2" name="addline2" value="<?php echo $address_street; ?>" required>
                        <span class="address-error"></span>

                        <label for="postcode"><b>Postcode & locality name:</b></label>
                        <input type="text" id="postcode" name="postcode" value="<?php echo $address_postcode; ?>" required>
                        <span class="address-error"></span>

                        <label for="state"><b>State:</b></label>
                        <select id="state" name="state" >
                            <option>Select</option>
                            <option value="Johor" <?php if ($address_state == "Johor") echo "selected"; ?>>Johor, Malaysia</option>
                            <option value="Kedah" <?php if ($address_state == "Kedah") echo "selected"; ?>>Kedah, Malaysia</option>
                            <option value="Kelantan" <?php if ($address_state == "Kelantan") echo "selected"; ?>>Kelantan, Malaysia</option>
                            <option value="Malacca" <?php if ($address_state == "Malacca") echo "selected"; ?>>Malacca, Malaysia</option>
                            <option value="Pahang" <?php if ($address_state == "Pahang") echo "selected"; ?>>Pahang, Malaysia</option>
                            <option value="Penang" <?php if ($address_state == "Penang") echo "selected"; ?>>Penang, Malaysia</option>
                            <option value="Perak" <?php if ($address_state == "Perak") echo "selected"; ?>>Perak, Malaysia</option>
                            <option value="Kuala Lumpur" <?php if ($address_state == "Kuala Lumpur") echo "selected"; ?>>Kuala Lumpur, Malaysia</option>
                        <select>
                        <span class="address-error"></span>
                    </div>
                        <button type="submit" id="submit-edit" name="update-address" class="edit-profile-btn" style="outline: none; margin-top:15px">Update</button>
                        
                      </div>
                  
                      <div class="pcontainer" style="background-color:#f3f3f3; height: 80px;">
                        <button type="button" class="edit-profile-btn cancelbtn" style="outline: none;" onclick="document.getElementById('id02').style.display='none'">Cancel</button>
                      </div>
                    </form>
                  </div>

                  <div id="id03" class="modal">
                    <form id="user-profile-form" class="profile-edit-content animate" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <div class="xcontainer">
                            <span class="txt">Address</span>
                            <span onclick="document.getElementById('id03').style.display='none'" class="closeedit" title="Close Modal">&times;</span>
                          </div>
                      <div class="pcontainer">
                        <div class="pcontainer-height">
                        <label for="newLabel"><b><i class="fas fa-hashtag"></i>Label as:</b></label>
                        <input type="text" id="newLabel" name="newLabel" placeholder="Home" required>
                        <span class="address-error"></span>
                        
                        <label for="newhouseno"><b>House / Unit No.:</b></label>
                        <input type="text" id="newhouseno" name="newhouseno" placeholder="No.07" required>
                        <span class="address-error"></span>

                        <label for="newaddline"><b>Buiding name / District:</b></label>
                        <input type="text" id="newaddline" name="newaddline" placeholder="Ambience Apartment / Taman Kenari" required>
                        <span class="address-error"></span>
                          
                        <label for="newaddline2"><b>Street Name:</b></label>
                        <input type="text" id="newaddline2" name="newaddline2" placeholder="Jalan Hang Jebat" required>
                        <span class="address-error"></span>

                        <label for="newpostcode"><b>Postcode & locality name:</b></label>
                        <input type="text" id="newpostcode" name="newpostcode" placeholder="56000 Cheras" required>
                        <span class="address-error"></span>

                        <label for="newstate"><b>State:</b></label>
                        <select id="newstate" name="newstate" >
                            <option>Select</option>
                            <option value="Johor">Johor, Malaysia</option>
                            <option value="Kedah" >Kedah, Malaysia</option>
                            <option value="Kelantan">Kelantan, Malaysia</option>
                            <option value="Malacca">Malacca, Malaysia</option>
                            <option value="Pahang">Pahang, Malaysia</option>
                            <option value="Penang">Penang, Malaysia</option>
                            <option value="Perak">Perak, Malaysia</option>
                            <option value="Kuala Lumpur">Kuala Lumpur, Malaysia</option>
                        <select>
                        <span class="address-error"></span>
                    </div>
                        <button type="submit" id="submit-add" name="add-address" class="edit-profile-btn" style="outline: none; margin-top:15px">Save</button>
                        
                      </div>
                  
                      <div class="pcontainer" style="background-color:#f3f3f3; height: 80px;">
                        <button type="button" class="edit-profile-btn cancelbtn" style="outline: none;" onclick="document.getElementById('id03').style.display='none'">Cancel</button>
                      </div>
                    </form>
                  </div>
        
                    <script>
                        //validate the updateform
                        document.addEventListener("DOMContentLoaded", function () {
                            const formElements = document.querySelectorAll("#Label, #houseno, #addline, #addline2, #postcode, #state");
                            formElements.forEach(element => {
                                element.addEventListener("input", function () {
                                    validateAddressForm();
                                });
                            });

                            // Attach event listener to the submit button
                            document.getElementById("submit-edit").addEventListener("click", function (event) {
                                // Validate form fields
                                if (!validateAddressForm()) {
                                    // Prevent form submission if validation fails
                                    event.preventDefault();
                                }
                            });
                        });

                        function validateAddressForm() {
                            const label = document.getElementById("Label").value;
                            const houseno = document.getElementById("houseno").value;
                            const addline = document.getElementById("addline").value;
                            const addline2 = document.getElementById("addline2").value;
                            const postcode = document.getElementById("postcode").value;
                            const state = document.getElementById("state").value;

                            let valid = true;

                            // Validate Label
                            if (label.trim() === "") {
                                errorDisplay(document.getElementById("Label"), "*Please enter a label.*");
                                valid = false;
                            } 
                            else if(label.trim().includes(",")){
                                errorDisplay(document.getElementById("Label"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }
                            else {
                                clearError(document.getElementById("Label"));
                            }

                            // Validate House/Unit No.
                            if (houseno.trim() === "") {
                                errorDisplay(document.getElementById("houseno"), "*Please enter a house/unit number.*");
                                valid = false;
                            } 
                            else if(houseno.trim().includes(",")){
                                errorDisplay(document.getElementById("houseno"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }
                            else {
                                clearError(document.getElementById("houseno"));
                            }

                            // Validate Building Name/District
                            if (addline.trim() === "") {
                                errorDisplay(document.getElementById("addline"), "*Please enter a building name/district.*");
                                valid = false;
                            } 
                            else if(addline.trim().includes(",")){
                                errorDisplay(document.getElementById("addline"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }
                            else {
                                clearError(document.getElementById("addline"));
                            }

                            // Validate Street Name
                            if (addline2.trim() === "") {
                                errorDisplay(document.getElementById("addline2"), "*Please enter a street name.*");
                                valid = false;
                            }
                            else if(addline2.trim().includes(",")){
                                errorDisplay(document.getElementById("addline2"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }  
                            else {
                                clearError(document.getElementById("addline2"));
                            }

                            // Validate Postcode & Locality Name
                            if (postcode.trim() === "") {
                                errorDisplay(document.getElementById("postcode"), "*Please enter a postcode & locality name.*");
                                valid = false;
                            }
                            else if(postcode.trim().includes(",")){
                                errorDisplay(document.getElementById("postcode"), "*Commas are not allowed in any field.*");
                                valid = false;
                            } 
                            else {
                                clearError(document.getElementById("postcode"));
                            }

                            // Validate State
                            if (state === "Select" || state === "") {
                                errorDisplay(document.getElementById("state"), "*Please select a state.*");
                                valid = false;
                            } else {
                                clearError(document.getElementById("state"));
                            }

                            // Return the overall validity of the form
                            return valid;
                        }

                        // Function to display error message
                        function errorDisplay(input, message) {
                            const errorElement = input.nextElementSibling;
                            errorElement.innerText = message;
                            errorElement.classList.remove('hidden');
                            input.classList.add('error-color');
                        }

                        // Function to clear error message
                        function clearError(input) {
                            const errorElement = input.nextElementSibling;
                            errorElement.innerText = "";
                            errorElement.classList.add('hidden');
                            input.classList.remove('error-color');
                        }

                        //validate add form
                        document.addEventListener("DOMContentLoaded", function () {
                            const formElements = document.querySelectorAll("#Label, #houseno, #addline, #addline2, #postcode, #state");
                            formElements.forEach(element => {
                                element.addEventListener("input", function () {
                                    validateAddAddressForm();
                                });
                            });

                            // Attach event listener to the submit button
                            document.getElementById("submit-add").addEventListener("click", function (event) {
                                // Validate form fields
                                if (!validateAddAddressForm()) {
                                    // Prevent form submission if validation fails
                                    event.preventDefault();
                                }
                            });
                        });

                        function validateAddAddressForm() {
                            const newlabel = document.getElementById("newLabel").value;
                            const newhouseno = document.getElementById("newhouseno").value;
                            const newaddline = document.getElementById("newaddline").value;
                            const newaddline2 = document.getElementById("newaddline2").value;
                            const newpostcode = document.getElementById("newpostcode").value;
                            const newstate = document.getElementById("newstate").value;

                            let valid = true;

                            // Validate Label
                            if (newlabel.trim() === "") {
                                errorDisplay(document.getElementById("newLabel"), "*Please enter a label.*");
                                valid = false;
                            } 
                            else if(newlabel.trim().includes(",")){
                                errorDisplay(document.getElementById("newLabel"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }
                            else {
                                clearError(document.getElementById("newLabel"));
                            }

                            // Validate House/Unit No.
                            if (newhouseno.trim() === "") {
                                errorDisplay(document.getElementById("newhouseno"), "*Please enter a house/unit number.*");
                                valid = false;
                            } 
                            else if(newhouseno.trim().includes(",")){
                                errorDisplay(document.getElementById("newhouseno"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }
                            else {
                                clearError(document.getElementById("newhouseno"));
                            }

                            // Validate Building Name/District
                            if (newaddline.trim() === "") {
                                errorDisplay(document.getElementById("newaddline"), "*Please enter a building name/district.*");
                                valid = false;
                            } 
                            else if(newaddline.trim().includes(",")){
                                errorDisplay(document.getElementById("newaddline"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }
                            else {
                                clearError(document.getElementById("newaddline"));
                            }

                            // Validate Street Name
                            if (newaddline2.trim() === "") {
                                errorDisplay(document.getElementById("newaddline2"), "*Please enter a street name.*");
                                valid = false;
                            }
                            else if(newaddline2.trim().includes(",")){
                                errorDisplay(document.getElementById("newaddline2"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }  
                            else {
                                clearError(document.getElementById("newaddline2"));
                            }

                            // Validate Postcode & Locality Name
                            if (newpostcode.trim() === "") {
                                errorDisplay(document.getElementById("newpostcode"), "*Please enter a postcode & locality name.*");
                                valid = false;
                            }
                            else if(newpostcode.trim().includes(",")){
                                errorDisplay(document.getElementById("newpostcode"), "*Commas are not allowed in any field.*");
                                valid = false;
                            } 
                            else {
                                clearError(document.getElementById("newpostcode"));
                            }

                            // Validate State
                            if (newstate === "Select" || newstate === "") {
                                errorDisplay(document.getElementById("newstate"), "*Please select a state.*");
                                valid = false;
                            } else {
                                clearError(document.getElementById("newstate"));
                            }

                            // Return the overall validity of the form
                            return valid;
                        }

                        // Function to display error message
                        function errorDisplay(input, message) {
                            const errorElement = input.nextElementSibling;
                            errorElement.innerText = message;
                            errorElement.classList.remove('hidden');
                            input.classList.add('error-color');
                        }

                        // Function to clear error message
                        function clearError(input) {
                            const errorElement = input.nextElementSibling;
                            errorElement.innerText = "";
                            errorElement.classList.add('hidden');
                            input.classList.remove('error-color');
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
                                                    echo '<span>+60</span> <span class="box-witdh2">-</span>';
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
                                    <small style="font-size:14px; padding-left:2px"><b>Max: 3 locations</b></small>
                                    <div class="delivery-box">
                                    <?php 
                                        //find the address from database
                                        $get_address = "SELECT cust_address FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
                                        $address_result = $conn->query($get_address);
                                        $address_row = mysqli_fetch_assoc($address_result);

                                        if(!empty($address_row['cust_address'])){
                                            $addresses = explode("},{", $address_row['cust_address']);
                                            $addresses = array_filter($addresses, 'strlen');
                                            //count the address
                                            $numAddresses = count($addresses);
                                            $index = 1;

                                            foreach($addresses as $address){
                                                //remove the outside{}
                                                $address= trim($address, "{}");
                                                //split
                                                $details = explode(",", $address);

                                                $address_label = trim($details[0], "()");
                                                $address_no = trim($details[1], "()");
                                                $address_street = trim($details[2], "()");
                                                $address_area = trim($details[3], "()");
                                                $address_postcode = trim($details[4], "()");
                                                $address_state = trim($details[5], "()");
                                                //combine the address
                                                $combined_address = $address_no.', '.$address_street.', '.$address_area.', '.$address_postcode.', '.$address_state.', Malaysia';

                                                //display
                                                echo '<button class="accordion">'.$address_label.'</button>
                                                <div class="panel">
                                                    <div class="btn-inside-address">
                                                    <form id="edit-form" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="POST">
                                                        <input type="hidden" id="edit-address" name="edit-address" value="'.$index.'">
                                                        <button type="submit" title="Edit" style="outline: none;" onclick="document.getElementById(\'id02\').style.display=\'flex\'">
                                                        <i class="fas fa-pen"></i>
                                                    </button>   
                                                    </form>   
                                                    <button type="button" style="outline: none;" title="Delete" class="bin" style="outline: none;"><input type="hidden" name="delete-address"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                    <div class="address-detail">
                                                        <span>'.$combined_address.'</span>
                                                    </div> 
                                                </div>';
                                                
                                                $index++;
                                            }
                                            if ($numAddresses < 3) {
                                                //if the addresses less than 3 then show the add button
                                                echo '
                                                <button class="add-accordion" title="Add Address" style="outline: none;" onclick="document.getElementById(\'id03\').style.display=\'flex\'"><i class="fas fa-plus"></i></button>
                                                ';
                                            }
                                        }else{
                                            echo '
                                            <button class="add-accordion" title="Add Address" style="outline: none;"  onclick="document.getElementById(\'id03\').style.display=\'flex\'"><i class="fas fa-plus"></i></button>
                                            ';
                                        }

                                    ?>
                                    
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