<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin List | Admin Panel</title>
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
            
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $admin_ids = $_POST['admin_ID'];
                $trashes = $_POST['trash_item'];

                if(isset($_POST['submit_trash_items'])){
                    $FailedUpdate = 0;

                    for($i=0;$i<count($admin_ids);$i++){
                        $admin_id = $admin_ids[$i];
                        $trash = $trashes[$i];

                        $trash_sql = "UPDATE admin SET trash = {$trash} WHERE admin_ID = $admin_id AND trash = 0";
                        
                        if ($conn->query($trash_sql) !== TRUE) {
                            $FailedUpdate = 1;
                            break;
                        }
                    }
                    if ($FailedUpdate == 0) {
                        $_SESSION['deleteAdmin_success'] = true;
                        header("Location: admins-all.php");
                        exit();
                    }
                    else{
                        $_SESSION['deleteAdmin_error'] = true;
                        header("Location: admins-all.php");
                        exit();
                    }
                }
            }
            if (isset($_SESSION['deleteAdmin_success'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast true fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Successfully deleted selected admin(s)!
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['deleteAdmin_success']);
            }

            if (isset($_SESSION['deleteAdmin_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Failed to delte admin(s). Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['deleteAdmin_error']);
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
                const levelFilter = document.getElementById('levelFilter');
                const presenceFilter = document.getElementById('presenceFilter');
                const clearAllButton = document.querySelector('.filter_header .clear');
                let currentPage = 1;
                let searchTerm = '';
                let selectedlevelFilter = 'all';
                let selectedpresenceFilter = 'all';

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

                    // Filter admin based on level
                    if (selectedlevelFilter !== 'all') {
                        filteredContainers = filteredContainers.filter(container => {
                            const level = container.querySelector('.t_level').textContent.trim();
                            if (selectedlevelFilter === '1') {
                                return level === 'Admin';
                            } else if (selectedlevelFilter === '2') {
                                return level === 'Superadmin';
                            }
                        });
                    }

                    // Filter admin based on presence
                    if (selectedpresenceFilter !== 'all') {
                        filteredContainers = filteredContainers.filter(container => {
                            const presence = container.querySelector('.t_presence i')
                            if (selectedpresenceFilter === '0') {
                                return presence.classList.contains('fa-circle') && presence.classList.contains('color-red');
                            } else if (selectedpresenceFilter === '1') {
                                return presence.classList.contains('fa-circle')  && presence.classList.contains('color-green');
                            }
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

                    if (selectedlevelFilter !== 'all') {
                        filteredContainers = filteredContainers.filter(container => {
                            const level = container.querySelector('.t_level').textContent.trim();
                            if (selectedlevelFilter === '1') {
                                return level === 'Admin';
                            } else if (selectedlevelFilter === '2') {
                                return level !== 'Superadmin';
                            }
                        });
                    }

                    if (selectedpresenceFilter !== 'all') {
                        filteredContainers = filteredContainers.filter(container => {
                            const presence = container.querySelector('.t_presence i')
                            if (selectedpresenceFilter === '0') {
                                return presence.classList.contains('fa-circle') && presence.classList.contains('color-red');
                            } else if (selectedpresenceFilter === '1') {
                                return presence.classList.contains('fa-circle')  && presence.classList.contains('color-green');
                            }
                        });
                    }

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

                function clearAllFilters() {
                    // Clear search term
                    searchTerm = '';
                    levelFilter.value = 'all';
                    selectedlevelFilter = 'all';
                    presenceFilter.value = 'all';
                    selectedpresenceFilter = 'all';

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

                levelFilter.addEventListener('change', function() {
                    selectedlevelFilter = levelFilter.value;
                    currentPage = 1;
                    showPage(currentPage);
                    createPagination();
                });

                presenceFilter.addEventListener('change', function() {
                    selectedpresenceFilter = presenceFilter.value;
                    currentPage = 1;
                    showPage(currentPage);
                    createPagination();
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
        <div class="container-fluid">
            <div class="col-12 m-auto">
                <div class="admin_page">
                     <div class="breadcrumbs">
                        <a>Admin</a> > <a>Users</a> > <a class="active">Admin List</a>
                    </div>
                    <div class="page_title">All Admins</div>
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
                        </div>
                        <div>
                            <div class="filter_type">
                                <label for="levelFilter">Filter by Admin Level</label>
                                <select id="levelFilter">
                                    <option value="all">All</option>
                                    <option value="1">Admin</option>
                                    <option value="2">Superadmin</option>
                                </select>
                            </div>
                            <div class="filter_type">
                                <label for="presenceFilter">Filter by Presence</label>
                                <select id="presenceFilter">
                                    <option value="all">All</option>
                                    <option value="1">Online</option>
                                    <option value="0">Offline</option>
                                </select>
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
                            <input type="submit" name="submit_trash_items" class="delete_button" id="submit_trash_items" value="Remove Admins" onclick="return confirmAction('delete the selected menu admin(s)')">
                            <a href="admins-add.php"><div class="add_button">New Admin</div></a>
                        </div>
                    </div>
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap mb-0 rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="t_no">No.</th>
                                            <th class="t_name">Name</th>
                                            <th class="t_username">Username</th>
                                            <th class="t_email">Email</th>
                                            <th class="t_level">Level</th>
                                            <th class="t_presence">Presence</th>
                                            <th class="t_action act1">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody border="transparent">
                                        <?php
                                            $no_count = 0;
                                            $admin_query = "SELECT * FROM admin WHERE trash = 0";
                                            $result = $conn->query($admin_query);
                                            if($result && $result->num_rows > 0){
                                                while ($row = $result->fetch_assoc()) {
                                                    $no_count++;
                                                    echo "<tr";
                                                    echo"><td class='t_no'>".$no_count."</td>";
                                                    echo "<td class='t_name'>".$row['admin_name']."</td>";
                                                    echo "<td class='t_username'>".$row['admin_username']."</td>";
                                                    echo "<td class='t_email'>".$row['admin_email']."</td>";
                                                    echo "<td class='t_level'>";
                                                    if($row['admin_level'] == 1){
                                                        echo "Admin";
                                                    }else{
                                                        echo "Superadmin";
                                                    }
                                                    echo "</td>";
                                                    echo "<td class='t_presence'>";
                                                    if($row['admin_active'] == 1){
                                                        echo "<i class='fas fa-circle color-green' style='color: #5a9498;'></i>";
                                                    }else{
                                                        echo "<i class='fas fa-circle color-red' style='color: #e77468;'></i>";
                                                    }
                                                    echo "</td>";
                                                    echo '<td class="t_action act1"><div><a href="admins-edit.php?ID=' . $row['admin_ID'] . '"><i class="fas fa-pen"></i></a><a style="position: relative;">';
                                                    echo "</i></a><a class='trash-icon'><i class='fas fa-trash'></i></a>
                                                        <input type='hidden' name='admin_ID[]' value='".$row['admin_ID']."'>
                                                        <input type='hidden' class='trash-item-input' name='trash_item[]' value='0' style='display:block;'>
                                                        </div>";
                                                     echo "</tr>";
                                                }
                                            }
                                            else {
                                                echo "<tr><td class='no_items' colspan='7'><i class='far fa-ghost'></i>No admins found...</td></tr>";
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