<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Order | Admin Panel</title>
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

        session_start();

        if (isset($_GET['ID'])) {
            // Retrieve the value of the ID parameter
            $order_ID = $_GET['ID'];
            $id = "K_".$order_ID;
        }
            
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['edit_submit'])){
                // Retrieve form data
                $readstatus = $_POST['readstatus'];
                $sql = "UPDATE contact_message SET markasread = '$readstatus' WHERE CF_ID = $CF_ID";
                

                if ($conn->query($sql) === TRUE) {
                    $_SESSION['editmsg_success'] = true;
                    header("Location: messages-all.php");
                    exit();
                } else {
                    $_SESSION['editmsg_error'] = "Error: " . $sql . "<br>" . $conn->error;
                    header("Location: messages-all.php");
                    exit();
                }
            }
            if (isset($_POST['delete'])){
                $trash = $_POST['delete'];
                $sql = "UPDATE contact_message SET trash = 1, markasread = 1 WHERE CF_ID = $CF_ID";

                if ($conn->query($sql) === TRUE) {
                    $_SESSION['delmsg_success'] = true;
                    header("Location: messages-all.php");
                    exit();
                } else {
                    $_SESSION['delmsg_error'] = "Error: " . $sql . "<br>" . $conn->error;
                    header("Location: messages-all.php");
                    exit();
                }
            }
        }

    ?>
    
    <style>
        .item-detail{
            width: 46%;
            display: flex;
            background-color: #fff;
            border-radius: 10px;
            margin:10px;
        }
        .item_image{
            width: 50%;
            max-height: 80px;
            max-width: 80px;
            margin: 10px;
        }
        .requirement{
            margin: 10px;
            border-radius: 10px;
            width: 100%;
            padding-bottom: 40px;
        }
        .requirement span{
            color: #8f8f8f;
            font-size: 16px;
            padding-left: 15px;
        }
        .replybtn{
            width: 50%;
            margin: 0 6px 0px 6px;
            background: #5a9498;
            color: #fff;
            font-weight: 600;
            border: #e9ecef 1px solid;
            border-radius: 7px;
            font-size: 17px;
            padding: 3px 5px;
            display: inline-block;
            text-align: center;
            text-decoration: none;
        }
        .replybtn i{
            margin-left:8px;
            font-size:16px;
        }
        .replybtn:hover{
            text-decoration:none;
            background-color:;
            color:white;
            background-color: #36676A;
            transform: scale(1.1);
            transition:0.15s;
        }
        .file-box a{
            color: #5a9498;
            font-weight: 600;
        }
        .file-box a:active{
            color: #000;
        }
        .requirement p{
            margin:unset;
        }
        .done_btn{
            display: block;
            font-size: 15px;
            font-weight: 600;
            color: white;
            background-color: #5a9498;
            border: none;
            outline: none;
            padding: 3px 5px;
            border-radius: 7px;
            width: 100%;
            margin-top: 5px;
            position: absolute;
            bottom: 0px;
            text-align:center;
            cursor:pointer;
        }
        .done_btn:hover{
            outline:none;
            background-color:#36676A;
            transform: scale(1.05);
            transition:0.15s;
        }
        @media (max-width: 770px) {
            .item-detail{
                width: 100%
            }
        }

        @media (max-width: 500px) {
            .file-box{
            width: 100%;
            
            }
    }
    </style>
    <?php
    $sqlall = "SELECT * FROM customer_orders WHERE order_ID = $order_ID";

    include 'navbar.php';
    $result = $conn->query($sqlall);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){ 
    ?>
    <div class="container-fluid container">
        <div class="col-12 m-auto">
            <div class="edit_items add_items">
                <form method="post" class="item_edit_form">
                    <div class="big_container" style="position: relative;">
                        <div class="breadcrumbs">
                            <a>Admin</a> > <a>Orders</a> > <a href="orders-all.php">Order List</a> > <a class="active"><?php echo $id; ?></a>
                        </div>
                        <span style="position: absolute;right: 20px;top: 10px;font-size: 16px;color: gray;"><?php echo $row['order_date']; ?></span>
                        <div class='item_details'>
                            <div class="page_title">Order Detail<i class="fas fa-clipboard-list-check"></i></div>
                            <div class='item_detail_container'>
                                <label for="id">Order ID</label>
                                <input type="text" title="Unable to edit" name="id" id="id" value="<?php echo $id; ?>" readonly>
                            </div>
                            <?php
                                $cust_query = "SELECT cust_username FROM customer WHERE trash = 0 AND cust_ID = ".$row['cust_ID'];
                                $query_result = $conn->query($cust_query);
                                $query_row = $query_result->fetch_assoc();
                                if($query_row && !empty($query_row['cust_username'])){
                                    $username = $query_row['cust_username'];
                                }
                                else{
                                    $username = "User is disabled.";
                                }
                                $ph_query = "SELECT cust_phone FROM customer WHERE trash = 0 AND cust_ID = ".$row['cust_ID'];
                                $qresult = $conn->query($ph_query);
                                $qrow = $qresult->fetch_assoc();
                                if($qrow && !empty($qrow['cust_phone'])){
                                    $phone = $qrow['cust_phone'];
                                }
                                else{
                                    $phone = "No number filled.";
                                }
                            ?>
                            <div class='item_detail_container'>
                                <label for="user">User</label>
                                <input type="text" title="Unable to edit" name="user" id="user" value="<?php echo $username; ?>" readonly>
                            </div>
                            <div class='item_detail_container'>
                                <label for="phone">Phone Number</label>
                                <div style="display: flex;width: 100%;"><input type="tel" title="Unable to edit" name="phone" id="phone" value="<?php echo $phone; ?>" readonly>
                                <a class="replybtn" title='Call' href='tel:<?php echo $phone; ?>'>Call<i class="fas fa-phone"></i></a></div>
                            </div>
                            <div class='item_detail_container'>
                                <label for="address">Delivery Address</label>
                                <div style="width:100%;color: initial;border: #e9ecef 1px solid;background-color: #e9ecef;border-radius: 7px;font-size: 18px;padding: 2px 5px;display:flex;flex-wrap:wrap;justify-content: space-between;" title="Unable to edit" name="address" id="address"><?php echo $row['order_address']; ?></div>
                            </div>
                            <hr style="width:100%;">
                        <div class='item_detail_container'>
                            <div name="delivery" id="delivery" style="width:100%;border: #e9ecef 1px solid;background-color: #e9ecef;border-radius: 7px;font-size: 18px;padding: 10px;display:flex;flex-wrap:wrap;justify-content: space-between;">
                                <?php
                                
                                $no_count = 0;
                                        
                                $sql_get_cart = "SELECT order_contents FROM customer_orders WHERE order_ID = $order_ID  AND trash = 0";
                                $result_get_cart = $conn->query($sql_get_cart);
                                if ($result_get_cart->num_rows > 0) {
                                    $i=1;
                                    while ($row_get_cart = $result_get_cart->fetch_assoc()) {
                                        $items[] = "";
                                        $items = explode("},{", $row_get_cart['order_contents']);
                                        $items = array_filter($items, 'strlen');
                                        if (count($items) != 0){
                                            foreach ($items as $item) {
                                                $item = trim($item, "{}");
                                                $details = explode(",", $item);
    
                                                $item_ID = trim($details[0], "()");
                                                $item_name = trim($details[1], "()");
                                                $item_price = trim($details[2], "()");
                                                $item_qty = trim($details[3], "()");
                                                $item_sumprice = trim($details[4], "()");
                                                $item_request = trim($details[5], "()");
                                                $item_custom = implode(',', array_slice($details, 6));
                                                preg_match_all('/\(\[([^\]]+)\]\)/', $item_custom, $matches);
                                                
                                                //take out item pic
                                                $sql = "SELECT * FROM menu_items WHERE item_ID = '$item_ID' AND trash = 0";
                                                $result = $conn->query($sql);
                                                
                                                if ($result->num_rows > 0) {
                                                    while ($rows = $result->fetch_assoc()){
                                                        $sql2 = "SELECT * FROM menu_images WHERE image_ID = {$rows['item_ID']} AND trash = 0 LIMIT 1";
                                                        $result2 = $conn->query($sql2);
                                                        while ($row2 = $result2->fetch_assoc()) {
                                                            $image_data = $row2["data"];
                                                            $mime_type = $row2["mime_type"];
                                                            $base64 = base64_encode($image_data);
                                                            $src = "data:$mime_type;base64,$base64";

                                                            // Display all items from customer order
                                                            echo '<div class="item-detail" id="item'.$i.'">
                                                                <img src="'.$src.'" class="item_image">
                                                                <div class="requirement" style="position:relative;">'.
                                                                    $item_qty.' x '.$item_name;
                                                                    if (!empty($matches[1])) {
                                                                        foreach ($matches[1] as $match) {
                                                                            $customs = explode(',', $match);
                                                                            if (count($customs) >= 2) {
                                                                                $custom_key = trim($customs[0]);
                                                                                $custom_value = trim($customs[1]);
                                                                                if (!empty($custom_key) && !empty($custom_value)) {
                                                                                    echo '<br><span>- ' .$custom_key.': '. $custom_value . '</span>';
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    if(!empty($item_request)){
                                                                        echo '<br><span>- ' . $item_request . '</span>';
                                                                    }
                                                            echo '<p class="done_btn" onclick="toggleSaturation(\'item'.$i.'\')">Done</p></div>
                                                            </div>';
                                                            ++$i;
                                                        }
                                                        
                                                    }
                                                }

                                                }
                                            }
                                        }
                                    }
                                                
                                                    

                                ?>
                            </div>
                        </div>
                        <hr style="width:100%;">
                        <div class='item_detail_container'>
                            <label for="readstatus">Tracking Status</label>
                            <select name="readstatus" id="readstatus" style="width:100%;">
                                <option value="0" <?php if ($row['tracking_stage'] == 0) echo "selected"; ?>>Queueing</option>
                                <option value="1" <?php if ($row['tracking_stage'] == 1) echo "selected"; ?>>Preparing</option>
                                <option value="2" <?php if ($row['tracking_stage'] == 2) echo "selected"; ?>>Delivering</option>
                                <option title="Can't choose" value="3" <?php if ($row['tracking_stage'] == 3) echo "selected"; ?> disabled>Received</option>
                            </select>
                        </div>
                        <div class='submit_buttons'>
                            <input type="submit" id="edit-submit" name="edit_submit" class="edit_submit" value="Save" onclick="return confirmAction('save the change');">
                            <input type="submit" name="delete" class="delete" value="Delete" onclick='return confirmAction("delete this order");'>
                        </div>
                    </div>
                    <a href="orders-all.php" class="back_button2">Back To List</a>
                </div>
            </div>
                </form>
                <?php
                }
                } else {
                    echo "No inbox found";
                }

                $conn->close();
                ?>
                <script>
                    function confirmAction(message) {
                        return confirm("Are you sure you want to " + message + "?");
                    }
                  
                </script>
                <script>
    document.addEventListener('DOMContentLoaded', (event) => {
        applySavedSaturation();
    });

    function applySavedSaturation() {
        var itemDetailDivs = document.querySelectorAll('.item-detail');
        itemDetailDivs.forEach(function(div) {
            var id = div.id;
            if (localStorage.getItem(id) === 'saturated') {
                div.style.filter = 'saturate(0.2)';
            } else {
                div.style.filter = 'saturate(1)';
            }
        });
    }

    function toggleSaturation(id) {
        var itemDetailDiv = document.getElementById(id);
        if (itemDetailDiv.style.filter === 'saturate(0.2)') {
            itemDetailDiv.style.filter = 'saturate(1)';
            localStorage.setItem(id, 'normal');
        } else {
            itemDetailDiv.style.filter = 'saturate(0.2)';
            localStorage.setItem(id, 'saturated');
        }
    }
</script>
            
    </body>
</html>