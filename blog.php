<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Blog | Kocha Cafe</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="icon" href="images/logo/logo_icon.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
    <script src="gototop.js"></script>
    <?php
        include 'connect.php';
        include 'top.php';
    ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('Blog-Writing.jpg');
            background-size: cover;
            filter: blur(1.5);
            background-position: center;
            min-height: 900px;
        }

        .container {
            margin: 0 auto;
            position: relative;
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
            margin-left: 0;
            margin-right: 0;
        }

        .post {
            width: 100%;
            height: 350px;
            overflow: hidden;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
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
            transition: 0.15s;
        }

        .post:hover{
            transform: scale(1.015);
            cursor: pointer;
        }

        .post img {
            width: 100%;
            height: 200px;
            /* Set a fixed height for the image */
            object-fit: cover;
            /* Ensure the image covers the entire area without distortion */
            border-radius: 8px 8px 0 0;
        }

        .post h2 {
            font-weight: 800;
            line-height: 1.0;
            margin-top: 5px;
            font-size: 27px;
        }

        .post p {
            flex-grow: 1;
            /* Allow the content to grow and fill the available space */
        }

        .blog-type-button {
            pointer-events: none;
            font-weight: 800;
            padding: 4px 15px;
            background-color: #E2857B;
            border: none;
            cursor: pointer;
            color: white;
            border-radius: 5px;
            width: 100px;
            text-align: center;
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1;
        }

        .blog-type-button:hover {
            background-color: #E2857B;
            opacity: 1;
            transform: scale(1.035);
            transition: 0.5s;
        }
        .post a{
            color: inherit;
            text-decoration: none;
        }
        .blog-contents {
            white-space: normal;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .blog_type_button {
            font-weight: 800;
            padding: 4px 15px;
            background-color: #E2857B;
            border: none;
            cursor: pointer;
            color: white;
            margin-top: 10px;
            border-radius: 5px;
            width: 100px;
            text-align: center;
            box-sizing: border-box;
            position: absolute;
            top: 10px;
            right: 20px;
            outline: none;
            pointer-events: none;
        }

        .filter_selectors input[type="text"] {
            margin: 50px 0px;
            width: 250px;
        }

        .search_bar{
            background-color: #EDEDED;
            border: #EDEDED 1px solid;
            border-radius: 6px;
            padding-left: 33px;
            font-size: 18px;
            box-shadow: none;
            width: 350px;
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

        .page-button {
            background-color: #e9ecef !important;
            border: 5px #e9ecef solid !important;
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

        .filter_type{
            white-space: nowrap;
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
        .search i {
            position: absolute;
            top: 11px;
            left: 15px;
            font-size: 16px;
        }
        .filter_container_2 {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0px;
            margin-top: 10px;
        }
        .filter_container_2 .search_container {
            display: flex;
            position: relative;
            align-items: center;
        }
        .filter_container_2 .search i {
            position: absolute;
            top: 11px;
            left: 15px;
            font-size: 16px;
        }
        .filter_container_2 .search input[type="text"] {
            background-color: #EDEDED;
            border: #EDEDED 1px solid;
            margin: 3px;
            width: 100%;
            border-radius: 6px;
            padding-left: 33px;
            font-size: 18px;
        }
        .filter_container_2 .sort_filter span {
            padding-right: 6px;
            font-weight: 800;
        }
        .filter_container_2 .sort_filter select {
            padding: 2px 10px 0px 3px;
            background-color: #EDEDED;
            border: #EDEDED 1px solid;
            border-radius: 6px;
            font-size: 18px;
        }
        .all_items .item_search input{
            background-color: #EDEDED;
            border: #EDEDED 1px solid;
            border-radius: 6px;
            padding-left: 33px;
            font-size: 18px;
            box-shadow: none;
        }
        .all_items .item_search select {
            padding: 2px 10px 0px 3px;
            background-color: #EDEDED;
            border: #EDEDED 1px solid;
            border-radius: 6px;
            font-size: 18px;
            box-shadow: none;
        }
        .filter-dropdown{
            padding: 2px 10px 0px 3px;
            background-color: #EDEDED;
            border: #EDEDED 1px solid;
            border-radius: 6px;
            font-size: 18px;
            box-shadow: none;
        }
        .categoryTypeFilter{
            font-size: 18px;
            font-weight: 800;
        }
        .navigation_container{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            margin-bottom: 30px;
        }
        #pagination{
            margin-bottom: 0;
        }
        #perPage{
            padding: 2px 10px 0px 3px;
            background-color: #EDEDED;
            border: #EDEDED 1px solid;
            border-radius: 6px;
            font-size: 18px;
            box-shadow: none;
        }
        .no_items{
            width: 100%;
            justify-content: center;
            display: flex;
            height: 300px;
            align-items: center;
        }
        @media screen and (max-width: 1200px) {
            .post {
                flex: 0 0 48.25%;
                max-width: 48.25%;
            }

            .row {
                gap: 3.5%;
            }
        }

        @media screen and (max-width: 1200px) {
            .search_bar{
                width: 295px;
            }
        }

        @media screen and (max-width: 768px) {
            .post {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .row {
                gap: 0%;
            }
            .filter_container_2 .search {
                width: 200px;
                position: relative;
            }
            .filter_type{
                width: 100%;
                display: flex;
                justify-content: end;
                align-items: center;
                margin-bottom: 7px;
            }
        }

        @media screen and (max-width: 480px) {
            .navigation_container{
                flex-direction: column;
                margin: 0;
            }
            .no_results_page{
                margin-top: 5px;
                margin-bottom: 20px;
            }
            #pagination{
                margin-top: 10px;
            }
            .search_bar{
                width: 60vw;
            }
        }

        @media screen and (max-width: 460px) {
            .search_bar{
                width: 50vw;
            }
        }

        @media screen and (max-width: 368) {
            .search_bar{
                width: 40vw;
            }
        }
    </style>
</head>
<script>
    
   function updateDisplay() {
  // Step 1: Select all elements with class '.post'
  const postElements = document.querySelectorAll('.post');

  // Step 2: Filter '.post' elements that are displayed as 'block' or 'table-row'
  const displayedPosts = Array.from(postElements).filter(post => {
    const computedStyle = window.getComputedStyle(post);
    return computedStyle.display === 'block' || computedStyle.display === 'table-row';
  });

  // Step 3: Count the number of displayed '.post' elements
  const displayedPostCount = displayedPosts.length;

  // Step 4: Select all elements with class '.no_items'
  const noItemsElements = document.querySelectorAll('.no_items');

  // Step 5: Set 'display: flex' or 'display: none' to '.no_items' elements based on count
  noItemsElements.forEach(item => {
    // Check if there are displayed posts in block or table-row format
    const hasDisplayedPosts = displayedPostCount > 0;

    if (hasDisplayedPosts) {
      item.style.display = 'none';
    } else {
      item.style.display = 'flex'; 
    }
  });
}

// Execute `updateDisplay` initially
updateDisplay();

// Execute `updateDisplay` every 1 second (adjust as needed)
setInterval(updateDisplay, 0);


</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reviewContainers = document.querySelectorAll('.post');
        const perPageSelector = document.getElementById('perPage');
        const pagination = document.getElementById('pagination');
        const categoryFilter = document.getElementById('categoryFilter');
        let currentPage = 1;

        function showPage(pageNumber) {
            const perPage = parseInt(perPageSelector.value);
            const startIndex = (pageNumber - 1) * perPage;
            const endIndex = startIndex + perPage;

            reviewContainers.forEach(container => {
                container.style.display = 'none';
            });

            for (let i = startIndex; i < endIndex && i < reviewContainers.length; i++) {
                reviewContainers[i].style.display = 'table-row';
            }

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
            noResultsPage.innerHTML = ''; 
            noResultsPage.appendChild(resultIndexesElement);
        }

        function createPagination() {
            const totalReviews = reviewContainers.length;
            const perPage = parseInt(perPageSelector.value);
            const totalPages = Math.ceil(totalReviews / perPage);
            pagination.innerHTML = '';

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

        function applySearchFilters() {
    const searchKeyword = document.getElementById('keywordSearch').value.trim().toLowerCase();
    const categoryFilterValue = document.getElementById('categoryFilter').value;

    const perPage = parseInt(perPageSelector.value);
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = startIndex + perPage;

    reviewContainers.forEach((container, index) => {
        const titleContent = container.querySelector('h2 span').textContent.trim().toLowerCase();

        // Determine if the container should be displayed based on filters
        let isCategoryMatch = false;
        if (categoryFilterValue === 'all') {
            isCategoryMatch = true;
        } else if (
            (categoryFilterValue === 'discount' && container.classList.contains('discount')) ||
            (categoryFilterValue === 'news' && container.classList.contains('news')) ||
            (categoryFilterValue === 'updates' && container.classList.contains('updates'))
        ) {
            isCategoryMatch = true;
        }

        const isKeywordMatch = searchKeyword === '' || titleContent.includes(searchKeyword);

        if (isKeywordMatch && isCategoryMatch) {
            // Show the container if it matches the filters
            if (index >= startIndex && index < endIndex) {
                container.style.display = 'table-row';
            } else {
                container.style.display = 'none';
            }
        } else {
            container.style.display = 'none';
        }
    });

    displayResultIndexes();
    updatePaginationButtons();
}


        perPageSelector.addEventListener('change', function () {
            currentPage = 1;
            showPage(currentPage);
            createPagination();
        });

        categoryFilter.addEventListener('change', applySearchFilters);
        document.getElementById('keywordSearch').addEventListener('input', applySearchFilters);

        showPage(currentPage);
        createPagination();
    });

    </script>

<body>
    <?php
    include 'sidebar.php';
    ?>
    <div class='container' style="min-height: 465px;">
        <div class="breadcrumbs">
            <a href="index.php">Home</a> > <a class="active">Blog</a>
        </div>
        <div class="all_items">
            <div class="search_container">
                    <div class="d-flex justify-content-between" style="width: 100%; white-space: nowrap;">
                        <div style="position: relative;">
                            <i class="fas fa-search" style="position: absolute; left: 8px; top: 8px;"></i>
                            <input type="text" class="search_bar" name="keywordSearch" id="keywordSearch" placeholder="Search blog...">
                        </div>
                        <div class="pl-2">
                            <select id="perPage">
                                <option value="6">6</option>
                                <option value="9" selected>9</option>
                                <option value="18">18</option>
                                <option value="30">30</option>
                            </select>
                            <label for="perPage" id="perPageLabel" style="font-size: 18px; font-weight: 800; margin-left: 4px;">per page</label>
                        </div>
                    </div>
                    <div class="filter_type">
                    <span class="categoryTypeFilter pr-1">Categories</span>
                    <select id="categoryFilter" class="filter-dropdown" style="width: 100px;">
                        <option value="all">All</option>
                        <option value="discount">Discount</option>
                        <option value="news">News</option>
                        <option value="updates">Updates</option>
                    </select>
            </div>
        </div>
    </div>
        <div class="row" style="margin: 0;">
            <?php
                $sql = "SELECT blog_ID, blog_title, blog_contents, image, file, date, blog_type FROM blog WHERE trash = 0";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='post";
                        
                        // Append additional classes based on blog_type
                        if ($row["blog_type"] == "Discount") {
                            echo " discount";
                        } elseif ($row["blog_type"] == "News") {
                            echo " news";
                        } elseif ($row["blog_type"] == "Updates") {
                            echo " updates";
                        }
                        
                        echo "'>";
                        
                        echo "<a href='post.php?ID=" . $row["blog_ID"] . "'>";
                        echo "<div><img src='" . $row["image"] . "' alt='Blog Image'></div>";
                        echo "<h2 style='justify-content: space-between;' class='mb-0'><span>" . $row["blog_title"] . "</span></h2>";
                        echo "<p class='mb-0' style='color: #95bfbe; font-weight: 900; font-size: 18px;'>" . $row["date"] . "</p>";
                        echo "<div class='blog-contents'><p>" . $row["blog_contents"] . "</p></div>";
                        echo "<button class='blog_type_button'>" . $row["blog_type"] . "</button>";
                        echo "</a></div>";
                    }
                } else {
                    echo "0 results";
                }
            ?>
            <div class="no_items"><i class="far fa-ghost"></i>No blogs...</div>
            <script>
                function applySearchFilters() {
                    const searchKeyword = document.getElementById('keywordSearch').value.trim().toLowerCase();
                    const startDate = document.getElementById('startDate').value.trim();
                    const endDate = document.getElementById('endDate').value.trim();
                    const categoryFilter = document.getElementById('categoryFilter').value;

                    const filteredContainers = document.querySelectorAll('.post');

                    filteredContainers.forEach(container => {
                        const titleContent = container.querySelector('h2 span').textContent.trim().toLowerCase();
                        const postDate = container.querySelector('p:nth-of-type(1)').textContent.trim();
                        const formattedDate = new Date(postDate).toISOString().slice(0, 10);
                        const isKeywordMatch = searchKeyword === '' || titleContent.includes(searchKeyword);
                        const isStartDateMatch = startDate === '' || formattedDate >= startDate;
                        const isEndDateMatch = endDate === '' || formattedDate <= endDate;

                        let isCategoryMatch = false;
                        if (categoryFilter === 'all') {
                            isCategoryMatch = true;
                        } else if (categoryFilter === 'discount' && container.classList.contains('discount')) {
                            isCategoryMatch = true;
                        } else if (categoryFilter === 'news' && container.classList.contains('news')) {
                            isCategoryMatch = true;
                        } else if (categoryFilter === 'updates' && container.classList.contains('updates')) {
                            isCategoryMatch = true;
                        }

                        if (isKeywordMatch && isStartDateMatch && isEndDateMatch && isCategoryMatch) {
                            container.style.display = 'block';
                        } else {
                            container.style.display = 'none';
                        }
                    });
                }

                document.getElementById('keywordSearch').addEventListener('input', applySearchFilters);
                document.getElementById('startDate').addEventListener('input', applySearchFilters);
                document.getElementById('endDate').addEventListener('input', applySearchFilters);
                document.getElementById('categoryFilter').addEventListener('change', applySearchFilters);
            </script>
        </div>
        <div class="navigation_container">
            <div id="pagination"></div>
            <div class="no_results_page">
                <span>Showing to of results</span>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>


</html>