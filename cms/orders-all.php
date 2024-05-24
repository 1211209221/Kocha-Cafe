<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List | Admin Panel</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="icon" href="../images/logo/logo_icon_2.png">
    <script src="../script.js"></script>
    <script src="../gototop.js"></script>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <?php
    include '../connect.php';
    include '../gototopbtn.php';
    include 'navbar.php';

    $query = "SELECT * FROM customer_orders";
    $result = mysqli_query($conn, $query);

    // Check if there are any results
    if (mysqli_num_rows($result) > 0) {
        echo "<h1>Order List</h1>";
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Order ID</th>";
        echo "<th>Customer ID</th>";
        echo "<th>Item</th>";
        echo "<th>Price per unit</th>";
        echo "<th>Quantity</th>";
        echo "<th>Total Price</th>";
        echo "<th>Sauce Type</th>"; // Changed from "Extra Note" to "Sauce Type"
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // Loop through each row
        while ($row = mysqli_fetch_assoc($result)) {
            // Ensure $row['order_contents'] exists and is not null
            if (isset($row['order_contents'])) {
                $order_details = $row['order_contents'];

                // Remove the outer braces and split the string by "},{"
                $orders = explode('},{', substr($order_details, 1, -1));

                foreach ($orders as $order) {
                    // Remove parentheses and split the order by commas
                    $components = explode(',', trim($order, '()'));

                    // Ensure the components array has the necessary elements before accessing them
                    if (count($components) >= 7) {
                        $cust_id = $row['cust_ID']; // Fetch Customer ID
                        $item = trim($components[1], '()');
                        $options = trim($components[2], '()');
                        $price = trim($components[3], '()');
                        $quantity = trim($components[4], '()');
                        $total_price = trim($components[5], '()');
                        $sauce_type = isset($components[6]) ? trim($components[7], '()') : ''; // Change "extra_note" to "sauce_type"

                        // Output row for each order
                        echo "<tr>";
                        echo "<td>" . $row['order_ID'] . "</td>";
                        echo "<td>" . $cust_id . "</td>";
                        echo "<td>" . $item . "</td>";
                        echo "<td>" . $price . "</td>";
                        echo "<td>" . $quantity . "</td>";
                        echo "<td>" . $total_price . "</td>";
                        echo "<td>" . $sauce_type . "</td>"; // Change from "extra_note" to "sauce_type"
                        echo "</tr>";
                    }
                }
            } else {
                echo "No order details found.";
            }
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "No orders found";
    }
    ?>
</body>

</html>
