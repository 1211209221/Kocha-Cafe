<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>New Admin | Admin Panel</title>
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
        <link rel="icon" href="../images/logo/logo_icon_2.png">
        <script src="../script.js"></script>
        <script src="../gototop.js"></script>
    </head>
    <body>
    <?php
        include '../connect.php';
        include '../gototopbtn.php';
        include 'navbar.php';

        //session_start();
        if (isset($_POST['add-admin'])) {
            // Retrieve form data
            $admin_name = $_POST['admin_name'];
            $admin_phone = $_POST['admin_phone'];
            $admin_username = $_POST['admin_username'];
            $admin_email = $_POST['admin_email'];
            $admin_password = $_POST['admin_password'];
            $admin_level = $_POST['admin_level'];

            $admin_password = password_hash($admin_password, PASSWORD_DEFAULT);

            //compare username and email first
            $check_query = "SELECT * FROM admin";
            $result = $conn->query($check_query);
            $username_exists = false;
            $email_exists = false;

            while ($row = $result->fetch_assoc()) {
                // Check if the username matches and it's not the user's own username
                if ($admin_username === $row['admin_username']) {
                    $username_exists = true;
                }

                if($admin_email === $row['admin_email']){
                    $email_exists = true;
                }
            }

            if($username_exists > 0 && $email_exists > 0){
                $_SESSION['update_match'] = 'both match';
                echo '<script>';
                echo 'window.location.href = "admins-add.php";';
                echo '</script>';
                //header("Location: admins-add.php");
                exit();
            }
            else if($email_exists > 0){
                $_SESSION['update_match'] = 'email match';
                echo '<script>';
                echo 'window.location.href = "admins-add.php";';
                echo '</script>';
                exit();
            }
            else if($username_exists > 0){
                $_SESSION['update_match'] = 'username match';
                echo '<script>';
                echo 'window.location.href = "admins-add.php";';
                echo '</script>';
                exit();
            }
            
            // Construct the SQL query to insert 
            $sqladmin = "INSERT INTO admin (admin_name, admin_username, admin_phno, admin_pass, admin_email, admin_level) VALUES ('$admin_name', '$admin_username', '$admin_phone', '$admin_password', '$admin_email', '$admin_level')";

            if ($conn->query($sqladmin) === TRUE) {
                $_SESSION['addAdmin_success'] = true;
                echo '<script>';
                echo 'window.location.href = "admins-add.php";';
                echo '</script>';
                exit();

            } else {
                $_SESSION['addAdmin_error'] = "Error: " . $sqladmin . "<br>" . $conn->error;
                echo '<script>';
                echo 'window.location.href = "admins-add.php";';
                echo '</script>';
                exit();
            }
        }

        if (isset($_SESSION['addAdmin_success']) && $_SESSION['addAdmin_success'] === true) {
            echo '<div class="toast_container">
                    <div id="custom_toast" class="custom_toast true fade_in">
                        <div class="d-flex align-items-center message">
                            <i class="fas fa-check-circle"></i> Admin successfully added to admin list!
                        </div>
                        <div class="timer"></div>
                    </div>
                </div>';

            unset($_SESSION['addAdmin_success']);
        }

        if (isset($_SESSION['addAdmin_error'])) {
            echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast false fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-times-circle"></i>Failed to add admin. Please try again...
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';

            unset($_SESSION['addAdmin_error']);
        }


    ?>
    <script>
                document.addEventListener('DOMContentLoaded', function() {
                    <?php
                    if (isset($_SESSION['update_match'])) {
                        if ($_SESSION['update_match'] == 'both match') {
                            echo 'document.getElementById("user-error").innerText = "*The username has been used by other.*";';
                            echo 'document.getElementById("email-error").innerText = "*The email has been used by other.*";';
                        }
                        else if($_SESSION['update_match'] == 'username match'){
                            echo 'document.getElementById("user-error").innerText = "*The username has been used by other.*";';
                        }
                        else if($_SESSION['update_match'] == 'email match'){
                            echo 'document.getElementById("email-error").innerText = "*The email has been used by other.*";';
                        }

                        // Clear the session variable after displaying the message
                        unset($_SESSION['update_match']);
                    }

                    ?>
                });
            </script>
    <style>
        @media (max-width: 768px) {
            .edit_items .item_details .fa-eye-slash, .edit_items .item_details .fa-eye {
                top:37px !important;
            }
        }
    </style>
    <div class="container-fluid container">
        <div class="col-12 m-auto">
            <div class="edit_items add_items">
                <form action="admins-add.php" method="post" class="item_edit_form">
                    <div class="big_container" style="position: relative;">
                        <div class="breadcrumbs">
                            <a>Admin</a> > <a>Users</a> > <a href="admins-all.php">Admin List</a> > <a class="active">Add New</a>
                        </div>
                        
                        <div class='item_details'>
                            <div class="page_title">New Admin<i class="fas fa-pen"></i></div>
                            <div class='item_detail_container'>
                                <label for="admin_name">Name</label>
                                <input type="text" name="admin_name" id="admin_name" placeholder="New Name" required>
                            </div>
                            <div class="address-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                        <div class='item_detail_container'>
                            <label for="admin_phone">Phone Number</label>
                            <input type="tel" value="+60" name="admin_phone" id="admin_phone" placeholder="+60123456789" required>
                        </div>
                        <div class="address-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                        <div class='item_detail_container'>
                            <label for="admin_username">Username</label>
                            <input type="text" name="admin_username" id="admin_username" placeholder="adminxxx123" required></textarea>
                        </div>
                        <div class="address-error" id="user-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                        <div class='item_detail_container'>
                            <label for="admin_email">Email Address</label>
                            <input type="email" name="admin_email" id="admin_email" placeholder="xxx@gmail.com" required>
                        </div>
                        <div class="address-error" id="email-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                        <div class='item_detail_container' style="position:relative;">
                            <label for="admin_password">Password</label>
                            <input type="password" name="admin_password" id="admin_password" required><i class="fas fa-eye-slash" style="position:absolute;right: 10px;top: 10px;font-size: 12px; cursor:pointer;" onclick="togglePasswordVisibility('admin_password', this)"></i>
                        </div>
                        <div class="address-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                        <div class='item_detail_container'>
                            <label for="admin_level">Admin Level</label>
                            <select name="admin_level" id="admin_level" style="width:100%;">
                                <option value="1">Admin</option>
                                <option value="2">Superadmin</option>
                            </select>
                        </div>
                        <div class='submit_buttons'>
                            <input type="submit" name="add-admin" id="add-admin" value="Add Admin" class="edit_submit">
                        </div>
                    </div>
                    <a href="admins-all.php" class="back_button2">Back</a>
                </div>
            </div>
                </form>
            </div>
        </div>
    </div>
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
            const formElements1 = document.querySelectorAll("#admin_name, #admin_phone, #admin_username, #admin_email, #admin_password");

            formElements1.forEach(element => {
                element.addEventListener("input", function () {
                    validateForm1();
                });
            });

            document.getElementById("add-admin").addEventListener("click", function (event) {
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
                            
            const admin_name = document.getElementById("admin_name").value;
            const admin_phone = document.getElementById("admin_phone").value;
            const admin_username = document.getElementById("admin_username").value;
            const admin_email = document.getElementById("admin_email").value;
            const admin_password = document.getElementById("admin_password").value;
            var letters = /^[a-zA-Z-' ]*$/;
            let valid = true;
      
            if (admin_name.trim() === "") {
                errorDisplay1(document.getElementById("admin_name"), "*Please enter your name.*");
                valid = false;
            }
            else if(!admin_name.match(letters)){
                errorDisplay1(document.getElementById("admin_name"), "*Only letters and white space allowed.*");
            }
            else{
                clearError1(document.getElementById("admin_name"));
            }

            if (admin_username.trim() === "") {
                errorDisplay1(document.getElementById("admin_username"), "*Username cannot be empty.*");
                valid = false;
            } else if(admin_username.length > 20){
                errorDisplay1(document.getElementById("admin_username"), "*Username cannot be more than 20 characters.*");
                valid = false;
            } 
            else {
                clearError1(document.getElementById("admin_username"));
            }

            if (admin_phone.trim() === "") {
                errorDisplay1(document.getElementById("admin_phone"), "*Please enter your phone number.*");
                valid = false;
            }else if (!admin_phone.trim().startsWith("+60")) {
                errorDisplay1(document.getElementById("admin_phone"), "*Please enter a phone number starting with +60*");
                valid = false;
            } else if (!isValidPhone(admin_phone.trim())) {
                errorDisplay1(document.getElementById("admin_phone"), "*Invalid phone number format*");
                valid = false;
            }
            else{
                clearError1(document.getElementById("admin_phone"));
            }

            if (admin_email.trim() === "") {
                errorDisplay1(document.getElementById("admin_email"), "*Please enter your email address.*");
                    valid = false;
            } else if (!isValidEmail(admin_email.trim())) {
                errorDisplay1(document.getElementById("admin_email"), "*Invalid email format*");
                valid = false;
            }
            else{
                clearError1(document.getElementById("admin_email"));
            }

            if (admin_password.trim() === "") {
                errorDisplay1(document.getElementById("admin_password"), "*Password cannot be empty.*");
                    valid = false;
            }
            else if (admin_password.length < 8) {
                errorDisplay1(document.getElementById("admin_password"), "*Password must be at least 8 characters long.*");
                valid = false;
            } else {
                clearError1(document.getElementById("admin_password"));
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
        $conn->close();
    ?>
    </body>
</html>