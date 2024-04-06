
        <style>
            input[type=number]::-webkit-inner-spin-button {
                opacity: 1
            }
            input[type=number]{
                width: 41px;
            }
        </style>
        <script>
            function confirmAction(message) {
                return confirm("Are you sure you want to " + message + "?");
            }
        </script>
        <?php
        // Include the connection file
        include '../../connect.php';

        // Check if form is submitted for update, then update the record
        if(isset($_POST['update'])) {
            $category_IDs = $_POST['category_ID'];
            $category_names = $_POST['category_name'];
            $category_parents = $_POST['category_parent'];
            $category_displays = $_POST['category_display'];
            $category_primarys = $_POST['category_primary'];

            $success_count = 0;

            // Loop through each entry and update
            for($i = 0; $i < count($category_IDs); $i++) {
                $category_ID = $category_IDs[$i];
                $category_name = $category_names[$i];
                $category_parent = $category_parents[$i];
                $category_display = $category_displays[$i];
                $category_primary = $category_primarys[$i];

                // Update query
                $sql = "UPDATE menu_categories SET category_name=?, category_parent=?, category_display=?, category_primary=? WHERE category_ID=?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'siiii', $category_name, $category_parent, $category_display, $category_primary, $category_ID);

                if(mysqli_stmt_execute($stmt)) {
                    $success_count++;
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
            }

            // Display confirmation message after all updates
            if($success_count == count($category_IDs)) {
                echo "All records updated successfully";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
        }

        if(isset($_POST['set_primary'])) {
            // Check if the selected category ID is posted
            if(isset($_POST['category_primary'])) {
                // Get the selected category ID
                $selected_category_id = $_POST['category_primary'];

                // Reset category_primary flag for all categories
                $sql_reset_primary = "UPDATE menu_categories SET category_primary = 0";
                if(mysqli_query($conn, $sql_reset_primary)) {
                    // Update the database to set the selected category as primary
                    $sql_update_primary = "UPDATE menu_categories SET category_primary = 1 WHERE category_ID = ?";
                    $stmt = mysqli_prepare($conn, $sql_update_primary);
                    mysqli_stmt_bind_param($stmt, 'i', $selected_category_id);

                    // Execute the update query
                    if(mysqli_stmt_execute($stmt)) {
                        echo "Category set as primary successfully";
                        header("Location: ".$_SERVER['PHP_SELF']);
                        exit();
                    } else {
                        echo "Error setting category as primary: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error resetting primary categories: " . mysqli_error($conn);
                }
            } else {
                echo "No category selected.";
            }
        }



        // Define deleteCategories function outside of the if block
        function deleteCategories($category_ID, $conn) {

            $sql = "SELECT * FROM menu_categories WHERE category_parent = '$category_ID' AND trash = 0";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // Fetch rows
                while ($row = $result->fetch_assoc()) {
                    // Recursively call deleteCategories for child categories
                    deleteCategories($row['category_ID'], $conn);
                }
            }
            // Delete query
            $sql2 = "UPDATE menu_categories SET trash = 1 WHERE category_ID=?";
            $stmt2 = mysqli_prepare($conn, $sql2);
            mysqli_stmt_bind_param($stmt2, 'i', $category_ID);

            if(mysqli_stmt_execute($stmt2)) {
                echo "Record with category ID $category_ID has been deleted successfully.";
            } else {
                echo "Error deleting record: " . mysqli_error($conn);
            }
        }

        // Check if form is submitted for deletion, then delete the record
        if(isset($_POST['trash'])) {
            $category_ID = $_POST['trash'];
            deleteCategories($category_ID, $conn);
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }

        if(isset($_POST['add'])) {
            // Retrieve the values submitted via the form
            $category_name = $_POST['category_name'];
            $category_parent = $_POST['category_parent'];

            // Insert query
            $sql = "INSERT INTO menu_categories (category_name, category_parent) VALUES ('$category_name','$category_parent')";

            // Execute the query
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Query to select all entries from the database
        $sql = "SELECT * FROM menu_categories WHERE trash = 0";

        // Execute the query
        $result = mysqli_query($conn, $sql);

        // Check if there are any results
        if (mysqli_num_rows($result) > 0) {
            // Output data of each row with editable text inputs within one form
        ?>
        
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Panel</title>
        <link rel="stylesheet" href="../../style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
        <link rel="icon" href="../../images/logo/logo_icon_2.png">
        <script src="../../script.js"></script>
        <script src="../../gototop.js"></script>
    </head>
    <body>
        <?php
            include '../../gototopbtn.php';
            include '../navbar.php';
        ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <?php
            function displayCategories($parentCategory, $conn, $level = 0) {
                $sql = "SELECT * FROM menu_categories WHERE category_parent = '$parentCategory' AND trash = 0";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<div style='margin-left: ".($level * 20)."px;'>"; // Adjust the margin based on the level
                    while ($row = $result->fetch_assoc()) {
                        echo "<input type='hidden' name='category_ID[]' value='" . $row['category_ID'] . "'>";
                        echo "<input type='text' name='category_name[]' value='" . $row['category_name'] . "' maxlength='20' onkeypress='return event.keyCode != 13;'>";
                        echo "<input type='hidden' name='category_parent[]' value='" . $row['category_parent'] . "'>";
                        echo "<select name='category_display[]'>";
                        echo "<option value='0'" . ($row['category_display'] == 0 ? ' selected' : '') . ">No</option>";
                        echo "<option value='1'" . ($row['category_display'] == 1 ? ' selected' : '') . ">Yes</option>";
                        echo "</select>";
                        echo "<button type='submit' name='trash' value='" . $row['category_ID'] . "' onclick=\"return confirmAction('delete this sub category');\">x</button>
                        <br>";
                        displayCategories($row['category_ID'], $conn, $level); // Increment the level for nested categories
                    }
                    echo "</div>";
                }
            }

            $sql = "SELECT * FROM menu_categories WHERE category_parent = '' AND trash = 0";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if($row['category_primary'] == 1){
                        echo '<i class="fas fa-star"></i>';
                    }
                    echo "<input type='hidden' name='category_ID[]' value='" . $row['category_ID'] . "'>";
                    echo "<input type='text' name='category_name[]' value='" . $row['category_name'] . "' maxlength='15' onkeypress='return event.keyCode != 13;'>";
                    echo "<input type='hidden' name='category_parent[]' value='" . $row['category_parent'] . "'>";
                    echo "<select name='category_display[]'>";
                    echo "<option value='0'" . ($row['category_display'] == 0 ? ' selected' : '') . ">No</option>";
                    echo "<option value='1'" . ($row['category_display'] == 1 ? ' selected' : '') . ">Yes</option>";
                    echo "</select>";
                    echo "<button type='submit' name='trash' value='" . $row['category_ID'] . "' onclick=\"return confirmAction('delete this category');\">x</button>
                    <br>";
                    displayCategories($row['category_ID'], $conn, 1); // Start with level 1 for top-level categories
                }
            } else {
                echo "No items found";
            }
        ?>
                <input type="submit" name="update" value="Update" onclick='return confirmAction("make these changes");'>
            </form>
        <?php
        } else {
            echo "0 results";
        }
        ?>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">  

            Name: <input type="text" name="category_name" maxlength='15'>
            Parent:
            <select name="category_parent">
                <option value="">None (main category)</option>
                <?php
                    // Query to fetch parent categories from the database
                    $parent_categories_sql = "SELECT category_ID, category_name FROM menu_categories WHERE trash = 0 ORDER BY category_name ASC";
                    $parent_categories_result = $conn->query($parent_categories_sql);

                    // Display options for parent categories
                    if ($parent_categories_result->num_rows > 0) {
                        while ($row = $parent_categories_result->fetch_assoc()) {
                            echo "<option value='" . $row['category_ID'] . "'>" . $row['category_name'] . "</option>";
                        }
                    }
                ?>
            </select><br>

            <input type="submit" name="add" value="Add">
        </form>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <?php
            $sql = "SELECT * FROM menu_categories WHERE category_parent = 0 AND trash = 0";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<select name='category_primary'>";
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['category_ID'] . "'" . ($row['category_primary'] == 1 ? ' selected' : '') . ">" . $row['category_name'] . "</option>";
                }
                echo "</select>";
            } else {
                echo "No main categories found";
            }
            ?>
            <input type="submit" name="set_primary" value="Set Primary">
        </form>
    </body>
</html>
<?php
    // Close the connection
    mysqli_close($conn);
?>
