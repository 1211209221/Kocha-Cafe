<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Cart | Kocha Café</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
        <link rel="icon" href="images/logo/logo_icon.png">
        <script src="script.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="gototop.js"></script>
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

                    $order_date = date('Y-m-d');
                    // Construct the SQL query to insert menu item data
                    $sql_cart = "INSERT INTO customer_orders (order_contents,order_date,cust_ID) VALUES ('$string','$order_date','$cust_ID')";

                    if(!empty($string)){
                        if ($conn->query($sql_cart) === TRUE) {
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
                }
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
                                    <i class="fas fa-check-circle"></i>Failed to update cart. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                echo '<div class="error_message">' . $_SESSION['addReview_error'] . '</div>';

                unset($_SESSION['addReview_error']);
            }

            if (isset($_SESSION['submitOrder_success']) && $_SESSION['submitOrder_success'] === true) {
                echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Order successfully submitted!
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';

                unset($_SESSION['submitOrder_success']);
            }

            if (isset($_SESSION['submitOrder_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Failed to submit order. Please try again...
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
                                    <i class="fas fa-check-circle"></i>You have no items in your cart! Please try again...
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
            document.addEventListener("DOMContentLoaded", function() {
                const updateTotal = (input) => {
                    const parent = input.parentElement.parentElement;
                    const price = parseFloat(parent.querySelector(".price").textContent.replace('RM ', ''));
                    const quantity = parseInt(input.value);
                    const total = price * quantity;
                    parent.querySelector(".price_sum").value = "RM " + total.toFixed(2);
                };

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

                const updateSubtotal = () => {
                    const priceSumElements = document.querySelectorAll('.price_sum');
                    let subtotal = 0;
                    priceSumElements.forEach(priceSumElement => {
                        const priceSumText = priceSumElement.value.replace('RM ', '');
                        subtotal += parseFloat(priceSumText);
                    });
                    const subtotalDiv = document.querySelector('.subtotal');
                    subtotalDiv.textContent = "RM " + subtotal.toFixed(2);
                };
                

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
            });


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

            function toggleActive(element) {
                // Remove active class from all divs inside payment_method
                var paymentMethods = document.querySelectorAll('.payment_method div');
                paymentMethods.forEach(function(item) {
                    item.classList.remove('active');
                });

                // Add active class to the clicked div
                element.classList.add('active');

                // Remove active class from all payment_details containers
                var paymentDetails = document.querySelectorAll('.payment_details');
                paymentDetails.forEach(function(item) {
                    item.classList.remove('active');
                });

                // Get the corresponding payment details container and add active class
                var paymentMethod = element.classList.contains('credit_card') ? 'credit_card' : 'paypal';
                var correspondingPaymentDetails = document.querySelector('.payment_details.' + paymentMethod);
                correspondingPaymentDetails.classList.add('active');

                // Remove 'required' attribute from all inputs
                var allInputs = document.querySelectorAll('.payment_details input');
                allInputs.forEach(function(input) {
                    input.removeAttribute('required');
                });

                // Add 'required' attribute to inputs in the active payment details container
                var activeInputs = correspondingPaymentDetails.querySelectorAll('input');
                activeInputs.forEach(function(input) {
                    input.setAttribute('required', true);
                });
            }

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
        <div class="cart">
            <div class="container-fluid container">
                <div class="col-12 m-auto">
                    <div class="breadcrumbs">
                        <a href="index.php">Home</a> > <a class="active">Cart</a>
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

                                            echo '<div class="p-0 d-flex justify-content-end"><input type="submit" name="update_cart" id="update_cart" value="Save Changes"></div>
                                            </div>
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
                                                                            <input type="text" value="'.$item_qty.'" name="quantity_input[]" id="quantity_input" class="quantity_input"/>
                                                                            <span class="plus">+</span>
                                                                        </div>
                                                                        <input id="price_sum col-2" class="price_sum" name="price_sum[]" value="">
                                                                        <div class="icons col-1">
                                                                            <div class="remove_item"><i class="far fa-times"></i></div>
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
                                                                                }
                                                                            }echo '</select>
                                                                          </div>';
                                                                        }
                                                                        echo '<div class="customization"><span class="custom_title">Extra Requests</span><textarea name="item_request[]" id="item_request">'.$item_request.'</textarea></div>
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
                                <div class=" pb-xl-4 pb-lg-5 pb-0" style="z-index: 13; position: relative; background-color: white;"></div>
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
                                                        <div class=" credit_card active" onclick="toggleActive(this)"><i class="fas fa-credit-card"></i>Credit Card</div>
                                                        <div class=" paypal" onclick="toggleActive(this)"><i class="fab fa-paypal"></i>Paypal</div>
                                                    </div>
                                                    <div class="payment_details credit_card active">
                                                        <span>Card Holder</span>
                                                        <input type="text" name="holder_name" id="holder_name" placeholder="Name Surname" onkeypress="return event.keyCode != 13;" required>
                                                        <span>Card Number</span>
                                                        <input type="text" name="card_number" id="card_number" placeholder="1111 1111 1111 1111" onkeypress="return event.keyCode != 13;" required>
                                                        <div>
                                                            <span>Expiry Date</span><span>CVV</span>
                                                        </div>
                                                        <div>
                                                            <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YY" onkeypress="return event.keyCode != 13;" required>
                                                            <input type="text" name="CVV" id="CVV" placeholder="123" onkeypress="return event.keyCode != 13;" required>
                                                        </div>
                                                    </div>
                                                    <div class="payment_details paypal">
                                                        <span>Email</span>
                                                        <input type="text" name="paypal_email" id="paypal_email" placeholder="Email" onkeypress="return event.keyCode != 13;">
                                                        <span>Password</span>
                                                        <input type="text" name="paypal_password" id="paypal_password" placeholder="Password" onkeypress="return event.keyCode != 13;">
                                                    </div>
                                                    <hr>
                                                    <div class="payment_location">
                                                        <div class="location_select">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                            <select onchange="toggleInputVisibility(this)" required>
                                                                <option value="" selected disabled>Select delivery address...</option>';
                                                                    $addresses_str = trim($row_payment['cust_address'], "{}");
                                                                    $addresses_array = explode("},{", $addresses_str);

                                                                    foreach ($addresses_array as $address) {
                                                                        echo "<option>". $address . "</option>";
                                                                    }
                                                                echo '<option value="different">Choose a different address...</option>
                                                            </select>
                                                        </div>
                                                        <div class="location_edit">
                                                            <i class="fas fa-pencil"></i>
                                                            <input type="text" class="new_location" placeholder="New delivery address..." onkeypress="return event.keyCode != 13;">
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
                                                            <input type="number" name="redeem_points" id="redeem_points" placeholder="" min="0" max="'.$row_payment['cust_points'].'" step="0.10" value="0.00" onkeypress="return event.keyCode != 13;">
                                                        </div>
                                                        <div class="description">Conversion rate is RM 1.00 per every 25 points. You currently have '.$row_payment['cust_points'].' points.</div>
                                                    </div>
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
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <span>Discount</span>
                                                            <div class="discounted">-RM 2.00</div>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <span>Points</span>
                                                            <div class="points_converted">-RM 4.00</div>
                                                        </div>
                                                        <hr>
                                                        <div class="d-flex justify-content-between align-items-end">
                                                            <span class="grand_total">Grand Total</span>
                                                            <div class="price_total">RM 64.00</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="submit" name="submit_order" id="submit_order" class="submit_order">
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
