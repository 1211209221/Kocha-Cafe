<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog List | Admin Panel</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="icon" href="../images/logo/logo_icon_2.png">
    <script src="../script.js"></script>
    <script src="../gototop.js"></script>
    <?php
    include '../connect.php';
    include '../gototopbtn.php';
    include 'navbar.php';
    ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('Blog-Writing.jpg');
            background-size: cover;
            filter: blur(1.5);
            background-position: center;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            color: black;
            text-shadow: black;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 2%;
        }

        .post {
            width: 350px;
            height: 350px;
            overflow: hidden;
            box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 8px;
            box-sizing: border-box;
            background-color: white;
            border-radius: 10px;
            position: relative;
            flex: 0 0 32%;
            max-width: 32%;
            text-overflow: ellipsis;
            white-space: normal;
        }

        .post h2 {
            font-weight: 800;
            line-height: 1.0;
            margin-top: 0;
            font-size: 27px;
            display: flex;
            align-items: center;

        }

        .post p {
            margin-top: 8px;
        }

        .post img {
            padding-top: 7px;
            width: 100%;
            height: auto;
        }

        .breadcrumbs {
            margin-bottom: 20px;
        }

        .page_title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .big_container {
            margin-bottom: 20px;
        }

        .container_header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .container_header i {
            margin-right: 10px;
        }

        .navigation_container {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .no_results_page {
            font-size: 14px;
        }

        .select1,
        .select2 {
            width: 200px;
            margin-bottom: 10px;
        }

        .input2 {
            width: 200px;
            margin-bottom: 10px;
        }

        .button_1,
        .button_2 {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            cursor: pointer;
            color: white;
            margin-right: 10px;
        }

        .icon_button1 {
            padding: 8px;
            background-color: #5a9498;
            border: none;
            cursor: pointer;
            color: white;
            margin-top: 10px;
            margin-right: 10px;
            border-radius: 50%;
            font-size: 16px;
            transition: 0.15s;
            /* Adjusted font size for the pen icon */
        }

        .icon_button1:hover {
            transform: scale(1.1);
            background-color: #36676A;
        }

        .admin_page {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        /* New Style for Blog Type Button */
        .blog_type_button {
            font-weight: 800;
            padding: 4px 15px;
            /* Increased padding for better button appearance */
            background-color: #E2857B;
            /* Green color */
            border: none;
            cursor: pointer;
            color: white;
            margin-top: 10px;
            border-radius: 5px;
            width: 100px;
            /* Make the button fill the width of its container */
            text-align: center;
            /* Center-align the text */
            box-sizing: border-box;
            /* Include padding and border in button's total width */
            position: absolute;
            top: 20px;
            right: 20px;
            outline: none;
            pointer-events: none;
        }

        .post img {
            width: 100%;
            height: 200px;
            /* Adjust the height as needed */
            object-fit: cover;
        }

        p {
            margin-top: 0px !important;
        }

        .all_items .filter_selectors label {
            color: #495057;
            margin-bottom: -4px;
        }

        .all_items .filter_selectors select {
            width: 50px;
        }

        .filter_type {
            top: -5px;
            position: relative;
        }

        .filter_type input {
            margin-right: 5px !important;
            width: 15px;
            height: 15px;
            accent-color: #5a9498;
            top: 2px;
            position: relative;
            cursor: pointer;
        }

        .input2 {
            background-color: #e9ecef;
            border: #e9ecef 1px solid;
            border-radius: 6px;
            padding-left: 5px;
            font-size: 18px;
        }

        .button_1 {

            padding: 4px 10px;
            width: fit-content;
            background-color: #5a9498;
            color: white;
            border-radius: 8px;
            font-weight: 800;
            font-size: 17px;
            transition: 0.15s;
            text-decoration: none;
            align-items: center;
            display: flex;
        }

        .button_1:hover {
            background-color: #E2857B;
        }

        #searchKeyword {
            width: 200px;
            /* Adjust the width as needed */
        }

        #perPageLabel {
            margin-right: 20px;
        }

        .select2 {
            width: 100px;
            margin-right: 10px;
        }

        @media screen and (max-width: 1200px) {
            .icon_button1 {
                margin-top: 0px;
            }
            .post {
                flex: 0 0 48.25%;
                max-width: 48.25%;
            }

            .row {
                gap: 3.5%;
            }
        }

        @media screen and (max-width: 768px) {
            .dateFilters {
                display: block !important;
            }
            .post {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .row {
                gap: 0%;
            }
        }

        @media screen and (max-width: 575px) {
            .icon_button1 {
                margin-top: 10px;
            }
        }

        .filter_selectors input[type="text"] {
            margin: 50px 0px;
            width: 250px;
        }

        .search_button {
            padding: 4px 10px;
            width: fit-content;
            background-color: #5a9498;
            color: white;
            border-radius: 8px;
            font-weight: 800;
            font-size: 17px;
            transition: 0.15s;
            text-decoration: none;
            align-items: center;
            display: flex;
        }

        .search_button:hover {
            background-color: #36676A;
            transform: scale(1.03);
        }

        #pagination {
            box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.1);
        }

        .page-button {
            background-color: white !important;
            border: 5px white solid !important;
        }

        .page-button:hover {
            background-color: #d0d5d9 !important;
            border: 5px #d0d5d9 solid !important;
        }

        div.active-page {
            background-color: #d0d5d9 !important;
            border: 5px #d0d5d9 solid !important;
            transform: scale(1.03) !important;
        }

        .filter_type span {
            margin-left: 17px;
            flex: 0 0 10%;
        }

        .filter_name {
            font-size: 18px;
            font-weight: 800;
            color: #5a9498;
        }

        .dateFilters {
            display: flex;
        }

        .dateFilters input {
            margin-right: 40px;
        }

        .dateFilters label {
            color: #5a9498 !important;
            flex: 0 0 80px !important;
        }

        .dateFilters span {
            display: flex;
        }

        .all_items .filter_selectors {
            padding-bottom: 5px;
        }

        .blog-contents{
            white-space: normal;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .no_items{
            width: 100%;
            justify-content: center;
            height: 200px;
            align-items: center;
        }
    </style>
</head>

<body>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reviewContainers = document.querySelectorAll('.post');
            const perPageSelector = document.getElementById('perPage');
            const pagination = document.getElementById('pagination');
            const discountFilter = document.getElementById('discountFilter');
            const newsFilter = document.getElementById('newsFilter');
            const updatesFilter = document.getElementById('updatesFilter');
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
                const totalReviews = reviewContainers.length;
                const perPage = parseInt(perPageSelector.value);
                const totalPages = Math.ceil(totalReviews / perPage);
                pagination.innerHTML = '';

                // Previous Button
                const prevButton = document.createElement('div');
                prevButton.textContent = 'Previous';
                prevButton.classList.add('page-button');
                prevButton.addEventListener('click', function () {
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
                    pageButton.addEventListener('click', function () {
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
                nextButton.addEventListener('click', function () {
                    if (currentPage < totalPages) {
                        currentPage++;
                        showPage(currentPage);
                    }
                });
                pagination.appendChild(nextButton);
            }

            function applyFilters() {
                const discountChecked = discountFilter.checked;
                const newsChecked = newsFilter.checked;
                const updatesChecked = updatesFilter.checked;

                // Show all posts if no filters are checked
                if (!discountChecked && !newsChecked && !updatesChecked) {
                    reviewContainers.forEach(container => {
                        container.style.display = 'table-row';
                    });
                    displayResultIndexes();
                    return;
                }

                reviewContainers.forEach(container => {
                    const isDiscount = container.classList.contains('discount');
                    const isNews = container.classList.contains('news');
                    const isUpdates = container.classList.contains('updates');

                    if ((discountChecked && isDiscount) || (newsChecked && isNews) || (updatesChecked && isUpdates)) {
                        container.style.display = 'table-row';
                    } else {
                        container.style.display = 'none';
                    }
                });

                displayResultIndexes();
            }

            perPageSelector.addEventListener('change', function () {
                currentPage = 1;
                showPage(currentPage);
                createPagination();
            });

            discountFilter.addEventListener('change', applyFilters);
            newsFilter.addEventListener('change', applyFilters);
            updatesFilter.addEventListener('change', applyFilters);

            showPage(currentPage);
            createPagination();
        });
    </script>

    <div class="container-fluid">
        <div class="col-12 m-auto">
            <div class="all_items">
                <div class="breadcrumbs">
                    <a>Admin</a> > <a>Blog</a> > <a class="active">Blog List</a>
                </div>
                <div class="page_title">All Blog</div>
                <div class="filter_selectors">
                    <div class="menu">
                        <div class="filter_header">
                            <div class="d-flex flex-row align-items-baseline">
                                <i class="fas fa-sliders-h"></i><span>Filters</span>
                            </div>
                            <div class="clear" onclick="clearAllFilters()">
                                <div>Clear all</div>
                            </div>
                        </div>
                        <hr class="mt-1">
                        <span class="filter_name">Menu Categories</span>
                        <div class="filter_type">
                            <span class="d-flex align-items-center">
                                <input type="checkbox" id="discountFilter" class="filter-checkbox">
                                <label for="discountFilter">Discount</label>
                            </span>
                            <span class="d-flex align-items-center">
                                <input type="checkbox" id="newsFilter" class="filter-checkbox">
                                <label for="newsFilter">News</label>
                            </span>
                            <span class="d-flex align-items-center">
                                <input type="checkbox" id="updatesFilter" class="filter-checkbox">
                                <label for="updatesFilter">Updates</label>
                            </span>
                        </div>
                        <hr class="mt-2" style="margin-bottom: 15px;">
                        <div class="row">
                            <div class="col-md-3 dateFilters">
                                <span>
                                    <label for="startDate">Start Date</label>
                                    <input type="date" id="startDate" class="input2">
                                </span>
                                <span>
                                    <label for="endDate">End Date</label>
                                    <input type="date" id="endDate" class="input2">
                                </span>
                            </div>
                        </div>

                    </div> <!-- Closing row -->

                </div>
                <div class="search_container">
                    <div class="item_search">
                        <i class="fas fa-search"></i>
                        <input type="text" class="search_bar" name="keywordSearch" id="keywordSearch"
                            placeholder="Search item...">
                        <select id="perPage">
                            <option value="6">6</option>
                            <option value="9" selected>9</option>
                            <option value="18">18</option>
                            <option value="30">30</option>
                        </select>
                        <label for="perPage" id="perPageLabel"><span>Shown</span> per page</label>
                    </div>
                    <?php
                    if ($admin['admin_level'] == 2) {
                        echo '<a href="blogs-add.php">
                                <div class="add_button">New Blog</div>
                                </a>';
                    }
                    ?>
                </div>
            </div>
            <div class="row" style="margin: 0;">
                <?php
                // Update SQL to exclude blogs with trash set to 1
                $sql = "SELECT blog_ID, blog_title, blog_contents, image, file, date, blog_type FROM blog WHERE trash = 0";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='post";
                        if ($row["blog_type"] == "Discount") {
                            echo " discount";
                        } elseif ($row["blog_type"] == "News") {
                            echo " news";
                        } elseif ($row["blog_type"] == "Updates") {
                            echo " updates";
                        }
                        echo "'>";
                        echo "<div><img src='" . $row["image"] . "' alt='Blog Image'></div>"; // Adjusted image size
                        echo "<h2 style='justify-content: space-between;' class='mb-0'><span>" . $row["blog_title"] . "</span><span><a href='blogs-edit.php?id=" . $row["blog_ID"] . "'><i class='fas fa-pen icon_button1'></i></a></span></h2>";
                        echo "<p class='mb-0' style='color: #95bfbe; font-weight: 900;'>" . $row["date"] . "</p>";
                        echo "<div class='blog-contents'><p>" . $row["blog_contents"] . "</p></div>";
                        echo "<button class='blog_type_button'>" . $row["blog_type"] . "</button>"; // Converted blog type to button
                        echo "</div>"; // Closing individual post container
                    }
                } else {
                    echo '<div class="no_items" style="display: flex;"><i class="far fa-ghost"></i>No blogs...</div>';
                }



                ?> <!-- Closing container -->
                <script>
                    function applySearchFilters() {
                        const searchKeyword = document.getElementById('keywordSearch').value.trim().toLowerCase();
                        const startDate = document.getElementById('startDate').value.trim(); // Get start date
                        const endDate = document.getElementById('endDate').value.trim(); // Get end date

                        const filteredContainers = document.querySelectorAll('.post');

                        filteredContainers.forEach(container => {
                            const titleContent = container.querySelector('h2 span').textContent.trim().toLowerCase();
                            const postDate = container.querySelector('p:nth-of-type(1)').textContent.trim();
                            const formattedDate = new Date(postDate).toISOString().slice(0, 10);

                            const isKeywordMatch = searchKeyword === '' || titleContent.includes(searchKeyword);
                            const isStartDateMatch = startDate === '' || formattedDate >= startDate;
                            const isEndDateMatch = endDate === '' || formattedDate <= endDate;

                            if (isKeywordMatch && isStartDateMatch && isEndDateMatch) {
                                container.style.display = 'block';
                            } else {
                                container.style.display = 'none';
                            }
                        });
                    }

                    // Add event listeners to the input fields to trigger the search
                    document.getElementById('keywordSearch').addEventListener('input', applySearchFilters);
                    document.getElementById('startDate').addEventListener('input', applySearchFilters);
                    document.getElementById('endDate').addEventListener('input', applySearchFilters);


                    function clearAllFilters() {
                        document.getElementById('keywordSearch').value = '';
                        document.getElementById('startDate').value = '';
                        document.getElementById('endDate').value = '';

                        const checkboxes = document.querySelectorAll('.filter-checkbox');
                        checkboxes.forEach(checkbox => checkbox.checked = false);

                        applySearchFilters();
                    }
                </script>
            </div>
            <div class="navigation_container">
                <div id="pagination"></div>
                <div class="no_results_page">
                    <span>Showing to of results</span>
                </div>
            </div>


</body>

</html>