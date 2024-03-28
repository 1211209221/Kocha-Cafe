<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Menu | Kocha Café</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
        <link rel="icon" href="images/logo/logo_icon.png">
        <script src="script.js"></script>
        <script src="gototop.js"></script>
    </head>
    <body>
        <?php
            include 'connect.php';
            include 'top.php';
            include 'sidebar.php';
            include 'gototopbtn.php';

            $sql2 = "SELECT * FROM menu_items WHERE 1=1 AND trash = 0";

            // // Close connection
            // $conn->close();
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var searchElement = document.querySelector('input[name="searchInput"]');
                var selectElement = document.querySelector('select[name="sortBySelect"]');
                var submitButton = document.getElementById("submit_filter");
                 var clearFiltersButton = document.querySelector('.clear_filters');

                selectElement.addEventListener('change', function() {
                     submitButton.click();
                });

                searchElement.addEventListener('change', function() {
                     submitButton.click();
                });

                clearFiltersButton.addEventListener('click', function() {
                    clearAllFilters();
                    submitButton.click();
                });
            });



            function generateSQL() {
                var sql_menu = "SELECT * FROM menu_items WHERE trash = 0 AND ";

                // Checkboxes
                var discountCheckbox = document.querySelector('input[name="discount"]');
                var availabilityCheckbox = document.querySelector('input[name="availability"]');
                var checkboxes = document.querySelectorAll('input[type="checkbox"]:not([name="discount"]):not([name="availability"])');

                // Price inputs
                var priceMinInput = document.querySelector('input[name="price_min"]');
                var priceMaxInput = document.querySelector('input[name="price_max"]');
                var searchBox = document.querySelector('input[name="searchInput"]');

                var selectBox = document.querySelector('select[name="sortBySelect"]');

                // Array to store checked checkbox ids
                var checkedIds = [];

                // Assuming checkboxes is an array of all checkbox elements
                checkboxes.forEach(function(checkbox) {
                    // Check if checkbox is checked
                    if (checkbox.checked) {
                        // Split the name attribute to get both IDs
                        var ids = checkbox.name.split(',');
                        // The first element in the array will be category_ID
                        var id = ids[0];
                        // The second element in the array will be category_parent
                        var parent = ids[1];

                        var primary = ids[1];
                        
                        // Push both IDs into the checkedIds array
                        checkedIds.push({
                            id: id,
                            parent: parent,
                            primary: primary
                        });
                    }
                });


            if (checkedIds.length > 0) {
                // Create an object to organize categories by primary parent
                var primaryCategories = {};
                checkedIds.forEach(function(category) {
                    if (!primaryCategories[category.primary]) {
                        primaryCategories[category.primary] = [];
                    }
                    primaryCategories[category.primary].push(category.id);
                });

                // Construct the IN clauses for each primary parent category
                var parentClauses = [];
                Object.keys(primaryCategories).forEach(function(primary) {
                    var childIds = primaryCategories[primary];
                    var childClause = childIds.map(function(childId) {
                        return "FIND_IN_SET('" + childId + "', item_category)";
                    }).join(primary == '1' ? " OR " : " AND ");
                    parentClauses.push("(" + childClause + ")");
                });

                // Join parent clauses with 'AND' to form the final inClause
                var inClause = parentClauses.join(" AND ");

                // Append the IN clause to the SQL query
                sql_menu += "(" + inClause + ")";
            } else {
                // If no checkboxes are checked, select all menu items
                sql_menu += "1=1 AND trash = 0";
            }

            // Append discount condition if the discount checkbox is checked
            if (discountCheckbox.checked) {
                sql_menu += " AND item_discount != 0";
            }

            // Append availability condition if the availability checkbox is checked
            if (availabilityCheckbox.checked) {
                sql_menu += " AND item_availability != 0";
            }

            // Append price range condition
            var minPrice = priceMinInput.value;
            var maxPrice = priceMaxInput.value;
            if (minPrice !== "" && !isNaN(minPrice)) {
                sql_menu += " AND item_price >= " + minPrice;
            }
            if (maxPrice !== "" && !isNaN(maxPrice)) {
                sql_menu += " AND item_price <= " + maxPrice;
            }

            var searchValue = searchBox.value;
            if (searchValue !== "") {
                sql_menu += " AND item_name LIKE '%" + searchValue + "%'";
            }

            var selectedValue = selectBox.value;
            if (selectedValue !== "" && selectedValue == "priceHighToLow") {
                sql_menu += " ORDER BY item_price*((100-item_discount)*0.01) DESC";
            }
            if (selectedValue !== "" && selectedValue == "priceLowToHigh") {
                sql_menu += " ORDER BY item_price*((100-item_discount)*0.01) ASC";
            }
            if (selectedValue !== "" && selectedValue == "topRated") {
                sql_menu += " ORDER BY item_rating DESC";
            }
            
            // Display the SQL command in an alert
            // alert(sql_menu);

            // Store the selected checkboxes and price range in localStorage
            localStorage.setItem('filterState', JSON.stringify({
                checkboxes: getCheckboxState(),
                availabilityChecked: availabilityCheckbox.checked,
                discountChecked: discountCheckbox.checked,
                priceMin: minPrice,
                priceMax: maxPrice,
                searchedKeyword: searchValue,
                valueSelected: selectedValue

            }));
            document.getElementById('filterStateInput').value = localStorage.getItem('filterState');

            <?php
                function filterSubCategories($selectedCategories, $conn, $categorySQL) {
                    $sql_sub_cate = "SELECT * FROM menu_categories WHERE category_parent = 0 AND trash = 0";
                    $result_sub_cate = $conn->query($sql_sub_cate);

                    $category_results = array(); // Initialize array to store results for each category
                    if ($result_sub_cate->num_rows > 0) {
                        while ($row_sub_cate = $result_sub_cate->fetch_assoc()) {
                            $category_results[$row_sub_cate['category_ID']] = getSubCategories2($row_sub_cate['category_ID'], $conn);
                        }
                    }
                    foreach ($category_results as $root_category_id => $sub_categories) {
                        $categorySQL.= " (";

                        foreach ($selectedCategories as $category_ID) {

                            if (in_array($category_ID, $sub_categories)) {
                                $categorySQL.=   " FIND_IN_SET('$category_ID', item_category)";


                                $sql_primary_cate = "SELECT category_ID FROM menu_categories WHERE category_primary = 1 AND trash = 0";
                                $result_primary_cate = $conn->query($sql_primary_cate);
                                if ($result_primary_cate->num_rows > 0) {
                                    while ($row_primary_cate = $result_primary_cate->fetch_assoc()) {
                                        if ($root_category_id == $row_primary_cate['category_ID']) {
                                            $categorySQL.= " OR";
                                        }
                                        else{
                                            $categorySQL.= " AND";
                                        }
                                        
                                    }
                                }
                            }
                        }

                        $categorySQL = rtrim($categorySQL, " AND ");
                        $categorySQL = rtrim($categorySQL, " OR ");
                        $categorySQL.=  ") AND";
                    }
                    $categorySQL = rtrim($categorySQL, " AND ");
                    return $categorySQL;
                    
                }

                // Function to get all sub-categories recursively
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

                // Check if filter state is stored in localStorage
                if(isset($_POST['submit_filter'])) {
                    $filterState = json_decode($_POST['filterState'], true);
                    // Construct the WHERE clause based on the stored filter state
                    $whereClause = " WHERE ";
                    if (!empty($filterState['checkboxes'])) {
                        // Separate item categories into primary and non-primary sets
                        $selectedCategories = [];
                        foreach ($filterState['checkboxes'] as $id => $selectedCheckbox) {
                            if ($selectedCheckbox == 1) {
                                echo '
                                ';
                                // echo $id;
                                $selectedCategories[] = $id;

                            }
                        }
                        $categorySQL = "";
                        $categorySQL = filterSubCategories($selectedCategories, $conn, $categorySQL);
                        $categorySQL = str_replace(' () AND () ', '1=1', $categorySQL);
                        $categorySQL = str_replace(' AND ()', '', $categorySQL);
                        $categorySQL = str_replace(' () AND', '', $categorySQL);
                        $categorySQL = str_replace('AND ()', '', $categorySQL);

                        $whereClause .= "($categorySQL)";
                    }
                     else {
                        $whereClause .= "trash = 0 AND 1=1";
                    }
                    if ($filterState['availabilityChecked']) {
                        $whereClause .= " AND item_availability != 0";
                    }
                    if ($filterState['discountChecked']) {
                        $whereClause .= " AND item_discount != 0";
                    }
                   if ((!empty($filterState['priceMin']) && !empty($filterState['priceMax'])) || (empty($filterState['priceMin']) && !empty($filterState['priceMax'])) || (!empty($filterState['priceMin']) && empty($filterState['priceMax']))) {
                        $whereClause .= " AND ";
                        if (!empty($filterState['priceMin']) && !empty($filterState['priceMax'])) {
                            $whereClause .= "item_price*((100-item_discount)*0.01) BETWEEN {$filterState['priceMin']} AND {$filterState['priceMax']}";
                        } elseif (empty($filterState['priceMin']) && !empty($filterState['priceMax'])) {
                            $whereClause .= "item_price*((100-item_discount)*0.01) <= {$filterState['priceMax']}";
                        } elseif (!empty($filterState['priceMin']) && empty($filterState['priceMax'])) {
                            $whereClause .= "item_price*((100-item_discount)*0.01) >= {$filterState['priceMin']}";
                        }
                    }
                    if (!empty($filterState['searchedKeyword'])){
                        $whereClause .= " AND item_name LIKE '%{$filterState['searchedKeyword']}%'";
                    }
                    $whereClause .= " AND trash = 0";
                    if (!empty($filterState['valueSelected'])&&($filterState['valueSelected']=="priceHighToLow")){
                        $whereClause .= " ORDER BY item_price*((100-item_discount)*0.01) DESC";
                    }
                    if (!empty($filterState['valueSelected'])&&($filterState['valueSelected']=="priceLowToHigh")){
                        $whereClause .= " ORDER BY item_price*((100-item_discount)*0.01) ASC";
                    }
                    if (!empty($filterState['valueSelected'])&&($filterState['valueSelected']=="topRated")){
                        $whereClause .= " ORDER BY item_rating DESC";
                    }

                    // Construct the SQL query
                    $sql2 = "SELECT * FROM menu_items" . $whereClause;
                } else {
                    // If no filter state is stored, select all menu items
                    $sql2 = "SELECT * FROM menu_items WHERE 1=1 AND trash = 0";
                }

                if (preg_match('/WHERE\s*\(\s*\)/i', $sql2)) {
                    // If it's empty, replace it with WHERE 1=1
                    $sql2 = preg_replace('/WHERE\s*\(\s*\)/i', 'WHERE (1=1)', $sql2);
                }

                $sql2 = str_replace('(1=1AND', '((1=1) AND ', $sql2);
                $sql2 = str_replace('1=1AND', '', $sql2);
                $sql2 = str_replace('( ()) AND ', '', $sql2);
                $sql2 = str_replace('( ())', '(1=1)', $sql2);

                echo  '//';
                echo  $sql2;
            ?>


                return true;
            }

            // Function to get the state of the checkboxes
            function getCheckboxState() {
                var checkboxState = {};
                var checkboxes = document.querySelectorAll('input[type="checkbox"]:not([name="discount"]):not([name="availability"])');

                checkboxes.forEach(function(checkbox) {
                    checkboxState[checkbox.id] = checkbox.checked;
                });

                return checkboxState;
            }

            // Function to restore filter state from localStorage
            function restoreFilterState() {
                var filterState = JSON.parse(localStorage.getItem('filterState'));

                if (filterState) {
                    // Restore checkbox state
                    var checkboxes = document.querySelectorAll('input[type="checkbox"]:not([name="discount"]):not([name="availability"])');
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = filterState.checkboxes[checkbox.id];
                    });

                    // Restore availability and discount checkboxes
                    var availabilityCheckbox = document.querySelector('input[name="availability"]');
                    var discountCheckbox = document.querySelector('input[name="discount"]');
                    availabilityCheckbox.checked = filterState.availabilityChecked;
                    discountCheckbox.checked = filterState.discountChecked;

                    // Restore price range inputs
                    var priceMinInput = document.querySelector('input[name="price_min"]');
                    var priceMaxInput = document.querySelector('input[name="price_max"]');
                    priceMinInput.value = filterState.priceMin || "";
                    priceMaxInput.value = filterState.priceMax || "";

                    var searchBox = document.querySelector('input[name="searchInput"]');
                    var searchBox = document.querySelector('input[name="searchInput"]');
                    searchBox.value = filterState.searchedKeyword || "";
                    searchBox.value = filterState.searchedKeyword || "";

                    var selectedValue = document.querySelector('select[name="sortBySelect"]');
                    // Check if the select input exists and filterState has the selectedValue
                    if (selectedValue && filterState.valueSelected !== undefined) {
                        // Set the value of the select input
                        selectedValue.value = filterState.valueSelected;
                    }

                }
            }

            // Restore filter state when the page is loaded
            document.addEventListener('DOMContentLoaded', function() {
                restoreFilterState();
            });

            // Function to clear all filters and reset localStorage
            function clearAllFilters() {
                // Clear localStorage
                localStorage.removeItem('filterState');

                // Reset checkbox state
                var checkboxes = document.querySelectorAll('input[type="checkbox"]:not([name="discount"]):not([name="availability"])');
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });

                // Reset availability and discount checkboxes
                var availabilityCheckbox = document.querySelector('input[name="availability"]');
                var discountCheckbox = document.querySelector('input[name="discount"]');
                availabilityCheckbox.checked = false;
                discountCheckbox.checked = false;

                // Reset price range inputs
                var priceMinInput = document.querySelector('input[name="price_min"]');
                var priceMaxInput = document.querySelector('input[name="price_max"]');
                priceMinInput.value = "";
                priceMaxInput.value = "";

            }

        </script>
        <div class="menu">
            <div class="container-fluid container">
                <div class="col-12 m-auto">
                    <div class="breadcrumbs">
                        <a href="index.php">Home</a> > <a class="active">Menu</a>
                    </div>
                    <form id="menuForm" action="menu.php" method="post" onsubmit="return generateSQL();">
                    <div class="row d-flex justify-content-between pb-1">
                        <div class="col-0 col-lg-3 mx-auto">
                            <div id="menuFilters" class="filter_container">
                                <div class="close_filter">
                                    <i class="fal fa-times" onclick="closeFilters()"></i>
                                </div>
                                <div class="active_filters">
                                    <div class="justify-content-between d-flex">
                                        <span><i class="fas fa-sliders-h"></i></i>Filters</span>
                                        <span class="clear_filters">Clear all</span>
                                    </div>
                                </div>
                                <hr>
                                <?php
                                    function getSubCategories($category_ID, $conn, $category_parent) {
                                        $sql_sub = "SELECT * FROM menu_categories WHERE category_parent = '$category_ID' AND trash = 0";
                                        $result_sub = $conn->query($sql_sub);
                                        
                                        if ($result_sub->num_rows > 0) {
                                            if($category_parent == 0){
                                                echo '<ul class="categoryToggle">';
                                            }
                                            else{
                                                echo '<ul>';
                                            }
                                            while ($row_sub = $result_sub->fetch_assoc()) {
                                                echo '<li>';
                                                echo '<input type="checkbox" name="'.$row_sub['category_ID'].','.$row_sub['category_parent'].','.$row_sub['category_primary'].'" id='.$row_sub['category_ID'].'>' . $row_sub['category_name'];

                                                
                                                // Check if this category has subcategories
                                                $sub_category_ID = $row_sub['category_ID'];
                                                $sql_has_sub = "SELECT * FROM menu_categories WHERE category_parent = '$sub_category_ID' AND trash = 0";
                                                $result_has_sub = $conn->query($sql_has_sub);
                                                
                                                if ($result_has_sub->num_rows > 0) {
                                                    echo '<i onclick="categoryToggle(this)" class="fas fa-angle-down"></i>';
                                                }
                                                
                                                getSubCategories($sub_category_ID, $conn, 1); // Recursively call for subcategories
                                                echo '</li>';
                                            }
                                            echo '</ul>';
                                        }
                                    }

                                    $sql_cate = "SELECT * FROM menu_categories WHERE category_parent = 0 and trash = 0 ORDER BY category_primary DESC";
                                    $result_cate = $conn->query($sql_cate);

                                    if ($result_cate->num_rows > 0) {
                                        while($row_cate = $result_cate->fetch_assoc()) {
                                            echo '<div class="categories_filter">';
                                            echo '<li>';
                                            echo '<span>'.$row_cate["category_name"].'</span><i onclick="categoryToggle(this)" class="fas fa-angle-up"></i>';
                                            getSubCategories($row_cate["category_ID"], $conn, $row_cate["category_parent"]);
                                            echo '</li>';
                                            echo '</div><hr>';
                                        }
                                    }
                                    else{
                                        echo '<hr>';
                                    }
                                ?>
                                <div class="discount_filter">
                                    <span>On Discount</span>
                                    <input type="checkbox" name="discount">
                                </div>
                                <hr>
                                <div class="availability_filter">
                                    <span>Availability</span>
                                    <input type="checkbox" name="availability">
                                </div>
                                <hr>
                                <div class="price_filter">
                                    <span>Price Range (MYR)</span>
                                    <div class="d-flex justify-content-center py-1">
                                        <input type="text" name="price_min" placeholder="Min.">
                                        <div class="px-2">-</div>
                                        <input type="text" name="price_max" placeholder="Max.">
                                    </div>
                                </div>
                                <hr>
                                 <input type="hidden" name="filterState" id="filterStateInput">
                                <button type="submit" name="submit_filter" id="submit_filter" class="submit_filter">Apply Filters</button>
                            </div>
                            <div id="filter_darken" onclick="closeFilters()"></div>
                        </div>
                        <div class="col-12 col-lg-9 mx-auto pb-md-0 pb-4">
                            <div class="filter_container_2">
                                <div class="search_container">
                                <a onclick="openFilters()" class="openFilters">
                                    <i class="fas fa-sliders-h"></i>
                                    <span>Filters</span>
                                </a>
                                <div class="search">
                                    <i class="fas fa-search"></i>
                                    <input type="text" name="searchInput" id="searchInput" placeholder="Search menu...">
                                </div>
                            </div>
                            <div class="sort_filter">
                                <span>Sort By</span>
                                 <select name="sortBySelect" id="sortBySelect" >
                                     <option value="recommended">Recommended</option>
                                     <option value="priceLowToHigh">Price: Low to High</option>
                                     <option value="priceHighToLow">Price: High to Low</option>
                                     <option value="topRated">Top Rated</option>
                                 </select>
                            </div>
                            </div>
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

                                $sql = "SELECT * FROM menu_categories WHERE category_display = 1 AND trash = 0";
                                $result = $conn->query($sql);

                                echo '<div class="series">';
                                echo '<div class="menu_items">';

                                $itemsGenerated = 0;

                                if ($result->num_rows > 0) {
                                    if (preg_match('/\bORDER\s+BY\b/i', $sql2)){
                                        $result2 = $conn->query($sql2);
                                        while($row2 = $result2->fetch_assoc()) {

                                            $selectedCategoryIDs = [];
                                            if ($row2['item_category']) {
                                                $selectedCategoryIDs = explode(',', $row2['item_category']);
                                            }

                                            $sql3 = "SELECT * FROM menu_images WHERE image_ID = {$row2['item_ID']} AND trash = 0 LIMIT 1";
                                            $result3 = $conn->query($sql3);
                                            while ($row3 = $result3->fetch_assoc()) {
                                                $image_data = $row3["data"];
                                                $mime_type = $row3["mime_type"];
                                                $base64 = base64_encode($image_data);
                                                $src = "data:$mime_type;base64,$base64";
                                            }

                                            echo '<a class="item_container fade_in" href="item.php?ID=' . $row2['item_ID'] . '">';
                                            if($row2['item_discount'] > 0){
                                                echo '<div class="discount"> '.$row2['item_discount'].'% DISCOUNT</div>';
                                            }
                                            if ($row2['item_availability'] == 0) {
                                                echo'<div class="sold_out">SOLD OUT</div>';
                                            }
                                            echo '
                                                <img src='.$src.'>
                                                <div class="item_text">
                                                    <div class="title">'.$row2['item_name'].'</div>';
                                                    if ($row2['item_discount'] > 0) {
                                                        $discounted_price = $row2['item_price'] * ((100 - $row2['item_discount']) * 0.01);
                                                        echo '<div class="price">RM ' . number_format($discounted_price, 2) . '</div>';
                                                        echo '<div class="price_crossed">RM ' . number_format($row2['item_price'], 2) . '</div>';
                                                    }

                                                    else{
                                                        echo '<div class="price">RM '.$row2['item_price'].'</div>';
                                                    }

                                                    echo '<div class="rating">';

                                                    ratingToStars($row2['item_rating']);

                                                    echo '('.number_format($row2['item_rating'],1).')
                                                    </div>
                                                </div>
                                            </a>';
                                            $itemsGenerated = 1;
                                        }
                                    }

                                    while($row = $result->fetch_assoc()) {

                                        if (preg_match('/\bORDER\s+BY\b/i', $sql2)){
                                        }
                                        else{
                                            // Remove ORDER BY and everything after it
                                            $sql2 = preg_replace('/\s+ORDER\s+BY\s+.*$/i', '', $sql2);
                                           // Replace the first occurrence of FIND_IN_SET

                                            $result2 = $conn->query($sql2);
                                            $num_menu_items = $sql2 . " AND (FIND_IN_SET('{$row['category_ID']}', item_category))";
                                            // echo $num_menu_items;
                                            $result_menu_items = $conn->query($num_menu_items);
                                            if($result_menu_items->num_rows > 0){
                                                echo '<span>' . $row["category_name"]. '</span>';
                                                
                                            }

                                            while($row2 = $result2->fetch_assoc()) {
                                                $selectedCategoryIDs = [];
                                                if ($row2['item_category']) {
                                                    $selectedCategoryIDs = explode(',', $row2['item_category']);
                                                }

                                                $sql3 = "SELECT * FROM menu_images WHERE image_ID = {$row2['item_ID']} and trash = 0 LIMIT 1";
                                                $result3 = $conn->query($sql3);
                                                while ($row3 = $result3->fetch_assoc()) {
                                                    $image_data = $row3["data"];
                                                    $mime_type = $row3["mime_type"];
                                                    $base64 = base64_encode($image_data);
                                                    $src = "data:$mime_type;base64,$base64";
                                                }
                                                
                                                foreach ($selectedCategoryIDs as $categoryId) {
                                                    if($categoryId === $row["category_ID"]){
                                                        echo '<a class="item_container fade_in" href="item.php?ID=' . $row2['item_ID'] . '">';
                                                        if($row2['item_discount'] > 0){
                                                            echo '<div class="discount"> '.$row2['item_discount'].'% DISCOUNT</div>';
                                                        }
                                                        if ($row2['item_availability'] == 0) {
                                                            echo'<div class="sold_out">SOLD OUT</div>';
                                                        }
                                                        echo '
                                                            <img src='.$src.'>
                                                            <div class="item_text">
                                                                <div class="title">'.$row2['item_name'].'</div>';
                                                                if ($row2['item_discount'] > 0) {
                                                                    $discounted_price = $row2['item_price'] * ((100 - $row2['item_discount']) * 0.01);
                                                                    echo '<div class="price">RM ' . number_format($discounted_price, 2) . '</div>';
                                                                    echo '<div class="price_crossed">RM ' . number_format($row2['item_price'], 2) . '</div>';
                                                                }

                                                                else{
                                                                    echo '<div class="price">RM '.$row2['item_price'].'</div>';
                                                                }

                                                                echo '<div class="rating">';

                                                                ratingToStars($row2['item_rating']);

                                                                echo '('.number_format($row2['item_rating'],1).')
                                                                </div>
                                                            </div>
                                                        </a>';
                                                    }
                                                }
                                                $itemsGenerated = 1;
                                            }
                                        }
                                    }
                                }

                                echo'</div>';
                                if ($itemsGenerated == 0) {
                                    echo '<div class="no_items"><i class="far fa-ghost"></i>No menu items.</div>';
                                }
                                echo'</div>';
                            ?>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </body>
</html>
