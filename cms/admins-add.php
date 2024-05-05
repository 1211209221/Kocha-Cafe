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
    ?>
    <div class="container-fluid container">
        <div class="col-12 m-auto">
            <div class="edit_items add_items">
                <form action="items-add.php" method="post" enctype="multipart/form-data" class="item_edit_form">
                    <div class="big_container" style="position: relative;">
                        <div class="breadcrumbs">
                            <a>Admin</a> > <a>Users</a> > <a href="admins-all.php">Item List</a> > <a class="active">Add New</a>
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
                            <input type="tel" value="+60" name="admin_phone" id="admin_phone" placeholder="+60 123456789" required>
                        </div>
                        <div class="address-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                        <div class='item_detail_container'>
                            <label for="admin_username">Username</label>
                            <input type="text" name="admin_username" id="admin_username" placeholder="adminxxx123" required></textarea>
                        </div>
                        <div class="address-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                        <div class='item_detail_container'>
                            <label for="admin_email">Email Address</label>
                            <input type="email" name="admin_email" id="admin_email" placeholder="xxx@gmail.com" required>
                        </div>
                        <div class="address-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                        <div class='item_detail_container'>
                            <label for="admin_level">Admin Level</label>
                            <select name="admin_level" id="admin_level">
                                <option value="1">Admin</option>
                                <option value="2">Superadmin</option>
                            </select>
                        </div>
                        <div class='submit_buttons'>
                            <input type="submit" id="add-admin" value="Add Admin" class="edit_submit">
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
        document.addEventListener("DOMContentLoaded", function () {
            const formElements1 = document.querySelectorAll("#admin_name, #admin_phone, #admin_username, #admin_email");

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
                clearError  (document.getElementById("admin_username"));
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