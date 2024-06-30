<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> New Item | Admin Panel</title>
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
            include 'navbar.php';

            //session_start();


            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Retrieve form data
                $item_name = $_POST['item_name'];
                $item_category = $_POST['item_category'];
                $item_price = $_POST['item_price'];
                $item_description = $_POST['item_description'];
                $item_discount = $_POST['item_discount'];
                $item_availability = $_POST['item_availability'];
                $item_options = $_POST['item_options'];

                $maxFileSize = 1 * 1024 * 1024; // 1MB in bytes

                // Check if file was uploaded without errors
                if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
                    if ($_FILES["image"]["size"] <= $maxFileSize) {
                        $filename = $_FILES["image"]["name"];
                        $tempname = $_FILES["image"]["tmp_name"];
                        $mime_type = mime_content_type($tempname);
                        $data = file_get_contents($tempname);

                        // Construct the SQL query to insert menu item data
                        $sql = "INSERT INTO menu_items (item_name, item_category, item_price, item_description, item_discount, item_availability, item_options) VALUES ('$item_name', '$item_category', '$item_price', '$item_description', '$item_discount', '$item_availability', '$item_options')";

                        // Execute the SQL query
                        if ($conn->query($sql) === TRUE) {
                            // Get the auto-incremented item_ID
                            $item_id = $conn->insert_id;

                            // Insert image data into database
                            $stmt = $conn->prepare("INSERT INTO menu_images (image_ID, filename, mime_type, data) VALUES (?, ?, ?, ?)");
                            $stmt->bind_param("isss", $item_id, $filename, $mime_type, $data);
                            $stmt->execute();

                            $_SESSION['addItem_success'] = true;
                            echo '<script>';
                            echo 'window.location.href = "items-add.php";';
                            echo '</script>';
                            //header("Location: items-add.php");
                            exit();
                        } else {
                            $_SESSION['addItem_error'] = "Error: " . $sql . "<br>" . $conn->error;
                            echo '<script>';
                            echo 'window.location.href = "items-add.php";';
                            echo '</script>';
                            exit();
                        }
                    } else {
                        // File is too large
                        $_SESSION['addItem_imageSize_error'] = "File size exceeds the maximum limit of 1MB.";
                        echo '<script>';
                        echo 'window.location.href = "items-add.php";';
                        echo '</script>';
                        exit();
                    }
                } elseif (isset($_FILES["image"]) && $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE) {
                    $_SESSION['addItem_image_error'] = "Error uploading file.";
                    echo '<script>';
                        echo 'window.location.href = "items-add.php";';
                        echo '</script>';
                    exit();
                } else {
                    // No image uploaded, proceed with inserting menu item data without image
                    $sql = "INSERT INTO menu_items (item_name, item_category, item_price, item_description, item_discount, item_availability, item_options) VALUES ('$item_name', '$item_category', '$item_price', '$item_description', '$item_discount', '$item_availability', '$item_options')";

                    // Execute the SQL query
                    if ($conn->query($sql) === TRUE) {
                        $_SESSION['addItem_success'] = true;
                        echo '<script>';
                        echo 'window.location.href = "items-add.php";';
                        echo '</script>';
                        exit();
                    } else {
                        $_SESSION['addItem_error'] = "Error: " . $sql . "<br>" . $conn->error;
                        echo '<script>';
                        echo 'window.location.href = "items-add.php";';
                        echo '</script>';
                        exit();
                    }
                }
            }

            if (isset($_SESSION['addItem_success']) && $_SESSION['addItem_success'] === true) {
                echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Item successfully added to menu list!
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';

                unset($_SESSION['addItem_success']);
            }

            if (isset($_SESSION['addItem_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-times-circle"></i>Failed to add item. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['addItem_error']);
            }

            if (isset($_SESSION['addItem_image_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-times-circle"></i>Failed to add item image. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['addItem_image_error']);
            }

            if (isset($_SESSION['addItem_imageSize_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-times-circle"></i>Item image cannot exceed the maximum limit of 1MB. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['addItem_imageSize_error']);
            }


            ?>
                <div class="container-fluid container">
                    <div class="col-12 m-auto">
                        <div class="edit_items add_items">
                        <form action="items-add.php" method="post" enctype="multipart/form-data" class="item_edit_form">
                            <div class="big_container" style="position: relative;">
                                <div class="breadcrumbs">
                                    <a>Admin</a> > <a>Menu</a> > <a href="items-all.php">Item List</a> > <a class="active">Add New</a>
                                </div>
                                <div class='image_container'><img src='../images/placeholder_image.png' id='item_image_diplay' class='item_image_diplay'>
                                    <input type="file" name="image" class="upload_image" id="upload_image" required accept="image/png, image/gif, image/jpeg" />
                                    <label class="upload_image_label" for="upload_image"><i class="fas fa-camera"></i></label>
                                </div>
                                <div class='item_details'>
                                    <div class="page_title">New Item<i class="fas fa-pen"></i></div>
                                    <div class='item_detail_container'>
                                        <label for="item_name">Item Name</label>
                                        <input type="text" name="item_name" id="item_name" placeholder="New Name" required>
                                    </div>
                                    <div class='item_detail_container'>
                                        <label for="item_category">Item Category</label>

                                        <?php
                                        // Query to fetch parent categories from the database
                                        $parent_categories_sql = "SELECT category_ID, category_name FROM menu_categories WHERE category_parent != 0 AND trash = 0 ORDER BY category_name ASC";
                                        $parent_categories_result = $conn->query($parent_categories_sql);

                                        // Populate selectedCategoryIDs array with category IDs retrieved from the database
                                        $selectedCategoryIDs = [];
                                        
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
                                    </div>
                                    <div id="selectedCategories">
                                        <?php
                                        // Display selected categories in divs without commas
                                        foreach ($selectedCategoryIDs as $categoryId) {
                                            $category_name_sql = "SELECT category_name FROM menu_categories WHERE category_ID = $categoryId AND trash = 0";
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
                                    <div class="item_detail_container">
                                        <label for="item_price">Customization Options</label>
                                
                                        <?php
                                        // Query to fetch parent categories from the database
                                        $menu_cutomization_sql = "SELECT * FROM menu_customization WHERE trash = 0 ORDER BY custom_name ASC";
                                        $menu_cutomization_result = $conn->query($menu_cutomization_sql);

                                        // Populate selectedOptionIDs array with option IDs retrieved from the database
                                        $selectedOptionIDs = [];
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
                                    </div>
                                    <div id="selectedOptions">
                                        <?php
                                        // Display selected categories in divs without commas
                                        foreach ($selectedOptionIDs as $optionId) {
                                            $option_name_sql = "SELECT custom_name FROM menu_customization WHERE custom_ID = $optionId AND trash = 0";
                                            $option_name_result = $conn->query($option_name_sql);
                                            if ($option_name_result->num_rows > 0) {
                                                $row3 = $option_name_result->fetch_assoc();
                                                echo "<div id='option_" . $optionId . "'>" . $row3['custom_name'] . " <button class='trashOptionButton'>x</button></div>";
                                            }
                                        }
                                        ?>
                                    </div>
                                    <input type="text" name="item_options" id="item_options" value="<?php echo implode(',', $selectedOptionIDs); ?>" style=" opacity: 0; height: 0; width: 100px; top: -10px; position: relative;">
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
                                <div class='item_detail_container'>
                                    <label for="item_price">Item Price</label>
                                    <input type="number" min='0' value="0.00" name="item_price" id="item_price" step="any" placeholder="New Price" required>
                                </div>
                                <div class='item_detail_container'>
                                    <label for="item_discount">Item Discount</label>
                                    <input type="number" min='0' name="item_discount" id="item_discount" value="0" required>
                                </div>
                                <div class='item_detail_container'>
                                    <label for="item_description" style="margin-bottom: auto;">Item Description</label>
                                    <textarea name="item_description" id="item_description" rows="4" cols="50" placeholder="New Description" required></textarea>
                                </div>
                                <div class='item_detail_container'>
                                    <label for="item_availability">Item Availability</label>
                                    <select name="item_availability" id="item_availability">
                                        <option value="1">Available</option>
                                        <option value="0">Not Available</option>
                                    </select>
                                </div>
                                <div class='submit_buttons'>
                                    <input type="submit" value="Add Item" class="edit_submit">
                                </div>
                                </div>
                                <a href="items-all.php" class="back_button2">Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </body>
            </html>
            <?php

            $conn->close();
        ?>
        <script>
            document.getElementById('upload_image').addEventListener('change', function (e) {
                var file = e.target.files[0];
                var reader = new FileReader();

                reader.onload = function (e) {
                    document.getElementById('item_image_diplay').setAttribute('src', e.target.result);
                }

                reader.readAsDataURL(file);
            });
        </script>
    </body>
</html>