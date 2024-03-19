<?php
include '../../../connect.php';

if (isset($_GET['ID'])) {
    // Retrieve the value of the ID parameter
    $item_ID = $_GET['ID'];
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        // Delete associated image first
        $delete_image_sql = "DELETE FROM menu_images WHERE image_ID = $item_ID";

        if ($conn->query($delete_image_sql) === TRUE) {
            // Proceed with deleting the item entry
            $delete_item_sql = "DELETE FROM menu_items WHERE item_ID = $item_ID";

            if ($conn->query($delete_item_sql) === TRUE) {
                echo "Item and associated image deleted successfully.";
                // Redirect to a different URL
                header("Location: all.php");
                exit();
            } else {
                echo "Error deleting item record: " . $conn->error;
            }
        } else {
            echo "Error deleting associated image: " . $conn->error;
        }
    } else {
        // Retrieve form data
        $item_name = $_POST['item_name'];
        $item_category = $_POST['item_category'];
        $item_price = $_POST['item_price'];
        $item_description = $_POST['item_description'];
        $item_discount = $_POST['item_discount'];
        $item_availability = $_POST['item_availability'];
        $item_options = $_POST['item_options'];

        // Prepare and execute the SQL statement to update data in the database
        $sql = "UPDATE menu_items 
                SET item_name = '$item_name', 
                    item_category = '$item_category', 
                    item_price = '$item_price', 
                    item_description = '$item_description', 
                    item_discount = '$item_discount', 
                    item_availability = '$item_availability',
                    item_options = '$item_options'
                WHERE item_ID = $item_ID";

        if ($conn->query($sql) === TRUE) {
            // Check if file was uploaded without errors
            if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
                $filename = $_FILES["image"]["name"];
                $tempname = $_FILES["image"]["tmp_name"];
                $mime_type = mime_content_type($tempname);
                $data = file_get_contents($tempname);

                // Insert image data into database
                $stmt = $conn->prepare("UPDATE menu_images SET filename=?, mime_type=?, data=? WHERE image_ID=?");
                $stmt->bind_param("sssi", $filename, $mime_type, $data, $item_ID);
                $stmt->execute();

                echo "Item and image uploaded successfully.";

                // Redirect to a different URL to prevent form resubmission
                header("Location: edit.php?ID=$item_ID");
                exit();
            } elseif (isset($_FILES["image"]) && $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE) {
            echo "Error uploading the image: ";
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Fetch item from the database
$sql = "SELECT * FROM menu_items WHERE item_ID = $item_ID";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Add form for editing the item inside the loop
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Item</title>
            <link rel="stylesheet" href="../../../style.css">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
            <script src="../../../script.js"></script>
        </head>
        <body>
            <h2>Edit Item</h2>
            <form action="edit.php?ID=<?php echo $item_ID; ?>" method="post" enctype="multipart/form-data">
                <?php
                    $sql2 = "SELECT * FROM menu_images WHERE image_ID = {$item_ID} LIMIT 1";
                    $result2 = $conn->query($sql2);
                    while ($row2 = $result2->fetch_assoc()) {
                        $image_data = $row2["data"];
                        $mime_type = $row2["mime_type"];
                        $base64 = base64_encode($image_data);
                        $src = "data:$mime_type;base64,$base64";
                        echo "<img src='".$src."' style=\"width:90px;\">";
                    }
                ?>
                <input type="file" name="image">
                <br>
                <label for="item_name">Item Name:</label>
                <input type="text" name="item_name" id="item_name" value="<?php echo $row['item_name']; ?>" required><br>
                <label for="item_category">Item Category:</label>

                <?php
                // Query to fetch parent categories from the database
                $parent_categories_sql = "SELECT category_ID, category_name FROM menu_categories ORDER BY category_name ASC";
                $parent_categories_result = $conn->query($parent_categories_sql);

                // Populate selectedCategoryIDs array with category IDs retrieved from the database
                $selectedCategoryIDs = [];
                if ($row['item_category']) {
                    $selectedCategoryIDs = explode(',', $row['item_category']);
                }
                ?>

                <select id="category_select">
                    <option id="select_default" value="Select..." selected disabled>Select...</option>
                    <?php
                    // Display options for parent categories
                    if ($parent_categories_result->num_rows > 0) {
                        while ($row2 = $parent_categories_result->fetch_assoc()) {
                            $selected = in_array($row2['category_ID'], $selectedCategoryIDs) ? 'selected' : '';
                            echo "<option value='" . $row2['category_ID'] . "'>" . $row2['category_name'] . "</option>";
                        }
                    }
                    ?>
                </select>
                <button id="addButton">Add</button>
                <div id="selectedCategories">
                    <?php
                    // Display selected categories in divs without commas
                    foreach ($selectedCategoryIDs as $categoryId) {
                        $category_name_sql = "SELECT category_name FROM menu_categories WHERE category_ID = $categoryId";
                        $category_name_result = $conn->query($category_name_sql);
                        if ($category_name_result->num_rows > 0) {
                            $row3 = $category_name_result->fetch_assoc();
                            echo "<div id='category_" . $categoryId . "'>" . $row3['category_name'] . " <button class='trashButton'>x</button></div>";
                        }
                    }
                    ?>
                </div>
                <input type="text" name="item_category" id="item_category" value="<?php echo implode(',', $selectedCategoryIDs); ?>" style=" opacity: 0; height: 0; width: 100px; top: -10px; position: relative;" required>
                <script>
                    var selectedCategoryIDs = <?php echo json_encode($selectedCategoryIDs); ?>;

                    document.getElementById('addButton').addEventListener('click', function(event) {
                        event.preventDefault(); // Prevent form submission
                        var selectElement = document.getElementById('category_select');
                        var selectedOption = selectElement.options[selectElement.selectedIndex];
                        
                        // Check if the selected option is not the default one ("Select...")
                        if (selectedOption.id !== "select_default") {
                            var categoryId = selectedOption.value;
                            var categoryName = selectedOption.text;
                            var div = document.createElement('div');
                            div.textContent = categoryName;
                            var trashButton = document.createElement('button');
                            trashButton.textContent = "x";
                            trashButton.className = "trashButton";
                            div.appendChild(trashButton);
                            div.id = 'category_' + categoryId; // Set the ID of the div
                            document.getElementById('selectedCategories').appendChild(div);
                            selectedCategoryIDs.push(categoryId);
                            updateItemCategoryInput(); // Update the item_category input value
                        }
                    });

                    // Add event listener for dynamically created trash buttons
                    document.addEventListener('click', function(event) {
                        if (event.target.classList.contains('trashButton')) {
                            var categoryDiv = event.target.closest('div');
                            var categoryId = categoryDiv.id.split('_')[1]; // Extract category ID from the ID attribute of the div
                            var index = selectedCategoryIDs.indexOf(categoryId);
                            if (index !== -1) {
                                selectedCategoryIDs.splice(index, 1);
                                categoryDiv.remove();
                                updateItemCategoryInput(); // Update the item_category input value
                            }
                        }
                    });

                    function updateItemCategoryInput() {
                        // Update the value of the item_category input with the comma-separated list of selected category IDs
                        document.getElementById('item_category').value = selectedCategoryIDs.join(',');
                    }
                </script>
                <br>
                <label for="item_price">Customization Options:</label>
                




                <?php
                // Query to fetch parent categories from the database
                $menu_cutomization_sql = "SELECT * FROM menu_customization ORDER BY custom_name ASC";
                $menu_cutomization_result = $conn->query($menu_cutomization_sql);

                // Populate selectedOptionIDs array with option IDs retrieved from the database
                $selectedOptionIDs = [];
                if ($row['item_options']) {
                    $selectedOptionIDs = explode(',', $row['item_options']);
                }
                ?>

                <select id="options_select">
                    <option id="select_default" value="Select..." selected disabled>Select...</option>
                    <?php
                    if ($menu_cutomization_result->num_rows > 0) {
                        while ($row4 = $menu_cutomization_result->fetch_assoc()) {
                            // $selected = in_array($row4['custom_ID'], $selectedOptionIDs) ? 'selected' : '';
                            echo "<option value='" . $row4['custom_ID'] . "'>" . $row4['custom_name'] . "</option>";
                        }
                    }
                    ?>
                </select>
                <button id="addOptionButton">Add</button>
                <div id="selectedOptions">
                    <?php
                    // Display selected categories in divs without commas
                    foreach ($selectedOptionIDs as $optionId) {
                        $option_name_sql = "SELECT custom_name FROM menu_customization WHERE custom_ID = $optionId";
                        $option_name_result = $conn->query($option_name_sql);
                        if ($option_name_result->num_rows > 0) {
                            $row3 = $option_name_result->fetch_assoc();
                            echo "<div id='option_" . $optionId . "'>" . $row3['custom_name'] . " <button class='trashOptionButton'>x</button></div>";
                        }
                    }
                    ?>
                </div>
                <input type="text" name="item_options" id="item_options" value="<?php echo implode(',', $selectedOptionIDs); ?>" style=" opacity: 0; height: 0; width: 100px; top: -10px; position: relative;" required>
                <script>
                    var selectedOptionIDs = <?php echo json_encode($selectedOptionIDs); ?>;

                    document.getElementById('addOptionButton').addEventListener('click', function(event) {
                        event.preventDefault(); // Prevent form submission
                        var selectElement2 = document.getElementById('options_select');
                        var selectedOption2 = selectElement2.options[selectElement2.selectedIndex];
                        
                        // Check if the selected option is not the default one ("Select...")
                        if (selectedOption2.id !== "select_default") {
                            var optionId = selectedOption2.value;
                            var optionName = selectedOption2.text;
                            var div = document.createElement('div');
                            div.textContent = optionName;
                            var trashOptionButton = document.createElement('button');
                            trashOptionButton.textContent = "x";
                            trashOptionButton.className = "trashOptionButton";
                            div.appendChild(trashOptionButton);
                            div.id = 'option_' + optionId; // Set the ID of the div
                            document.getElementById('selectedOptions').appendChild(div);
                            selectedOptionIDs.push(optionId);
                            updateItemOptionInput(); // Update the item_category input value
                        }
                    });

                    // Add event listener for dynamically created trash buttons
                    document.addEventListener('click', function(event) {
                        if (event.target.classList.contains('trashOptionButton')) {
                            var optionDiv = event.target.closest('div');
                            var optionId = optionDiv.id.split('_')[1]; // Extract category ID from the ID attribute of the div
                            var index = selectedOptionIDs.indexOf(optionId);
                            if (index !== -1) {
                                selectedOptionIDs.splice(index, 1);
                                optionDiv.remove();
                                updateItemOptionInput(); // Update the item_category input value
                            }
                        }
                    });

                    function updateItemOptionInput() {
                        // Update the value of the item_category input with the comma-separated list of selected category IDs
                        document.getElementById('item_options').value = selectedOptionIDs.join(',');
                    }
                </script>











                <br>
                <label for="item_price">Item Price:</label>
                <input type="number" name="item_price" id="item_price" step="any" value="<?php echo $row['item_price']; ?>" required><br>
                <label for="item_description">Item Description:</label><br>
                <textarea name="item_description" id="item_description" rows="4" cols="50" required><?php echo $row['item_description']; ?></textarea><br>
                <label for="item_discount">Item Discount:</label>
                <input type="number" name="item_discount" id="item_discount" value="<?php echo $row['item_discount']; ?>" required><br>
                <label for="item_availability">Item Availability:</label>
                <select name="item_availability" id="item_availability">
                    <option value="1" <?= $row['item_availability'] == '1' ? 'selected' : '' ?>>Available</option>
                    <option value="0" <?= $row['item_availability'] == '0' ? 'selected' : '' ?>>Not Available</option>
                </select><br>
                <input type="submit" value="Edit">
                <input type="submit" name="delete" value="Delete">
            </form>

            <a href="all.php">Back</a>
        </body>
        </html>
        <?php
    }
} else {
    echo "No item found";
}

$conn->close();
?>
