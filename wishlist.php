<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Title</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
        <script src="script.js"></script>
    </head>
    <body>
        <?php
            include 'connect.php';
            include 'top.php';
            include 'gototopbtn.php';

            if(empty($user)){
                header("Location: login.php");
                exit();
            }

            $sortClause = '';
            $sortBySelect = '';

            if (isset($_GET['searchInput'])) {
                $searchInput = $_GET['searchInput'];
                if ($searchInput !== "") {
                    $sortClause .= " AND item_name LIKE '%";
                    $sortClause .= $searchInput;
                    $sortClause .= "%'";
                }
            }

            if (isset($_GET['sortBySelect'])) {
                $sortBySelect = $_GET['sortBySelect'];
                if($sortBySelect == 'newest'){
                    $sortClause .= '';
                }
                else if($sortBySelect == 'priceLowToHigh'){
                    $sortClause .= ' ORDER BY item_price*((100-item_discount)*0.01) ASC';
                }
                else if($sortBySelect == 'priceHighToLow'){
                    $sortClause .= ' ORDER BY item_price*((100-item_discount)*0.01) DESC';
                }
                else if($sortBySelect == 'topRated'){
                    $sortClause .= '  ORDER BY item_rating DESC';
                }
            }

            $sql_getWishlist = "SELECT cust_wishlist FROM customer WHERE cust_ID = $cust_ID AND trash = 0 AND cust_wishlist != 0 LIMIT 1";

            $result_getWishlist = $conn->query($sql_getWishlist);

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['remove_wishlist'])) {
                    // Retrieve form data
                    $item_ID = $_POST['item_ID'];

                    $updated_wishlist = '';
                    if ($result_getWishlist->num_rows > 0) {
                        while ($row_getWishlist = $result_getWishlist->fetch_assoc()) {
                            $saved_items = explode(',', $row_getWishlist['cust_wishlist']);
                            foreach ($saved_items as $key => $saved_item) {
                                if($saved_item == $item_ID){
                                    unset($saved_items[$key]);
                                    $found_wishlisted_item = 1;
                                }
                            }
                            $updated_wishlist = implode(',', $saved_items);
                        }
                    }

                    $sql_insert = "UPDATE customer SET cust_wishlist = '$updated_wishlist' WHERE trash = 0 AND cust_ID = $cust_ID";

                    // Execute the SQL query
                    if ($conn->query($sql_insert) === TRUE) {
                        header("Location: wishlist.php");
                        exit();
                        // echo "New record created successfully";
                    } else {
                        echo "Error: " . $sql_insert . "<br>" . $conn->error;
                    }
                }
            }
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var searchElement = document.querySelector('input[name="searchInput"]');
                var selectElement = document.querySelector('select[name="sortBySelect"]');
                var filterForm = document.getElementById("filterForm");

                selectElement.addEventListener('change', function() {
                     filterForm.submit();
                });

                searchElement.addEventListener('change', function() {
                     filterForm.submit();
                });
            });
        </script>
        <div class="wishlist">
            <div class="menu">
                <div class="container-fluid container">
                    <div class="col-12 m-auto">
                        <div class="breadcrumbs">
                            <a href="index.php">Home</a> > <a class="active">Wishlist</a>
                        </div>
                        <div class="row d-flex justify-content-between pb-1">
                            <div class="col-12 mx-auto pb-md-0 pb-4">
                                <form id="filterForm" action="wishlist.php" method="get">
                                    <div class="filter_container_2">
                                        <div class="search_container">
                                            <div class="search">
                                                <i class="fas fa-search"></i>
                                                <input type="text" name="searchInput" id="searchInput" placeholder="Search menu..." value="<?php echo isset($_GET['searchInput']) ? htmlspecialchars($_GET['searchInput']) : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="sort_filter">
                                            <span>Sort By</span> 
                                            <select name="sortBySelect" id="sortBySelect">
                                                <option value="newest" <?php echo (isset($_GET['sortBySelect']) && $_GET['sortBySelect'] == 'newest') ? 'selected' : ''; ?>>Newest Added</option>
                                                <option value="priceLowToHigh" <?php echo (isset($_GET['sortBySelect']) && $_GET['sortBySelect'] == 'priceLowToHigh') ? 'selected' : ''; ?>>Price: Low to High</option>
                                                <option value="priceHighToLow" <?php echo (isset($_GET['sortBySelect']) && $_GET['sortBySelect'] == 'priceHighToLow') ? 'selected' : ''; ?>>Price: High to Low</option>
                                                <option value="topRated" <?php echo (isset($_GET['sortBySelect']) && $_GET['sortBySelect'] == 'topRated') ? 'selected' : ''; ?>>Top Rated</option>
                                            </select>
                                        </div>
                                        <input type="submit" value="submit_filters" style="display: none;">
                                    </div>
                                </form>
                                <div class="menu_container">
                                <?php
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

                                    // $sql_getWishlist = "SELECT * FROM menu_categories WHERE category_display = 1 AND trash = 0";
                                    $result = $conn->query($sql);

                                    echo '<div class="series">';
                                    echo '<div class="menu_items">';

                                    $itemsGenerated = 0;

                                    if ($result->num_rows > 0) {
                                        if ($result->num_rows > 0) {
                                            while ($row_getWishlist = $result_getWishlist->fetch_assoc()) {
                                                $saved_items = explode(',', $row_getWishlist['cust_wishlist']);

                                                $sql_rearrange = "SELECT * FROM menu_items WHERE trash = 0";
                                                $sql_rearrange .= $sortClause;
                                                $result_rearrange = $conn->query($sql_rearrange);

                                                if($sortBySelect != 'newest'){
                                                    $selected_ids = array();

                                                    while ($row_rearrange = $result_rearrange->fetch_assoc()) {
                                                        foreach ($saved_items as $key => $saved_item){
                                                            if ($row_rearrange['item_ID'] == $saved_item){
                                                                $selected_ids[] = $row_rearrange['item_ID'];
                                                            }
                                                        }
                                                    }
                                                }else if($sortBySelect == 'newest' && $searchInput !== ""){
                                                    $saved_items2 = array_reverse($saved_items);
                                                    $selected_ids = array();

                                                    $rearrange_items = array();
                                                    while ($row_rearrange = $result_rearrange->fetch_assoc()) {
                                                        $rearrange_items[$row_rearrange['item_ID']] = $row_rearrange;
                                                    }

                                                    foreach ($saved_items2 as $saved_item) {
                                                        if (isset($rearrange_items[$saved_item])) {
                                                            $selected_ids[] = $rearrange_items[$saved_item]['item_ID'];
                                                        }
                                                    }
                                                }else{
                                                    $selected_ids = array_reverse($saved_items);
                                                }

                                                foreach ($selected_ids as $key => $saved_item) {
                                                    $sql3 = "SELECT * FROM menu_images WHERE image_ID = {$saved_item} AND trash = 0 LIMIT 1";
                                                    $result3 = $conn->query($sql3);
                                                    while ($row3 = $result3->fetch_assoc()) {
                                                        $image_data = $row3["data"];
                                                        $mime_type = $row3["mime_type"];
                                                        $base64 = base64_encode($image_data);
                                                        $src = "data:$mime_type;base64,$base64";
                                                    }

                                                    $sql2 = "SELECT * FROM menu_items WHERE item_ID = {$saved_item} AND trash = 0";
                                                    $result2 = $conn->query($sql2);
                                                    while ($row2 = $result2->fetch_assoc()) {
                                                        ?>
                                                        <div class="item_container fade_in">
                                                            <form method="POST" id="removeWishlistForm" action="wishlist.php">
                                                                <input type="hidden" name="item_ID" value="<?php echo $row2['item_ID']; ?>">
                                                                <button type="submit" name="remove_wishlist" class="remove_wishlist"><i class="fal fa-times"></i></button>
                                                            </form>
                                                            <a href="item.php?ID=<?php echo $row2['item_ID']; ?>">
                                                                <?php
                                                                if ($row2['item_discount'] > 0) {
                                                                    echo '<div class="discount">' . $row2['item_discount'] . '% DISCOUNT</div>';
                                                                }
                                                                if ($row2['item_availability'] == 0) {
                                                                    echo '<div class="sold_out">SOLD OUT</div>';
                                                                }
                                                                ?>
                                                                <img src="<?php echo $src; ?>">
                                                                <div class="item_text">
                                                                    <div class="title"><?php echo $row2['item_name']; ?></div>
                                                                    <?php
                                                                    if ($row2['item_discount'] > 0) {
                                                                        $discounted_price = $row2['item_price'] * ((100 - $row2['item_discount']) * 0.01);
                                                                        echo '<div class="price">RM ' . number_format($discounted_price, 2) . '</div>';
                                                                        echo '<div class="price_crossed">RM ' . number_format($row2['item_price'], 2) . '</div>';
                                                                    } else {
                                                                        echo '<div class="price">RM ' . $row2['item_price'] . '</div>';
                                                                    }
                                                                    ?>
                                                                    <div class="rating">
                                                                        <?php
                                                                        ratingToStars($row2['item_rating']);
                                                                        echo '(' . number_format($row2['item_rating'], 1) . ')';
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <?php
                                                            $itemsGenerated = 1;
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    echo'</form></div>';
                                    if ($itemsGenerated == 0) {
                                        echo '<div class="no_items"><i class="far fa-ghost"></i>No menu items.</div>';
                                    }
                                    echo'</div>';
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
            include 'sidebar.php';
            include 'footer.php';
        ?>
    </body>
</html>
