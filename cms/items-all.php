<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item List | Admin Panel</title>
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

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $item_IDs = $_POST['item_ID'];
                $trashes = $_POST['trash_item'];

                if (isset($_POST['submit_trash_items'])) {
                    $FailedUpdate = 0;

                    for ($i = 0; $i < count($item_IDs); $i++) {
                        $item_ID = $item_IDs[$i];
                        $trash = $trashes[$i];

                        $trash_sql = "UPDATE menu_items SET trash = {$trash} WHERE item_ID = {$item_ID}";

                        if ($conn->query($trash_sql) !== TRUE) {
                            $FailedUpdate = 1;
                            break;
                        }
                    }

                    if ($FailedUpdate == 0) {
                        $_SESSION['deleteItem_success'] = true;
                        header("Location: items-all.php");
                        exit();
                    }
                    else{
                        $_SESSION['deleteItem_error'] = true;
                        header("Location: items-all.php");
                        exit();
                    }
                }
            }

            if (isset($_SESSION['deleteItem_success'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast true fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Successfully deleted selected items!
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
                                    <i class="fas fa-times-circle"></i>Failed to delte items. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['deleteItem_error']);
            }


            include 'navbar.php';
        ?>
        <script>
            function confirmAction(message) {
                return confirm("Are you sure you want to " + message + "?");
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Get all trash icons
                var trashIcons = document.querySelectorAll('tr .trash-icon');

                // Loop through each trash icon
                trashIcons.forEach(function(icon) {
                    // Add click event listener
                    icon.addEventListener('click', function() {
                        // Find the corresponding trash item input
                        var trashItemInput = this.parentElement.querySelector('.trash-item-input');
                        
                        // Toggle the value between 0 and 1
                        trashItemInput.value = trashItemInput.value === '0' ? '1' : '0';

                        // Get the parent tr element
                        var parentTR = this.closest('tr');

                        // Add or remove the 'delete' class based on the trash item input value
                        if (trashItemInput.value === '1') {
                            parentTR.classList.add('delete');
                        } else {
                            parentTR.classList.remove('delete');
                        }
                    });
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const reviewContainers = document.querySelectorAll('tbody tr');
                const perPageSelector = document.getElementById('perPage');
                const pagination = document.getElementById('pagination');
                const discountFilter = document.getElementById('discountFilter');
                const availabilityFilter = document.getElementById('availabilityFilter');
                const priceMinInput = document.getElementById('priceMin');
                const priceMaxInput = document.getElementById('priceMax');
                const categoryCheckboxes = document.querySelectorAll('.categories_filter input[type="checkbox"]');
                const clearAllButton = document.querySelector('.filter_header .clear');
                let currentPage = 1;
                let searchTerm = '';
                let selectedDiscountFilter = 'all';
                let selectedAvailabilityFilter = 'all';
                let minPrice = '';
                let maxPrice = '';
                let selectedCategories = [];

                function showPage(pageNumber) {
                    const perPage = parseInt(perPageSelector.value);
                    const startIndex = (pageNumber - 1) * perPage;
                    const endIndex = startIndex + perPage;

                    let filteredContainers = Array.from(reviewContainers);

                    // Filter items based on search term
                    if (searchTerm) {
                        filteredContainers = filteredContainers.filter(container => {
                            const itemName = container.querySelector('.t_name').textContent.toLowerCase();
                            return itemName.includes(searchTerm.toLowerCase());
                        });
                    }

                    // Filter items based on discount
                    if (selectedDiscountFilter !== 'all') {
                        filteredContainers = filteredContainers.filter(container => {
                            const discount = container.querySelector('.t_discount').textContent.trim();
                            if (selectedDiscountFilter === '1') {
                                return discount === '-';
                            } else if (selectedDiscountFilter === '2') {
                                return discount !== '-';
                            }
                        });
                    }

                    // Filter items based on availability
                    if (selectedAvailabilityFilter !== 'all') {
                        filteredContainers = filteredContainers.filter(container => {
                            const availabilityIcon = container.querySelector('.t_availability i');
                            if (selectedAvailabilityFilter === 'Available') {
                                return availabilityIcon.classList.contains('fa-check');
                            } else if (selectedAvailabilityFilter === 'SoldOut') {
                                return availabilityIcon.classList.contains('fa-times');
                            }
                        });
                    }

                    // Filter items based on price range
                    if (minPrice || maxPrice) {
                        filteredContainers = filteredContainers.filter(container => {
                            const price = parseFloat(container.querySelector('.t_price').textContent.trim().replace('RM', ''));
                            if (minPrice && maxPrice) {
                                return price >= minPrice && price <= maxPrice;
                            } else if (minPrice) {
                                return price >= minPrice;
                            } else if (maxPrice) {
                                return price <= maxPrice;
                            }
                            return true;
                        });
                    }

                    // Filter items based on selected categories
                    if (selectedCategories.length > 0) {
                        filteredContainers = filteredContainers.filter(container => {
                            const categories = container.querySelector('.t_categories div').textContent;
                            return selectedCategories.some(category => categories.includes(category));
                        });
                    }

                    // Add 'unfiltered' class to the tr elements that do not meet the filter criteria
                    reviewContainers.forEach(container => {
                        if (!filteredContainers.includes(container)) {
                            container.classList.add('unfiltered');
                        } else {
                            container.classList.remove('unfiltered');
                        }
                    });

                    // Hide all review containers
                    reviewContainers.forEach(container => {
                        container.style.display = 'none';
                    });

                    // Show review containers for the current page
                    for (let i = startIndex; i < endIndex && i < filteredContainers.length; i++) {
                        const container = filteredContainers[i];
                        container.style.display = 'table-row';
                        // Update entry numbers
                        container.querySelector('.t_no').textContent = i + 1;
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
                    displayResultIndexes();
                }

                function displayResultIndexes() {
                    const perPage = parseInt(perPageSelector.value);
                    const filteredContainers = Array.from(reviewContainers).filter(container => !container.classList.contains('unfiltered'));
                    const startIndex = (currentPage - 1) * perPage + 1;
                    const endIndex = Math.min(startIndex + perPage - 1, filteredContainers.length);

                    const resultIndexesElement = document.createElement('div');
                    resultIndexesElement.textContent = `Showing ${startIndex} to ${endIndex} of ${filteredContainers.length} results`;

                    const noResultsPage = document.querySelector('.no_results_page');
                    noResultsPage.innerHTML = ''; // Clear existing content
                    noResultsPage.appendChild(resultIndexesElement);
                }

                function createPagination() {
                    let filteredContainers = Array.from(reviewContainers);

                    // Filter items based on search term
                    if (searchTerm) {
                        filteredContainers = filteredContainers.filter(container => {
                            const itemName = container.querySelector('.t_name').textContent.toLowerCase();
                            return itemName.includes(searchTerm.toLowerCase());
                        });
                    }

                    // Filter items based on discount
                    if (selectedDiscountFilter !== 'all') {
                        filteredContainers = filteredContainers.filter(container => {
                            const discount = container.querySelector('.t_discount').textContent.trim();
                            if (selectedDiscountFilter === '1') {
                                return discount === '-';
                            } else if (selectedDiscountFilter === '2') {
                                return discount !== '-';
                            }
                        });
                    }

                    // Filter items based on availability
                    if (selectedAvailabilityFilter !== 'all') {
                        filteredContainers = filteredContainers.filter(container => {
                            const availabilityIcon = container.querySelector('.t_availability i');
                            if (selectedAvailabilityFilter === 'Available') {
                                return availabilityIcon.classList.contains('fa-check');
                            } else if (selectedAvailabilityFilter === 'SoldOut') {
                                return availabilityIcon.classList.contains('fa-times');
                            }
                        });
                    }

                    // Filter items based on price range
                    if (minPrice || maxPrice) {
                        filteredContainers = filteredContainers.filter(container => {
                            const price = parseFloat(container.querySelector('.t_price').textContent.trim().replace('RM', ''));
                            if (minPrice && maxPrice) {
                                return price >= minPrice && price <= maxPrice;
                            } else if (minPrice) {
                                return price >= minPrice;
                            } else if (maxPrice) {
                                return price <= maxPrice;
                            }
                            return true;
                        });
                    }

                    // Filter items based on selected categories
                    if (selectedCategories.length > 0) {
                        filteredContainers = filteredContainers.filter(container => {
                            const categories = container.querySelector('.t_categories div').textContent;
                            return selectedCategories.some(category => categories.includes(category));
                        });
                    }

                    const totalReviews = filteredContainers.length;
                    const perPage = parseInt(perPageSelector.value);
                    const totalPages = Math.ceil(totalReviews / perPage);
                    pagination.innerHTML = '';

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

                function clearAllFilters() {
                    // Clear search term
                    searchTerm = '';

                    // Reset select inputs for discount and availability filters
                    discountFilter.value = 'all';
                    selectedDiscountFilter = 'all';
                    availabilityFilter.value = 'all';
                    selectedAvailabilityFilter = 'all';

                    // Clear price range inputs
                    minPrice = '';
                    maxPrice = '';
                    priceMinInput.value = '';
                    priceMaxInput.value = '';

                    // Uncheck all category checkboxes
                    categoryCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });

                    // Reset selected categories arrays
                    selectedCategories = [];
                    selectedPrimaryCategories = [];

                    // Trigger filter update
                    currentPage = 1;
                    showPage(currentPage);
                    createPagination();
                }

                perPageSelector.addEventListener('change', function() {
                    currentPage = 1;
                    showPage(currentPage);
                    createPagination();
                });

                discountFilter.addEventListener('change', function() {
                    selectedDiscountFilter = discountFilter.value;
                    currentPage = 1;
                    showPage(currentPage);
                    createPagination();
                });

                availabilityFilter.addEventListener('change', function() {
                    selectedAvailabilityFilter = availabilityFilter.value;
                    currentPage = 1;
                    showPage(currentPage);
                    createPagination();
                });

                priceMinInput.addEventListener('input', function() {
                    minPrice = parseFloat(priceMinInput.value) || '';
                    currentPage = 1;
                    showPage(currentPage);
                    createPagination();
                });

                priceMaxInput.addEventListener('input', function() {
                    maxPrice = parseFloat(priceMaxInput.value) || '';
                    currentPage = 1;
                    showPage(currentPage);
                    createPagination();
                });

                categoryCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        selectedCategories = Array.from(categoryCheckboxes)
                            .filter(checkbox => checkbox.checked)
                            .map(checkbox => checkbox.nextSibling.textContent.trim());
                        currentPage = 1;
                        showPage(currentPage);
                        createPagination();
                    });
                });

                clearAllButton.addEventListener('click', function() {
                    clearAllFilters();
                });

                const searchInput = document.querySelector('.search_bar');
                searchInput.addEventListener('input', function(event) {
                    searchTerm = event.target.value.trim();
                    currentPage = 1;
                    showPage(currentPage);
                    createPagination();
                });

                showPage(currentPage);
                createPagination();
            });
        </script>
        <style>
            
        </style>
        <div class="container-fluid">
            <div class="col-12 m-auto">
                <div class="all_items">
                     <div class="breadcrumbs">
                        <a>Admin</a> > <a>Menu</a> > <a class="active">Item List</a>
                    </div>
                    <div class="page_title">All Items</div>
                    <div class="filter_selectors">
                        <div class="menu">
                            <div class="filter_header">
                                <div class="d-flex flex-row align-items-baseline">
                                    <i class="fas fa-sliders-h"></i><span>Filters</span>
                                </div>
                                <div class="clear">
                                    <div>Clear all</div>
                                </div>
                            </div>
                            <hr class="mt-1">
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
                                            echo '<span><input type="checkbox" name="'.$row_sub['category_ID'].','.$row_sub['category_parent'].','.$row_sub['category_primary'].'" id='.$row_sub['category_ID'].'>' . $row_sub['category_name'].'</span>';

                                            
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
                                        echo '<div class="categories_filter';
                                        if($row_cate["category_primary"] == 1){
                                            echo ' primary';
                                        }
                                        echo'">';
                                        echo '<li>';
                                        echo '<span class="filter_name">'.$row_cate["category_name"].'</span>';
                                        getSubCategories($row_cate["category_ID"], $conn, $row_cate["category_parent"]);
                                        echo '</li>';
                                        echo '</div><hr>';
                                    }
                                }
                                else{
                                    echo '<hr>';
                                }
                            ?>
                        </div>
                        <div>
                            <div class="filter_type">
                                <label for="discountFilter">Filter by Discount</label>
                                <select id="discountFilter">
                                    <option value="all">All</option>
                                    <option value="1">No Discount</option>
                                    <option value="2">With Discount</option>
                                </select>
                            </div>
                            <div class="filter_type">
                                <label for="availabilityFilter">Filter by Availability</label>
                                <select id="availabilityFilter">
                                    <option value="all">All</option>
                                    <option value="Available">Available</option>
                                    <option value="SoldOut">Sold Out</option>
                                </select>
                            </div>
                            <div class="filter_type">
                                <label for="priceMin">Price Range (MYR)</label>
                                <div>
                                    <input type="text" class="priceMin" name="priceMin" id="priceMin" placeholder="Min.">
                                    <span class="mx-2"> - </span>
                                    <input type="text" class="priceMax" name="priceMax" id="priceMax" placeholder="Max.">
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="search_container">
                        <div class="item_search">
                            <i class="fas fa-search"></i>
                            <input type="text" class="search_bar" name="keywordSearch" id="keywordSearch" placeholder="Search item...">
                            <select id="perPage">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            <label for="perPage" id="perPageLabel"><span>Shown </span>per page</label>
                        </div>
                        <form method='post' name='trash_form' class='trash-form' id='trash_form'>
                        <div class="d-flex align-items-center justify-content-center">
                            <input type="submit" name="submit_trash_items" class="delete_button" id="submit_trash_items" value="Delete items" onclick="return confirmAction('delete the selected menu items(s)')">
                            <a href="items-add.php"><div class="add_button">New Item</div></a>
                        </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded">
                        <thead class="thead-light">
                            <tr>
                                <th class="t_no">No.</th>
                                <th class="t_image">Image</th>
                                <th class="t_name">Name</th>
                                <th class="t_categories">Categories</th>
                                <th class="t_price">Price</th>
                                <th class="t_discount">Discount</th>
                                <th class="t_availability">Availability</th>
                                <th class="t_action">Action</th>
                            </tr>
                        </thead>
                        <tbody border="transparent">
                            <?php
                            $no_count = 0;
                            $sql = "SELECT * FROM menu_items WHERE trash = 0";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $no_count++;
                                    echo "<tr";
                                    if($row['item_availability'] != 1){
                                        echo " class='unavailable'";
                                    }
                                    echo"><td class='t_no'>".$no_count."</td>";

                                    $sql2 = "SELECT * FROM menu_images WHERE image_ID = {$row['item_ID']} AND trash = 0 LIMIT 1";
                                    $result2 = $conn->query($sql2);
                                    if ($result2->num_rows > 0) {
                                        while ($row2 = $result2->fetch_assoc()) {
                                            $image_data = $row2["data"];
                                            $mime_type = $row2["mime_type"];
                                            $base64 = base64_encode($image_data);
                                            $src = "data:$mime_type;base64,$base64";
                                            echo "<td class='t_image' style='height: 90px;'><img src='".$src."'></td>";
                                        }
                                    }
                                    else{
                                        echo "<td class='t_image' style='height: 90px;'><img src='../images/placeholder_image.png'></td>";
                                    }

                                    echo "<td class='t_name'>".$row['item_name']."</td><td class='t_categories'><div>";

                                    $categories = $row['item_category'];
                                    // Remove any commas
                                    $category_array = explode(',', $categories);

                                    $valid_category_ids = array(); // Define an array to store valid category IDs
                                    $category_count = 0; // Initialize category count

                                    foreach ($category_array as $category) {
                                        $sql3 = "SELECT category_name FROM menu_categories WHERE category_ID = {$category} AND trash = 0 LIMIT 1";
                                        $result3 = $conn->query($sql3);

                                        if ($result3 && $result3->num_rows > 0) {
                                            // Category exists, add it to the valid category IDs array
                                            while ($row3 = $result3->fetch_assoc()) {
                                                echo $row3['category_name'];
                                                
                                                // Check if it's not the last category
                                                if ($category_count < count($category_array) - 1) {
                                                    echo ", ";
                                                }
                                                
                                                $valid_category_ids[] = $category;
                                            }
                                        } else {
                                            $category_array = array_diff($category_array, array($category));
                                        }
                                        
                                        $category_count++;
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
                                        $sql4 = "SELECT * FROM menu_customization WHERE custom_ID = '{$option}' AND trash = 0 LIMIT 1";
                                        $result4 = $conn->query($sql4);

                                        if ($result4 && $result4->num_rows > 0) {
                                            while ($row4 = $result4->fetch_assoc()) {
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

                                    echo "</div></td><td class='t_price'";
                                    if ($row['item_discount'] > 0){
                                        echo "style='color:#5a9498;'";
                                    }
                                    echo">RM ".number_format(($row['item_price'] * (100-$row['item_discount']) *0.01),2)."</td>";
                                    echo "<td class='t_discount'>";
                                    if ($row['item_discount'] > 0){
                                        echo $row['item_discount']."%";
                                    }
                                    else{
                                        echo "-";
                                    }
                                    echo "</td>";
                                    echo "<td class='t_availability'>";
                                    if($row['item_availability'] == 1){
                                        echo "<i class='fas fa-check'></i>";
                                    }else{
                                        echo "<i class='fas fa-times'></i>";
                                    }
                                    echo "</td>";
                                    echo '<td class="t_action"><div><a href="items-edit.php?ID=' . $row['item_ID'] . '"><i class="fas fa-pen"></i></a><a style="position: relative;"><i class="fas fa-comment-alt">';

                                    $sql_get_cart_no = "SELECT * FROM customer_reviews WHERE review_approve = 0 AND item_ID = {$row['item_ID']} AND trash = 0";
                                        $result_get_cart_no = $conn->query($sql_get_cart_no);
                                        if ($result_get_cart_no->num_rows > 0) {
                                            echo '<div class="notification_circle">'.$result_get_cart_no->num_rows.'</div>';
                                        }

                                    echo "</i></a><a class='trash-icon'><i class='fas fa-trash'></i></a>
                                    <input type='hidden' name='item_ID[]' value='".$row['item_ID']."'>
                                    <input type='hidden' class='trash-item-input' name='trash_item[]' value='0' style='display:block;'>
                                    </div>";
                                    echo "</tr>";


                                }
                            } else {
                                echo "<tr><td class='no_items' colspan='7' ><i class='far fa-ghost'></i>No items found...</td></tr>";
                            }

                            $conn->close();
                            ?>
                            </form>
                            </tbody> 
                        </table>
                        <div class="navigation_container">
                            <div id="pagination"></div>
                            <div class="no_results_page">
                                <span>Showing to of results</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>