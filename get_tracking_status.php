<?php
include 'connect.php'; 
header('Content-Type: application/json');

if (isset($_GET['ID'])) {
    // Retrieve the value of the ID parameter
    $order_ID = $_GET['ID'];

    $query = "SELECT tracking_stage FROM customer_orders WHERE trash = 0 AND order_ID = $order_ID";
    $result = $conn->query($query);

    $tracking_statuses = [];
    if (!$result) {
        die("Query execution failed: " . mysqli_error($conn));
    }
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $tracking_statuses[] = $row['tracking_stage'];
        }
    }

    echo json_encode($tracking_statuses); // return the tracking statuses as JSON
} else {
    // Handle the case where ID parameter is not set
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "ID parameter is missing"]);
}
?>