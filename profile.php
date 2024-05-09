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
        <script src="script.js"></script>
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

            $get_ph = "SELECT cust_phone FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
            $ph_result = $conn->query($get_ph);
            $ph_row = $ph_result->fetch_assoc();
            if($ph_row && !empty($ph_row['cust_phone'])){
                $phone = $ph_row['cust_phone'];
                $phone_without_country_code = substr($phone, 3);
            }
            else{
                $phone = "";
            }

            $get_name = "SELECT cust_username FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
            $name_result = $conn->query($get_name);
            $name_row = $name_result->fetch_assoc();
            if($name_row && !empty($name_row['cust_username'])){
                $name = $name_row['cust_username'];
            }
            else{
                $name = "There is a problem on database.";
            }

            $get_point = "SELECT cust_points FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
            $point_result = $conn->query($get_point);
            $point_row = $point_result->fetch_assoc();
            if($point_row && !empty($point_row['cust_points'])){
                $point = $point_row['cust_points'];
            }
            else{
                $point = "0";
            }

            $get_email = "SELECT cust_email FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
            $email_result = $conn->query($get_email);
            $email_row = $email_result->fetch_assoc();
            if($email_row && !empty($email_row['cust_email'])){
                $email_address = $email_row['cust_email'];
            }
            else{
                $email_address = "There is a problem on database.";
            }

            $add_label = array();
            $add_unit = array();
            $add_building = array();
            $add_street = array();
            $add_postcode = array();
            $add_state = array();

            $get_address = "SELECT cust_address FROM customer WHERE cust_ID = $cust_ID AND trash = 0"; 
            $address_result = $conn->query($get_address);

            if($address_result && $address_result->num_rows > 0) {
                while($row = $address_result->fetch_assoc()) {
                    $addresses = explode("},{", $row['cust_address']);

                    foreach($addresses as $address) {
                        // Remove { and } from the beginning and end of the address
                        $address = trim($address, "{}");
                        
                        // Split the address into components
                        $components = explode(",", $address);
                        
                        // Extract individual components
                        $add_label[] = trim($components[0], "()");
                        $add_unit[] = trim($components[1], "()");
                        $add_building[] = trim($components[2], "()");
                        $add_street[] = trim($components[3], "()");
                        $add_postcode[] = trim($components[4], "()");
                        $add_state[] = trim($components[5], "()");
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

                // Split the addresses string into an array
                $addresses = explode("},{", $addresses_string);
                $addresses = array_filter($addresses, 'strlen');
            
                // Add the new address to the array
                $new_address = "($add_label),($add_no),($add_building),($add_street),($add_postcode),($add_state)";
                $addresses[] = $new_address;
            
                // Concatenate all addresses back into a single string
                $updated_addresses_string = implode("},{", $addresses);
                $updated_add_string = "{" . $updated_addresses_string . "}";
            
                // Update the database 
                $update_query = "UPDATE customer SET cust_address = '$updated_add_string' WHERE cust_ID = $cust_ID AND trash = 0";
                if($conn->query($update_query) === TRUE) {
                    $_SESSION['addAddress_success'] = true;
                    echo '<script>';
                    echo 'window.location.href = "profile.php";';
                    echo '</script>';
                    exit();
                } else {
                    $_SESSION['addAddress_error'] = "Error updating record: " . $conn->error;
                    echo '<script>';
                    echo 'window.location.href = "profile.php";';
                    echo '</script>';
                    exit();
                }
            }
            if(isset($_POST['update-address'])){
                $add_id = $_POST['index'];
                $add_label = $_POST['Label'];
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
                $updated_add_string = "{" . $updated_addresses_string . "}";
                // Update the database 
                $update_query = "UPDATE customer SET cust_address = '$updated_add_string' WHERE cust_ID = $cust_ID AND trash = 0";
                if($conn->query($update_query) === TRUE) {
                    $_SESSION['updateAddress_success'] = true;
                    echo '<script>';
                    echo 'window.location.href = "profile.php";';
                    echo '</script>';
                    exit();
                } else {
                    $_SESSION['updateAddress_error'] = "Error updating record: " . $conn->error;
                    echo '<script>';
                    echo 'window.location.href = "profile.php";';
                    echo '</script>';
                    exit();
                }
                
            }
            if(isset($_POST['del-address'])){
                $index = $_POST['delete-index'];

                // Retrieve all addresses from the database
                $get_addresses_query = "SELECT cust_address FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
                $addresses_result = $conn->query($get_addresses_query);
                $address_row = mysqli_fetch_assoc($addresses_result);
                $addresses_string = $address_row['cust_address'];

                // Split the addresses string into an array
                $addresses = explode("},{", $addresses_string);
                $addresses = array_filter($addresses, 'strlen');
                $numAddresses = count($addresses);

                // Check if the selected index is valid
                if ($index >= 0 && $index < $numAddresses) {
                    // Mark the selected address as deleted
                    unset($addresses[$index]);

                    // Concatenate all remaining addresses back into a single string
                    $updated_addresses_string = implode("},{", $addresses);
                    $updated_add_string = "{" . $updated_addresses_string . "}";

                    // Update the database with the modified addresses
                    $update_query = "UPDATE customer SET cust_address = '$updated_add_string' WHERE cust_ID = $cust_ID AND trash = 0";
                    if ($conn->query($update_query) === TRUE) {
                        $_SESSION['deleteAddress_success'] = true;
                        echo '<script>';
                        echo 'window.location.href = "profile.php";';
                        echo '</script>';
                        exit();
                    } else {
                        $_SESSION['deleteAddress_error'] = "Error updating record: " . $conn->error;
                        echo '<script>';
                        echo 'window.location.href = "profile.php";';
                        echo '</script>';
                        exit();
                    }
                }
            }
            if (isset($_POST['changepwd'])) {
                $get_pwd = "SELECT cust_pass FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
                $pwd_result = $conn->query($get_pwd);
                $pwd_row = $pwd_result->fetch_assoc();
            
                if ($pwd_row) {
                    if (password_verify($_POST['originalpwd'], $pwd_row['cust_pass'])) {
                        $_SESSION['password_verification'] = 'correct';
                    } else {
                        $_SESSION['password_verification'] = 'Incorrect password';
                    }
                }
            
                // Redirect to the same page to prevent form resubmission
                header("Location: profile.php");
                exit();
            }
            if(isset($_POST['changepwd1'])){
                $new_password = $_POST['newpwd1'];
                $h_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_query = "UPDATE customer SET cust_pass = '$h_new_password' WHERE cust_ID = $cust_ID AND trash = 0";

                if ($conn->query($update_query) === TRUE) {
                    // Password updated successfully
                    $_SESSION['password_update_success'] = true;
                    echo '<script>';
                    echo 'window.location.href = "profile.php";';
                    echo '</script>';
                    exit();
                } else {
                    // Error updating password
                    $_SESSION['password_update_error'] = "Error updating password: " . $conn->error;
                    echo '<script>';
                    echo 'window.location.href = "profile.php";';
                    echo '</script>';
                    exit();
                }
            }
            if(isset($_POST['del-acc'])){
                $update_query = "UPDATE customer SET trash = 1 WHERE cust_ID = $cust_ID AND trash = 0";
                if ($conn->query($update_query) === TRUE) {
                    // Password updated successfully
                    $_SESSION['delete_acc_success'] = true;
                    echo '<script>';
                    echo 'window.location.href = "profile.php";';
                    echo '</script>';
                    exit();
                } else {
                    // Error updating password
                    $_SESSION['delete_acc_error'] = "Error updating record: " . $conn->error;
                    echo '<script>';
                    echo 'window.location.href = "profile.php";';
                    echo '</script>';
                    exit();
                }
            }
            if(isset($_POST['update-profile'])){
                $un = $_POST['uname'];
                $ph = $_POST['pn'];

                $phone_with_country_code = "+60" . $ph;

                $update_username = "UPDATE customer SET cust_username = '$un' WHERE cust_ID = $cust_ID AND trash = 0";
                $update_phone = "UPDATE customer SET cust_phone = '$phone_with_country_code' WHERE cust_ID = $cust_ID AND trash = 0";

                if($conn->query($update_username) === TRUE && $conn->query($update_phone) === TRUE) {
                    $_SESSION['update_pro_success'] = true;
                    echo '<script>';
                    echo 'window.location.href = "profile.php";';
                    echo '</script>';
                    exit();
                } else {
                    $_SESSION['update_pro_error'] = "Error updating record: " . $conn->error;
                    echo '<script>';
                    echo 'window.location.href = "profile.php";';
                    echo '</script>';
                    exit();
                }
            }
            if (isset($_SESSION['update_pro_success']) && $_SESSION['update_pro_success'] === true) {
                echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Profile successfully updated!
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';

                unset($_SESSION['update_pro_success']);
            }
            if (isset($_SESSION['update_pro_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Failed to update profile. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                echo '<div class="error_message">' . $_SESSION['update_pro_error'] . '</div>';

                unset($_SESSION['update_pro_error']);
            }
            if (isset($_SESSION['delete_acc_success']) && $_SESSION['delete_acc_success'] === true) {
                echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Password successfully changed!
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';

                unset($_SESSION['delete_acc_success']);
            }
            if (isset($_SESSION['delete_acc_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Failed to change password. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                echo '<div class="error_message">' . $_SESSION['delete_acc_error'] . '</div>';

                unset($_SESSION['delete_acc_error']);
            }

            if (isset($_SESSION['password_update_success']) && $_SESSION['password_update_success'] === true) {
                echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Password successfully changed!
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';

                unset($_SESSION['password_update_success']);
            }
            if (isset($_SESSION['password_update_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Failed to change password. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                echo '<div class="error_message">' . $_SESSION['password_update_error'] . '</div>';

                unset($_SESSION['password_update_error']);
            }
            if (isset($_SESSION['deleteAddress_success']) && $_SESSION['deleteAddress_success'] === true) {
                echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Profile successfully deleted!
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';

                unset($_SESSION['deleteAddress_success']);
            }
            if (isset($_SESSION['deleteAddress_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Failed to delete address. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                echo '<div class="error_message">' . $_SESSION['deleteAddress_error'] . '</div>';

                unset($_SESSION['deleteAddress_error']);
            }
            if (isset($_SESSION['updateAddress_success']) && $_SESSION['updateAddress_success'] === true) {
                echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Profile successfully updated!
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';

                unset($_SESSION['updateAddress_success']);
            }
            if (isset($_SESSION['updateAddress_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Failed to update address. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                echo '<div class="error_message">' . $_SESSION['updateAddress_error'] . '</div>';

                unset($_SESSION['updateAddress_error']);
            }
            if (isset($_SESSION['addAddress_success']) && $_SESSION['addAddress_success'] === true) {
                echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Address successfully added!
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';

                unset($_SESSION['addAddress_success']);
            }
            if (isset($_SESSION['addAddress_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Failed to add address. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                echo '<div class="error_message">' . $_SESSION['addAddress_error'] . '</div>';

                unset($_SESSION['addAddress_error']);
            }

            include 'sidebar.php';
            include 'gototopbtn.php'
        ?>
        <script>
                document.addEventListener('DOMContentLoaded', function() {
                    <?php
                    if (isset($_SESSION['password_verification'])) {
                        if ($_SESSION['password_verification'] == 'correct') {
                            echo 'document.getElementById(\'changepwd1\').style.display=\'flex\';';
                        } else {
                            echo 'document.getElementById(\'changepwd\').style.display=\'flex\';';
                            echo 'document.getElementById("pwd-error").innerText = "Incorrect password";';
                        }

                        // Clear the session variable after displaying the message
                        unset($_SESSION['password_verification']);
                    }
                    ?>
                });
            </script>
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
                        <input type="text" id="uname" name="uname" value="<?php echo $name;?>" required>
                        <span class="address-error"></span>
                  
                        <label for="pn"><b><i class="fas fa-phone-alt"></i> Phone Number</b></label>
                        <div style="display:flex;"><span style="text-align: center;border: 1px solid #ccc;padding: 5px; margin-left: 8px;">+60</span><input type="tel" id="pn" name="pn" value="<?php echo $phone_without_country_code;?>" required></div>
                        <span class="address-error"></span>
                          
                        <button type="submit" id="updateprofile" name="update-profile" class="edit-profile-btn" style="outline: none;">Update</button>
                        
                      </div>
                  
                      <div class="pcontainer" style="background-color:#f3f3f3; height: 80px;">
                        <button type="button" class="edit-profile-btn cancelbtn" style="outline: none;" onclick="document.getElementById('id01').style.display='none'">Cancel</button>
                      </div>
                    </form>
                  </div>
                
                  <div id="add-id01" class="modal">
                    <form class="user-profile-form profile-edit-content animate" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <div class="xcontainer">
                            <span class="txt">Address</span>
                            <span onclick="document.getElementById('add-id01').style.display='none'" class="closeedit" title="Close Modal">&times;</span>
                          </div>
                      <div class="pcontainer">
                        <div class="pcontainer-height">
                        <input type="hidden" name="index" value="1">
                        <label for="Label1"><b><i class="fas fa-hashtag"></i>Label as:</b></label>
                        <input type="text" id="Label1" name="Label" value="<?php echo $add_label[0]; ?>" required> 
                        <span class="address-error"></span>

                        <label for="houseno1"><b>House / Unit No.:</b></label>
                        <input type="text" id="houseno1" name="houseno" value="<?php echo $add_unit[0]; ?>" required>
                        <span class="address-error"></span>

                        <label for="addline1"><b>Buiding name / District:</b></label>
                        <input type="text" id="addline1"  name="addline" value="<?php echo  $add_building[0]; ?>" required>
                        <span class="address-error"></span>
                          
                        <label for="addline21"><b>Street Name:</b></label>
                        <input type="text" id="addline21"  name="addline2" value="<?php echo $add_street[0]; ?>" required>
                        <span class="address-error"></span>

                        <label for="postcode1"><b>Postcode & locality name:</b></label>
                        <input type="text" id="postcode1"  name="postcode" value="<?php echo $add_postcode[0]; ?>" required>
                        <span class="address-error"></span>

                        <label for="state1"><b>State:</b></label>
                        <select id="state1" name="state" >
                            <option>Select</option>
                            <option value="Johor" <?php if ($add_state[0] == "Johor") echo "selected"; ?>>Johor, Malaysia</option>
                            <option value="Kedah" <?php if ($add_state[0] == "Kedah") echo "selected"; ?>>Kedah, Malaysia</option>
                            <option value="Kelantan" <?php if ($add_state[0] == "Kelantan") echo "selected"; ?>>Kelantan, Malaysia</option>
                            <option value="Malacca" <?php if ($add_state[0] == "Malacca") echo "selected"; ?>>Malacca, Malaysia</option>
                            <option value="Pahang" <?php if ($add_state[0] == "Pahang") echo "selected"; ?>>Pahang, Malaysia</option>
                            <option value="Penang" <?php if ($add_state[0] == "Penang") echo "selected"; ?>>Penang, Malaysia</option>
                            <option value="Perak" <?php if ($add_state[0] == "Perak") echo "selected"; ?>>Perak, Malaysia</option>
                            <option value="Kuala Lumpur" <?php if ($add_state[0] == "Kuala Lumpur") echo "selected"; ?>>Kuala Lumpur, Malaysia</option>
                        <select>
                        <span class="address-error"></span>
                    </div>
                        <button type="submit" name="update-address" id="submit-edit1" class="edit-profile-btn" style="outline: none; margin-top:15px">Update</button>
                        
                      </div>
                  
                      <div class="pcontainer" style="background-color:#f3f3f3; height: 80px;">
                        <button type="button" class="edit-profile-btn cancelbtn" style="outline: none;" onclick="document.getElementById('add-id01').style.display='none'">Cancel</button>
                      </div>
                    </form>
                  </div>
                  <div id="add-id02" class="modal">
                    <form class="user-profile-form profile-edit-content animate" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <div class="xcontainer">
                            <span class="txt">Address</span>
                            <span onclick="document.getElementById('add-id02').style.display='none'" class="closeedit" title="Close Modal">&times;</span>
                          </div>
                          <div class="pcontainer">
                        <div class="pcontainer-height">
                        <input type="hidden" name="index" value="2">
                        <label for="Label2"><b><i class="fas fa-hashtag"></i>Label as:</b></label>
                        <input type="text" id="Label2" name="Label" value="<?php echo $add_label[1]; ?>" required> 
                        <span class="address-error"></span>

                        <label for="houseno2"><b>House / Unit No.:</b></label>
                        <input type="text" id="houseno2" name="houseno" value="<?php echo $add_unit[1]; ?>" required>
                        <span class="address-error"></span>

                        <label for="addline2"><b>Buiding name / District:</b></label>
                        <input type="text" id="addline2"  name="addline" value="<?php echo  $add_building[1]; ?>" required>
                        <span class="address-error"></span>
                          
                        <label for="addline22"><b>Street Name:</b></label>
                        <input type="text" id="addline22"  name="addline2" value="<?php echo $add_street[1]; ?>" required>
                        <span class="address-error"></span>

                        <label for="postcode2"><b>Postcode & locality name:</b></label>
                        <input type="text" id="postcode2"  name="postcode" value="<?php echo $add_postcode[1]; ?>" required>
                        <span class="address-error"></span>

                        <label for="state2"><b>State:</b></label>
                        <select id="state2" name="state" >
                            <option>Select</option>
                            <option value="Johor" <?php if ($add_state[1] == "Johor") echo "selected"; ?>>Johor, Malaysia</option>
                            <option value="Kedah" <?php if ($add_state[1] == "Kedah") echo "selected"; ?>>Kedah, Malaysia</option>
                            <option value="Kelantan" <?php if ($add_state[1] == "Kelantan") echo "selected"; ?>>Kelantan, Malaysia</option>
                            <option value="Malacca" <?php if ($add_state[1] == "Malacca") echo "selected"; ?>>Malacca, Malaysia</option>
                            <option value="Pahang" <?php if ($add_state[1] == "Pahang") echo "selected"; ?>>Pahang, Malaysia</option>
                            <option value="Penang" <?php if ($add_state[1] == "Penang") echo "selected"; ?>>Penang, Malaysia</option>
                            <option value="Perak" <?php if ($add_state[1] == "Perak") echo "selected"; ?>>Perak, Malaysia</option>
                            <option value="Kuala Lumpur" <?php if ($add_state[1] == "Kuala Lumpur") echo "selected"; ?>>Kuala Lumpur, Malaysia</option>
                        <select>
                        <span class="address-error"></span>
                    </div>
                        <button type="submit" name="update-address" id="submit-edit2" class="edit-profile-btn" style="outline: none; margin-top:15px">Update</button>
                        
                      </div>
                  
                      <div class="pcontainer" style="background-color:#f3f3f3; height: 80px;">
                        <button type="button" class="edit-profile-btn cancelbtn" style="outline: none;" onclick="document.getElementById('add-id02').style.display='none'">Cancel</button>
                      </div>
                    </form>
                  </div>
                  <div id="add-id03" class="modal">
                    <form class="user-profile-form profile-edit-content animate" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <div class="xcontainer">
                            <span class="txt">Address</span>
                            <span onclick="document.getElementById('add-id03').style.display='none'" class="closeedit" title="Close Modal">&times;</span>
                          </div>
                          <div class="pcontainer">
                        <div class="pcontainer-height">
                        <input type="hidden" name="index" value="3">
                        <label for="Label3"><b><i class="fas fa-hashtag"></i>Label as:</b></label>
                        <input type="text" id="Label3" name="Label" value="<?php echo $add_label[2]; ?>" required> 
                        <span class="address-error"></span>

                        <label for="houseno3"><b>House / Unit No.:</b></label>
                        <input type="text" id="houseno3" name="houseno" value="<?php echo $add_unit[2]; ?>" required>
                        <span class="address-error"></span>

                        <label for="addline3"><b>Buiding name / District:</b></label>
                        <input type="text" id="addline3"  name="addline" value="<?php echo  $add_building[2]; ?>" required>
                        <span class="address-error"></span>
                          
                        <label for="addline23"><b>Street Name:</b></label>
                        <input type="text" id="addline23"  name="addline2" value="<?php echo $add_street[2]; ?>" required>
                        <span class="address-error"></span>

                        <label for="postcode3"><b>Postcode & locality name:</b></label>
                        <input type="text" id="postcode3"  name="postcode" value="<?php echo $add_postcode[2]; ?>" required>
                        <span class="address-error"></span>

                        <label for="state3"><b>State:</b></label>
                        <select id="state3"  name="state" >
                            <option>Select</option>
                            <option value="Johor" <?php if ($add_state[2] == "Johor") echo "selected"; ?>>Johor, Malaysia</option>
                            <option value="Kedah" <?php if ($add_state[2] == "Kedah") echo "selected"; ?>>Kedah, Malaysia</option>
                            <option value="Kelantan" <?php if ($add_state[2] == "Kelantan") echo "selected"; ?>>Kelantan, Malaysia</option>
                            <option value="Malacca" <?php if ($add_state[2] == "Malacca") echo "selected"; ?>>Malacca, Malaysia</option>
                            <option value="Pahang" <?php if ($add_state[2] == "Pahang") echo "selected"; ?>>Pahang, Malaysia</option>
                            <option value="Penang" <?php if ($add_state[2] == "Penang") echo "selected"; ?>>Penang, Malaysia</option>
                            <option value="Perak" <?php if ($add_state[2] == "Perak") echo "selected"; ?>>Perak, Malaysia</option>
                            <option value="Kuala Lumpur" <?php if ($add_state[2] == "Kuala Lumpur") echo "selected"; ?>>Kuala Lumpur, Malaysia</option>
                        <select>
                        <span class="address-error"></span>
                    </div>
                        <button type="submit" name="update-address" id="submit-edit3" class="edit-profile-btn" style="outline: none; margin-top:15px">Update</button>
                        
                      </div>
                  
                      <div class="pcontainer" style="background-color:#f3f3f3; height: 80px;">
                        <button type="button" class="edit-profile-btn cancelbtn" style="outline: none;" onclick="document.getElementById('add-id03').style.display='none'">Cancel</button>
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
                        <button type="submit" name="add-address" id="submit-add" class="edit-profile-btn" style="outline: none; margin-top:15px">Save</button>
                        
                      </div>
                  
                      <div class="pcontainer" style="background-color:#f3f3f3; height: 80px;">
                        <button type="button" class="edit-profile-btn cancelbtn" style="outline: none;" onclick="document.getElementById('id03').style.display='none'">Cancel</button>
                      </div>
                    </form>
                  </div>
                  <div id="changepwd" class="modal">
                    <form class="profile-edit-content animate" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <div class="xcontainer">
                            <span class="txt">Account</span>
                            <span onclick="document.getElementById('changepwd').style.display='none'" class="closeedit" title="Close Modal">&times;</span>
                          </div>
                      <div class="pcontainer">
                        <div class="pcontainer-height">
                        <label for="email"><b><i class="fas fa-at"></i>Email:</b></label>
                        <input type="email" id="email" name="email" value="<?php echo $email_address;?>" readonly>
                        <span class="address-error"></span>
                        
                        <label for="originalpwd"><b><i class="fas fa-lock"></i>Original Password:</b></label>
                        <input type="password" id="originalpwd" name="originalpwd" placeholder="" required>
                        <span id="pwd-error" class="address-error"></span>
                        
                    </div>
                        <p style = "margin-top:10px; margin-left:8px; margin-bottom:unset; font-size: 14px">Forgot Password? <a href="recover_psw.php" style="color:#5a9498;"><b> CLICK HERE</b></a></p>
                        <button type="submit" name="changepwd" id="submit-pwd" class="edit-profile-btn" style="outline: none; margin-top:7px">Done</button>
                      </div>
                  
                      <div class="pcontainer" style="background-color:#f3f3f3; height: 80px;">
                        <button type="button" class="edit-profile-btn cancelbtn" style="outline: none;" onclick="document.getElementById('changepwd').style.display='none'">Cancel</button>
                      </div>
                    </form>
                  </div>
                  <div id="changepwd1" class="modal">
                    <form class="profile-edit-content animate" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <div class="xcontainer">
                            <span class="txt">Account</span>
                            <span onclick="document.getElementById('changepwd1').style.display='none'" class="closeedit" title="Close Modal">&times;</span>
                          </div>
                      <div class="pcontainer">
                        <div class="pcontainer-height">
                        <label for="email1"><b><i class="fas fa-at"></i>Email:</b></label>
                        <input type="email" id="email1" name="email1" value="<?php echo $email_address;?>" readonly>
                        <span class="address-error"></span>
                        
                        <label for="newpwd1"><b><i class="fas fa-key"></i>New Password:</b></label>
                        <div style="position:relative;"><i class="fas fa-eye-slash" style="position:absolute;right: 20px;top: 12px;font-size: 13px; cursor:pointer;" onclick="togglePasswordVisibility('newpwd1', this)"></i><input type="password" id="newpwd1" name="newpwd1" required></div>
                        <span class="address-error"></span>
                          
                        <label for="newpwd2"><b><i class="fas fa-key"></i>Confirm New Password:</b></label>
                        <div style="position:relative;"><i class="fas fa-eye-slash" style="position:absolute;right: 20px;top: 12px;font-size: 13px;cursor:pointer;" onclick="togglePasswordVisibility('newpwd2', this)"></i><input type="password" id="newpwd2" name="newpwd2" required></div>
                        <span class="address-error"></span>
                    </div>
                        <button type="submit" name="changepwd1" id="submit-pwd1" class="edit-profile-btn" style="outline: none; margin-top:7px">Done</button>
                      </div>
                  
                      <div class="pcontainer" style="background-color:#f3f3f3; height: 80px;">
                        <button type="button" class="edit-profile-btn cancelbtn" style="outline: none;" onclick="document.getElementById('changepwd1').style.display='none'">Cancel</button>
                      </div>
                    </form>
                  </div>
                  <div id="deleteacc" class="modal">
                    <form class="profile-edit-content animate" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <div class="xcontainer">
                            <span class="txt" style="color: #e2857b;"><i class="fas fa-exclamation" style="color: #e2857b;"></i> Alert</span>
                            <span onclick="document.getElementById('deleteacc').style.display='none'" class="closeedit" title="Close Modal">&times;</span>
                        </div>
                        <div class="pcontainer">
                            <p style="margin-bottom:unset;">Are you sure you want to <b style="color:#e2857b;">DELETE</b> this account?</p>
                            <p style="margin-top:5px;">Your purchase history, wishlist, and other data will be <b style="color:#e2857b;">LOST!</b></p>
                            <button type="submit" name="del-acc" class="edit-profile-btn" style="outline: none; margin-top:15px">Confirm</button>
                        </div>
                        <div class="pcontainer" style="background-color:#f3f3f3; height: 70px;">
                            <button type="button" class="edit-profile-btn cancelbtn" style="outline: none;" onclick="document.getElementById('deleteacc').style.display='none'">Cancel</button>
                        </div>
                    </form>
                </div>

                  <script>
                  function togglePasswordVisibility(passwordFieldId, icon) {
                        const passwordField = document.getElementById(passwordFieldId);
                        
                        if (passwordField.type === "password") {
                            passwordField.type = "text";
                            icon.classList.remove("fa-eye-slash");
                            icon.classList.add("fa-eye");
                        } else {
                            passwordField.type = "password";
                            icon.classList.remove("fa-eye");
                            icon.classList.add("fa-eye-slash");
                        }
                    }
                    </script>
                    <script>
                        //validate the updateform
                        document.addEventListener("DOMContentLoaded", function () {
                            const formElements1 = document.querySelectorAll("#Label1, #houseno1, #addline1, #addline21, #postcode1, #state1");
                            const formElements2 = document.querySelectorAll("#Label2, #houseno2, #addline2, #addline22, #postcode2, #state2");
                            const formElements3 = document.querySelectorAll("#Label3, #houseno3, #addline3, #addline23, #postcode3, #state3");
                            const formElements4 = document.querySelectorAll("#newLabel, #newhouseno, #newaddline, #newaddline2, #newpostcode, #newstate");
                            const formElements5 = document.querySelectorAll("#newpwd1, #newpwd2");
                            const formElements6 = document.querySelectorAll("#uname, #pn");
                            
                            formElements1.forEach(element => {
                                element.addEventListener("input", function () {
                                    validateAddressForm1();
                                });
                            });
                            formElements2.forEach(element => {
                                element.addEventListener("input", function () {
                                    validateAddressForm2();
                                });
                            });
                            formElements3.forEach(element => {
                                element.addEventListener("input", function () {
                                    validateAddressForm3();
                                });
                            });
                            formElements4.forEach(element => {
                                element.addEventListener("input", function () {
                                    validateAddressForm4();
                                });
                            });
                            formElements5.forEach(element => {
                                element.addEventListener("input", function () {
                                    validateAddressForm5();
                                });
                            });
                            formElements6.forEach(element => {
                                element.addEventListener("input", function () {
                                    validateAddressForm6();
                                });
                            });

                            // Attach event listener to the submit button
                            document.getElementById("submit-edit1").addEventListener("click", function (event) {
                                // Validate form fields
                                if (!validateAddressForm1()) {
                                    // Prevent form submission if validation fails
                                    event.preventDefault();
                                }
                            });
                            document.getElementById("submit-edit2").addEventListener("click", function (event) {
                                // Validate form fields
                                if (!validateAddressForm2()) {
                                    // Prevent form submission if validation fails
                                    event.preventDefault();
                                }
                            });
                            document.getElementById("submit-edit3").addEventListener("click", function (event) {
                                // Validate form fields
                                if (!validateAddressForm3()) {
                                    // Prevent form submission if validation fails
                                    event.preventDefault();
                                }
                            });
                            document.getElementById("submit-add").addEventListener("click", function (event) {
                                // Validate form fields
                                if (!validateAddressForm4()) {
                                    // Prevent form submission if validation fails
                                    event.preventDefault();
                                }
                            });
                            document.getElementById("submit-pwd1").addEventListener("click", function (event) {
                                // Validate form fields
                                if (!validateAddressForm5()) {
                                    // Prevent form submission if validation fails
                                    event.preventDefault();
                                }
                            });
                            document.getElementById("updateprofile").addEventListener("click", function (event) {
                                // Validate form fields
                                if (!validateAddressForm6()) {
                                    // Prevent form submission if validation fails
                                    event.preventDefault();
                                }
                            });
                        });

                        function validateAddressForm1() {
                            <?php if (!empty($add_label[1])): ?>
                                const existingLabels1 = <?php echo json_encode($add_label[1]); ?>;
                            <?php endif; ?>
                            <?php if (!empty($add_label[2])): ?>
                                const existingLabels2 = <?php echo json_encode($add_label[2]); ?>;
                            <?php endif; ?>
                            
                            const label = document.getElementById("Label1").value;
                            const houseno = document.getElementById("houseno1").value;
                            const addline= document.getElementById("addline1").value;
                            const addline2 = document.getElementById("addline21").value;
                            const postcode = document.getElementById("postcode1").value;
                            const state = document.getElementById("state1").value;

                            let valid = true;
      
                            // Validate Label
                            if (label.trim() === "") {
                                errorDisplay(document.getElementById("Label1"), "*Please enter a label.*");
                                valid = false;
                            } 
                            else if(label.trim().includes(",")){
                                errorDisplay(document.getElementById("Label1"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }
                            <?php if (!empty($add_label[1])): ?>
                            else if(existingLabels1==label.trim()){
                                errorDisplay(document.getElementById("Label1"), "*Avoid same label name from other address.*");
                                valid = false;
                            }
                            <?php endif; ?>
                            <?php if (!empty($add_label[2])): ?>
                            else if(existingLabels2==label.trim()){
                                errorDisplay(document.getElementById("Label1"), "*Avoid same label name from other address.*");
                                valid = false;
                            }
                            <?php endif; ?>
                            else {
                                clearError(document.getElementById("Label1"));
                            }

                            // Validate House/Unit No.
                            if (houseno.trim() === "") {
                                errorDisplay(document.getElementById("houseno1"), "*Please enter a house/unit number.*");
                                valid = false;
                            } 
                            else if(houseno.trim().includes(",")){
                                errorDisplay(document.getElementById("houseno1"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }
                            else {
                                clearError(document.getElementById("houseno1"));
                            }

                            // Validate Building Name/District
                            if (addline.trim() === "") {
                                errorDisplay(document.getElementById("addline1"), "*Please enter a building name/district.*");
                                valid = false;
                            } 
                            else if(addline.trim().includes(",")){
                                errorDisplay(document.getElementById("addline1"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }
                            else {
                                clearError(document.getElementById("addline1"));
                            }

                            // Validate Street Name
                            if (addline2.trim() === "") {
                                errorDisplay(document.getElementById("addline21"), "*Please enter a street name.*");
                                valid = false;
                            }
                            else if(addline2.trim().includes(",")){
                                errorDisplay(document.getElementById("addline21"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }  
                            else {
                                clearError(document.getElementById("addline21"));
                            }

                            // Validate Postcode & Locality Name
                            if (postcode.trim() === "") {
                                errorDisplay(document.getElementById("postcode1"), "*Please enter a postcode & locality name.*");
                                valid = false;
                            }
                            else if(postcode.trim().includes(",")){
                                errorDisplay(document.getElementById("postcode1"), "*Commas are not allowed in any field.*");
                                valid = false;
                            } 
                            else {
                                clearError(document.getElementById("postcode1"));
                            }

                            // Validate State
                            if (state === "Select" || state === "") {
                                errorDisplay(document.getElementById("state1"), "*Please select a state.*");
                                valid = false;
                            } else {
                                clearError(document.getElementById("state1"));
                            }

                            // Return the overall validity of the form
                            return valid;
                        }
                        function validateAddressForm2() {
                            <?php if (!empty($add_label[1])): ?>
                                const existingLabels1 = <?php echo json_encode($add_label[0]); ?>;
                            <?php endif; ?>
                            <?php if (!empty($add_label[2])): ?>
                                const existingLabels2 = <?php echo json_encode($add_label[2]); ?>;
                            <?php endif; ?>
                            const label = document.getElementById("Label2").value;
                            const houseno = document.getElementById("houseno2").value;
                            const addline = document.getElementById("addline2").value;
                            const addline2 = document.getElementById("addline22").value;
                            const postcode = document.getElementById("postcode2").value;
                            const state = document.getElementById("state2").value;

                            let valid = true;
      
                            // Validate Label
                            if (label.trim() === "") {
                                errorDisplay(document.getElementById("Label2"), "*Please enter a label.*");
                                valid = false;
                            } 
                            else if(label.trim().includes(",")){
                                errorDisplay(document.getElementById("Label2"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }
                            <?php if (!empty($add_label[0])): ?>
                            else if(existingLabels1==label.trim()){
                                errorDisplay(document.getElementById("Label2"), "*Avoid same label name from other address.*");
                                valid = false;
                            }
                            <?php endif; ?>
                            <?php if (!empty($add_label[2])): ?>
                            else if(existingLabels2==label.trim()){
                                errorDisplay(document.getElementById("Label2"), "*Avoid same label name from other address.*");
                                valid = false;
                            }
                            <?php endif; ?>
                            else {
                                clearError(document.getElementById("Label2"));
                            }

                            // Validate House/Unit No.
                            if (houseno.trim() === "") {
                                errorDisplay(document.getElementById("houseno2"), "*Please enter a house/unit number.*");
                                valid = false;
                            } 
                            else if(houseno.trim().includes(",")){
                                errorDisplay(document.getElementById("houseno2"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }
                            else {
                                clearError(document.getElementById("houseno2"));
                            }

                            // Validate Building Name/District
                            if (addline.trim() === "") {
                                errorDisplay(document.getElementById("addline2"), "*Please enter a building name/district.*");
                                valid = false;
                            } 
                            else if(addline.trim().includes(",")){
                                errorDisplay(document.getElementById("addline2"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }
                            else {
                                clearError(document.getElementById("addline2"));
                            }

                            // Validate Street Name
                            if (addline2.trim() === "") {
                                errorDisplay(document.getElementById("addline22"), "*Please enter a street name.*");
                                valid = false;
                            }
                            else if(addline2.trim().includes(",")){
                                errorDisplay(document.getElementById("addline22"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }  
                            else {
                                clearError(document.getElementById("addline22"));
                            }

                            // Validate Postcode & Locality Name
                            if (postcode.trim() === "") {
                                errorDisplay(document.getElementById("postcode2"), "*Please enter a postcode & locality name.*");
                                valid = false;
                            }
                            else if(postcode.trim().includes(",")){
                                errorDisplay(document.getElementById("postcode2"), "*Commas are not allowed in any field.*");
                                valid = false;
                            } 
                            else {
                                clearError(document.getElementById("postcode2"));
                            }

                            // Validate State
                            if (state === "Select" || state === "") {
                                errorDisplay(document.getElementById("state2"), "*Please select a state.*");
                                valid = false;
                            } else {
                                clearError(document.getElementById("state2"));
                            }

                            // Return the overall validity of the form
                            return valid;
                        }
                        function validateAddressForm3() {
                            <?php if (!empty($add_label[0])): ?>
                                const existingLabels1 = <?php echo json_encode($add_label[0]); ?>;
                            <?php endif; ?>
                            <?php if (!empty($add_label[1])): ?>
                                const existingLabels2 = <?php echo json_encode($add_label[1]); ?>;
                            <?php endif; ?>
                            const label = document.getElementById("Label3").value;
                            const houseno = document.getElementById("houseno3").value;
                            const addline = document.getElementById("addline3").value;
                            const addline2 = document.getElementById("addline23").value;
                            const postcode = document.getElementById("postcode3").value;
                            const state = document.getElementById("state3").value;

                            let valid = true;
      
                            // Validate Label
                            if (label.trim() === "") {
                                errorDisplay(document.getElementById("Label3"), "*Please enter a label.*");
                                valid = false;
                            } 
                            else if(label.trim().includes(",")){
                                errorDisplay(document.getElementById("Label3"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }
                            <?php if (!empty($add_label[0])): ?>
                            else if(existingLabels1==label.trim()){
                                errorDisplay(document.getElementById("Label3"), "*Avoid same label name from other address.*");
                                valid = false;
                            }
                            <?php endif; ?>
                            <?php if (!empty($add_label[1])): ?>
                            else if(existingLabels2==label.trim()){
                                errorDisplay(document.getElementById("Label3"), "*Avoid same label name from other address.*");
                                valid = false;
                            }
                            <?php endif; ?>
                            else {
                                clearError(document.getElementById("Label3"));
                            }

                            // Validate House/Unit No.
                            if (houseno.trim() === "") {
                                errorDisplay(document.getElementById("houseno3"), "*Please enter a house/unit number.*");
                                valid = false;
                            } 
                            else if(houseno.trim().includes(",")){
                                errorDisplay(document.getElementById("houseno3"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }
                            else {
                                clearError(document.getElementById("houseno3"));
                            }

                            // Validate Building Name/District
                            if (addline.trim() === "") {
                                errorDisplay(document.getElementById("addline3"), "*Please enter a building name/district.*");
                                valid = false;
                            } 
                            else if(addline.trim().includes(",")){
                                errorDisplay(document.getElementById("addline3"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }
                            else {
                                clearError(document.getElementById("addline3"));
                            }

                            // Validate Street Name
                            if (addline2.trim() === "") {
                                errorDisplay(document.getElementById("addline23"), "*Please enter a street name.*");
                                valid = false;
                            }
                            else if(addline2.trim().includes(",")){
                                errorDisplay(document.getElementById("addline23"), "*Commas are not allowed in any field.*");
                                valid = false;
                            }  
                            else {
                                clearError(document.getElementById("addline23"));
                            }

                            // Validate Postcode & Locality Name
                            if (postcode.trim() === "") {
                                errorDisplay(document.getElementById("postcode3"), "*Please enter a postcode & locality name.*");
                                valid = false;
                            }
                            else if(postcode.trim().includes(",")){
                                errorDisplay(document.getElementById("postcode3"), "*Commas are not allowed in any field.*");
                                valid = false;
                            } 
                            else {
                                clearError(document.getElementById("postcode3"));
                            }

                            // Validate State
                            if (state === "Select" || state === "") {
                                errorDisplay(document.getElementById("state3"), "*Please select a state.*");
                                valid = false;
                            } else {
                                clearError(document.getElementById("state3"));
                            }

                            // Return the overall validity of the form
                            return valid;
                        }
                        //validate add form/
                        function validateAddressForm4() {
                            <?php if (!empty($add_label[0])): ?>
                                const existingLabels1 = <?php echo json_encode($add_label[0]); ?>;
                            <?php endif; ?>
                            <?php if (!empty($add_label[1])): ?>
                                const existingLabels2 = <?php echo json_encode($add_label[1]); ?>;
                            <?php endif; ?>
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
                            <?php if (!empty($add_label[0])): ?>
                            else if(existingLabels1==newlabel.trim()){
                                errorDisplay(document.getElementById("newLabel"), "*Avoid same label name from other address.*");
                                valid = false;
                            }
                            <?php endif; ?>
                            <?php if (!empty($add_label[1])): ?>
                            else if(existingLabels2==newlabel.trim()){
                                errorDisplay(document.getElementById("newLabel"), "*Avoid same label name from other address.*");
                                valid = false;
                            }
                            <?php endif; ?>
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
                        function validateAddressForm5() {
                            
                            const newpwd1 = document.getElementById("newpwd1").value;
                            const newpwd2 = document.getElementById("newpwd2").value;

                            let valid = true;
      
                            // Validate pwd
                            if (newpwd1.length < 8) {
                                errorDisplay1(document.getElementById("newpwd1"), "*Password must be at least 8 characters long.*");
                                valid = false;
                            } else {
                                clearError1(document.getElementById("newpwd1"));
                            }

                            // Validate both passwords are not empty and match
                            if (newpwd1.trim() === "" || newpwd2.trim() === "") {
                                errorDisplay1(document.getElementById("newpwd2"), "*Passwords cannot be empty.*");
                                valid = false;
                            } else if (newpwd1 !== newpwd2) {
                                errorDisplay1(document.getElementById("newpwd2"), "*Passwords do not match.*");
                                valid = false;
                            } else {
                                clearError1(document.getElementById("newpwd2"));
                            }
                            
                            // Return the overall validity of the form
                            return valid;
                        }
                        function validateAddressForm6() {
                            
                            const uname = document.getElementById("uname").value;
                            const pn = document.getElementById("pn").value;

                            let valid = true;
      
                            // Validate pwd
                            if (uname.trim() === "") {
                                errorDisplay(document.getElementById("uname"), "*Username cannot be empty.*");
                                valid = false;
                            } else if(uname.length > 20){
                                errorDisplay(document.getElementById("uname"), "*Username cannot be more than 20 characters.*");
                                valid = false;
                            } 
                            else {
                                clearError(document.getElementById("uname"));
                            }

                            // Validate both passwords are not empty and match
                            if (pn.trim() === "") {
                                errorDisplay1(document.getElementById("pn"), "*Phone Number cannot be empty.*");
                                valid = false;
                            } else if (pn.length < 9) {
                                errorDisplay1(document.getElementById("pn"), "*Please fill the valid phone number.*");
                                valid = false;
                            }else if (pn.charAt(0) === '0') {
                                errorDisplay1(document.getElementById("pn"), "*Please remove the first 0 in front.*");
                                valid = false;
                            }else if (!/^\d+$/.test(pn)) {
                                errorDisplay1(document.getElementById("pn"), "*Please enter only digits in the phone number.*");
                                valid = false;
                            } 
                            else {
                                clearError1(document.getElementById("pn"));
                            }
                            
                            // Return the overall validity of the form
                            return valid;
                        }
                        function errorDisplay1(input, message) {
                            const errorElement = input.parentNode.nextElementSibling;
                            errorElement.innerText = message;
                            errorElement.classList.remove('hidden');
                            input.classList.add('error-color');
                        }

                        // Function to clear error message
                        function clearError1(input) {
                            const errorElement = input.parentNode.nextElementSibling;
                            errorElement.innerText = "";
                            errorElement.classList.add('hidden');
                            input.classList.remove('error-color');
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
                            <p><?php echo $name;?></p>
                            <div class="btm-pt">
                                <img src="images/coin.png" width="22px">
                                <div class="btm-pt-prt">
                                    <p>Points Owned:</p>
                                    <span><?php echo $point;?> pts</span>
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
                        <a href="logout.php" style="text-decoration:none;color:#E2857B;"><i class="fas fa-sign-out-alt"></i> Log Out</a>
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
                                                    echo '<span class="box-witdh2">'.htmlspecialchars($phone_number) .'</span>';
                                                }
                                                else{
                                                    echo '<span class="box-witdh2">-</span>';
                                                }
                                            ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="account-container">
                                        <button class="changepwdbtn" style="outline: none;" onclick="document.getElementById('changepwd').style.display='flex'">Change Password</button>
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
                                            <button style="border: none; outline: none;" onclick="document.getElementById('deleteacc').style.display='flex'"><i class="fas fa-trash-alt" style="font-size: 13px;margin-right: 5px;"></i>Delete Account</button>
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
                                                        <button type="button" title="Edit" style="outline: none;" id="edit-btn-'.$index.'">
                                                        <i class="fas fa-pen"></i>
                                                    </button>   
                                                      
                                                    <button style="outline: none;" title="Delete" class="bin" style="outline: none;" onclick="document.getElementById(\'confirmdelete\').style.display=\'flex\'"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                    <div class="address-detail">
                                                        <span>'.$combined_address.'</span>
                                                    </div> 
                                                </div>';

                                                echo '<div id="confirmdelete" class="modal">
                                                <form class="profile-edit-content animate" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
                                                    <div class="xcontainer">
                                                        <span class="txt" style="color: #e2857b;"><i class="fas fa-exclamation" style="color: #e2857b;"></i> Alert</span>
                                                        <span onclick="document.getElementById(\'confirmdelete\').style.display=\'none\'" class="closeedit" title="Close Modal">&times;</span>
                                                      </div>
                                                  <div class="pcontainer">
                                                    <p>Are you sure you want to <b style="color:#e2857b;">DELETE</b> this address?</p>
                                                    <input type="hidden" name="delete-index" value="'.$index.'">
                                                    <button type="submit" name="del-address" class="edit-profile-btn" style="outline: none; margin-top:15px">Confirm</button>
                                                  </div>
                                                  <div class="pcontainer" style="background-color:#f3f3f3; height: 70px;">
                                                    <button type="button" class="edit-profile-btn cancelbtn" style="outline: none;" onclick="document.getElementById(\'confirmdelete\').style.display=\'none\'">Cancel</button>
                                                  </div>
                                                </form>
                                              </div>';

                                                echo '<script>';
                                                echo 'document.addEventListener("DOMContentLoaded", function() {';
                                                for ($i = 1; $i <= $numAddresses; $i++) {
                                                    echo 'document.getElementById("edit-btn-'.$i.'").addEventListener("click", function() {';
                                                    echo 'document.getElementById("add-id0'.$i.'").style.display = "flex";';
                                                    echo '});';
                                                }
                                                echo '});';
                                                echo '</script>';
                                                
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