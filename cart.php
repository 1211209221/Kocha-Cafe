<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Cart | Kocha Café</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
        <link rel="icon" href="images/logo/logo_icon.png">
        <script src="script.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="gototop.js"></script>
    </head>
    <body>
        <?php
            include 'connect.php';
            include 'top.php';
            include 'sidebar.php';
            include 'gototopbtn.php';

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
                    parent.querySelector(".price_sum").textContent = "RM " + total.toFixed(2);
                };

                const selects = document.querySelectorAll('.custom_select');
                selects.forEach(select => {
                    let previousSelectedValue = 0; // Initialize the previous selected value for each select input
                    select.addEventListener('change', function() {
                        const parent = this.closest('.item_container');
                        const priceElement = parent.querySelector(".price");
                        const price = parseFloat(priceElement.textContent.replace('RM ', ''));
                        const selectedValue = parseFloat(this.value);

                        const totalPrice = price - previousSelectedValue + selectedValue;
                        priceElement.textContent = "RM " + totalPrice.toFixed(2);
                        updateTotal(parent.querySelector(".quantity_input"));
                        previousSelectedValue = selectedValue;
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
                        });

                        return false;
                    });
                });

                // Trigger the updateTotal function for each quantity input upon the initial loading
                quantityInputs.forEach(input => {
                    updateTotal(input);
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

        </script>
        <div class="cart">
            <div class="container-fluid container">
                <div class="col-12 m-auto">
                    <div class="breadcrumbs">
                        <a href="index.php">Home</a> > <a class="active">Cart</a>
                    </div>
                    <form id="cartForm" action="cart.php" method="post">
                        <div class="row d-flex justify-content-between pb-1">
                            <div class="col-12 col-lg-9">
                                <div class="cart_container">
                                    <div class="cart_header">
                                        <span class="p-0 d-flex justify-content-start">Shopping Cart</span>
                                    <?php
                                    $sql_get_cart = "SELECT cust_cart FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
                                    $result_get_cart = $conn->query($sql_get_cart);
                                    if ($result_get_cart->num_rows > 0) {
                                        while ($row_get_cart = $result_get_cart->fetch_assoc()) {
                                            $items[] = "";
                                            $items = explode("},{", $row_get_cart['cust_cart']);
                                            $items = array_filter($items, 'strlen');

                                            echo '<div class="p-0 d-flex justify-content-end">'.count($items).' Items in your cart</div>
                                            </div>
                                            <hr>';
                                            
                                            $j = 0;

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
                                                            $price_sum = 0;
                                                            echo '
                                                            <div class="item_container">
                                                                <div class="item_top">
                                                                    <div class=" col-7 d-flex flex-row p-0">
                                                                        <img src='.$src.' class="item_image">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="details">
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
                                                                                echo '</div>';
                                                                                    if(!empty($option_IDs[$j])){
                                                                                        echo '<div class="customization">
                                                                                            <span class="expand">
                                                                                                Expand details<i class="fas fa-angle-down"></i>
                                                                                            </span>
                                                                                        </div>';
                                                                                    }
                                                                                    echo ' 
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="number">
                                                                            <span class="minus">-</span>
                                                                            <input type="text" value="'.$item_qty.'" id="quantity_input" class="quantity_input"/>
                                                                            <span class="plus">+</span>
                                                                        </div>
                                                                        <div id="price_sum" class="price_sum col-2">RM '.number_format(number_format($price_sum, 2)*$item_qty, 2).'</div>
                                                                        <div class="icons col-1"><i class="far fa-times"></i></div>
                                                                    </div>';
                                                                        if(!empty($option_IDs[$j])){
                                                                            echo '<div class="item_bottom">';
                                                                            foreach ($option_IDs as $option_ID) {
                                                                                $chosen_options = "SELECT * FROM menu_customization WHERE custom_ID = '$option_ID' AND trash = 0";

                                                                                $result_options = $conn->query($chosen_options);
                                                                                if ($result_options->num_rows > 0) {
                                                                                    while ($row_options = $result_options->fetch_assoc()) {
                                                                                        echo '<div class="customization"><span class="custom_title">'.$row_options['custom_name'].'</span><select value="" class="custom_select">
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

                                                                                            echo "<option value='$option_price'" . ($foundMatch ? ' selected' : '') . "> $option_choice (+RM $option_price)</option>";
                                                                                        }
                                                                                    }
                                                                                }echo '</select></div>';
                                                                            }
                                                                            echo '<div class="customization"><span class="custom_title">Extra Requests</span><textarea>'.$item_request.'</textarea></div>
                                                                            </div>';
                                                                        }
                                                                        echo ' 
                                                                </div>
                                                                <hr>';
                                                            }
                                                        }
                                                    }

                                                    $j++;
                                                }else{
                                                    echo '<div class="no_items"><i class="far fa-ghost"></i>No items in your cart.</div>';
                                                }
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="col-0 col-lg-3 mx-auto">
                            s
                              
                            </div>
                        </div>
                        <input type="submit">
                    </form>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </body>
</html>
