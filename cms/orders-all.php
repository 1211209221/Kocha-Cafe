<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order List | Admin Panel</title>
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
        <link rel="icon" href="../images/logo/logo_icon_2.png">
        <script src="../script.js"></script>
        <script src="../gototop.js"></script>
    </head>
    <body>
    <style>
            table tr .t_no{
                display:none;
            }
            table tr .t_id{
                width: 9%;
                padding-left:15px !important;
                border-top-left-radius: 7px;
                border-bottom-left-radius: 7px;
                
            }
            table tbody tr .t_id{
                font-size:16px;
            }
            table tr .t_name{
                width: 10%;
            }
            table tr .t_item{
                width: 15%;
            }
            table tr .t_status{
                width: 8%;
                text-align:center;
            }
            table tr .t_date{
                width: 10%;
            }
            table tbody tr .t_date{
                font-size:16px;
            }
            .sta-queue{
                padding: 2px 5px;
                border: 2px solid #ff984f;
                border-radius: 15px;
                font-size: 16px;
                color: #ff984f;
                display: ruby-text;
                margin: 3px;
            }
            .sta-prepare{
                padding: 2px 5px;
                border: 2px solid #6d98bc;
                border-radius: 15px;
                font-size: 16px;
                color: #6d98bc;
                display: ruby-text;
                margin: 3px;
            }
            .sta-deliver{
                padding: 2px 5px;
                border: 2px solid #00b7c6;
                border-radius: 15px;
                font-size: 16px;
                color: #00b7c6;
                display: ruby-text;
                margin: 3px;
            }
            .sta-receive{
                padding: 2px 5px;
                border: 2px solid #5a9498;
                border-radius: 15px;
                font-size: 16px;
                color: #5a9498;
                display: ruby-text;
                margin: 3px;
            }
            .t_status p{
                margin:unset;
            }
            .t_status i{
                margin-right:5px;
                font-size: 14px;
            }
            @media (max-width: 575px) {
                table tr .t_id{
                    padding-left:8px !important;
                }
                table tbody tr .t_date{
                    font-size: 14px;
                }
                table tr .t_item{
                    display:none;
                }
                .t_status p{
                    display:none;
                }
                .t_status i{
                    margin:3px;
                    font-size:13px;
                }
                table tbody tr .t_id{
                    font-size:14px;
                }
            }
            @media (max-width: 480px) {
                

            }
            
        </style>
    <?php
            include '../connect.php';
            include '../gototopbtn.php';

            session_start();
            
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $CF_IDs = $_POST['CF_ID'];
                $trashes = $_POST['trash_item'];

                if(isset($_POST['submit_trash_items'])){
                    $FailedUpdate = 0;

                    for($i=0;$i<count($CF_IDs);$i++){
                        $CF_ID = $CF_IDs[$i];
                        $trash = $trashes[$i];

                        $trash_sql = "UPDATE contact_message SET trash = {$trash} WHERE CF_ID = $CF_ID AND trash = 0";
                        
                        if ($conn->query($trash_sql) !== TRUE) {
                            $FailedUpdate = 1;
                            break;
                        }
                    }
                    if ($FailedUpdate == 0) {
                        $_SESSION['deletemessage_success'] = true;
                        header("Location: messages-all.php");
                        exit();
                    }
                    else{
                        $_SESSION['deletemessage_error'] = true;
                        header("Location: messages-all.php");
                        exit();
                    }
                }
            }
            if (isset($_SESSION['delmsg_success'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast true fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Successfully deleted the message!
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['delmsg_success']);
            }

            if (isset($_SESSION['delmsg_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Failed to delete message. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['delmsg_error']);
            }
            if (isset($_SESSION['deletemessage_success'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast true fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Successfully deleted selected message(s)!
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['deletemessage_success']);
            }

            if (isset($_SESSION['deletemessage_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Failed to delete message(s). Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['deletemessage_error']);
            }


            include 'navbar.php';
        ?>
        
        <div class="container-fluid">
            <div class="col-12 m-auto">
                <div class="admin_page">
                     <div class="breadcrumbs">
                        <a>Admin</a> > <a>Orders</a> > <a class="active">Order List</a>
                    </div>
                    <div class="page_title">All Orders</div>
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
                                <label for="dateFilter">Sort by Date</label>
                                <select id="dateFilter">
                                    <option value="1">Oldest</option>
                                    <option value="2">Latest</option>
                                </select>
                            </div>
                            <div class="filter_type">
                                <label for="statusFilter">Filter by Order Status</label>
                                <select id="statusFilter">
                                    <option value="all">All</option>
                                    <option value="0">Queueing</option>
                                    <option value="1">Preparing</option>
                                    <option value="2">Delivering</option>
                                    <option value="3">Received</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="search_container">
                        <div class="item_search">
                            <i class="fas fa-search"></i>
                            <input type="text" class="search_bar" name="keywordSearch" id="keywordSearch" placeholder="Search order ID...">
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
                            <input type="submit" name="submit_trash_items" class="delete_button" id="submit_trash_items" value="Delete Order" onclick="return confirmAction('delete the selected order(s)')">
                        </div>
                    </div>
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap mb-0 rounded" id="dataTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="t_no">No.</th>
                                            <th class="t_id">ID</th>
                                            <th class="t_name">User</th>
                                            <th class="t_item">Items</th>
                                            <th class="t_date">Date</th>
                                            <th class="t_status">Status</th>
                                            <th class="t_action act1">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody border="transparent">
                                        <?php
                                            $no_count = 0;

                                            $cf_query = "SELECT * FROM customer_orders WHERE trash = 0";

                                            $result = $conn->query($cf_query);
                                            if($result && $result->num_rows > 0){
                                                while ($row = $result->fetch_assoc()) {
                                                    $no_count++;
                                                    $date = date('Y-m-d H:i:s', strtotime($row['order_date']));
                                                    if($row['tracking_stage']==3){
                                                        echo "<tr class='unavailable'"; 
                                                    }
                                                    echo "<tr";
                                                    echo"><td class='t_no'>".$no_count."</td>";
                                                    $order_id = "K_".$row['order_ID'];
                                                    echo "<td class='t_id'>".$order_id."</td>";
                                                    $cust_query = "SELECT cust_username FROM customer WHERE trash = 0 AND cust_ID = ".$row['cust_ID'];
                                                    $query_result = $conn->query($cust_query);
                                                    $query_row = $query_result->fetch_assoc();
                                                    if($query_row && !empty($query_row['cust_username'])){
                                                        $username = $query_row['cust_username'];
                                                    }
                                                    else{
                                                        $username = "User is disabled.";
                                                    }
                                                    echo "<td class='t_name'>".$username."</td>";
                                                    echo "<td class='t_item'>...</td>";
                                                    echo "<td class='t_date'>".$date."</td>";
                                                    if($row['tracking_stage']==0){
                                                        echo "<td class='t_status'><span class='sta-queue'><i class='fas fa-boxes'></i><p>Queueing</p></span></td>";
                                                    }
                                                    else if($row['tracking_stage']==1){
                                                        echo "<td class='t_status'><span class='sta-prepare'><i class='fas fa-box-open'></i><p>Preparing</p></span></td>";
                                                    }
                                                    else if($row['tracking_stage']==2){
                                                        echo "<td class='t_status'><span class='sta-deliver'><i class='fas fa-truck-loading'></i><p>Delivering</p></span></td>";
                                                    }
                                                    else{
                                                        echo "<td class='t_status'><span class='sta-receive'><i class='fas fa-check-square'></i><p>Received</p></span></td>";
                                                    }
                                                    echo '<td class="t_action act1"><div><a class="trash-icon"><i class="fas fa-trash"></i></a><a href="orders-view.php?ID=' . $row['order_ID'] . '"><i class="fas fa-chevron-circle-right"></i></a><a style="position: relative;">';
                                                    echo "</i></a>
                                                        <input type='hidden' name='order_ID[]' value='".$row['order_ID']."'>
                                                        <input type='hidden' class='trash-item-input' name='trash_item[]' value='0' style='display:block;'>
                                                        </div>";
                                                     echo "</tr>";
                                                }
                                            }
                                            else {
                                                echo "<tr><td class='no_items' colspan='7'><i class='far fa-ghost'></i>No messages found...</td></tr>";
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
                const dateFilter = document.getElementById('dateFilter');
                const statusFilter = document.getElementById('statusFilter');
                const clearAllButton = document.querySelector('.filter_header .clear');
                let currentPage = 1;
                let searchTerm = '';
                let selecteddateFilter = '1';
                let selectedstatusFilter = 'all';

                function sortTable() {
                    var table = document.getElementById("dataTable");
                    var rows = table.rows;

                    var dataRows = [];
                    for (var i = 1; i < rows.length; i++) {
                        var row = rows[i];
                        var dateCell = row.cells[4]; // Date column
                        var dateString = dateCell.textContent.trim(); // Get date string from the table cell
                        var dateObject = new Date(dateString); // Parse date string into Date object
                        dataRows.push({ row: row, date: dateObject });
                    }

                    dataRows.sort(function(a, b) {
                    var dateA = a.date.getTime();
                    var dateB = b.date.getTime();
                    if (selecteddateFilter === '1') {
                        return dateA - dateB; // Ascending order (oldest to latest)
                    } else if (selecteddateFilter === '2') {
                        return dateB - dateA; // Descending order (latest to oldest)
                    }
                });

                    // Reorder table rows based on sorted dataRows
                    var tbody = table.querySelector('tbody');
                    dataRows.forEach(function(dataRow) {
                        tbody.appendChild(dataRow.row);
                    });
                }


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


                    // Filter status
                    if (selectedstatusFilter !== 'all') {
                        filteredContainers = filteredContainers.filter(container => {
                            const status = container.querySelector('.t_status').textContent.trim();
                            if (selectedstatusFilter === '0') {
                                return status === "0";
                            } else if (selectedstatusFilter === '1') {
                                return status === "1";
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


                    if (selectedstatusFilter !== 'all') {
                        filteredContainers = filteredContainers.filter(container => {
                            const status = container.querySelector('.t_status').textContent.trim();
                            if (selectedstatusFilter === '0') {
                                return status === "0";
                            } else if (selectedstatusFilter === '1') {
                                return status === "1";
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
                    dateFilter.value = '1';
                    selecteddateFilter = '1';
                    statusFilter.value = 'all';
                    selectedstatusFilter = 'all';

                    // Trigger filter update
                    currentPage = 1;
                    //updateURL();
                    showPage(currentPage);
                    createPagination();
                }

                perPageSelector.addEventListener('change', function() {
                    currentPage = 1;
                    showPage(currentPage);
                    createPagination();
                });

                dateFilter.addEventListener('change', function() {
                    selecteddateFilter = dateFilter.value;
                    
                    currentPage = 1;
                    showPage(currentPage);
                    sortTable();
                    createPagination();
                });

                statusFilter.addEventListener('change', function() {
                    selectedstatusFilter = statusFilter.value;
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
                sortTable();
                createPagination();

            });
        </script>
    </body>
</html>