<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../../../style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
    <script src="../../../script.js"></script>
</head>
<body>
    <h2>Item List</h2>
    <table border="1">
        <tr>
            <th>Image</th>
            <th>Item Name</th>
            <th>Item Category</th>
            <th>Item Price</th>
            <th>Item Discount</th>
            <th>Item Availability</th>
            <th>Action</th>
        </tr>
        <?php
        include '../../../connect.php';

        $sql = "SELECT * FROM menu_items";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";

                $sql2 = "SELECT * FROM menu_images WHERE image_ID = {$row['item_ID']} LIMIT 1";
                $result2 = $conn->query($sql2);
                while ($row2 = $result2->fetch_assoc()) {
                    $image_data = $row2["data"];
                    $mime_type = $row2["mime_type"];
                    $base64 = base64_encode($image_data);
                    $src = "data:$mime_type;base64,$base64";
                    echo "<td><img src='".$src."' style=\"width:60px;\"></td>";
                }

                echo "<td>".$row['item_name']."</td><td>";

                $categories = $row['item_category'];
                // Remove any commas
                $category_array = explode(',', $categories);

                $valid_category_ids = array(); // Define an array to store valid category IDs

                foreach ($category_array as $category) {
                    $sql3 = "SELECT category_name FROM menu_categories WHERE category_ID = {$category} LIMIT 1";
                    $result3 = $conn->query($sql3);

                    if ($result3 && $result3->num_rows > 0) {
                        // Category exists, add it to the valid category IDs array
                        while ($row3 = $result3->fetch_assoc()) {
                            echo $row3['category_name'];
                            echo ", ";
                            $valid_category_ids[] = $category;
                        }
                    } else {
                        $category_array = array_diff($category_array, array($category));
                    }
                }

                // Construct updated item_category string
                $updated_category_string = implode(',', $valid_category_ids);

                // Update item_category in the database
                $update_sql = "UPDATE menu_items SET item_category = '{$updated_category_string}' WHERE item_ID = {$row['item_ID']}";
                $conn->query($update_sql);

                $options = $row['item_options'];
                // Remove any commas
                $options_array = explode(',', $options);

                $valid_options_ids = array(); // Define an array to store valid category IDs

                foreach ($options_array as $option) {
                    $sql4 = "SELECT * FROM menu_customization WHERE custom_ID = {$option} LIMIT 1";
                    $result4 = $conn->query($sql4);

                    if ($result4 && $result4->num_rows > 0) {
                        while ($row4 = $result4->fetch_assoc()) {
                            echo $row4['custom_name'];
                            echo ", ";
                            $valid_options_ids[] = $option;
                        }
                    } else {
                        $options_array = array_diff($options_array, array($option));
                    }
                }

                // Construct updated item_category string
                $updated_options_string = implode(',', $valid_options_ids);

                // Update item_category in the database
                $update_sql2 = "UPDATE menu_items SET item_options = '{$updated_options_string}' WHERE item_ID = {$row['item_ID']}";
                $conn->query($update_sql2);

                echo "</td><td>".$row['item_price']."</td>";
                echo "<td>".$row['item_discount']."</td>";
                echo "<td>".$row['item_availability']."</td>";
                echo '<td><a href="edit.php?ID=' . $row['item_ID'] . '">Edit</a>';
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No items found</td></tr>";
        }

        $conn->close();
        ?>
    </table>
    <a href="add.php">Add</a>
</body>
</html>


