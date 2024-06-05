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
                $sql = "UPDATE customer SET trash = $trash WHERE cust_ID = $cust_ID";
                

                if ($conn->query($sql) === TRUE) {
                    $_SESSION['editcust_success'] = true;
                    header("Location: customers-edit.php?ID=$cust_ID");
                    exit();
                } else {
                    $_SESSION['editcust_error'] = "Error: " . $sql . "<br>" . $conn->error;
                    header("Location: customers-edit.php?ID=$cust_ID");
                    exit();
                }
            }
        }

        if (isset($_SESSION['editcust_success']) && $_SESSION['editcust_success'] === true) {
            echo '<div class="toast_container">
                    <div id="custom_toast" class="custom_toast true fade_in">
                        <div class="d-flex align-items-center message">
                            <i class="fas fa-check-circle"></i> User changes saved!
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
                                <i class="fas fa-times-circle"></i>Failed to edit customer. Please try again...
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';

            unset($_SESSION['editcust_error']);
        }
        

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
                            <input type="tel" title="Unable to edit" name="cust_phone" id="cust_phone" placeholder="+60123456789" value="<?php echo !empty($row['cust_phone']) ? $row['cust_phone'] : "-"; ?>" readonly>
                        </div>
                        <div class="address-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                        <div class='item_detail_container'>
                            <label for="cust_email">Email Address</label>
                            <input type="email" title="Unable to edit" name="cust_email" id="cust_email" placeholder="xxx@gmail.com" value="<?php echo $row['cust_email']; ?>" readonly>
                        </div>
                        <div class="address-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                        <div class='item_detail_container'>
                            <label for="delivery">Delivery Address</label>
                            <div name="delivery" title="Unable to edit" id="delivery" style="width:100%;border: #e9ecef 1px solid;background-color: #e9ecef;border-radius: 7px;font-size: 18px;padding: 2px 5px;">
                                <?php
                                    //take out addresses
                                    $get_address = "SELECT cust_address FROM customer WHERE cust_ID = $cust_ID";
                                        $address_result = $conn->query($get_address);
                                        $address_row = mysqli_fetch_assoc($address_result);

                                        if(!empty($address_row['cust_address'])){
                                            $addresses = explode("},{", $address_row['cust_address']);
                                            $addresses = array_filter($addresses, 'strlen');
                                            //count the address
                                            $numAddresses = count($addresses);
                                            $i=1;
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
                                                echo '<span style="font-weight:600;font-size:16px;padding-left: 3px;">'.$address_label.'</span>
                                                    <span style="display:block;font-size: 16px;background-color: #ffffff;padding: 3px;border-radius: 5px;margin: 2px;">'.$combined_address.'</span>
                                                    '; 
                                                $i++; 
                                            }
                                        }else{
                                            echo '
                                            <tr><td class="no_items"><i class="far fa-ghost"></i>No address filled...</td></tr>
                                            ';
                                        }
                                ?>
                            </div>
                        </div>
                        <hr style="width:100%;">
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