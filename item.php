<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Title</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="script.js"></script>
        <script src="gototop.js"></script>
    </head>
    <body>
        <?php
            include 'connect.php';
            include 'top.php';
            include 'gototopbtn.php';

            // // Close connection
            // $conn->close();

            if (isset($_GET['ID'])) {
                // Retrieve the value of the ID parameter
                $item_ID = $_GET['ID'];
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if(isset($_POST['submit_review'])) {
                    // Retrieve form data
                    $review_rating = $_POST['review_rating'];
                    $review_title = $_POST['review_title'];
                    $review_comment = $_POST['review_comment'];
                    $review_date = date('Y-m-d');
                    // $cust_ID = $_POST['cust_ID'];
                    $cust_ID = '1';

                    // Construct the SQL query to insert menu item data
                    $sql_insert = "INSERT INTO customer_reviews (review_rating, review_title, review_comment, review_date, item_ID, cust_ID) VALUES ('$review_rating', '$review_title', '$review_comment', '$review_date', '$item_ID', '$cust_ID')";

                    // echo $sql_insert;

                    // Execute the SQL query
                    if ($conn->query($sql_insert) === TRUE) {
                        header("Location: item.php?ID=".$item_ID);
                        exit();
                        // echo "New record created successfully";
                    } else {
                        echo "Error: " . $sql_insert . "<br>" . $conn->error;
                    }
                } elseif(isset($_POST['ratingForm'])) {
                  
                } elseif(isset($_POST['menuForm'])) {
                  
                }
            }

             function getSubCategories2($category_ID, $conn) {
                $sub_categories = array(); // Initialize array to store sub-categories
                $sql_sub = "SELECT * FROM menu_categories WHERE category_parent = '$category_ID'";
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

            $sql = "SELECT * FROM menu_items WHERE item_ID = $item_ID LIMIT 1";

            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $sql2 = "SELECT * FROM menu_images WHERE image_ID = {$row['item_ID']} LIMIT 1";
                    $result2 = $conn->query($sql2);
                    while ($row2 = $result2->fetch_assoc()) {
                        $image_data = $row2["data"];
                        $mime_type = $row2["mime_type"];
                        $base64 = base64_encode($image_data);
                        $src = "data:$mime_type;base64,$base64";
                    }
            $sql_reviews = "SELECT * FROM customer_reviews WHERE item_ID = $item_ID";
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
                    <form id="menuForm" action="menu.php" method="post">
                        <div class="row d-flex justify-content-between pb-1">
                            <div class="col-0 col-md-5 mx-auto  fade_in" style="transition: 0.5s;">
                                <div class="item_image_container">
                                    <?php
                                        echo'
                                        <img src='.$src.' class="item_image">';
                                    ?>

                                </div>
                            </div>
                            <div class="col-12 col-md-7 mx-auto pb-md-0 pb-4  fade_in"  style="transition: 0.5s;">
                                <div class="justify-content-between d-flex align-items-center">
                                <?php
                                    $item_name_review = $row['item_name'];

                                    echo'
                                    <div class="item_title">'.$row['item_name'].'</div>
                                    <a class="item_saved"><i class="far fa-heart"></i></a></div>';

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
                                    echo '<div class="item_details">';

                                    $sql_sub_cate = "SELECT * FROM menu_categories WHERE category_parent = 0";
                                    $result_sub_cate = $conn->query($sql_sub_cate);

                                    $category_results = array(); // Initialize array to store results for each category
                                    if ($result_sub_cate->num_rows > 0) {
                                        while ($row_sub_cate = $result_sub_cate->fetch_assoc()) {
                                            $category_results[$row_sub_cate['category_ID']] = getSubCategories2($row_sub_cate['category_ID'], $conn);
                                        }
                                    }
                                    foreach ($category_results as $root_category_id => $sub_categories) {

                                        $get_parent_category = "SELECT * FROM menu_categories WHERE category_ID = {$root_category_id} LIMIT 1";
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
                                                $get_category_name = "SELECT * FROM menu_categories WHERE category_ID = {$category_ID} LIMIT 1";
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
                                        $option_name_sql = "SELECT * FROM menu_customization WHERE custom_ID = $customOptionId";
                                        $option_name_result = $conn->query($option_name_sql);
                                        if ($option_name_result->num_rows > 0) {
                                            $row3 = $option_name_result->fetch_assoc();
                                            $custom_options_string = $row3['custom_options'];

                                            echo '<div class="item_attribute options">
                                            <div class="item_attribute_container">'. $row3['custom_name'].'</div>
                                            <select class="option_choices">
                                            <option id="select_default" value="Select..." selected disabled>Select...</option>';

                                            // Extracting option choice and price using regular expressions
                                            preg_match_all('/\("([^"]+)",([\d.]+)\)/', $custom_options_string, $matches, PREG_SET_ORDER);

                                            // $matches will contain all the matches found in the string
                                            foreach ($matches as $match) {
                                                $option_choice = $match[1];
                                                $option_price = $match[2];

                                                echo "<option> $option_choice (+RM $option_price)</option>";
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
                                                echo '<input type="text" value="1"/>';
                                                echo '<span class="plus">+</span>';
                                           }
                                            
                                        echo '</div>
                                    </div>';
                                    echo '<div class="item_attribute options">
                                        <div class="item_attribute_container">Extra Requests</div>';
                                        if($row['item_availability'] == 0){
                                            echo '<textarea class="disabled"></textarea>';
                                        }
                                        else{
                                            echo '<textarea></textarea>';
                                        }
                                     echo '</div>';
                                    echo '</div>';
                                ?>
                                <input type="button" value="Add to Cart" class="cart_button <?php if($row['item_availability'] == 0){ echo 'disabled'; } ?>">

                            </div>
                        </div>
                    </form>
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
                var ratingForm = document.getElementById('ratingForm'); // Get the form element

               // Load saved filter state from localStorage, if any
                var savedFilterState = localStorage.getItem('filterState');
                if (savedFilterState) {
                    var filterState = JSON.parse(savedFilterState);
                    selectStars.value = filterState.starsSelected;
                    selectComments.value = filterState.commentsSelected;
                }

                selectStars.addEventListener('change', function() {
                    if (selectStars.value !== "0") {
                        sql_reviews += " AND review_rating = " + selectStars.value;
                    }
                    saveFilterState(); // Save filter state to localStorage
                    ratingForm.submit(); // Automatically submit the form
                });

                selectComments.addEventListener('change', function() {
                    if (selectComments.value === "0") {
                        sql_reviews += "";
                    } else if (selectComments.value === "1") {
                        sql_reviews += " AND review_comment != ''";
                    } else if (selectComments.value === "2") {
                        sql_reviews += " AND review_comment = ''";
                    }
                    saveFilterState(); // Save filter state to localStorage
                    ratingForm.submit(); // Automatically submit the form
                });

                function saveFilterState() {
                    var filterState = {
                        starsSelected: selectStars.value,
                        commentsSelected: selectComments.value
                    };
                    localStorage.setItem('filterState', JSON.stringify(filterState));
                }

                if (!(selectStars.value == "" || selectComments.value == "")){
                    scrollToElement();
                }

                if (selectStars.value == ""){
                    selectStars.value = "0";
                }
                if (selectComments.value == ""){
                    selectComments.value = "0";
                }

                function scrollToElement() {
                    var element = document.getElementById('reviews_section');
                    if (element) {
                        var offset = element.getBoundingClientRect().top;
                        window.scrollTo({
                            top: offset - 80,
                            behavior: 'smooth'
                        });
                    }
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
        </script>
        <div class="reviews" id="reviews_section">
            <div class="container-fluid container">
                <div class="col-12 m-auto">
                    <span class="reviews_title">Customer Reviews</span>
                    <hr>
                    <div class="reviews_top">
                        <div class="overall_ratings">
                        <?php
                            // Check if filter options are set in POST request
                            if(isset($_POST['ratings_stars']) && isset($_POST['ratings_comments'])) {
                                $stars = $_POST['ratings_stars'];
                                $comments = $_POST['ratings_comments'];

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
                            $sql = "SELECT * FROM menu_items WHERE item_ID = $item_ID LIMIT 1";

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
                                    echo'<input type="text" name="review_rating" id="review_rating" required><input type="text" name="review_title" id="review_title" placeholder="Review title..." required>';
                                    echo'<textarea name="review_comment" id="review_comment" placeholder="Review Contents..." required></textarea>';
                                    echo'<div class="buttons">
                                        <div name="cancel_review" id="cancel_review">Cancel</div>
                                        <input type="submit" name="submit_review" id="submit_review">
                                    </div>';
                                ?>
                                </div>
                            </div>
                        </form>
                        <div class="ratings_filter">
                        <form id="ratingForm" action="item.php?ID=<?php echo $item_ID; ?>" method="post">
                            <div class="review_button">Write Review</div>
                            <div>
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
                                        echo '<div class="bottom_container">"'.$row_reviews['review_comment'].'"</div>';
                                    }

                                    $sql_review_user = "SELECT cust_username FROM customer WHERE cust_ID = {$row_reviews['cust_ID']} LIMIT 1";

                                    $result_review_user = $conn->query($sql_review_user);
                                    if ($result_review_user->num_rows > 0) {
                                        while ($row_review_user = $result_review_user->fetch_assoc()) {
                                            echo '<div class="username">-'.$row_review_user['cust_username'].'</div>';
                                        }
                                    }
                                    echo '</div>';
                                    // $overall_rating += $row_reviews['review_rating'];
                                }
                            }
                            else{
                                echo '<div class="no_reviews"><div>No Reviews.</div></div>';
                            }
                            // echo number_format($overall_rating/$result_reviews->num_rows,1);
                        ?>
                    </div>
                    <hr>
                    <div>
                        cscs
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
