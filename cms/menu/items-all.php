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
            include '../../connect.php';
            include '../../gototopbtn.php';
            include '../navbar.php';
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const reviewContainers = document.querySelectorAll('tbody tr');
                const perPageSelector = document.getElementById('perPage');
                const pagination = document.getElementById('pagination');
                let currentPage = 1;

               function showPage(pageNumber) {
                    const perPage = parseInt(perPageSelector.value);
                    const startIndex = (pageNumber - 1) * perPage;
                    const endIndex = startIndex + perPage;

                    // Hide all review containers
                    reviewContainers.forEach(container => {
                        container.style.display = 'none';
                    });

                    // Show review containers for the current page
                    for (let i = startIndex; i < endIndex && i < reviewContainers.length; i++) {
                        reviewContainers[i].style.display = 'table-row';
                    }

                    // Update active page button
                    const pageButtons = pagination.querySelectorAll('.page-button');
                    pageButtons.forEach(button => {
                        button.classList.remove('active-page');
                        button.classList.remove('adjacent'); // Remove adjacent class from all buttons
                        if (parseInt(button.textContent) === pageNumber) {
                            button.classList.add('active-page');
                            // Add adjacent class to the two buttons preceding and following the active page button
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
                    const startIndex = (currentPage - 1) * perPage + 1;
                    const endIndex = Math.min(startIndex + perPage - 1, reviewContainers.length);

                    const resultIndexesElement = document.createElement('div');
                    resultIndexesElement.textContent = `Showing ${startIndex} to ${endIndex} of ${reviewContainers.length} results`;
                    
                    const noResultsPage = document.querySelector('.no_results_page');
                    noResultsPage.innerHTML = ''; // Clear existing content
                    noResultsPage.appendChild(resultIndexesElement);
                }

                function createPagination() {
                    const totalReviews = reviewContainers.length;
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
                        <a href="index.php">Home</a> > <a href="index.php">Menu</a> > <a class="active">Item List</a>
                    </div>
                    <div class="page_title">All Items</div>
                    <div class="search_container">
                        <div class="item_search">
                            <i class="fas fa-search"></i>
                            <input type="text" class="search_bar" placeholder="Search item...">
                            <select id="perPage">
                                <option value="5" selected>5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                            </select>
                            <label for="perPage" id="perPageLabel">Show per page</label>
                        </div>
                        <a href="items-add.php"><div class="add_button"><i class="fas fa-plus"></i>New Item</div></a>
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
                        echo "<td class='t_image' style='height: 90px;'><img src='../../images/placeholder_image.png'></td>";
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
                        $sql4 = "SELECT * FROM menu_customization WHERE custom_ID = {$option} AND trash = 0 LIMIT 1";
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

                    echo'</i></a><a><i class="fas fa-trash"></i></a></div>';
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No items found</td></tr>";
            }

            $conn->close();
            ?>
           </tbody> 
        </table>
        <div class="navigation_container">
            <div id="pagination"></div>
            <div class="no_results_page">
                <span>Showing 1 to 25 of 52 results</span>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
</body>
</html>


