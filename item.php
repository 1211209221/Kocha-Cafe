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
    </head>
    <body>
        <?php
            include 'connect.php';
            include 'top.php';
            include 'sidebar.php';

            // // Close connection
            // $conn->close();

            if (isset($_GET['ID'])) {
                // Retrieve the value of the ID parameter
                $item_ID = $_GET['ID'];
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
                                    echo'
                                    <div class="item_title">'.$row['item_name'].'</div>
                                    <a class="item_saved"><i class="far fa-heart"></i></a></div>';

                                    echo '<div class="rating">';

                                    ratingToStars($row['item_rating']);

                                    echo '('.number_format($row['item_rating'],1).')
                                    </div>
                                    <div class="no_ratings">34 Ratings</div>
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
        <div class="reviews">
            <div class="container-fluid container">
                <div class="col-12 m-auto">
                    <span class="reviews_title">Reviews</span>
                    <div class="overall_ratings">
                    <?php
                        $sql = "SELECT * FROM menu_items WHERE item_ID = $item_ID LIMIT 1";

                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="value">'.number_format($row['item_rating'],1).'</div><div class="stars">';
                                ratingToStars($row['item_rating']);
                                echo '</div><div class="no_ratings">3 Reviews</div><div class="stars">';
                            }
                        }
                    ?>
                    </div>
                    <div class="display_reviews">
                        <div class="review_container"></div>
                        <div class="review_container"></div>
                        <div class="review_container"></div>
                        <div class="review_container"></div>
                        <div class="review_container"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include 'footer.php'; ?>
    </body>
</html>
