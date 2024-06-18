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
    <?php
            
            include '../connect.php';
            include '../gototopbtn.php';
            include 'navbar.php';

            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $order_IDs = $_POST['order_ID'];
                $trashes = $_POST['trash_item'];

                if(isset($_POST['submit_trash_items'])){
                    $FailedUpdate = 0;

                    for($i=0;$i<count($order_IDs);$i++){
                        $order_ID = $order_IDs[$i];
                        $trash = $trashes[$i];

                        $trash_sql = "UPDATE customer_orders SET trash = {$trash} WHERE order_ID = $order_ID AND trash = 0";
                        
                        if ($conn->query($trash_sql) !== TRUE) {
                            $FailedUpdate = 1;
                            break;
                        }
                    }
                    if ($FailedUpdate == 0) {
                        $_SESSION['deleteorder_success'] = true;
                        echo '<script>';
                        echo 'window.location.href = "orders-all.php";';
                        echo '</script>';
                        //header("Location: orders-all.php");
                        exit();
                    }
                    else{
                        $_SESSION['deleteorder_error'] = true;
                        echo '<script>';
                        echo 'window.location.href = "orders-all.php";';
                        echo '</script>';
                        exit();
                    }
                }
            }
            if (isset($_SESSION['delorder_success']) && $_SESSION['delorder_success'] === true) {
                echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Order is deleted successfully!
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';
    
                unset($_SESSION['delorder_success']);
            }
    
            if (isset($_SESSION['delorder_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-times-circle"></i>Failed to delete order. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';
                echo '<div class="error_message">' . $_SESSION['delorder_error'] . '</div>';
    
                unset($_SESSION['delorder_error']);
            }
            if (isset($_SESSION['deleteorder_success'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast true fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-check-circle"></i>Successfully deleted selected order(s)!
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['deleteorder_success']);
            }

            if (isset($_SESSION['deleteorder_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-times-circle"></i>Failed to delete order(s). Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['deleteorder_error']);
            }


            
            if(empty($admin)){
                header("Location: admin.php");
                exit();
            }
        ?>
    <style>
            .search_container{
                display: flex !important;
                align-items: flex-end !important;
            }
            table tr .t_no{
                display:none;
            }
            table thead .t_id{
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
                font-size:16px;
            }
            table tr .t_item ul{
                list-style: none;
                padding: 0;
                margin:5px 0px 5px 0px;
            }
            table tr .t_item ul li span{
                font-weight:400;
                color: #8a8a8a;
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
            .otheritem{
                color: #5a9498;
                font-size: 13px;
                margin: 0 5px 0px 3px;
                font-weight:500;
            }
            .otheritem:hover{
                transform:scale(1.035);
                transition: 0.15s;
                color:#000;
            }
            .t_status p{
                margin:unset;
            }
            .t_status i{
                margin-right:5px;
                font-size: 14px;
            }
            .t_status span{
                font-size:14px
            }
            @media (max-width: 768px) {
                .admin_page .trash-form {
                    display: block;
                }
            }
            @media (max-width: 480px) {
                table tr .t_name{
                    display:none;
                }
            }
            @media (max-width: 575px) {
                table tr .t_id{
                    padding-left:8px !important;
                }
                table tbody tr .t_date{
                    font-size: 14px;
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
            @media (max-width: 800px) {
                table tr .t_item ul li span{
                    padding-left:3px;
                }
                table tr .t_item{
                    display:none;
                }
            }
            @media (max-width: 1040px){
                
                .admin_page .t_action .fa-trash {
                    margin-left: unset;
                }

            } 
            
        </style>
    
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
                let selecteddateFilter = '2';
                let selectedstatusFilter = 'all';

                function sortTable() {
                    const table = document.getElementById("dataTable");
                    const rows = table.rows;

                    const dataRows = [];
                    for (let i = 1; i < rows.length; i++) {
                        const row = rows[i];
                        const dateCell = row.cells[4]; // Date column
                        const dateString = dateCell.textContent.trim(); // Get date string from the table cell
                        const dateObject = new Date(dateString); // Parse date string into Date object
                        dataRows.push({ row: row, date: dateObject });
                    }

                    dataRows.sort(function(a, b) {
                        const dateA = a.date.getTime();
                        const dateB = b.date.getTime();
                        if (selecteddateFilter === '1') {
                            return dateA - dateB; // Ascending order (oldest to latest)
                        } else if (selecteddateFilter === '2') {
                            return dateB - dateA; // Descending order (latest to oldest)
                        }
                    });

                    // Reorder table rows based on sorted dataRows
                    const tbody = table.querySelector('tbody');
                    dataRows.forEach(function(dataRow) {
                        tbody.appendChild(dataRow.row);
                    });

                    updateRowsDisplay();
                }

                function updateRowsDisplay() {
                    const perPage = parseInt(perPageSelector.value);
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
                            const status = container.querySelector('.t_status i');
                            if (selectedstatusFilter === '0') {
                                return status.classList.contains('fa-boxes');
                            } else if (selectedstatusFilter === '1') {
                                return status.classList.contains('fa-box-full');
                            }else if (selectedstatusFilter === '2') {
                                return status.classList.contains('fa-truck-loading');
                            }else if (selectedstatusFilter === '3') {
                                return status.classList.contains('fa-box-check');
                            }

                            
                        });
                    }

                    const startIndex = (currentPage - 1) * perPage;
                    const endIndex = startIndex + perPage;

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

                    displayResultIndexes(filteredContainers.length, startIndex + 1, Math.min(endIndex, filteredContainers.length));
                    updatePagination(filteredContainers.length, perPage);
                }

                function showPage(pageNumber) {
                    currentPage = pageNumber;
                    updateRowsDisplay();
                }

                function displayResultIndexes(totalFiltered, start, end) {
                    const resultIndexesElement = document.createElement('div');
                    resultIndexesElement.textContent = `Showing ${start} to ${end} of ${totalFiltered} results`;

                    const noResultsPage = document.querySelector('.no_results_page');
                    noResultsPage.innerHTML = ''; // Clear existing content
                    noResultsPage.appendChild(resultIndexesElement);
                }

                function updatePagination(totalFiltered, perPage) {
                    const totalPages = Math.ceil(totalFiltered / perPage);
                    pagination.innerHTML = '';

                    // Previous Button
                    const prevButton = document.createElement('div');
                    prevButton.textContent = 'Previous';
                    prevButton.classList.add('page-button', 'previous-button');
                    prevButton.addEventListener('click', function() {
                        if (currentPage > 1) {
                            showPage(currentPage - 1);
                        }
                    });
                    pagination.appendChild(prevButton);

                    // Page Buttons
                    for (let i = 1; i <= totalPages; i++) {
                        const pageButton = document.createElement('div');
                        pageButton.textContent = i;
                        pageButton.classList.add('page-button', 'page');
                        if (i === currentPage) {
                            pageButton.classList.add('active-page');
                        }
                        pageButton.addEventListener('click', function() {
                            showPage(i);
                        });
                        pagination.appendChild(pageButton);
                    }

                    // Next Button
                    const nextButton = document.createElement('div');
                    nextButton.textContent = 'Next';
                    nextButton.classList.add('page-button', 'next-button');
                    nextButton.addEventListener('click', function() {
                        if (currentPage < totalPages) {
                            showPage(currentPage + 1);
                        }
                    });
                    pagination.appendChild(nextButton);

                }

                function clearAllFilters() {
                    // Clear search term
                    searchTerm = '';
                    dateFilter.value = '2';
                    selecteddateFilter = '2';
                    statusFilter.value = 'all';
                    selectedstatusFilter = 'all';

                    sortTable();

                    // Trigger filter update
                    currentPage = 1;
                    showPage(currentPage);
                }

                perPageSelector.addEventListener('change', function() {
                    currentPage = 1;
                    updateRowsDisplay();
                });

                dateFilter.addEventListener('change', function() {
                    selecteddateFilter = dateFilter.value;
                    sortTable();
                    currentPage = 1;
                    showPage(currentPage);
                });

                statusFilter.addEventListener('change', function() {
                    selectedstatusFilter = statusFilter.value;
                    sortTable();
                    currentPage = 1;
                    showPage(currentPage);
                });

                clearAllButton.addEventListener('click', function() {
                    clearAllFilters();
                });

                const searchInput = document.querySelector('.search_bar');
                searchInput.addEventListener('input', function(event) {
                    searchTerm = event.target.value.trim();
                    currentPage = 1;
                    updateRowsDisplay();
                });

                sortTable();
                showPage(currentPage);
            });
        </script>
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
                                    <option value="2">Latest</option>
                                    <option value="1">Oldest</option>
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
                    <form method='post' name='trash_form' class='trash-form' id='trash_form'>
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
                        <div class="d-flex align-items-center justify-content-center">
                            <?php
                                if($admin['admin_level'] == 2){
                                    echo'<input type="submit" name="submit_trash_items" class="delete_button" id="submit_trash_items" value="Delete Order" onclick="return confirmAction("delete the selected order(s)")">';
                                }
                            ?>
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

                                            $cf_query = "SELECT * FROM customer_orders WHERE trash = 0 ORDER BY order_date DESC";

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
                                                    echo "<td class='t_item'><ul>";
                                                    $oid = $row['order_ID'];
                                                    $sql_get_cart = "SELECT order_contents FROM customer_orders WHERE order_ID = $oid  AND trash = 0";
                                                    $result_get_cart = $conn->query($sql_get_cart);
                                                    if ($result_get_cart->num_rows > 0) {
                                                        while ($row_get_cart = $result_get_cart->fetch_assoc()) {
                                                            $items[] = "";
                                                            $items = explode("},{", $row_get_cart['order_contents']);
                                                            $items = array_filter($items, 'strlen');
                                                            $j = 0;
                                                            if (count($items) != 0){
                                                                foreach ($items as $item) {
                                                                    ++$j;

                                                                    $item = trim($item, "{}");
                                                                    $details = explode(",", $item);                
                                                                    $item_ID = trim($details[0], "()");
                                                                    $item_name = trim($details[1], "()");
                                                                    $item_price = trim($details[2], "()");
                                                                    $item_qty = trim($details[3], "()");
                                                                    $item_sumprice = trim($details[4], "()");
                                                                    $item_request = trim($details[5], "()");
                                                                    $item_custom = implode(',', array_slice($details, 6));
                                                                    
                                                                    preg_match_all('/\(\[([^\]]+)\]\)/', $item_custom, $matches);
                                                                    
                                                                    echo '<li>' . $item_qty . ' x  ' . $item_name;

                                                                    if (!empty($matches)) {
                                                                        $pairs = explode('],[', trim($item_custom, '()'));

                                                                        foreach ($pairs as $pair) {
                                                                            // Remove any remaining brackets and trim spaces, then split by comma
                                                                            $cus = explode(',', str_replace(['[', ']'], '', $pair));
                                                                            
                                                                            // Check if both key and value are not empty
                                                                            if (count($cus) == 2 && !empty(trim($cus[0])) && !empty(trim($cus[1]))) {
                                                                                $custom_key = trim($cus[0]);
                                                                                $custom_value = trim($cus[1]);
                                                                                     
                                                                                echo '<br><span> ' . $custom_key . ': ' . $custom_value . '</span>';
                                                                                    
                                                                                
                                                                            }
                                                                        }
                                                                    }
                                                                    if(!empty($item_request)){
                                                                        echo '<br><span>' . $item_request . '</span>';

                                                                    }
                                                                    if($j>=3 && count($items)>3){
                                                                        echo '<br><a class="otheritem" title="View all" href="orders-view.php?ID=' . $row['order_ID'] . '"><i class="fas fa-ellipsis-h"></i></a>';
                                                                        break;
                                                                    }
                                                                    echo '</li>';
                                                                    echo '&nbsp';
                                                                }
                                                                
                                                            }
                                                        }
                                                    }

                                                    echo "</ul></td>";
                                                    echo "<td class='t_date'>".$date."</td>";
                                                    if($row['tracking_stage']==0){
                                                        echo "<td class='t_status'><span class='sta-queue'><i class='fas fa-boxes'></i><p>Queueing</p></span></td>";
                                                    }
                                                    else if($row['tracking_stage']==1){
                                                        echo "<td class='t_status'><span class='sta-prepare'><i class='fas fa-box-full'></i><p>Preparing</p></span></td>";
                                                    }
                                                    else if($row['tracking_stage']==2){
                                                        echo "<td class='t_status'><span class='sta-deliver'><i class='fas fa-truck-loading'></i><p>Delivering</p></span></td>";
                                                    }
                                                    else{
                                                        echo "<td class='t_status'><span class='sta-receive'><i class='fas fa-box-check'></i><p>Received</p></span></td>";
                                                    }
                                                    echo '<td class="t_action act1"><div>';
                                                    if ($admin['admin_level'] == 2) {
                                                        echo '<a class="trash-icon"><i class="fas fa-trash"></i></a>';
                                                    }
                                                    echo '<a href="orders-view.php?ID=' . $row['order_ID'] . '"><i class="fas fa-chevron-circle-right"></i></a><a style="position: relative;"></a>
                                                        <input type="hidden" name="order_ID[]" value="' . $row['order_ID'] . '">
                                                        <input type="hidden" class="trash-item-input" name="trash_item[]" value="0" style="display:block;">
                                                        </div></td>';

                                                }
                                            }
                                            else {
                                                echo "<tr><td class='no_items' colspan='7'><i class='far fa-ghost'></i>No orders found...</td></tr>";
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
