<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Cart | Kocha Café</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" type="text/css" href="contact.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data.min.js"></script>
        <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
        <link rel="icon" href="images/logo/logo_icon.png">
        <script src="script.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="gototop.js"></script>
        <style>
        /* Style for the disabled submit button */
        input[type="submit"]:disabled {
            opacity: 50%;
            border:none;
            cursor: not-allowed;
        }
        input[type="submit"]:hover:disabled {
            transform:scale(1);
            opacity: 50%;
        }
    </style>
    </head>
    <body>
        <?php
            include 'connect.php';
            include 'top.php';

            if(empty($user)){
                header("Location: login.php");
                exit();
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if(isset($_POST['update_cart'])) {
                    // Retrieve form data
                    if(!empty($_POST['item_ID'])){
                        $item_IDs = $_POST['item_ID'];
                    }else{
                        $item_IDs = "";
                    }
                    
                    if(!empty($_POST['quantity_input'])){
                        $quantity_inputs = $_POST['quantity_input'];
                    }else{
                        $quantity_inputs = "";
                    }

                    if(!empty($_POST['price_sum'])){
                        $price_sums = $_POST['price_sum'];
                    }else{
                        $price_sums = "";
                    }

                    if(!empty($_POST['item_request'])){
                        $item_requests = $_POST['item_request'];
                    }else{
                        $item_requests = "";
                    }

                    $verify_item = $_POST['verify_item'];
                    $verify_item_array = explode(",", $verify_item);


                    if(!empty($_POST['item_option_selected'])){
                        $item_options_selected = $_POST['item_option_selected'];
                        $item_options_selected = implode("", $item_options_selected);
                        $options = explode(")(", $item_options_selected);
                    }
                    else{
                        $item_options_selected = "";
                    }
                    

                    $additional_price_values = [];
                    $option_names = [];
                    $customization_ID = [];

                    
                    if(!empty($_POST['item_option_selected'])){
                        foreach ($options as $option) {
                            $option = trim($option, '()');
                            list($additional_price_value, $option_name, $customization_ID, $index_number) = explode(',', $option);

                            if (isset($additional_price_values[$index_number])) {
                                $additional_price_values[$index_number] .= ',' . $additional_price_value;
                                $option_names[$index_number] .= ',' . $option_name;
                                $customization_IDs[$index_number] .= ',' . $customization_ID;
                            } else {
                                $additional_price_values[$index_number] = $additional_price_value;
                                $option_names[$index_number] = $option_name;
                                $customization_IDs[$index_number] = $customization_ID;
                            }
                        }
                    }

                    $string = "";
                    if(!empty($_POST['price_sum'])){

                        $k = 0;
                        $max_iterations = 100;

                        for($i = 0; $i < count($price_sums); $i++) {
                            $item_ID = $item_IDs[$i];
                            $quantity_input = $quantity_inputs[$i];
                            $price_sum = $price_sums[$i];
                            $item_request = $item_requests[$i];
                            $price_sum = str_replace("RM ", "", $price_sum);
                            $string .= '{(' . $item_ID .'),('. $quantity_input.'),('. $item_request.'),(';

                                while(in_array($k, $verify_item_array)){
                                    $k++;

                                    if ($k > $max_iterations) {
                                        break;
                                    }
                                }
                                
                            
                            if (isset($option_names[$k]) && isset($additional_price_values[$k]) && isset($customization_IDs[$k])) {
                                // Your existing code for accessing the arrays
                                $option_name = $option_names[$k];
                                $additional_price_value = $additional_price_values[$k];
                                $customization_ID = $customization_IDs[$k];
                            }
                            else{
                                $option_name = "";
                                $additional_price_value = "";
                                $customization_ID = "";
                            }


                            $option_name = explode(",", trim($option_name, "[]"));
                            $additional_price_value = explode(",", trim($additional_price_value, "[]"));
                            $customization_ID = explode(",", trim($customization_ID, "[]"));

                            for($j = 0; $j < count($option_name); $j++) {
                                $string .= '['. $customization_ID[$j].','.$option_name[$j].'],';
                            }
                            $string = rtrim($string, ',');

                            $k++;

                            $string .= ')},';
                        }
                        $string = rtrim($string, ',');
                    }else{
                        $string = '';
                    }

                    // Construct the SQL query to insert menu item data
                    $sql_cart = "UPDATE customer SET cust_cart = '$string' WHERE cust_ID = $cust_ID";


                    if ($conn->query($sql_cart) === TRUE) {
                        $_SESSION['saveCart_success'] = true;
                        header("Location: cart.php");
                        exit();
                    } else {
                        $_SESSION['saveCart_error'] = "Error: " . $sql_cart . "<br>" . $conn->error;
                        header("Location: cart.php");
                        exit();
                    }
                }else if(isset($_POST['submit_order'])) {
                    // Retrieve form data
                    if(!empty($_POST['item_ID'])){
                        $item_IDs = $_POST['item_ID'];
                    }else{
                        $item_IDs = "";
                    }

                    if(!empty($_POST['item_name'])){
                        $item_names = $_POST['item_name'];
                    }else{
                        $item_names = "";
                    }

                    if(!empty($_POST['item_price'])){
                        $item_prices = $_POST['item_price'];
                    }else{
                        $item_prices = "";
                    }
                    
                    if(!empty($_POST['quantity_input'])){
                        $quantity_inputs = $_POST['quantity_input'];
                    }else{
                        $quantity_inputs = "";
                    }

                    if(!empty($_POST['price_sum'])){
                        $price_sums = $_POST['price_sum'];
                    }else{
                        $price_sums = "";
                    }

                    if(!empty($_POST['item_request'])){
                        $item_requests = $_POST['item_request'];
                    }else{
                        $item_requests = "";
                    }

                    if (!empty($_POST['address'])) {
                        $add = $_POST['address'];
                        if ($add == 'different') {
                            $add = $_POST['new_location'];
                        }
                    }

                    if (!empty($_POST['holder_name'])) {
                        $holder_name = $_POST['holder_name'];
                    }
                    else{
                        $holder_name = "";
                    }

                    if (!empty($_POST['card_number'])) {
                        $card_number = $_POST['card_number'];
                        // Remove spaces from the card number
                        $card_number_cleaned = str_replace(' ', '', $card_number);
                        $last_four_digits = substr($card_number_cleaned, -4);//save last 4 digit into database
                    }
                    else{
                        $last_four_digits = 0;
                    }

                    //remember to decrease the point that user want to spend
                    if (!empty($_POST['redeem_points'])) {
                        $redeem_points = $_POST['redeem_points'];
                    }
                    else{
                        $redeem_points = 0;
                    }
                    
                    if (!empty($_POST['earn_point_value'])) {
                        $earn_point_value = $_POST['earn_point_value'];
                    }
                    else{
                        $earn_point_value = 0;
                    }

                    //take out point from user table
                    $get_pt = "SELECT cust_points FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
                    $pt_result = $conn->query($get_pt);
                    $pt_row = $pt_result->fetch_assoc();
                    
                    if($pt_row['cust_points']){
                        //add session
                        $currentpoint = $pt_row['cust_points'];
                    }
                    else{
                        $currentpoint = 0;
                    }
                    $resultafterpay = $currentpoint - $redeem_points + $earn_point_value;

                    if (!empty($_POST['sub'])) {
                        $subtotal = $_POST['sub'];
                    }
                    else{
                        $subtotal = 0;
                    }

                    if (!empty($_POST['totalprice'])) {
                        $totalprice = $_POST['totalprice'];
                    }
                    else{
                        $totalprice = 0;
                    }

                    $verify_item = $_POST['verify_item'];
                    $verify_item_array = explode(",", $verify_item);


                    if(!empty($_POST['item_option_selected'])){
                        $item_options_selected = $_POST['item_option_selected'];
                        $item_options_selected = implode("", $item_options_selected);
                        $options = explode(")(", $item_options_selected);
                    }
                    else{
                        $item_options_selected = "";
                    }

                    $additional_price_values = [];
                    $option_names = [];
                    $customization_ID = [];

                    
                    if(!empty($_POST['item_option_selected'])){
                        foreach ($options as $option) {
                            $option = trim($option, '()');
                            list($additional_price_value, $option_name, $customization_ID, $index_number) = explode(',', $option);

                            if (isset($additional_price_values[$index_number])) {
                                $additional_price_values[$index_number] .= ',' . $additional_price_value;
                                $option_names[$index_number] .= ',' . $option_name;
                                $customization_IDs[$index_number] .= ',' . $customization_ID;
                            } else {
                                $additional_price_values[$index_number] = $additional_price_value;
                                $option_names[$index_number] = $option_name;
                                $customization_IDs[$index_number] = $customization_ID;
                            }
                        }
                    }

                    $string = "";
                    if(!empty($_POST['price_sum'])){

                        $k = 0;
                        $max_iterations = 100;

                        for($i = 0; $i < count($price_sums); $i++) {
                            $item_ID = $item_IDs[$i];
                            $item_name = $item_names[$i];
                            $item_price = $item_prices[$i];
                            $quantity_input = $quantity_inputs[$i];
                            $price_sum = $price_sums[$i];
                            $item_request = $item_requests[$i];
                            $price_sum = str_replace("RM ", "", $price_sum);
                            $string .= '{(' . $item_ID .'),(' . $item_name .'),(' . $item_price .'),('. $quantity_input.'),(' . $price_sum .'),('. $item_request.'),(';

                                while(in_array($k, $verify_item_array)){
                                    $k++;

                                    if ($k > $max_iterations) {
                                        break;
                                    }
                                }
                                
                            
                            if (isset($option_names[$k]) && isset($additional_price_values[$k]) && isset($customization_IDs[$k])) {
                                // Your existing code for accessing the arrays
                                $option_name = $option_names[$k];
                                $additional_price_value = $additional_price_values[$k];
                                $customization_ID = $customization_IDs[$k];
                            }
                            else{
                                $option_name = "";
                                $additional_price_value = "";
                                $customization_ID = "";
                            }


                            $option_name = explode(",", trim($option_name, "[]"));
                            $additional_price_value = explode(",", trim($additional_price_value, "[]"));
                            $customization_ID = explode(",", trim($customization_ID, "[]"));

                            for($j = 0; $j < count($option_name); $j++) {
                                $customization_name = "";
                                $sql_get_customization = "SELECT custom_name FROM menu_customization WHERE custom_ID = '$customization_ID[$j]'  LIMIT 1";

                                $result_get_customization = $conn->query($sql_get_customization);
                                if ($result_get_customization->num_rows > 0) {
                                    while ($row_get_customization = $result_get_customization->fetch_assoc()) {
                                         $customization_name = $row_get_customization['custom_name'];
                                    }
                                }

                                $string .= '['. $customization_name.','.$option_name[$j].'],';
                            }
                            $string = rtrim($string, ',');

                            $k++;

                            $string .= ')},';
                        }
                        $string = rtrim($string, ',');
                    }else{
                        $string = '';
                        $_SESSION['submitOrderEmpty_error'] = true;
                        header("Location: cart.php");
                        exit();
                    }

                    //before that, check whether got filled ph no
                    $get_ph = "SELECT cust_phone FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
                    $ph_result = $conn->query($get_ph);
                    $ph_row = $ph_result->fetch_assoc();
                    
                    if(empty($ph_row['cust_phone'])){
                        //add session
                        $_SESSION['no_phone'] = "nophone";
                        header("Location: cart.php");
                        exit();
                    }

                    //create order id
                    function generate_order_id($conn) {
                        date_default_timezone_set('Asia/Kuala_Lumpur');
                        // Get the current timestamp and format it as Ymd
                        $timestamp = date('Ymd');
                    
                        // Fetch the last order ID from the database
                        $sql = "SELECT order_ID FROM customer_orders ORDER BY order_ID DESC LIMIT 1";
                        $result = $conn->query($sql);
                    
                        if ($result->num_rows > 0) {
                            // Get the last order ID
                            $row = $result->fetch_assoc();
                            $lastOrderID = $row['order_ID'];
                    
                            if ($lastOrderID) {
                                // Separate the date part and the increment part from the last order ID
                                $lastDate = substr($lastOrderID, 0, 8); // Extract the first 8 characters as the date part
                                $lastIncrement = intval(substr($lastOrderID, 8)); // Extract the remaining characters as the increment part
                                
                                // Get the current date in Ymd format
                                $currentDate = date('Ymd');
                                
                                if ($lastDate !== $currentDate) {
                                    // Reset the increment to 1 for a new day
                                    $increment = 1;
                                } else {
                                    // Increment the order number if it's the same day
                                    $increment = $lastIncrement + 1;
                                }

                            } else {
                                // Initialize the first order ID if no ID is found
                                $currentDate = date('Ymd');
                                $increment = 1;
                            }
                    
                            // Ensure the incrementing part is 5 digits
                            $newIncrementPart = str_pad($increment, 5, '0', STR_PAD_LEFT); // 5 digits for the incremental part
                            $orderID = $timestamp . $newIncrementPart;
                        }
                    
                        return $orderID;
                    }
                    // Generate the new order ID
                    $newOrderID = generate_order_id($conn);

                    // generate invoice number using the current timestamp
                    function generateInvoiceNumber() {
                        date_default_timezone_set('Asia/Kuala_Lumpur');
                        return 'INV-' . date('YmdHis'); // Example: INV-20240529123000
                    }

                    // Generate a new invoice number
                    $invoice_number = generateInvoiceNumber();

                    //update
                    $order_date = date('Y-m-d H:i:s');
                    // Construct the SQL query to insert menu item data
                    $sql_cart = "INSERT INTO customer_orders (order_ID, order_contents, order_subtotal, order_total, order_address, order_date,cust_ID) VALUES ('$newOrderID','$string', $subtotal, $totalprice, '$add','$order_date','$cust_ID')";
                    $payment = "INSERT INTO payment (payment_ID, payment_name, payment_items, payment_subtotal, payment_total, payment_cardnum, payment_time,cust_ID) VALUES ('$invoice_number','$holder_name','$string',$subtotal, $totalprice,'$last_four_digits','$order_date','$cust_ID')";

                    for ($i = 0; $i < (count($price_sums)); $i++) {
                        $item_ID = $item_IDs[$i];
                        $quantity_input = $quantity_inputs[$i];
                        $item_sold_no = 0;
                    
                        $sql_items = "SELECT * FROM menu_items WHERE item_ID = $item_ID";
                        $result_items = $conn->query($sql_items);
                    
                        if ($result_items->num_rows > 0) {
                            while ($row_items = $result_items->fetch_assoc()) {
                                $item_sold_no = $row_items['item_sold'];
                            }
                        }
                    
                        $sql_items_sold = "UPDATE menu_items SET item_sold = ($item_sold_no + $quantity_input) WHERE item_ID = $item_ID";
                        $conn->query($sql_items_sold);
                    }

                    //update point
                    $update_point = "UPDATE customer SET cust_points = $resultafterpay  WHERE cust_ID = $cust_ID AND trash = 0";

                    if(!empty($string)){
                        if ($conn->query($sql_cart) === TRUE && $conn->query($update_point) && $conn->query($payment)) {
                            $sql_empty_cart = "UPDATE customer SET cust_cart = '' WHERE cust_ID = $cust_ID";
                            $conn->query($sql_empty_cart);
                            $_SESSION['submitOrder_success'] = true;
                            header("Location: cart.php");
                            exit();
                        } else {
                            $_SESSION['submitOrder_error'] = "Error: " . $sql_cart . "<br>" . $conn->error;
                            header("Location: cart.php");
                            exit();
                        }
                    }
                    else{
                        $_SESSION['submitOrder_error'] = "Error: " . $sql_cart . "<br>" . $conn->error;
                        header("Location: cart.php");
                        exit();
                    }
                }
                else if(isset($_POST['update-profile'])){
                    $ph = $_POST['pn'];
    
                    $phone_with_country_code = "+60" . $ph;
    
                
                    $update_phone = "UPDATE customer SET cust_phone = '$phone_with_country_code' WHERE cust_ID = $cust_ID AND trash = 0";
    
                    if($conn->query($update_phone) === TRUE) {
                        $_SESSION['update_pro_success'] = true;
                        echo '<script>';
                        echo 'window.location.href = "cart.php";';
                        echo '</script>';
                        exit();
                    } else {
                        $_SESSION['update_pro_error'] = "Error updating record: " . $conn->error;
                        echo '<script>';
                        echo 'window.location.href = "cart.php";';
                        echo '</script>';
                        exit();
                    }
                    
                    
                }
            }
            if (isset($_SESSION['update_pro_success']) && $_SESSION['update_pro_success'] === true) {
                echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Phone number successfully updated!
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
                                    <i class="fas fa-times-circle"></i>Failed to update phone number. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                echo '<div class="error_message">' . $_SESSION['update_pro_error'] . '</div>';

                unset($_SESSION['update_pro_error']);
            }


            if (isset($_SESSION['saveCart_success']) && $_SESSION['saveCart_success'] === true) {
                echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Cart successfully updated!
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';

                unset($_SESSION['saveCart_success']);
            }

            if (isset($_SESSION['saveCart_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-times-circle"></i>Failed to update cart. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                echo '<div class="error_message">' . $_SESSION['addReview_error'] . '</div>';

                unset($_SESSION['addReview_error']);
            }

            if (isset($_SESSION['submitOrder_success']) && $_SESSION['submitOrder_success'] === true) {

                $_SESSION['redirected_from_order'] = true;

                echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Order successfully submitted!
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';

                unset($_SESSION['submitOrder_success']);
                //go to order tracking page
                echo '<script>
                    setTimeout(function(){
                        window.location.href = "profile.php"; 
                    }, 3000); // 3000 milliseconds = 3 seconds
                </script>';
            }

            if (isset($_SESSION['submitOrder_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-times-circle"></i>Failed to submit order. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                echo '<div class="error_message">' . $_SESSION['submitOrder_error'] . '</div>';

                unset($_SESSION['submitOrder_error']);
            }

            if (isset($_SESSION['submitOrderEmpty_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-times-circle"></i>You have no items in your cart! Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['submitOrderEmpty_error']);
            }

            // // Close connection
            // $conn->close();
        ?>
        <script>
                document.addEventListener('DOMContentLoaded', function() {
                    <?php
                    if (isset($_SESSION['no_phone'])) {
                        if ($_SESSION['no_phone'] == 'nophone') {
                            echo 'document.getElementById(\'id01\').style.display=\'flex\';';
                        } 

                        // Clear the session variable after displaying the message
                        unset($_SESSION['no_phone']);
                    }

                    ?>
                });
            </script>
        <style>
            .btm-pt{
                display:flex;
                position: relative;
            } 
            .btm-pt #tooltip{
                right: -5px;
                top: 3px;
                z-index:10;
            }
            .btm-pt .tooltip-content{
                    left: -310%;
                    top: -130%;
                    width: 160px;
                    position: absolute;
                    font-size: 13px !important;
                    text-align: justify;
                    transform: translate(-50%);
                    background-color: #000000db;
                    color: #fff !important;
                    padding: 5px 10px;
                    border-radius: 7px;
                    visibility: hidden;
                    opacity: 0;
                    transition: opacity 0.5s ease;
                }
            .btm-pt .tooltip-content::before{
                    content: "";
                    position: absolute;
                    left: 108%;
                    top: 33%;
                    transform: translate(-50%);
                    border: 13px solid;
                    border-color: #0000 #0000 #0000 #000;
                }

        </style>
        <script>
            //for payment
            <?php
                    //take out point from database to compare
                    $get_point = "SELECT cust_points FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
                    $point_result = $conn->query($get_point);
                    $point_row = $point_result->fetch_assoc();
                    if($point_row && !empty($point_row['cust_points'])){
                        $point = $point_row['cust_points'];
                    }
                    else{
                        $point = 0;
                    }
                ?>
            document.addEventListener("DOMContentLoaded", function() {
                //for payment form
                const formElements1 = document.querySelectorAll("#holder_name, #card_number, #expiry_date, #CVV, #redeem_points, #selectlocation");
                const formElements2 = document.querySelectorAll("#pn");
                formElements1.forEach(element => {
                    element.addEventListener("input", function () {
                        validateForm1();
                    });
                });
                formElements2.forEach(element => {
                    element.addEventListener("input", function () {
                        validateForm2();
                    });
                });
                document.getElementById("updateprofile").addEventListener("click", function (event) {
                    // Validate form fields
                    if (!validateForm2()) {
                        // Prevent form submission if validation fails
                        event.preventDefault();
                    }
                });

                // document.getElementById("submit_order").addEventListener("click", function (event) {
                //     // Validate form fields
                //     if (!validateForm1()) {
                //         // Prevent form submission if validation fails
                //         event.preventDefault();
                //     }
                // });
                
            });
            
            function validateForm2() {
                            
                            const pn = document.getElementById("pn").value;

                            let valid = true;
      

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

            function validateForm1(){
                const holder_name = document.getElementById("holder_name").value;
                let card_number = document.getElementById("card_number").value;
                const CVV = document.getElementById("CVV").value;
                let redeem_points = document.getElementById("redeem_points").value;
                const selectlocation = document.getElementById("selectlocation");
                const maxPoints = <?php echo $point; ?>;
                var letters = /^[a-zA-Z-' ]*$/;
                let valid = true;

                redeem_points = redeem_points.replace(/\D/g, '');
                
                if(card_number.trim() === ""){
                    errorDisplay(document.getElementById("card_number"), "*Card number is required.");
                    valid = false;
                }
                else if(card_number.trim().length!=19){
                    errorDisplay(document.getElementById("card_number"), "*Please fill in 16 digits.*");
                    valid = false;
                }
                else {
                    clearError(document.getElementById("card_number"));
                    
                }

                if(holder_name.trim() === ""){
                    errorDisplay(document.getElementById("holder_name"), "*Please fill your holder name.*");
                    valid = false;
                }
                else if(!holder_name.match(letters)){
                    errorDisplay(document.getElementById("holder_name"), "*Only letters and white space allowed.*");
                    valid = false;
                }
                else{
                    clearError(document.getElementById("holder_name"));
                }

                if(CVV.trim()==="" && document.getElementById("expiry_date").value.trim() ===""){
                    errorDisplay1(document.getElementById("expiry_date"), "*Please fill expiry date and CVV.*");
                    valid = false;
                }
                else if(CVV.trim()===""){
                    errorDisplay1(document.getElementById("expiry_date"), "*Please fill CVV.*");
                    valid = false;
                }
                else if(document.getElementById("expiry_date").value.trim() ===""){
                    errorDisplay1(document.getElementById("expiry_date"), "*Please fill expiry date.*");
                    valid = false;
                }
                else if(!limitExpiryDate(expiry_date)){
                    errorDisplay1(document.getElementById("expiry_date"), "*Invalid expiry date. Use MM/YY format.");
                    valid = false;
                }
                else{
                    clearError1(document.getElementById("expiry_date"));
                }

                
                if(redeem_points.trim()>maxPoints){
                    errorDisplay1(document.getElementById("redeem_points"), "*Exceeded points.*");
                }
                else{
                    clearError1(document.getElementById("redeem_points"));
                }

                if(selectlocation.value === ""){
                    errorDisplay1(document.getElementById("selectlocation"), "*Please select a location.");
                }
                else{
                    clearError1(document.getElementById("selectlocation"));
                }

                formatCardNumber(document.getElementById("card_number"));
                limitLength(document.getElementById("CVV"), 3);

                valid = true;

            }

            function formatCardNumber(input) {
                // Remove all non-digit characters
                let cardNumber = input.value.replace(/\D/g, '');

                // Add a space every 4 digits
                cardNumber = cardNumber.replace(/(.{4})/g, '$1 ').trim();

                // Set the formatted value back to the input field
                input.value = cardNumber;

                // Limit to 19 characters (16 digits + 3 spaces)
                if (input.value.length > 19) {
                    input.value = input.value.slice(0, 19);
                }
            }
            

            function limitLength(input, maxLength) {
                input.value = input.value.replace(/[^0-9\/]/g, '');
                if (input.value.length > maxLength) {
                    input.value = input.value.slice(0, maxLength);
                }
            }

            function limitExpiryDate(input) {
                const maxLength = 5;
                
                if (input.value.length > maxLength) {
                    input.value = input.value.slice(0, maxLength);
                }
                // Remove any non-numeric characters except for '/'
                input.value = input.value.replace(/[^0-9\/]/g, '');

                // Auto-format MM/YY
                if (input.value.length === 2 && !input.value.includes("/")) {
                    input.value = input.value + "/";
                }

                const parts = input.value.split('/');
                if (parts.length === 2) {
                    valid = false;
                    const month = parseInt(parts[0]);
                    const year = parseInt(parts[1]);
                    const currentDate = new Date();
                    const currentYear = currentDate.getFullYear() % 100; // Get last two digits of the current year
                    if (isNaN(month) || isNaN(year) || month < 1 || month > 12 || year < currentYear || year > currentYear + 20) {
                        
                        valid = false;
                    } else {
                        valid = true;
                    }
                    return valid;
                }

            }

            function errorDisplay(input, message) {
                const errorElement = input.nextElementSibling;
                errorElement.innerText = message;
                errorElement.classList.remove('hidden');
                input.classList.add('error-color');
            }
            function clearError(input) {
                const errorElement = input.nextElementSibling;
                errorElement.innerText = "";
                errorElement.classList.add('hidden');
                input.classList.remove('error-color');
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


            document.addEventListener("DOMContentLoaded", function() {
            const expandSpans = document.querySelectorAll('.expand');
            expandSpans.forEach(span => {
                span.addEventListener('click', function() {
                    const itemBottom = this.closest('.item_container').querySelector('.item_bottom');
                    itemBottom.classList.toggle('expand');

                    // Toggle the expand/collapse icon
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-angle-down');
                    icon.classList.toggle('fa-angle-up');

                    if (itemBottom.classList.contains('expand')) {
                        const containerHeight = itemBottom.scrollHeight;
                        itemBottom.style.maxHeight = containerHeight + 20 + 'px';
                    } else {
                        itemBottom.style.maxHeight = null; // Reset max-height when collapsing
                    }
                });
            });
        });

       document.addEventListener("DOMContentLoaded", function() {
            //other
            const updateTotal = (input) => {
                    const parent = input.parentElement.parentElement;
                    const price = parseFloat(parent.querySelector(".price").textContent.replace('RM ', ''));
                    const quantity = parseInt(input.value);
                    const total = price * quantity;
                    parent.querySelector(".price_sum").value = "RM " + total.toFixed(2);
                };

                const updateSubtotal = () => {
                    const priceSumElements = document.querySelectorAll('.price_sum');
                    let sub = document.getElementById('sub');
                    let subtotal = 0;
                    priceSumElements.forEach(priceSumElement => {
                        const priceSumText = priceSumElement.value.replace('RM ', '');
                        subtotal += parseFloat(priceSumText);
                    });
                    const subtotalDiv = document.querySelector('.subtotal');
                    subtotalDiv.textContent = "RM " + subtotal.toFixed(2);
                    sub.value = subtotal.toFixed(2);
                };
                
                //point
                const updatePoint = () => {
                    const redeem_points = document.getElementById("redeem_points").value;
                    let points_converted = document.querySelector('.points_converted');

                    // Remove '-RM ' from the value
                    const pointsValue = parseFloat(points_converted.textContent.replace('-RM ', ''));

                    if(redeem_points >= 0 && redeem_points <= <?php echo $point; ?>) {
                        // Calculate the new point value based on the redeem_points input
                        const point = 0.10 * redeem_points;
                        // Update the text content of points_converted element
                        points_converted.textContent = "-RM " + point.toFixed(2);
                    }
                }

                document.getElementById("redeem_points").addEventListener("input", updatePoint);

                updatePoint();

                const selects = document.querySelectorAll('.custom_select');
                selects.forEach(select => {
                    let previousSelectedValue = 0;
                    select.addEventListener('change', function() {
                        const parent = this.closest('.item_container');
                        const priceElement = parent.querySelector(".price");
                        const price = parseFloat(priceElement.textContent.replace('RM ', ''));
                        const selectedValue = parseFloat(this.value.match(/\((\d+)/)[1]);

                        const totalPrice = price - previousSelectedValue + selectedValue;
                        priceElement.textContent = "RM " + totalPrice.toFixed(2);
                        updateTotal(parent.querySelector(".quantity_input"));
                        previousSelectedValue = selectedValue;

                        updateSubtotal();
                    });

                    select.dispatchEvent(new Event('change'));
                });


                const quantityInputs = document.querySelectorAll(".quantity_input");

                $(document).ready(function() {
                    $('.minus').click(function() {
                        var $input = $(this).parent().find('input');
                        var count = parseInt($input.val()) - 1;
                        count = count < 1 ? 1 : count;
                        $input.val(count);
                        $input.change();

                        quantityInputs.forEach(input => {
                            input.addEventListener("change", function() {
                                updateTotal(this);
                            });
                            updateTotal(input);
                            updateSubtotal();
                        });

                        return false;
                    });
                    $('.plus').click(function() {
                        var $input = $(this).parent().find('input');
                        $input.val(parseInt($input.val()) + 1);
                        $input.change();

                        quantityInputs.forEach(input => {
                            input.addEventListener("change", function() {
                                updateTotal(this);
                            });
                            updateTotal(input);
                            updateSubtotal();
                        });

                        return false;
                    });
                });

                // Trigger the updateTotal function for each quantity input upon the initial loading
                quantityInputs.forEach(input => {
                    updateTotal(input);
                    updateSubtotal();
                });

                const updateTotalPrice = () => {
                    // Get the elements containing subtotal, points, discount, and total price
                    let subtotal = parseFloat(document.querySelector('.subtotal').textContent.replace('RM ', ''));
                    let points = parseFloat(document.querySelector('.points_converted').textContent.replace('-RM ', ''));
                    let discount = parseFloat(document.querySelector('.discounted').textContent.replace('-RM ', ''));
                    let totalPriceElement = document.querySelector('.price_total');
                    let earn_point = document.getElementById('earn_point');
                    let earnPointInput = document.getElementById('earn_point_input');
                    let totalprice = document.getElementById('totalprice');

                    // Calculate the total price
                    let totalPrice = subtotal - points - discount;

                    // Update the total price displayed on the page
                    totalPriceElement.textContent = "RM " + totalPrice.toFixed(2);
                    totalprice.value = totalPrice.toFixed(2);

                    if (totalPrice > 20 && totalPrice < 50) {
                        earn_point.innerHTML = "5"; // Set the integer value
                        earnPointInput.value= "5";
                    } else if(totalPrice >= 50 && totalPrice < 80){
                        earn_point.innerHTML = "10";
                        earnPointInput.value = "10";  
                    }else if(totalPrice > 80){
                        earn_point.innerHTML = "50"; 
                        earnPointInput.value = "50"; 
                    }else{
                        earn_point.innerHTML = "0";
                        earnPointInput.value = "0"; 
                    }
                    
                };

                updateTotalPrice();
                
                // document.getElementById("redeem_points").addEventListener("input", updateTotalPrice);
                // document.querySelector('.subtotal').addEventListener("DOMSubtreeModified", updateTotalPrice);
                // document.querySelector('.discounted').addEventListener("DOMSubtreeModified", updateTotalPrice);
                // document.querySelector('.points_converted').addEventListener("DOMSubtreeModified", updateTotalPrice);
                
                //another way to update the total except DOMSubtreeModified
                const observer = new MutationObserver(updateTotalPrice);

                // Define the options for the observer
                const observerOptions = {
                    subtree: true, // Observe changes to descendant elements
                    characterData: true, // Observe changes to the text content of the elements
                    childList: true // Observe changes to the child nodes of the elements
                };

                // Observe changes to the subtotal, points, and voucher discount elements
                observer.observe(document.querySelector('.subtotal'), observerOptions);
                observer.observe(document.querySelector('.discounted'), observerOptions);
                observer.observe(document.querySelector('.points_converted'), observerOptions);
                
                //next
            const trashButtons = document.querySelectorAll('.item_container .remove_item');

            trashButtons.forEach(trashButton => {
                trashButton.addEventListener('click', function() {
                    const itemContainer = this.closest('.item_container');
                    if (itemContainer) {
                        const rowIDInput = itemContainer.querySelector('input[name="row_ID"]');
                        const verifyItemInput = document.getElementById('verify_item');
                        
                        // Get the value of row_ID input
                        const rowIDValue = rowIDInput.value.trim();

                        if (rowIDValue) {
                            // Remove trailing comma if it exists
                            const verifyItemValue = verifyItemInput.value.trim();
                            verifyItemInput.value = verifyItemValue ? verifyItemValue.replace(/,$/, '') : '';

                            // Append row ID value to verify_item input
                            if (verifyItemInput.value) {
                                verifyItemInput.value += ',' + rowIDValue;
                            } else {
                                verifyItemInput.value = rowIDValue;
                            }
                            
                            // Remove the item container
                            itemContainer.remove();

                            // After removing an item container, update the count
                            updateCartItemCount();
                            updateSubtotal();
                        } else {
                            console.error('No value found for row_ID input.');
                        }
                    } else {
                        console.error('No .item_container found for the clicked trash button.');
                    }
                });
            });

            // Function to update the count of items in the cart
           function updateCartItemCount() {
                // Select all elements with the class .item_container
                const itemContainers = document.querySelectorAll('.item_container');

                // Get the number of total existing .item_container elements
                const totalItemContainers = itemContainers.length;

                // Display the total number of items in the cart
                const cartNoElement = document.getElementById('cart_no');
                cartNoElement.textContent = totalItemContainers + " Items";

                // Update the notification circle count
                const notificationCircles = document.querySelectorAll('.notification_circle');
                notificationCircles.forEach(circle => {
                    circle.textContent = totalItemContainers.toString(); // Convert to string before setting textContent
                });
            }

            // Call the function to update the count initially
            updateCartItemCount();

            });

            window.addEventListener('beforeunload', function(event) {
                // Submit the form here
                // For example:
                const form = document.getElementById('cartForm');
                form.submit();
            });

            // function toggleActive(element) {
            //     // Remove active class from all divs inside payment_method
            //     var paymentMethods = document.querySelectorAll('.payment_method div');
            //     paymentMethods.forEach(function(item) {
            //         item.classList.remove('active');
            //     });

            //     // Add active class to the clicked div
            //     element.classList.add('active');

            //     // Remove active class from all payment_details containers
            //     var paymentDetails = document.querySelectorAll('.payment_details');
            //     paymentDetails.forEach(function(item) {
            //         item.classList.remove('active');
            //     });

            //     // Get the corresponding payment details container and add active class
            //     var paymentMethod = element.classList.contains('credit_card') ? 'credit_card' : 'paypal';
            //     var correspondingPaymentDetails = document.querySelector('.payment_details.' + paymentMethod);
            //     correspondingPaymentDetails.classList.add('active');

            //     // Remove 'required' attribute from all inputs
            //     var allInputs = document.querySelectorAll('.payment_details input');
            //     allInputs.forEach(function(input) {
            //         input.removeAttribute('required');
            //     });

            //     // Add 'required' attribute to inputs in the active payment details container
            //     var activeInputs = correspondingPaymentDetails.querySelectorAll('input');
            //     activeInputs.forEach(function(input) {
            //         input.setAttribute('required', true);
            //     });
            // }

            function toggleInputVisibility(selectElement) {
                var inputField = document.querySelector('.location_edit .new_location');
                var selectedValue = selectElement.value;
                var locationEditDiv = document.querySelector('.location_edit');
                if (selectedValue === 'different') {
                    locationEditDiv.classList.add('appear');
                    inputField.setAttribute("required", "true");
                } else {
                    locationEditDiv.classList.remove('appear');
                    inputField.removeAttribute("required");
                }
            }

            document.addEventListener("DOMContentLoaded", function() {
                const checkoutButton = document.querySelector('.checkout_button');
                const cancelButon = document.querySelector('.cancel_button');
                const sideContainer = document.querySelector('.side_container');

                checkoutButton.addEventListener('click', function() {
                    // Add the class 'reveal' to the side container
                    sideContainer.classList.add('reveal');
                    
                    // Add class to body to prevent scrolling
                    document.body.classList.add('no-scroll');
                    
                    // Scroll to the top
                    window.scrollTo(0, 0);
                });

                cancelButon.addEventListener('click', function() {
                    // Remove the class 'reveal' from the side container
                    sideContainer.classList.remove('reveal');
                    
                    // Remove class from body to enable scrolling
                    document.body.classList.remove('no-scroll');
                });

                document.getElementById('update_cart').addEventListener('click', function() {
                    var cartForm = document.getElementById('cartForm');
                    var paymentDetailsDivs = document.querySelectorAll('.payment_details input[required]');
                    var deliveryAddressSelect = document.querySelector('select[required]');
                    var newLocationInput = document.querySelector('.new_location[required]');
                    
                    paymentDetailsDivs.forEach(function(input) {
                        input.removeAttribute('required');
                    });
                    deliveryAddressSelect.removeAttribute('required');
                    if (newLocationInput) {
                        newLocationInput.removeAttribute('required');
                    }
                });
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Get the current time in Malaysia
                var now = moment.tz("Asia/Kuala_Lumpur");

                // Define the operating hours
                var openingTime = moment.tz("Asia/Kuala_Lumpur").set({ hour: 8, minute: 30 });
                var closingTime = moment.tz("Asia/Kuala_Lumpur").set({ hour: 21, minute: 0 });

                // Check if the current time is outside the operating hours
                if (now.isBefore(openingTime) || now.isAfter(closingTime)) {
                    // Show the message to the user
                    document.getElementById("noteforuser").style.display = "block";
                    // Disable the submit button
                    document.getElementById("submit_order").disabled = true;
                }
            });
        </script>
        <div id="noteforuser" style="padding: 15px;display:none;background-color: #5a9498;font-size: 17px;color: #fff;letter-spacing: 0.8px;font-weight: 300;text-align: center;"><strong>
            <i class="fas fa-exclamation" style="margin-right:3px;"></i> Our operating hours are from <u>8:30 AM to 9:00 PM</u>. Please place your orders within this timeframe. Thank you! &#128522;</strong></div>
        <div class="cart">
            <div class="container-fluid container">
                <div class="col-12 m-auto">
                    <div class="breadcrumbs">
                        <a href="index.php">Home</a> > <a class="active">Cart</a>
                    </div>
                    <div id="id01" class="modal">
                    <form class="profile-edit-content animate" action="" method="post">
                        <div class="xcontainer">
                            <span class="txt">Note <i class="fas fa-comment-exclamation"></i></span>
                            <span onclick="document.getElementById('id01').style.display='none'" class="closeedit" title="Close Modal">&times;</span>
                          </div>
                      <div class="pcontainer">
                        <p style="margin-bottom:unset;margin: 5px 10px 0;">You haven't fill your <b style="color:#e2857b;">phone number</b></p>
                        <p style="margin-top:5px;margin: 5px 10px 0;text-align: justify;">Please fill your phone number as it is <b style="color:#e2857b;">important</b> to contact your whenever there is a problem regarding order.</p>
                  
                        <label for="pn"><b><i class="fas fa-phone-alt"></i> Phone Number</b></label>
                        <div style="display:flex;"><span style="text-align: center;border: 1px solid #ccc;padding: 5px; margin-left: 8px;">+60</span><input type="tel" id="pn" name="pn" required></div>
                        <span class="address-error"></span>
                          
                        <button type="submit" id="updateprofile" name="update-profile" class="edit-profile-btn" style="outline: none;">Done</button>
                        
                      </div>
                  
                      <div class="pcontainer" style="background-color:#f3f3f3; height: 80px;">
                        <button type="button" class="edit-profile-btn cancelbtn" style="outline: none;" onclick="document.getElementById('id01').style.display='none'">Cancel</button>
                      </div>
                    </form>
                  </div>
                    <form id="cartForm" method="post">
                        <div class="row d-flex justify-content-between pb-1">
                            <div class="col-12 col-lg-8">
                                <div class="cart_header">
                                    <span class="p-0 d-flex justify-content-start align-items-center"><i class="far fa-shopping-cart pr-2"></i>Shopping Cart</span>
                                    <?php
                                    $sql_get_cart = "SELECT cust_cart FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
                                    $result_get_cart = $conn->query($sql_get_cart);
                                    if ($result_get_cart->num_rows > 0) {
                                        while ($row_get_cart = $result_get_cart->fetch_assoc()) {
                                            $items[] = "";
                                            $items = explode("},{", $row_get_cart['cust_cart']);
                                            $items = array_filter($items, 'strlen');

                                            echo '<div class="p-0 d-flex justify-content-end"><input type="submit" name="update_cart" id="update_cart" title="Click to save changes" value="Save Changes" style="font-weight:800;"></div>
                                            </div>
                                            <span style="
                                            color: #6c757d;
                                            font-size: 14px;
                                            margin-left: 5px;
                                        ">* All items are included SST tax.</span>
                                            <hr><div class="cart_container">';
                                            
                                            $j = 0;

                                            echo '<input type="hidden" value="" name="verify_item" id="verify_item"/>';

                                            if (count($items) != 0){
                                                foreach ($items as $item) {
                                                    $item = trim($item, "{}");
                                                    $details = explode(",", $item);

                                                    $item_ID = trim($details[0], "()");
                                                    $item_qty = trim($details[1], "()");
                                                    $item_request = trim($details[2], "()");

                                                    $options_str = implode(",", array_slice($details, 3));
                                                    preg_match_all("/\[(\d+),([^]]+)]/", $options_str, $matches);
                                                    $option_IDs = $matches[1];
                                                    $option_values = $matches[2];

                                                    $sql = "SELECT * FROM menu_items WHERE item_ID = '$item_ID' AND trash = 0";

                                                    $result = $conn->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            $sql2 = "SELECT * FROM menu_images WHERE image_ID = {$row['item_ID']}  AND trash = 0 LIMIT 1";
                                                            $result2 = $conn->query($sql2);
                                                            while ($row2 = $result2->fetch_assoc()) {
                                                                $image_data = $row2["data"];
                                                                $mime_type = $row2["mime_type"];
                                                                $base64 = base64_encode($image_data);
                                                                $src = "data:$mime_type;base64,$base64";
                                                            }
                                                            echo '
                                                            <div class="item_container fade_in" style="-index: 12;">
                                                                <div class="item_top">
                                                                    <div class=" col-sm-7 col-10 d-flex flex-row p-0">
                                                                        <img src='.$src.' class="item_image">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="details">
                                                                                <input type="hidden" value="'.$j.'" name="row_ID" id="row_ID"/>
                                                                                <input type="hidden" value="'.$row['item_ID'].'" name="item_ID[]" id="item_ID"/>
                                                                                <input type="hidden" value="'.$row['item_name'].'" name="item_name[]" id="item_name"/>
                                                                                <input type="hidden" value="'.$row['item_price'].'" name="item_price[]" id="item_price"/>
                                                                                <span class="item_name">'.$row['item_name'].'</span>
                                                                                <div class="d-flex align-items-end">';
                                                                                if ($row['item_discount'] > 0) {
                                                                                    $discounted_price = $row['item_price'] * ((100 - $row['item_discount']) * 0.01);

                                                                                    $price_sum = $discounted_price;
                                                                                    echo '<div class="price" id="price">RM ' . number_format($price_sum, 2) . '</div>';
                                                                                }
                                                                                else{
                                                                                    $price_sum = $row['item_price'];
                                                                                    echo '<div class="price" id="price">RM '.$price_sum.'</div>';
                                                                                }
                                                                                echo '</div>
                                                                                    <div class="customization">
                                                                                        <span class="expand">
                                                                                            Expand details<i class="fas fa-angle-down"></i>
                                                                                        </span>
                                                                                    </div> 
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="number">
                                                                            <span class="minus">-</span>
                                                                            <input type="text" value="'.$item_qty.'" name="quantity_input[]" class="quantity_input"/>
                                                                            <span class="plus">+</span>
                                                                        </div>
                                                                        <input class="price_sum" name="price_sum[]" value="">
                                                                        <div class="icons col-1">
                                                                            <div class="remove_item" title="Remove item"><i class="far fa-times"></i></div>
                                                                        </div>
                                                                    </div>';
                                                                    echo '<div class="item_bottom">';
                                                                    foreach ($option_IDs as $option_ID) {
                                                                        $chosen_options = "SELECT * FROM menu_customization WHERE custom_ID = '$option_ID' AND trash = 0";

                                                                        $result_options = $conn->query($chosen_options);
                                                                        if ($result_options->num_rows > 0) {
                                                                            while ($row_options = $result_options->fetch_assoc()) {
                                                                                echo '
                                                                                    <div class="customization">
                                                                                        <span class="custom_title">'.$row_options['custom_name'].'</span>
                                                                                        <select value="" class="custom_select" name="item_option_selected[]" id="item_option_selected">
                                                                            <option disabled>Select...</option>';
                                                                                $custom_options_string = $row_options['custom_options'];
                                                                                preg_match_all('/\("([^"]+)",([\d.]+)\)/', $custom_options_string, $matches, PREG_SET_ORDER);
                                                                                foreach ($matches as $match) {
                                                                                    $option_choice = $match[1];
                                                                                    $option_price = $match[2];

                                                                                    $foundMatch = false;
                                                                                    foreach ($option_values as $option_value) {
                                                                                        if ($option_value == $option_choice) {
                                                                                            $foundMatch = true;
                                                                                            break;
                                                                                        }
                                                                                    }
                                                                                    echo "<option value='"."(" .$option_price . "," .$option_choice . "," .$row_options['custom_ID'] . ",".$j.")"."' id='" .$option_price . "'" . ($foundMatch ? ' selected' : '') . "> " . $option_choice . " (+RM " . $option_price . ")</option>";
                                                                                }
                                                                            }echo '</select></div>';
                                                                        }
                                                                    }
                                                                        echo '<div class="customization"><span class="custom_title">Extra Requests</span><textarea name="item_request[]">'.$item_request.'</textarea></div>
                                                                        </div>';
                                                                    echo ' 
                                                                </div>';
                                                            }
                                                        }$j++; 
                                                    }
                                                }echo '<div class="no_items"><i class="far fa-ghost"></i>Your cart is empty...</div>';
                                            }
                                        }
                                    ?>
                                </div>
                                <hr>
                                <div class="pb-lg-5 pb-0" style="z-index: 13; position: relative; background-color: white;"></div>
                                <div class="checkout_button"> Check Out</div>
                            </div>
                            <?php
                                $sql_payment = "SELECT * FROM customer WHERE cust_ID = $cust_ID  AND trash = 0 LIMIT 1";
                                $result_payment = $conn->query($sql_payment);

                                if ($result_payment->num_rows > 0) {
                                    while ($row_payment = $result_payment->fetch_assoc()) {
                                        echo '<div class="col-0 col-lg-4 pb-lg-5 pb-0 mx-auto cart_side">
                                            <div class="side_container">
                                                <div class="payment_container">
                                                    <div class="payment_header">
                                                        <i class="far fa-money-check-edit"></i><span>Payment & Address</span>
                                                    </div>
                                                    <hr>
                                                    <div class="payment_method">
                                                        <div class=" credit_card active"><i class="fas fa-credit-card"></i>Credit / Debit Card</div>
                                                    </div>
                                                    <div class="payment_details credit_card active">
                                                        <span>Card Holder</span>
                                                        <input type="text" name="holder_name" id="holder_name" placeholder="Name Surname" onkeypress="return event.keyCode != 13;" required>
                                                        <div class="address-error" style="justify-content: left;font-size: 12px;color: #dc3545;background-color: #f8f9fa;padding: 0px 4px;border-radius: 3px;margin-bottom: 2px;"></div>
                                                        <span>Card Number</span>
                                                        <input type="text" name="card_number" id="card_number" placeholder="1111 1111 1111 1111" maxlength="19" oninput="formatCardNumber(this)" onkeypress="return event.keyCode != 13;" required>
                                                        <div class="address-error" style="justify-content: left;font-size: 12px;color: #dc3545;background-color: #f8f9fa;padding: 0px 4px;border-radius: 3px;margin-bottom: 2px;"></div>
                                                        <div>
                                                            <span>Expiry Date</span><span>CVV</span>
                                                        </div>
                                                        <div>
                                                            <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YY" maxlength="5" oninput="limitExpiryDate(this)" onkeypress="return event.keyCode != 13;" required>
                                                            <input type="text" name="CVV" id="CVV" placeholder="123" maxlength="3" oninput="limitLength(this, 3)" onkeypress="return event.keyCode != 13;" required>
                                                        </div>
                                                        <div class="address-error" style="justify-content: left;font-size: 12px;color: #dc3545;background-color: #f8f9fa;padding: 0px 4px;border-radius: 3px;margin-bottom: 2px;"></div>
                                                    </div>
                                                    
                                                    <hr>
                                                    <div class="payment_location">
                                                        <div class="location_select">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                            <select id="selectlocation" onchange="toggleInputVisibility(this)" name="address" required>
                                                                <option value="" selected disabled>Select delivery address...</option>';
                                                                    $addresses_str = trim($row_payment['cust_address'], "{}");
                                                                    $addresses = explode("},{", $addresses_str);
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
                                        
                                                                        $addressall = $address_label." ~ ".$address_no.", ".$address_building.", ".$address_street.", ".$address_postcode.", ".$address_state.", Malaysia";
                                                                        $addressnolabel = $address_no.", ".$address_building.", ".$address_street.", ".$address_postcode.", ".$address_state.", Malaysia";
                                                                        
                                                                        echo '<option value="'.htmlspecialchars($addressnolabel).'">'. htmlspecialchars($addressall) . '</option>';
                                                                        
                                                                    }
                                                                echo '<option value="different">Choose a different address...</option>
                                                            </select>
                                                        </div>
                                                        <div class="address-error" style="margin-top:3px;font-size: 12px;color: #dc3545;background-color: #f8f9fa;padding: 0px 4px;border-radius: 3px;margin-bottom: 2px;"></div>
                                                        <div class="location_edit">
                                                            <i class="fas fa-pencil"></i>
                                                            <input type="text" id="newlocation" name="new_location" class="new_location" placeholder="New delivery address..." onkeypress="return event.keyCode != 13;">
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="payment_coupon">
                                                        <div class="coupon_title">
                                                            <div>
                                                                <i class="fas fa-ticket-alt"></i><span>Redeem Voucher</span>
                                                            </div>
                                                        </div>
                                                        <input type="text" placeholder="ABC12" name="payment_coupon" id="payment_coupon" onkeypress="return event.keyCode != 13;">
                                                    </div>
                                                    <hr>
                                                    <div class="payment_points">
                                                        <div class="points_display">
                                                            <div>
                                                                <i class="fas fa-usd-circle"></i><span>Redeem Points</span>
                                                            </div>
                                                            <input type="number" name="redeem_points" id="redeem_points" placeholder="" min="0" max="'.$row_payment['cust_points'].'" value="0" onkeypress="return event.keyCode != 13;">
                                                        </div>
                                                        <div class="address-error" style="margin-top:3px;font-size: 12px;color: #dc3545;background-color: #f8f9fa;padding: 0px 4px;border-radius: 3px;margin-bottom: 2px;"></div>
                                                            <div class="description">Conversion rate is RM 0.10 per every 1 point. You currently have '.$row_payment['cust_points'].' points.</div>                                                    </div>
                                                </div> 
                                                <div class="order_summary_container">
                                                    <div class="justify-content-between d-flex align-items-center">
                                                        <div class="order_summary_header">
                                                            <i class="far fa-clipboard-list-check"></i><span>Order Summary</span>
                                                        </div>
                                                        <div id="cart_no"></div>
                                                    </div>
                                                    <hr>
                                                    <div class="order_summary_details">
                                                        <div class="d-flex justify-content-between">
                                                            <span>Subtotal</span>
                                                            <div class="subtotal">RM 0.00</div>
                                                            <input type="hidden" id="sub" name="sub">
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <span>Discount</span>
                                                            <div id="vou" class="discounted">-RM 0.00</div>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <span>Points</span>
                                                            <div id="pts" class="points_converted">-RM 0.00</div>
                                                        </div>
                                                        <div class="btm-pt">
                                                        <div class="description" style="margin-top: 5px;font-size: 14px;line-height: 1.1;color: #244f53;">After payment, you can earn <span id="earn_point" style="font-size: 14px;line-height: 1.1;color: #244f53;"></span> point(s).
                                                            <input type="hidden" id="earn_point_input" name="earn_point_value">
                                                        </div>
                                                            <div id="tooltip">
                                                                <i class="far fa-question-circle"></i>
                                                                <span class="tooltip-content">You can earn:<br>~ 5 pts every up to RM 20 <br>~ 10 pts every up to RM 50<br>~ 50 pts every up to RM 80<br>purchase.</span>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="d-flex justify-content-between align-items-end">
                                                            <span class="grand_total">Grand Total</span>
                                                            <div class="price_total">RM 0.00</div>
                                                            <input type="hidden" id="totalprice" name="totalprice">
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="submit" id="submit_order" name="submit_order" class="submit_order">
                                                <div class="cancel_button">Cancel</div> 
                                            </div>
                                        </div>';
                                    }
                                }
                             ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
            include 'gototopbtn.php';
            include 'sidebar.php';
            include 'footer.php';
        ?>
    </body>
</html>
