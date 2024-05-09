<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Customer | Admin Panel</title>
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
        <link rel="icon" href="../images/logo/logo_icon_2.png">
        <script src="../script.js"></script>
        <script src="../gototop.js"></script>
    </head>
    <body>
    <style>
        @media (max-width: 768px) {
            .edit_items .item_details .fa-eye-slash, .edit_items .item_details .fa-eye {
                top:37px !important;
            }
        }
    </style>
    <?php
        include '../connect.php';
        include '../gototopbtn.php';

        session_start();

        if (isset($_GET['ID'])) {
            // Retrieve the value of the ID parameter
            $cust_ID = $_GET['ID'];
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['edit_submit'])){
                // Retrieve form data
                $trash = $_POST['cust_trash'];
                $sql = "UPDATE customer SET trash = 1 WHERE cust_ID = $cust_ID";
                

                if ($conn->query($sql) === TRUE) {
                    $_SESSION['editcust_success'] = true;
                    header("Location: admins-edit.php?ID=$cust_ID");
                    exit();
                } else {
                    $_SESSION['editcust_error'] = "Error: " . $sql . "<br>" . $conn->error;
                    header("Location: admins-edit.php?ID=$cust_ID");
                    exit();
                }
            }
        }

        if (isset($_SESSION['editcust_success']) && $_SESSION['editcust_success'] === true) {
            echo '<div class="toast_container">
                    <div id="custom_toast" class="custom_toast true fade_in">
                        <div class="d-flex align-items-center message">
                            <i class="fas fa-check-circle"></i> Admin changes saved!
                        </div>
                        <div class="timer"></div>
                    </div>
                </div>';

            unset($_SESSION['editcust_success']);
        }

        if (isset($_SESSION['editcust_error'])) {
            echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast false fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i>Failed to edit admin. Please try again...
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';

            unset($_SESSION['editcust_error']);
        }
        // if (isset($_SESSION['deleteAdmin_success']) && $_SESSION['deleteAdmin_success'] === true) {
        //     echo '<div class="toast_container">
        //             <div id="custom_toast" class="custom_toast true fade_in">
        //                 <div class="d-flex align-items-center message">
        //                     <i class="fas fa-check-circle"></i> Admin successfully removed from admin list!
        //                 </div>
        //                 <div class="timer"></div>
        //             </div>
        //         </div>';

        //     unset($_SESSION['deleteAdmin_success']);
        // }

        // if (isset($_SESSION['deleteAdmin_error'])) {
        //     echo '<div class="toast_container">
        //                 <div id="custom_toast" class="custom_toast false fade_in">
        //                     <div class="d-flex align-items-center message">
        //                         <i class="fas fa-check-circle"></i>Failed to remove admin. Please try again...
        //                     </div>
        //                     <div class="timer"></div>
        //                 </div>
        //             </div>';

        //     unset($_SESSION['deleteAdmin_error']);
        // }

        $sql = "SELECT * FROM customer WHERE cust_ID = $cust_ID";

        include 'navbar.php';
        $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){  
    ?>
    <div class="container-fluid container">
        <div class="col-12 m-auto">
            <div class="edit_items add_items">
                <form method="post" class="item_edit_form">
                    <div class="big_container" style="position: relative;">
                        <div class="breadcrumbs">
                            <a>Admin</a> > <a>Users</a> > <a href="customers-all.php">Customer List</a> > <a class="active"><?php echo $row['cust_username']; ?></a>
                        </div>
                        
                        <div class='item_details'>
                            <div class="page_title">Customer Information<i class="fas fa-id-card"></i></div>
                            <div class='item_detail_container'>
                                <label for="cust_username">Username</label>
                                <input type="text" title="Unable to edit" name="cust_username" id="cust_username" placeholder="Customer Username" value="<?php echo $row['cust_username']; ?>" readonly>
                            </div>
                            <div class="address-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                        <div class='item_detail_container'>
                            <label for="cust_phone">Phone Number</label>
                            <input type="tel" title="Unable to edit" name="cust_phone" id="cust_phone" placeholder="+60123456789" value="<?php echo $row['cust_phone']; ?>" readonly>
                        </div>
                        <div class="address-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                        <div class='item_detail_container'>
                            <label for="cust_email">Email Address</label>
                            <input type="email" title="Unable to edit" name="cust_email" id="cust_email" placeholder="xxx@gmail.com" value="<?php echo $row['cust_email']; ?>" readonly>
                        </div>
                        <div class="address-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>

                        <div class='item_detail_container'>
                            <label for="cust_trash">Access Mode</label>
                            <select name="cust_trash" id="cust_trash" style="width:100%;">
                                <option value="0" <?php if ($row['trash'] == "0") echo "selected"; ?>>Enabled</option>
                                <option value="1" <?php if ($row['trash'] == "1") echo "selected"; ?>>Disabled</option>
                            </select>
                        </div>
                        <div class='submit_buttons'>
                            <input type="submit" id="edit-submit" name="edit_submit" class="edit_submit" value="Save" onclick="confirmAction('submit the change');">
                        </div>
                    </div>
                    <a href="customers-all.php" class="back_button2">Back To List</a>
                </div>
            </div>
                </form>
                <script>
                    function confirmAction(message) {
                        return confirm("Are you sure you want to " + message + "?");
                    }
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
        document.addEventListener("DOMContentLoaded", function () {
            const formElements1 = document.querySelectorAll("#admin_name, #cust_phone, #admin_username, #cust_email, #admin_password");

            formElements1.forEach(element => {
                element.addEventListener("input", function () {
                    validateForm1();
                });
            });

            document.getElementById("edit-submit").addEventListener("click", function (event) {
                // Validate form fields
                if (!validateForm1()) {
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
        function validateForm1() {
                            
            const cust_phone = document.getElementById("cust_phone").value;
            const cust_username = document.getElementById("cust_username").value;
            const cust_email = document.getElementById("cust_email").value;
            var letters = /^[a-zA-Z-' ]*$/;
            let valid = true;

            if (cust_username.trim() === "") {
                errorDisplay1(document.getElementById("cust_username"), "*Username cannot be empty.*");
                valid = false;
            } else if(cust_username.length > 20){
                errorDisplay1(document.getElementById("cust_username"), "*Username cannot be more than 20 characters.*");
                valid = false;
            } 
            else {
                clearError1(document.getElementById("cust_username"));
            }

            if (cust_phone.trim() === "") {
                errorDisplay1(document.getElementById("cust_phone"), "*Please enter your phone number.*");
                valid = false;
            }else if (!cust_phone.trim().startsWith("+60")) {
                errorDisplay1(document.getElementById("cust_phone"), "*Please enter a phone number starting with +60*");
                valid = false;
            } else if (!isValidPhone(cust_phone.trim())) {
                errorDisplay1(document.getElementById("cust_phone"), "*Invalid phone number format*");
                valid = false;
            }
            else{
                clearError1(document.getElementById("cust_phone"));
            }

            if (cust_email.trim() === "") {
                errorDisplay1(document.getElementById("cust_email"), "*Please enter your email address.*");
                    valid = false;
            } else if (!isValidEmail(cust_email.trim())) {
                errorDisplay1(document.getElementById("cust_email"), "*Invalid email format*");
                valid = false;
            }
            else{
                clearError1(document.getElementById("cust_email"));
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
    </script>
                <?php
                }
                } else {
                    echo "No admin found";
                }

                $conn->close();
                ?>
            </div>
        </div>
    </div>
    </body>
</html>