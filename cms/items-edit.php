<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item | Admin Panel</title>
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
                $item_ID = $_GET['ID'];
            }

            // Check if form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['delete'])) {
                    // Delete associated image first
                    // $delete_image_sql = "DELETE FROM menu_images WHERE image_ID = $item_ID";
                    $delete_image_sql = "UPDATE menu_images SET trash = 1 WHERE image_ID = $item_ID";

                    if ($conn->query($delete_image_sql) === TRUE) {
                        // Proceed with deleting the item entry
                        $delete_item_sql = "UPDATE menu_items SET trash = 1 WHERE item_ID = $item_ID";

                        if ($conn->query($delete_item_sql) === TRUE) {
                            $_SESSION['deleteItem_success'] = true;
                            header("Location: items-all.php");
                            exit();
                        } else {
                            $_SESSION['deleteItem_error'] = "Error: " . $sql_cart . "<br>" . $conn->error;
                            header("Location: items-all.php");
                            exit();
                        }
                    } else {
                        $_SESSION['deleteItem_image_error'] = "Error: " . $sql_cart . "<br>" . $conn->error;
                        header("Location: items-all.php");
                        exit();
                    }
                }
                else if (isset($_POST['edit_submit'])) {
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

                    $maxFileSize = 1 * 1024 * 1024; // 1MB in bytes

                    $edit_set = 0;

                    if ($conn->query($sql) === TRUE) {
                        $edit_set = 1;
                        // Check if file was uploaded without errors
                        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
                            if ($_FILES["image"]["size"] <= $maxFileSize) {
                                $filename = $_FILES["image"]["name"];
                                $tempname = $_FILES["image"]["tmp_name"];
                                $mime_type = mime_content_type($tempname);
                                $data = file_get_contents($tempname);

                                // Insert image data into database
                                $stmt = $conn->prepare("UPDATE menu_images SET filename=?, mime_type=?, data=? WHERE image_ID=?");
                                $stmt->bind_param("sssi", $filename, $mime_type, $data, $item_ID);
                                $stmt->execute();
                            } else {
                                // File is too large
                                $_SESSION['editItem_imageSize_error'] = "File size exceeds the maximum limit of 1MB.";
                                $edit_set = 0;
                            }
                        } elseif (isset($_FILES["image"]) && $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE) {
                            $_SESSION['editItem_image_error'] = "Error uploading file.";
                        }
                        
                        if($edit_set == 1){
                            $_SESSION['editItem_success'] = true;
                        }

                        header("Location: items-edit.php?ID=$item_ID");
                        exit();
                    } else {
                        $_SESSION['editItem_error'] = "Error: " . $sql_cart . "<br>" . $conn->error;
                        header("Location: items-edit.php?ID=$item_ID");
                        exit();
                    }
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['approve_submit'])) {
                    // Assuming $_POST['review_ID'] and $_POST['review_approve'] are arrays
                    $review_ids = $_POST['review_ID'];
                    $review_approvals = $_POST['review_approve'];
                    $trashs = $_POST['trash'];
                    $ratings_sum = 0;

                    // Loop through each review
                    foreach ($review_ids as $key => $review_id) {
                        $approve_status = $review_approvals[$key];
                        $trash = isset($trashs[$key]) ? $trashs[$key] : 0; // Default to 0 if not checked
                        // Update your database with the approval status
                        $approval_sql = "UPDATE customer_reviews SET review_approve = $approve_status, trash = $trash WHERE review_ID = $review_id";

                        if ($conn->query($approval_sql) === TRUE) {
                            echo "Successfully updated customer review";
                        } else {
                            echo "Error updating customer review: " . $conn->error;
                        }
                    }


                    $sql_ratings = "SELECT review_rating FROM customer_reviews WHERE item_ID = $item_ID AND review_approve = 1 AND trash = 0";

                    $result_ratings = $conn->query($sql_ratings);
                    if ($result_ratings->num_rows > 0) {
                        while ($row_ratings = $result_ratings->fetch_assoc()) {
                            $ratings_sum += $row_ratings['review_rating'];
                        }

                        $sql_ratings_insert = "UPDATE menu_items SET item_rating = ($ratings_sum/($result_ratings->num_rows)) WHERE item_ID = $item_ID";

                        if ($conn->query($sql_ratings_insert) === TRUE) {
                            echo "Successfully updated customer review";
                        } else {
                            echo "Error updating item_rating: " . $conn->error;
                        }
                    }

                    $_SESSION['submitReviews_success'] = true;
                    header("Location: items-edit.php?ID=" . $_GET['ID']);
                    exit();
                }
            }

            if (isset($_SESSION['editItem_success']) && $_SESSION['editItem_success'] === true) {
                echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Item changes saved!
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';

                unset($_SESSION['editItem_success']);
            }

            if (isset($_SESSION['editItem_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-times-circle"></i>Failed to edit item. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['editItem_error']);
            }

            if (isset($_SESSION['editItem_image_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-times-circle"></i>Failed to edit item image. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['editItem_image_error']);
            }

            if (isset($_SESSION['editItem_imageSize_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-times-circle"></i>File size exceeds the maximum limit of 1MB..
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['editItem_imageSize_error']);
            }

            if (isset($_SESSION['deleteItem_success']) && $_SESSION['deleteItem_success'] === true) {
                echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Item successfully deleted!
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';

                unset($_SESSION['deleteItem_success']);
            }

            if (isset($_SESSION['deleteItem_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-times-circle"></i>Failed to delete item. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['deleteItem_error']);
            }

            if (isset($_SESSION['deleteItem_image_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-times-circle"></i>Failed to delete item image...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['deleteItem_image_error']);
            }

            if (isset($_SESSION['submitReviews_success']) && $_SESSION['submitReviews_success'] === true) {
                echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Successfully updated item reviews!
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';

                unset($_SESSION['submitReviews_success']);
            }

            function ratingToStars($rating) {
                // Separate integer and decimal parts of the rating
                $integer = floor($rating);
                $decimal = $rating - $integer;

                // Output full stars for the integer part
                for ($i = 0; $i < $integer; $i++) {
                    echo '<i class="fas fa-star"></i>';
                }

                if ($decimal >= 0.8) {
                    echo '<i class="fas fa-star"></i>';
                } elseif ($decimal >= 0.3) {
                    // Half star
                    echo '<i class="fad fa-star-half-alt"></i>';
                } else {
                    // No star
                    // echo '<i class="far fa-star"></i>';
                }

                // Calculate the number of stars generated
                $totalStarsGenerated = $integer;
                if ($decimal >= 0.3 && $decimal < 0.8) {
                    $totalStarsGenerated += 1;
                } elseif ($decimal >= 0.8) {
                    $totalStarsGenerated += 1;
                }

                // Calculate the number of remaining stars needed to reach 5
                $remainingStars = 5 - $totalStarsGenerated;

                // Echo out the remaining empty stars
                for ($j = 0; $j < $remainingStars; $j++) {
                    echo '<i class="far fa-star"></i>';
                }
            }

            // Fetch item from the database
            $sql = "SELECT * FROM menu_items WHERE item_ID = $item_ID";

            include 'navbar.php';
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Add form for editing the item inside the loop
                    ?>
                    <div class="container-fluid container">
                        <div class="col-12 m-auto">
                            <div class="edit_items">
                                <form method="post" enctype="multipart/form-data" class="item_edit_form">
                                    <div class="big_container">
                                        <div class="breadcrumbs">
                                            <a>Admin</a> > <a>Menu</a> > <a href="items-all.php">Item List</a> > <a class="active"><?php echo $row['item_name']; ?></a>
                                        </div>
                                        <div class='image_container'>
                                    <?php
                                        $sql2 = "SELECT * FROM menu_images WHERE image_ID = {$item_ID} LIMIT 1";
                                        $result2 = $conn->query($sql2);
                                        if ($result2->num_rows > 0) {
                                            while ($row2 = $result2->fetch_assoc()) {
                                                $image_data = $row2["data"];
                                                $mime_type = $row2["mime_type"];
                                                $base64 = base64_encode($image_data);
                                                $src = "data:$mime_type;base64,$base64";
                                                echo "<img src='".$src."' class='item_image_diplay'>";
                                            }
                                        }else{
                                            echo "<img src='../images/placeholder_image.png' class='item_image_diplay'>";
                                        }

                                    ?>
                                    <input type="file" name="image" class="upload_image" id="upload_image">
                                    <label class="upload_image_label" for="upload_image"><i class="fas fa-camera"></i></label>
                                    <?php
                                        // SQL query to select the cust_wishlist column
                                        $sql_saved = "SELECT cust_wishlist FROM customer WHERE trash = 0";
                                        $result_saved = $conn->query($sql_saved);

                                        // Associative array to store the count of occurrences of each number
                                        $wishlistCount = array();

                                        // Check if there are any rows returned
                                        if ($result_saved->num_rows > 0) {
                                            while ($row_saved = $result_saved->fetch_assoc()) {
                                                // Split the wishlist string into an array
                                                $wishlistItems = explode(",", $row_saved['cust_wishlist']);

                                                // Iterate through each wishlist item
                                                foreach ($wishlistItems as $item) {
                                                    // Trim any extra whitespace
                                                    $item = trim($item);
                                                    // Increment the count for the current item
                                                    if (!isset($wishlistCount[$item])) {
                                                        $wishlistCount[$item] = 1; // Initialize count to 1
                                                    } else {
                                                        $wishlistCount[$item]++;
                                                    }
                                                }
                                            }
                                        }

                                        // Assuming $item_ID is the ID of the item you want to check the count for
                                        $countOfItem = isset($wishlistCount[$item_ID]) ? $wishlistCount[$item_ID] : 0;

                                        // Your HTML code
                                        echo '<div class="d-flex flex-row">
                                                <div class="rating">'.number_format($row['item_rating'], 1).'<i class="fas fa-star"></i></div>
                                                <div class="sold"> | '.$row['item_sold'].' <i class="fas fa-shopping-cart"></i></div>
                                                <div class="saved"> | '.$countOfItem.'<i class="fas fa-heart"></i></div>
                                              </div>
                                            </div>';
                                    ?>

                                    
                                    <div class="item_details">
                                        <div class="page_title">Edit Item<i class="fas fa-pen"></i></div>
                                        <div class="item_detail_container">
                                            <label for="item_name">Item Name</label>
                                            <input type="text" name="item_name" id="item_name" value="<?php echo $row['item_name']; ?>" maxlength='50' required>
                                        </div>
                                        <div class="item_detail_container">
                                            <label for="item_category">Item Category</label>

                                            <?php
                                            // Query to fetch parent categories from the database
                                            $parent_categories_sql = "SELECT category_ID, category_name FROM menu_categories WHERE category_parent != 0 AND trash = 0 ORDER BY category_name ASC";
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
                                        </div>
                                        <div id="selectedCategories">
                                            <?php
                                            // Display selected categories in divs without commas
                                            foreach ($selectedCategoryIDs as $categoryId) {
                                                $category_name_sql = "SELECT category_name FROM menu_categories WHERE category_ID = $categoryId AND trash = 0";
                                                $category_name_result = $conn->query($category_name_sql);
                                                if ($category_name_result->num_rows > 0) {
                                                    $row3 = $category_name_result->fetch_assoc();
                                                    echo "<div id='category_" . $categoryId . "'>" . $row3['category_name'] . " <button class='trashButton'><i class='fas fa-times'></i></button></div>";
                                                }
                                            }
                                            ?>
                                        </div>
                                        <input type="text" name="item_category" id="item_category" value="<?php echo implode(',', $selectedCategoryIDs); ?>" style=" opacity: 0; height: 0; width: 0; top: -10px; position: absolute;" required>
                                        <script>
                                            function confirmAction(message) {
                                                return confirm("Are you sure you want to " + message + "?");
                                            }

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
                                                    trashButton.innerHTML = "<i class='fas fa-times'></i>"; // Use innerHTML to render HTML content
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
                                         </div>
                                        <div id="selectedOptions">
                                            <?php
                                            // Display selected categories in divs without commas
                                            foreach ($selectedOptionIDs as $optionId) {
                                                $option_name_sql = "SELECT custom_name FROM menu_customization WHERE custom_ID = $optionId AND trash = 0";
                                                $option_name_result = $conn->query($option_name_sql);
                                                if ($option_name_result->num_rows > 0) {
                                                    $row3 = $option_name_result->fetch_assoc();
                                                    echo "<div id='option_" . $optionId . "'>" . $row3['custom_name'] . " <button class='trashOptionButton'><i class='fas fa-times'></i></button></div>";
                                                }
                                            }
                                            ?>
                                        </div>
                                        <input type="text" name="item_options" id="item_options" value="<?php echo implode(',', $selectedOptionIDs); ?>" style=" opacity: 0; height: 0; width: 0; top: -10px; position: absolute;">
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
                                                    trashOptionButton.innerHTML = "<i class='fas fa-times'></i>";
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
                                                document.getElementById('item_options').value = selectedOptionIDs.join(',');
                                            }

                                            document.addEventListener('DOMContentLoaded', function() {
                                                const reviewContainers = document.querySelectorAll('.review_container');
                                                const perPageSelector = document.getElementById('perPage');
                                                const pagination = document.getElementById('pagination');
                                                const approvalFilter = document.getElementById('approvalFilter');
                                                const commentFilter = document.getElementById('commentFilter');
                                                const starFilter = document.getElementById('starFilter');

                                                let currentPage = 1;

                                                function showPage(pageNumber) {
                                                    const perPage = parseInt(perPageSelector.value);
                                                    const startIndex = (pageNumber - 1) * perPage;
                                                    const endIndex = startIndex + perPage;

                                                    // Hide all review containers
                                                    reviewContainers.forEach(container => {
                                                        container.style.display = 'none';
                                                    });

                                                    // Show review containers for the current page and filtered by approval status, comment status, and star rating
                                                    let displayedReviews = 0;
                                                    reviewContainers.forEach((container, index) => {
                                                        const isApproved = container.querySelector('select[name="review_approve[]"]').value === "1";
                                                        const hasComment = container.querySelector('.bottom_container') !== null;
                                                        const starElements = container.querySelectorAll('.review_stars .fa-star.fas');
                                                        const starRating = starElements.length;

                                                        if (displayedReviews >= startIndex && displayedReviews < endIndex) {
                                                            if (
                                                                (approvalFilter.value === "all" || (approvalFilter.value === "approved" && isApproved) || (approvalFilter.value === "notApproved" && !isApproved)) &&
                                                                (commentFilter.value === "all" || (commentFilter.value === "withComment" && hasComment) || (commentFilter.value === "withoutComment" && !hasComment)) &&
                                                                (starFilter.value === "all" || starRating === parseInt(starFilter.value))
                                                            ) {
                                                                container.style.display = 'flex';
                                                            }
                                                        }
                                                        if (
                                                            (approvalFilter.value === "all" || (approvalFilter.value === "approved" && isApproved) || (approvalFilter.value === "notApproved" && !isApproved)) &&
                                                            (commentFilter.value === "all" || (commentFilter.value === "withComment" && hasComment) || (commentFilter.value === "withoutComment" && !hasComment)) &&
                                                            (starFilter.value === "all" || starRating === parseInt(starFilter.value))
                                                        ) {
                                                            displayedReviews++;
                                                        }
                                                    });

                                                    const container = document.querySelector('.container');
                                                    const emptyContainer = container.querySelector('.display_reviews .no_reviews');

                                                    if (displayedReviews === 0) {
                                                        emptyContainer.classList.add('unhide');
                                                    }
                                                    else{
                                                        emptyContainer.classList.remove('unhide');
                                                    }


                                                    // Update active page button
                                                    const pageButtons = pagination.querySelectorAll('.page-button');
                                                    pageButtons.forEach(button => {
                                                        button.classList.remove('active-page');
                                                        button.classList.remove('adjacent');
                                                        if (parseInt(button.textContent) === pageNumber) {
                                                            button.classList.add('active-page');
                                                            const index = parseInt(button.textContent);
                                                            if (index > 1) {
                                                                pageButtons[index - 2].classList.add('adjacent');
                                                            }
                                                        }
                                                    });


                                                }

                                                function createPagination() {
                                                    const totalReviews = Array.from(reviewContainers).filter(container => {
                                                        const isApproved = container.querySelector('select[name="review_approve[]"]').value === "1";
                                                        const hasComment = container.querySelector('.bottom_container') !== null;
                                                        const starElements = container.querySelectorAll('.review_stars .fa-star');
                                                        const starRating = starElements.length;
                                                        return (
                                                            (approvalFilter.value === "all") || (approvalFilter.value === "approved" && isApproved) || (approvalFilter.value === "notApproved" && !isApproved)
                                                        ) && (
                                                            (commentFilter.value === "all") || (commentFilter.value === "withComment" && hasComment) || (commentFilter.value === "withoutComment" && !hasComment)
                                                        ) && (
                                                            (starFilter.value === "all") || starRating === parseInt(starFilter.value)
                                                        );
                                                    }).length;

                                                    const perPage = parseInt(perPageSelector.value);
                                                    const totalPages = Math.ceil(totalReviews / perPage);
                                                    pagination.innerHTML = '';

                                                    // Previous Button
                                                    const prevButton = document.createElement('div');
                                                    prevButton.textContent = 'Previous';
                                                    prevButton.classList.add('page-button');
                                                    prevButton.classList.add('previous-button');
                                                    prevButton.addEventListener('click', function() {
                                                        if (currentPage > 1) {
                                                            currentPage--;
                                                            showPage(currentPage);
                                                        }
                                                    });
                                                    pagination.appendChild(prevButton);

                                                    // Page Buttons
                                                    for (let i = 1; i <= totalPages; i++) {
                                                        const pageButton = document.createElement('div');
                                                        pageButton.textContent = i;
                                                        pageButton.classList.add('page-button');
                                                        pageButton.classList.add('page');
                                                        if (i === currentPage) {
                                                            pageButton.classList.add('active-page');
                                                        }
                                                        pageButton.addEventListener('click', function() {
                                                            currentPage = i;
                                                            showPage(currentPage);
                                                        });
                                                        pagination.appendChild(pageButton);
                                                    }

                                                    // Next Button
                                                    const nextButton = document.createElement('div');
                                                    nextButton.textContent = 'Next';
                                                    nextButton.classList.add('page-button');
                                                    nextButton.classList.add('next-button');
                                                    nextButton.addEventListener('click', function() {
                                                        if (currentPage < totalPages) {
                                                            currentPage++;
                                                            showPage(currentPage);
                                                        }
                                                    });
                                                    pagination.appendChild(nextButton);
                                                }

                                                perPageSelector.addEventListener('change', function() {
                                                    currentPage = 1;
                                                    showPage(currentPage);
                                                    createPagination();
                                                });

                                                approvalFilter.addEventListener('change', function() {
                                                    currentPage = 1;
                                                    showPage(currentPage);
                                                    createPagination();
                                                });

                                                commentFilter.addEventListener('change', function() {
                                                    currentPage = 1;
                                                    showPage(currentPage);
                                                    createPagination();
                                                });

                                                starFilter.addEventListener('change', function() {
                                                    currentPage = 1;
                                                    showPage(currentPage);
                                                    createPagination();
                                                });

                                                showPage(currentPage);
                                                createPagination();
                                            });


                                            document.addEventListener('DOMContentLoaded', function() {
                                                const trashLabels = document.querySelectorAll('.fa.fa-trash');

                                                trashLabels.forEach(label => {
                                                    label.addEventListener('click', function() {
                                                        const inputId = this.getAttribute('for');
                                                        const input = document.getElementById(inputId);

                                                        // Toggle the value between 0 and 1
                                                        input.value = input.value === '0' ? '1' : '0';

                                                        const reviewContainer = input.closest('.review_container');

                                                        // Toggle the 'delete' class based on input value
                                                        if (input.value === '1') {
                                                            reviewContainer.classList.add('delete');
                                                        } else {
                                                            reviewContainer.classList.remove('delete');
                                                        }
                                                    });
                                                });

                                                const filterSelectors = document.querySelector('.filter_selectors');
                                                const slidersIcon = document.querySelector('.no_page .fa-sliders-h');
                                                const closeIcon = document.querySelector('.filters .fa-times');

                                                slidersIcon.addEventListener('click', function() {
                                                    filterSelectors.classList.add('opened');
                                                });


                                                closeIcon.addEventListener('click', function() {
                                                    filterSelectors.classList.remove('opened');
                                                });
                                            });
                                        </script>
                                        <div class="item_detail_container">
                                            <label for="item_price">Item Price</label>
                                            <input type="number" min='0' name="item_price" id="item_price" step="any" value="<?php echo $row['item_price']; ?>" required>
                                        </div>
                                        <div class="item_detail_container">
                                            <label for="item_discount">Item Discount</label>
                                            <input type="number" min='0' name="item_discount" id="item_discount" value="<?php echo $row['item_discount']; ?>" required>
                                        </div>
                                        <div class="item_detail_container">
                                            <label for="item_description" style="margin-bottom: auto;">Item Description</label>
                                            <textarea name="item_description" id="item_description" rows="4" cols="50" required><?php echo $row['item_description']; ?></textarea>
                                        </div>
                                        <div class="item_detail_container">
                                            <label for="item_availability">Item Availability</label>
                                            <select name="item_availability" id="item_availability">
                                                <option value="1" <?= $row['item_availability'] == '1' ? 'selected' : '' ?>>Available</option>
                                                <option value="0" <?= $row['item_availability'] == '0' ? 'selected' : '' ?>>Not Available</option>
                                            </select>
                                        </div>
                                        <div class="submit_buttons">
                                            <input type="submit" name="edit_submit" class="edit_submit" value="Save">
                                            <input type="submit" name="delete" class="delete" value="Delete" onclick='return confirmAction("delete this item");'>
                                        </div>
                                    </div>
                                    </div>
                                </form>
                                <form method="post" class="justify-content-center d-flex">
                                    <div class="reviews">
                                        <div class="d-flex align-items-baseline">
                                            <div class="page_title">Reviews<i class="fas fa-comment-alt-dots title_icon"></i></div>
                                            <div class="text-nowrap">
                                                <div class="no_page">
                                                    <label for="perPage" id="perPageLabel">Show per page</label>
                                                    <select id="perPage">
                                                        <option value="4">4</option>
                                                        <option value="8" selected>8</option>
                                                        <option value="16">16</option>
                                                    </select>
                                                    <i class="fas fa-sliders-h"></i>
                                                </div>
                                            </div>
                                            <div class="filters">
                                                <div class="filter_selectors" style="color: #495057;">
                                                    <div class="d-flex justify-content-between flex-row align-items-center">
                                                        <div class="d-flex flex-row align-items-baseline">
                                                            <i class="fas fa-sliders-h"></i><span>Filters</span>
                                                        </div>
                                                        <div>
                                                            <i class="fas fa-times"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label for="approvalFilter">Filter by Approval</label>
                                                        <select id="approvalFilter">
                                                            <option value="all">All</option>
                                                            <option value="approved">Approved</option>
                                                            <option value="notApproved">Not Approved</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="commentFilter">Filter by Comment</label>
                                                        <select id="commentFilter">
                                                            <option value="all">All</option>
                                                            <option value="withComment">With Comment</option>
                                                            <option value="withoutComment">No Comment</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="starFilter">Filter by Stars</label>
                                                        <select id="starFilter">
                                                            <option value="all">All</option>
                                                            <option value="1">1 Star</option>
                                                            <option value="2">2 Stars</option>
                                                            <option value="3">3 Stars</option>
                                                            <option value="4">4 Stars</option>
                                                            <option value="5">5 Stars</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="display_reviews">
                                        <?php
                                            $sql_reviews = "SELECT * FROM customer_reviews WHERE item_ID = {$row['item_ID']} AND trash = 0 ORDER BY review_ID DESC";
                                            $result_reviews = $conn->query($sql_reviews);

                                            if ($result_reviews->num_rows > 0) {
                                                while ($row_reviews = $result_reviews->fetch_assoc()) {
                                                    $sql_customer = "SELECT cust_username FROM customer WHERE cust_ID = {$row_reviews['cust_ID']} AND trash = 0";
                                                    $result_customer = $conn->query($sql_customer);

                                                    echo "<input type='hidden' name='review_ID[]' value='".$row_reviews['review_ID']."'>";

                                                    echo '<div class="review_container"><div class="review_details"><div class="top_container"><img src="../images/icons/user.png"><div><span>'.$row_reviews['review_title'].'</span><div class="container"><div class="review_stars">';
                                                    ratingToStars($row_reviews['review_rating']);
                                                    echo '</div>
                                                    <div class="review_date">'.$row_reviews['review_date'].'</div></div></div></div>';
                                                    if(!empty($row_reviews['review_comment'])){
                                                        echo '<div class="bottom_container"><i class="fas fa-quote-left"></i>'.$row_reviews['review_comment'].'<i class="fas fa-quote-right"></i></div>';
                                                    }

                                                    echo '</div><div class="review_bottom">';

                                                    $sql_review_user = "SELECT cust_username FROM customer WHERE cust_ID = {$row_reviews['cust_ID']} AND trash = 0 LIMIT 1";

                                                    $result_review_user = $conn->query($sql_review_user);
                                                    if ($result_review_user->num_rows > 0) {
                                                        while ($row_review_user = $result_review_user->fetch_assoc()) {
                                                            echo '<div class="username">-'.$row_review_user['cust_username'].'</div>';
                                                        }
                                                    }
                                                    else{
                                                        echo '<div class="username">-Deleted User</div>';
                                                    }
                                                    echo "<div class='review_edit'><select name='review_approve[]' id='review_approve'>
                                                            <option value='1' " . ($row_reviews['review_approve'] == '1' ? 'selected' : '') . ">Approved</option>
                                                            <option value='0' " . ($row_reviews['review_approve'] == '0' ? 'selected' : '') . ">Not Approved</option>

                                                            <input type='hidden' value='0' name='trash[]' id='trash_" . $row_reviews['review_ID'] . "'>
                                                            <label class='fa fa-trash' for='trash_". $row_reviews['review_ID'] . "'></label>
                                                            </div>
                                                            </div>
                                                            </div>";
                                                }
                                                echo'<div class="no_reviews ';
                                            }
                                            else{
                                                 echo '<div class="no_reviews';
                                            }
                                            echo'"><i class="far fa-ghost"></i>No reviews...</div>';
                                        ?>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center page_bottom">
                                            <div id="pagination"></div>
                                            <input type="submit" name="approve_submit" id="approve_submit" value="Submit Changes" onclick='return confirmAction("save these changes");'>
                                        </div>
                                        <a href="items-all.php" class="back_button">Back To List</a>
                                    </div>
                                </form>
                            <?php
                            }
                        } else {
                            echo "No item found";
                        }

                        $conn->close();
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
