<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
        <link rel="icon" href="images/logo/logo_icon.png">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="script.js"></script>
        <script src="gototop.js"></script>
        <?php
            include 'connect.php';

            if (isset($_GET['ID'])) {
                $item_ID = $_GET['ID'];
            }
            $sql_getItemName = "SELECT item_name FROM menu_items WHERE item_ID = $item_ID AND trash = 0 LIMIT 1";
            
            $result_getItemName = $conn->query($sql_getItemName);
            if ($result_getItemName->num_rows > 0) {
                 while ($row_getItemName = $result_getItemName->fetch_assoc()) {
                    echo '<title>'.$row_getItemName['item_name'].' | Kocha Café</title>';
                 }
            }
            // // Close connection
            // $conn->close();
    
        ?>
    </head>
    <body>
        <?php
            include 'top.php';
            include 'gototopbtn.php';

            $sql_getWishlist = "SELECT cust_wishlist FROM customer WHERE cust_ID = $cust_ID AND trash = 0 LIMIT 1";

            $found_wishlisted_item = 0;
            $updated_wishlist = ''; // Variable to store the updated wishlist
            $result_getWishlist = $conn->query($sql_getWishlist);
            if ($result_getWishlist->num_rows > 0) {
                while ($row_getWishlist = $result_getWishlist->fetch_assoc()) {
                    $saved_items = explode(',', $row_getWishlist['cust_wishlist']);
                    foreach ($saved_items as $key => $saved_item) {
                        if($saved_item == $item_ID){
                            unset($saved_items[$key]); // Remove the item_ID from the array
                            $found_wishlisted_item = 1;
                        }
                    }
                    $updated_wishlist = implode(',', $saved_items);
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if(isset($_POST['submit_review'])) {
                    // Retrieve form data
                    $review_rating = $_POST['review_rating'];
                    $review_title = $_POST['review_title'];
                    $review_comment = $_POST['review_comment'];
                    $review_date = date('Y-m-d');

                    // Construct the SQL query to insert menu item data
                    $sql_insert = "INSERT INTO customer_reviews (review_rating, review_title, review_comment, review_date, item_ID, cust_ID, review_approve) VALUES ('$review_rating', '$review_title', '$review_comment', '$review_date', '$item_ID', '$cust_ID', '0')";

                    // Execute the SQL query
                    if ($conn->query($sql_insert) === TRUE) {
                        header("Location: item.php?ID=".$item_ID);
                        exit();
                        // echo "New record created successfully";
                    } else {
                        echo "Error: " . $sql_insert . "<br>" . $conn->error;
                    }
                } elseif(isset($_POST['add_button'])) {
                    $item_cart = "";
                    $sql_cart_check = "SELECT cust_cart FROM customer WHERE cust_ID = $cust_ID";

                    $result_cart_check = $conn->query($sql_cart_check);
                    if ($result_cart_check->num_rows > 0) {
                        while ($row_cart_check = $result_cart_check->fetch_assoc()) {
                            $cust_cart_value = $row_cart_check['cust_cart'];
                            if(!empty($cust_cart_value)){
                                $item_cart = $cust_cart_value . ",";
                            }
                        }
                    }

                    if(!empty($_POST['item_customization_ID'])){
                        $item_customization_IDs = $_POST['item_customization_ID'];
                        $item_customization_values = $_POST['item_customization_value'];
                    }
                    
                    $item_quantity = $_POST['item_quantity'];
                    $item_request = $_POST['item_request'];

                    $item_cart .= "{(".$item_ID."),(".$item_quantity."),(".$item_request."),(";

                    if(!empty($_POST['item_customization_ID'])){
                        for($i = 0; $i < count($item_customization_values); $i++) {
                            $item_customization_value = $item_customization_values[$i];
                            $item_customization_ID = $item_customization_IDs[$i];

                            $item_cart .= "[".$item_customization_ID .",". $item_customization_value ."]";

                            if(isset($item_customization_IDs[$i + 1])) {
                                $item_cart .= ",";
                            }
                        }
                    }
                    $item_cart .= ")}";

                    $sql_cart = "UPDATE customer SET cust_cart = '$item_cart' WHERE cust_ID = $cust_ID";

                    // Execute the SQL query
                    if ($conn->query($sql_cart) === TRUE) {
                        header("Location: item.php?ID=".$item_ID);
                        exit();
                        // echo "New record created successfully";
                    } else {
                        echo "Error: " . $sql_cart . "<br>" . $conn->error;
                    }
                  
                } else if(isset($_POST['submit_wishlist'])) {
                    if(empty($user)){
                        header("Location: login.php");
                        exit();
                    }

                    if($found_wishlisted_item == 1){
                        $sql_updateWishlist = "UPDATE customer SET cust_wishlist = '$updated_wishlist' WHERE cust_ID = $cust_ID";
                    }
                    else{
                        if($updated_wishlist == ""){
                            $updated_wishlist .= $item_ID;
                        }else{
                            $updated_wishlist .= ",".$item_ID;
                        }
                        $sql_updateWishlist = "UPDATE customer SET cust_wishlist = '$updated_wishlist' WHERE cust_ID = $cust_ID";
                    }

                    if ($conn->query($sql_updateWishlist) === TRUE) {
                        header("Location: item.php?ID=".$item_ID);
                        exit();
                    } else {
                        echo "Error updating wishlist: " . $conn->error;
                    }
                }
            }

             function getSubCategories2($category_ID, $conn) {
                $sub_categories = array(); // Initialize array to store sub-categories
                $sql_sub = "SELECT * FROM menu_categories WHERE category_parent = '$category_ID' AND trash = 0";
                $result_sub = $conn->query($sql_sub);
                
                if ($result_sub->num_rows > 0) {
                    while ($row_sub = $result_sub->fetch_assoc()) {
                        $sub_categories[] = $row_sub['category_ID']; // Add sub-category ID to the array
                        // Recursively call the function for sub-categories and merge their results
                        $sub_categories = array_merge($sub_categories, getSubCategories2($row_sub['category_ID'], $conn));
                    }
                }
                return $sub_categories;
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

            $sql = "SELECT * FROM menu_items WHERE item_ID = $item_ID AND trash = 0 LIMIT 1";

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
            $sql_reviews = "SELECT * FROM customer_reviews WHERE item_ID = $item_ID AND review_approve = 1 AND trash = 0";
            $result_reviews = $conn->query($sql_reviews);

            $item_name_review = "";
        ?>
        <script>
            $(document).ready(function() {
              $('.minus').click(function() {
                var $input = $(this).parent().find('input');
                var count = parseInt($input.val()) - 1;
                count = count < 1 ? 1 : count;
                $input.val(count);
                $input.change();
                return false;
              });
              $('.plus').click(function() {
                var $input = $(this).parent().find('input');
                $input.val(parseInt($input.val()) + 1);
                $input.change();
                return false;
              });
            });
        </script>
        <div class="item">
            <div class="container-fluid container">
                <div class="col-12 m-auto">
                    <div class="breadcrumbs">
                        <a href="index.php">Home</a> > <a href="menu.php">Menu</a> > <a class="active"><?php echo $row['item_name']; ?></a>
                    </div>
                    <div class="row d-flex justify-content-between pb-1">
                        <div class="col-0 col-md-5 mx-auto" style="transition: 0.5s;">
                            <div class="item_image_container">
                                <?php
                                    echo'
                                    <img src='.$src.' class="item_image">';
                                ?>

                            </div>
                        </div>
                        <div class="col-12 col-md-7 mx-auto pb-md-0 pb-4"  style="transition: 0.5s;">
                            <div class="justify-content-between d-flex align-items-center">
                            <?php
                                $item_name_review = $row['item_name'];

                                echo'
                                <div class="item_title">'.$row['item_name'].'</div>
                                <form id="wishlistForm" method="post">';

                                echo'<button type="submit" name="submit_wishlist" id="submit_wishlist" class="submit_wishlist">';

                                    if($found_wishlisted_item == 1){
                                        echo '<a class="item_saved saved" id="item_saved"><i class="far fa-heart"></i></a>';
                                    }
                                    else{
                                        echo '<a class="item_saved" id="item_saved"><i class="far fa-heart"></i></a>';
                                    }

                                echo'</button>
                                </form></div>';

                                echo '<div class="rating">';

                                ratingToStars($row['item_rating']);

                                echo '('.number_format($row['item_rating'],1).')
                                </div>
                                <div class="no_ratings">'.$result_reviews->num_rows.' Ratings</div>
                                <div class="no_ratings">'.$row['item_sold'].' Sold</div>
                                <div class="item_price">';

                                if ($row['item_discount'] > 0) {
                                    $discounted_price = $row['item_price'] * ((100 - $row['item_discount']) * 0.01);
                                    echo '<div class="discount">-'.$row['item_discount'].'%</div>';
                                    echo '<div class="price">RM ' . number_format($discounted_price, 2) . '</div>';
                                    echo '<div class="price_crossed">RM ' . number_format($row['item_price'], 2) . '</div>';
                                }
                                else{
                                    echo '<div class="price">RM '.$row['item_price'].'</div>';
                                }
                                echo '</div>';
                                echo '<form id="addForm" method="post">';
                                echo '<div class="item_details">';

                                $sql_sub_cate = "SELECT * FROM menu_categories WHERE category_parent = 0  AND trash = 0 ";
                                $result_sub_cate = $conn->query($sql_sub_cate);

                                $category_results = array(); // Initialize array to store results for each category
                                if ($result_sub_cate->num_rows > 0) {
                                    while ($row_sub_cate = $result_sub_cate->fetch_assoc()) {
                                        $category_results[$row_sub_cate['category_ID']] = getSubCategories2($row_sub_cate['category_ID'], $conn);
                                    }
                                }
                                foreach ($category_results as $root_category_id => $sub_categories) {

                                    $get_parent_category = "SELECT * FROM menu_categories WHERE category_ID = {$root_category_id}  AND trash = 0  LIMIT 1";
                                    $result_parent_category = $conn->query($get_parent_category);

                                    if ($result_parent_category->num_rows > 0) {
                                        while ($row_parent_category = $result_parent_category->fetch_assoc()) {
                                            echo '<div class="item_attribute"><div class="item_attribute_container">
                                            '.$row_parent_category['category_name'].'
                                            </div><div class="item_tag_container">';
                                        }
                                    }

                                    $categories = $row['item_category'];
                                    $category_array = explode(',', $categories);

                                    $empty_category_flag = 0; // Flag to check if any category is empty

                                    foreach ($category_array as $category_ID) {
                                        if (in_array($category_ID, $sub_categories)) {
                                            $get_category_name = "SELECT * FROM menu_categories WHERE category_ID = {$category_ID}  AND trash = 0  LIMIT 1";
                                            $result_category_name = $conn->query($get_category_name);

                                            if ($result_category_name->num_rows > 0) {
                                                while ($row_category_name = $result_category_name->fetch_assoc()) {
                                                    echo '<div class="item_tag">
                                                        '.$row_category_name['category_name'].'
                                                    </div>';
                                                    $empty_category_flag = 1; // Set flag to false if category is not empty
                                                }
                                            }
                                        }
                                    }

                                    if ($empty_category_flag == 0) {
                                        echo '<div class="item_tag">None</div>';
                                    }
                                    echo "</div></div>";
                                }

                                echo '<div class="item_attribute">
                                    <div class="item_attribute_container">Description</div>
                                    <div style="color:black; font-weight: 400;">'.$row['item_description'].'</div>
                                </div>
                                <hr>
                                <span class="options">Available Options</span>';

                                $item_custom_options = [];
                                if ($row['item_options']) {
                                    $item_custom_options = explode(',', $row['item_options']);
                                }

                                foreach ($item_custom_options as $customOptionId) {
                                    $option_name_sql = "SELECT * FROM menu_customization WHERE custom_ID = $customOptionId AND trash = 0 ";
                                    $option_name_result = $conn->query($option_name_sql);
                                    if ($option_name_result->num_rows > 0) {
                                        $row3 = $option_name_result->fetch_assoc();
                                        $custom_options_string = $row3['custom_options'];

                                        echo '<div class="item_attribute options">
                                        <div class="item_attribute_container">'. $row3['custom_name'].'</div>
                                        <input type="hidden" name="item_customization_ID[]" value='. $row3['custom_ID'] . '>
                                        <select name="item_customization_value[]" class="option_choices" required>
                                        <option id="select_default" value="" selected disabled>Select...</option>';

                                        // Extracting option choice and price using regular expressions
                                        preg_match_all('/\("([^"]+)",([\d.]+)\)/', $custom_options_string, $matches, PREG_SET_ORDER);

                                        // $matches will contain all the matches found in the string
                                        foreach ($matches as $match) {
                                            $option_choice = $match[1];
                                            $option_price = $match[2];

                                            echo "<option value='$option_choice'> $option_choice (+RM $option_price)</option>";
                                        }

                                        echo '
                                        </select></div>';
                                    }
                                }
                                echo '<div class="item_attribute options"><div class="item_attribute_container">Quantity</div>
                                    <div class="number">';
                                        if($row['item_availability'] == 0){
                                            echo '<span class="minus disabled">-</span>';
                                            echo '<input type="text" value="0" class="disabled"/>';
                                            echo '<span class="plus disabled">+</span>';
                                            echo '<div class="pl-2">Item is currently unnavailable.</div>';
                                        }
                                       else{
                                            echo '<span class="minus">-</span>';
                                            echo '<input name="item_quantity" id="item_quantity" type="text" value="1"/>';
                                            echo '<span class="plus">+</span>';
                                       }
                                        
                                    echo '</div>
                                </div>';
                                echo '<div class="item_attribute options">
                                    <div class="item_attribute_container">Extra Requests</div>';
                                    if($row['item_availability'] == 0){
                                        echo '<input name="item_request" id="item_request" class="disabled"></textarea>';
                                    }
                                    else{
                                        echo '<textarea name="item_request" id="item_request"></textarea>';
                                    }
                                echo '</div>';
                                echo '</div>';
                            ?>
                            <input type="submit" name="add_button" id="add_button" value="Add to Cart" class="cart_button <?php if($row['item_availability'] == 0){ echo 'disabled'; } ?>">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            echo '
                </div>';
                }
            }
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var sql_reviews = "SELECT * FROM customer_reviews WHERE item_ID = $item_ID ";

                var selectStars = document.querySelector('select[name="ratings_stars"]');
                var selectComments = document.querySelector('select[name="ratings_comments"]');
                var filterStateInput = document.getElementById('filterStateInput');
                var ratingForm = document.getElementById('ratingForm');

               // Load saved filter state from localStorage, if any
                var savedFilterState = localStorage.getItem('filterState');
                if (savedFilterState) {
                    var filterState = JSON.parse(savedFilterState);
                    selectStars.value = filterState.starsSelected;
                    selectComments.value = filterState.commentsSelected;
                }
                else{
                    selectStars.value = "";
                    selectComments.value = "";
                }

                selectStars.addEventListener('change', function() {
                    if (selectStars.value !== "0") {
                        sql_reviews += " AND review_rating = " + selectStars.value;
                    }
                    saveFilterState();
                    ratingForm.submit();
                    setTimeout(clearFilterState, 1000);

                });

                selectComments.addEventListener('change', function() {
                    if (selectComments.value === "0") {
                        sql_reviews += "";
                    } else if (selectComments.value === "1") {
                        sql_reviews += " AND review_comment != ''";
                    } else if (selectComments.value === "2") {
                        sql_reviews += " AND review_comment = ''";
                    }
                    saveFilterState();
                    ratingForm.submit();
                    setTimeout(clearFilterState, 1000);

                });

                function saveFilterState() {
                    var filterState = {
                        starsSelected: selectStars.value,
                        commentsSelected: selectComments.value
                    };
                    localStorage.setItem('filterState', JSON.stringify(filterState));
                }

                if (selectStars.value == "" && selectComments.value == ""){
                }
                else if (!(selectStars.value == "" || selectComments.value == "")){
                    scrollToElement();
                }

                if (selectStars.value == ""){
                    selectStars.value = "0";
                }
                if (selectComments.value == ""){
                    selectComments.value = "0";
                }

                function scrollToElement() {
                    window.addEventListener('DOMContentLoaded', () => {
                        // select the whole html & disable smooth-scroll behavior in css
                        let htmlElement = document.querySelector('html');
                        htmlElement.style.scrollBehavior = 'auto';

                        // go to the anchor point
                        let reviewsSection = document.getElementById('reviews_section');
                        reviewsSection.scrollIntoView({ block: 'start' });
                        
                        // Subtract 80px from the scroll position
                        window.scrollBy(0, -80);

                        // enable smooth-scroll behavior again
                        htmlElement.style.scrollBehavior = 'auto';
                    });
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                var stars = document.querySelectorAll('.rate_stars .fa-star');

                stars.forEach(function(star) {
                    star.addEventListener('mouseenter', function() {
                        var index = Array.from(stars).indexOf(star);
                        for (var i = 0; i <= index; i++) {
                            stars[i].classList.add('hovered');
                        }
                    });

                    star.addEventListener('click', function() {
                        stars.forEach(function(star) {
                            star.classList.remove('clicked');
                        });

                        var index = Array.from(stars).indexOf(star);
                        for (var i = 0; i <= index; i++) {
                            stars[i].classList.add('clicked');
                        }
                    });

                    star.addEventListener('mouseleave', function() {
                        stars.forEach(function(star) {
                            star.classList.remove('hovered');
                        });
                    });
                });
            });

            function insertStar() {
                // Retrieve the ID of the clicked star
                var starId = event.target.id;

                // Set the value of the review_rating input field to the star number
                document.getElementById('review_rating').value = starId;
            }

            document.addEventListener('DOMContentLoaded', function() {
                var button_reviews = document.querySelectorAll('.review_button');
                var cancel_reviews = document.querySelectorAll('[name="cancel_review"]');
                var review_writes = document.querySelectorAll('.write_review .container');
                var darken_review = document.querySelectorAll('.review_darken');

                button_reviews.forEach(function(button_review, index) {
                    button_review.addEventListener('click', function() {
                        review_writes[index].classList.add('active');
                        darken_review[index].classList.add('appear');
                    });
                });

                cancel_reviews.forEach(function(cancel_review, index) {
                    cancel_review.addEventListener('click', function() {
                        review_writes[index].classList.remove('active');
                        darken_review[index].classList.remove('appear');
                    });
                });
            });

            function clearFilterState() {
                localStorage.removeItem('filterState');
                // selectStars.value = "";
                // selectComments.value = "";
            }
        </script>
        <div class="reviews" id="reviews_section">
            <div class="container-fluid container">
                <div class="col-12 m-auto">
                    <span class="reviews_title"><i class="fas fa-comment-alt-dots title_icon"></i>Customer Reviews</span>
                    <hr>
                    <div class="reviews_top">
                        <div class="overall_ratings">
                        <?php
                            // Check if filter options are set in POST request
                            if(isset($_GET['ratings_stars']) && isset($_GET['ratings_comments'])) {
                                $stars = $_GET['ratings_stars'];
                                $comments = $_GET['ratings_comments'];

                                // Construct SQL query based on filter options
                                if($stars !== "0") {
                                    $sql_reviews .= " AND review_rating = $stars";
                                }
                                if($comments === "1") {
                                    $sql_reviews .= " AND review_comment != ''";
                                } elseif($comments === "2") {
                                    $sql_reviews .= " AND review_comment = ''";
                                }
                            }

                            $result_reviews = $conn->query($sql_reviews);

                            // $overall_rating = 0;
                            $sql = "SELECT * FROM menu_items WHERE item_ID = $item_ID AND trash = 0 LIMIT 1";

                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<div class="value">'.number_format($row['item_rating'],1).'</div><div class="stars">';
                                    // echo '<div class="value">'.number_format($row['item_rating'],1).'</div><div class="stars">';
                                    ratingToStars($row['item_rating']);
                                    echo '</div><div class="no_ratings">'.$result_reviews->num_rows.' Reviews</div>';
                                }
                            }
                        ?>
                        </div>
                        <form id="reviewForm" action="item.php?ID=<?php echo $item_ID; ?>" method="post">
                            <div class="write_review">
                                <div class="container">
                                <?php
                                    echo'<div class="title">Rate and Review</div>';
                                    echo'<img src='.$src.' class="item_image">';
                                    echo'<div class="name">'.$item_name_review.'</div>';
                                    echo'<span class="rate_stars">
                                        <a class="far fa-star" id="1" onclick="insertStar();"></a>
                                        <a class="far fa-star" id="2" onclick="insertStar();"></a>
                                        <a class="far fa-star" id="3" onclick="insertStar();"></a>
                                        <a class="far fa-star" id="4" onclick="insertStar();"></a>
                                        <a class="far fa-star" id="5" onclick="insertStar();"></a>
                                    </span>';
                                    echo'<input type="text" name="review_rating" id="review_rating" required><input type="text" name="review_title" id="review_title" placeholder="Review title..." maxlength="25" required>';
                                    echo'<textarea name="review_comment" id="review_comment" placeholder="Review Contents... (Optional)"></textarea>';
                                    echo'<div class="buttons">
                                        <div name="cancel_review" id="cancel_review">Cancel</div>
                                        <input type="submit" name="submit_review" id="submit_review">
                                    </div>';
                                ?>
                                </div>
                            </div>
                        </form>
                        <div class="ratings_filter">
                        <form id="ratingForm" action="item.php?ID=<?php echo $item_ID; ?>" method="get">
                            <?php
                                if(!empty($user)){
                                    $sql_review_limit = "SELECT * FROM customer_reviews WHERE item_ID = $item_ID AND cust_ID = $cust_ID AND trash = 0";

                                    $result_review_limit = $conn->query($sql_review_limit);
                                    if ($result_review_limit->num_rows > 0) {
                                        while ($row_review_limit = $result_review_limit->fetch_assoc()) {
                                        }
                                    }
                                    else{
                                        echo '<div class="review_button">Write Review</div>';
                                    }
                                }
                               
                            ?>
                            <div><input type="hidden" name="ID" id="ID" value="<?php echo $item_ID; ?>">
                                <select name="ratings_stars" id="ratings_stars">
                                    <option value="0">All ratings</option>
                                    <option value="1">1 Star</option>
                                    <option value="2">2 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="5">5 Stars</option>
                                </select>
                                <select name="ratings_comments" id="ratings_comments">
                                    <option value="0">All reviews</option>
                                    <option value="1">With comment</option>
                                    <option value="2">No comment</option>
                                </select>
                            </div>
                            <input type="hidden" name="filterState" id="filterStateInput">
                            <button type="submit" name="submit_filter" id="submit_filter" class="submit_filter" style="display: none;">Apply Filters</button>
                        </form>
                    </div>
                    </div>
                    <div class="display_reviews">
                        <?php
                            if ($result_reviews->num_rows > 0) {
                                while ($row_reviews = $result_reviews->fetch_assoc()) {
                                    echo '<div class="review_container fade_in"><div class="top_container"><img src="images/icons/user.png"><div><span>'.$row_reviews['review_title'].'</span><div class="container"><div class="review_stars">';
                                    ratingToStars($row_reviews['review_rating']);
                                    echo '</div>
                                    <div class="review_date">'.$row_reviews['review_date'].'</div></div></div></div>';
                                    if(!empty($row_reviews['review_comment'])){
                                        echo '<div class="bottom_container"><i class="fas fa-quote-left"></i>'.$row_reviews['review_comment'].'<i class="fas fa-quote-right"></i></div>';
                                    }

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
                                    echo '</div>';
                                    // $overall_rating += $row_reviews['review_rating'];
                                }
                            }
                            else{
                                echo '<div class="no_reviews"><div><i class="far fa-ghost"></i>No Reviews.</div></div>';
                            }
                            // echo number_format($overall_rating/$result_reviews->num_rows,1);
                        ?>
                    </div>
                    <?php
                        function getCategoryLevel($category_ID, $categoryParent_ID, $conn, &$level) {
                            $sql = "SELECT * FROM menu_categories WHERE category_ID = {$categoryParent_ID} AND trash = 0 ";
                            $result = $conn->query($sql);
                            
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $level++; // Increment the level for each parent category
                                    getCategoryLevel($category_ID, $row['category_parent'], $conn, $level);
                                }
                            }
                        }

                        $sortedCategories = array(); // Array to store category IDs sorted by level

                        $sql6 = "SELECT * FROM menu_items WHERE item_ID = {$_GET['ID']} AND trash = 0 ";
                        $result6 = $conn->query($sql6);

                        if ($result6 && $result6->num_rows > 0) {
                            while ($row6 = $result6->fetch_assoc()) {
                                $categories = $row6['item_category'];
                                $category_array = explode(',', $categories);

                                foreach ($category_array as $category_ID) {
                                    $sql7 = "SELECT * FROM menu_categories WHERE category_ID = {$category_ID} AND trash = 0 ";
                                    $result7 = $conn->query($sql7);

                                    if ($result7 && $result7->num_rows > 0) {
                                        $level = 1; // Reset level for each category
                                        while ($row7 = $result7->fetch_assoc()) {
                                            getCategoryLevel($category_ID, $row7['category_parent'], $conn, $level);
                                            $sortedCategories[$category_ID] = $level; // Store category ID and its level
                                        }
                                    }
                                }
                            }
                        }
                    ?>
                    <div class="recommended menu">
                        <div class="recommended_title"><i class="fas fa-grin-hearts title_icon"></i>You Might Also Like</div>
                        <hr>
                        <div class="menu_items">
                            <?php
                                // Sort the categories based on their levels
                                arsort($sortedCategories);

                                // Initialize an array to keep track of processed item IDs
                                $processedItemIDs = array();

                                // Initialize a counter to keep track of the total processed item IDs
                                $totalProcessedItemIDs = 0;

                                // Output the sorted category IDs
                                foreach ($sortedCategories as $category_ID => $level) {
                                    // echo "Category ID: $category_ID, Level: $level <br>";

                                    $sql = "SELECT * FROM menu_items WHERE item_category AND trash = 0 ";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            // Example string containing category IDs
                                            $categoryString = $row['item_category'];

                                            // Explode the string into an array
                                            $categoryArray = explode(',', $categoryString);

                                            if (in_array($category_ID, $categoryArray)) {
                                                // Check if the item ID has already been processed and the total count is less than 10
                                                if (!in_array($row['item_ID'], $processedItemIDs) && $totalProcessedItemIDs < 10 && $row['item_ID'] != $item_ID) {
                                                    $sql9 = "SELECT * FROM menu_items WHERE item_availability = 1 AND item_ID = {$row['item_ID']} AND trash = 0  LIMIT 1";
                                                    $result9 = $conn->query($sql9);

                                                    if ($result9->num_rows > 0) {
                                                        while($row9 = $result9->fetch_assoc()) {

                                                            $sql2 = "SELECT * FROM menu_images WHERE image_ID = {$row9['item_ID']} AND trash = 0 LIMIT 1";
                                                            $result2 = $conn->query($sql2);
                                                            while ($row2 = $result2->fetch_assoc()) {
                                                                $image_data = $row2["data"];
                                                                $mime_type = $row2["mime_type"];
                                                                $base64 = base64_encode($image_data);
                                                                $src = "data:$mime_type;base64,$base64";
                                                            }

                                                            echo '<a class="item_container" href="item.php?ID=' . $row9['item_ID'] . '" onclick="clearFilterState()">';
                                                            if($row9['item_discount'] > 0){
                                                                echo '<div class="discount"> '.$row9['item_discount'].'% DISCOUNT</div>';
                                                            }
                                                            if ($row9['item_availability'] == 0) {
                                                                echo'<div class="sold_out">SOLD OUT</div>';
                                                            }
                                                            echo '
                                                                <img src='.$src.'>
                                                                <div class="item_text">
                                                                    <div class="title">'.$row9['item_name'].'</div>';
                                                                    if ($row9['item_discount'] > 0) {
                                                                        $discounted_price = $row9['item_price'] * ((100 - $row9['item_discount']) * 0.01);
                                                                        echo '<div class="price">RM ' . number_format($discounted_price, 2) . '</div>';
                                                                        echo '<div class="price_crossed">RM ' . number_format($row9['item_price'], 2) . '</div>';
                                                                    }

                                                                    else{
                                                                        echo '<div class="price">RM '.$row9['item_price'].'</div>';
                                                                    }

                                                                    echo '<div class="rating">';

                                                                    ratingToStars($row9['item_rating']);

                                                                    echo '('.number_format($row9['item_rating'],1).')
                                                                    </div>
                                                                </div>
                                                            </a>';
                                                        }
                                                    }
                                                    // Add the processed item ID to the list
                                                    $processedItemIDs[] = $row['item_ID'];
                                                    // Increment the total count of processed item IDs
                                                    $totalProcessedItemIDs++;
                                                }
                                                // If the total count reaches 10, break out of the loop
                                                if ($totalProcessedItemIDs >= 10) {
                                                    break 2; // Break both the inner and outer loop
                                                }
                                            }
                                        }
                                    }
                                    echo '<br>';
                                }

                                // If no matches are found, choose the first 10 items by item_ID
                                if ($totalProcessedItemIDs == 0) {
                                    $sql = "SELECT * FROM menu_items WHERE trash = 0 LIMIT 10";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            // Check if the item ID has already been processed
                                            if (!in_array($row['item_ID'], $processedItemIDs) && $row['item_ID'] != $item_ID) {
                                                $sql9 = "SELECT * FROM menu_items WHERE item_availability = 1 AND item_ID = {$row['item_ID']} AND trash = 0 LIMIT 1";
                                                    $result9 = $conn->query($sql9);

                                                    if ($result9->num_rows > 0) {
                                                        while($row9 = $result9->fetch_assoc()) {

                                                            $sql2 = "SELECT * FROM menu_images WHERE image_ID = {$row9['item_ID']} AND trash = 0 LIMIT 1";
                                                            $result2 = $conn->query($sql2);
                                                            while ($row2 = $result2->fetch_assoc()) {
                                                                $image_data = $row2["data"];
                                                                $mime_type = $row2["mime_type"];
                                                                $base64 = base64_encode($image_data);
                                                                $src = "data:$mime_type;base64,$base64";
                                                            }

                                                            echo '<a class="item_container" href="item.php?ID=' . $row9['item_ID'] . '" onclick="clearFilterState()">';
                                                            if($row9['item_discount'] > 0){
                                                                echo '<div class="discount"> '.$row9['item_discount'].'% DISCOUNT</div>';
                                                            }
                                                            if ($row9['item_availability'] == 0) {
                                                                echo'<div class="sold_out">SOLD OUT</div>';
                                                            }
                                                            echo '
                                                                <img src='.$src.'>
                                                                <div class="item_text">
                                                                    <div class="title">'.$row9['item_name'].'</div>';
                                                                    if ($row9['item_discount'] > 0) {
                                                                        $discounted_price = $row9['item_price'] * ((100 - $row9['item_discount']) * 0.01);
                                                                        echo '<div class="price">RM ' . number_format($discounted_price, 2) . '</div>';
                                                                        echo '<div class="price_crossed">RM ' . number_format($row9['item_price'], 2) . '</div>';
                                                                    }

                                                                    else{
                                                                        echo '<div class="price">RM '.$row9['item_price'].'</div>';
                                                                    }

                                                                    echo '<div class="rating">';

                                                                    ratingToStars($row9['item_rating']);

                                                                    echo '('.number_format($row9['item_rating'],1).')
                                                                    </div>
                                                                </div>
                                                            </a>';
                                                        }
                                                    }

                                                $totalProcessedItemIDs++;
                                                $processedItemIDs[] = $row['item_ID'];
                                            }
                                            // If the total count reaches 10, break out of the loop
                                            if ($totalProcessedItemIDs >= 10) {
                                                break; // Break the loop
                                            }
                                        }
                                    }
                                }

                                // Display any remaining items in menu_items if the total count is still less than 10
                                if ($totalProcessedItemIDs < 10) {
                                    $sql = "SELECT * FROM menu_items WHERE item_ID NOT IN (" . implode(',', $processedItemIDs) . ") AND trash = 0 LIMIT " . (10 - $totalProcessedItemIDs);
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            // Fetch necessary details and display item HTML here...
                                            $totalProcessedItemIDs++;
                                        }
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="review_darken"></div>
        <?php
            include 'sidebar.php';
            include 'footer.php';
        ?>
    </body>
</html>
