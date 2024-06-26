<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Message List | Admin Panel</title>
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

            //session_start();

            
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                
                if (isset($_POST['submit_trash_items'])) {
                    if (isset($_POST['CF_ID']) && isset($_POST['trash_item'])) {
                        $CF_ids = $_POST['CF_ID'];
                        $trashes = $_POST['trash_item'];
                        $FailedUpdate = 0;
            
                        for ($i = 0; $i < count($CF_ids); $i++) {
                            $CF_id = $CF_ids[$i];
                            $trash = $trashes[$i];
            
                            $trash_sql = "UPDATE contact_message SET trash = {$trash} WHERE CF_ID = $CF_id AND trash = 0";
            
                            if ($conn->query($trash_sql) !== TRUE) {
                                $FailedUpdate = 1;
                                break;
                            }
                        }
                        if ($FailedUpdate == 0) {
                            $_SESSION['deletemessage_success'] = true;
                            echo '<script>';
                            echo 'window.location.href = "messages-all.php";';
                            echo '</script>';
                            //header("Location: messages-all.php");
                            exit();
                        } else {
                            $_SESSION['deletemessage_error'] = true;
                            echo '<script>';
                            echo 'window.location.href = "messages-all.php";';
                            echo '</script>';
                            exit();
                        }
                    } else {
                        $_SESSION['deletemessage_error'] = true;
                        echo '<script>';
                        echo 'window.location.href = "messages-all.php";';
                        echo '</script>';
                        exit();
                    }
                }
            }
            

            if (isset($_SESSION['editmsg_success']) && $_SESSION['editmsg_success'] === true) {
                echo '<div class="toast_container">
                        <div id="custom_toast" class="custom_toast true fade_in">
                            <div class="d-flex align-items-center message">
                                <i class="fas fa-check-circle"></i> Read status updated.
                            </div>
                            <div class="timer"></div>
                        </div>
                    </div>';
    
                unset($_SESSION['editmsg_success']);
            }
    
            if (isset($_SESSION['editmsg_error'])) {
                echo '<div class="toast_container">
                            <div id="custom_toast" class="custom_toast false fade_in">
                                <div class="d-flex align-items-center message">
                                    <i class="fas fa-times-circle"></i>Failed to update message. Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';
    
                unset($_SESSION['editmsg_error']);
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
                                    <i class="fas fa-times-circle"></i>Failed to delete message. Please try again...
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
                                    <i class="fas fa-times-circle"></i>Failed to delete message(s). Please try again...
                                </div>
                                <div class="timer"></div>
                            </div>
                        </div>';

                unset($_SESSION['deletemessage_error']);
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
            table tr .t_name{
                width: 12%;
                padding-left:15px !important;
            }
            table thead tr .t_name{
                border-top-left-radius: 7px;
                border-bottom-left-radius: 7px;
            }
            table tr .t_email{
                width: 13%;
            }
            table tr .t_sender{
                width: 10%;
            }
            table tr .t_date{
                width: 9%;
            }
            table tbody tr .t_date{
                font-size: 16px;
            }
            table .t_status{
                display:none;
            }
            @media (max-width: 575px) {
                .admin_page .t_email {
                    display: revert;
                }
                table tr .t_sender{
                    display:none;
                }
                table tr .t_name{
                    padding-left:8px !important;
                }
                table tbody tr .t_date{
                    font-size: 14px;
                }
            }
            @media (max-width: 768px) {
                .admin_page .trash-form {
                    display: block;
                }
            }
            @media (max-width: 480px) {
                table tbody tr .t_name{
                    font-size:15px;
                }
                
                table tbody tr .t_email{
                    font-size:15px;
                }
                table tr .t_date{
                    display:none;
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
                            const status = container.querySelector('.t_status').textContent.trim();
                            if (selectedstatusFilter === '0') {
                                return status === "0";
                            } else if (selectedstatusFilter === '1') {
                                return status === "1";
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
                        <a>Admin</a> > <a>Inbox</a> > <a class="active">Message List</a>
                    </div>
                    <div class="page_title">All Messages</div>
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
                                <label for="statusFilter">Filter by Read Status</label>
                                <select id="statusFilter">
                                    <option value="all">All</option>
                                    <option value="1">Read mail</option>
                                    <option value="0">Unread mail</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <form method='post' name='trash_form' class='trash-form' id='trash_form'>
                    <div class="search_container">
                        <div class="item_search">
                            <i class="fas fa-search"></i>
                            <input type="text" class="search_bar" name="keywordSearch" id="keywordSearch" placeholder="Search subject...">
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
                            if ($admin['admin_level'] == 2) {
                                echo'<input type="submit" name="submit_trash_items" class="delete_button" id="submit_trash_items" value="Delete Message" onclick="return confirmAction("delete the selected message(s)")">';
                            }                                
                        ?>
                        </div>
                    </div>
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap mb-0 rounded" id="dataTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="t_no">No.</th>
                                            <th class="t_name">Subject</th>
                                            <th class="t_email">Email</th>
                                            <th class="t_sender">Sender</th>
                                            <th class="t_date">Date</th>
                                            <th class="t_status">Status</th>
                                            <th class="t_action act1">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody border="transparent">
                                        <?php
                                            $no_count = 0;

                                            $cf_query = "SELECT * FROM contact_message WHERE trash = 0";

                                            $result = $conn->query($cf_query);
                                            if($result && $result->num_rows > 0){
                                                while ($row = $result->fetch_assoc()) {
                                                    $no_count++;
                                                    $date = date('Y-m-d H:i:s', strtotime($row['CF_time']));
                                                    echo "<tr" . ($row['markasread'] == 1 ? " class='unavailable'" : "") . ">";
                                                    echo "<td class='t_no'>" . $no_count . "</td>";
                                                    echo "<td class='t_name'>" . $row['CF_subject'] . "</td>";
                                                    echo "<td class='t_email'><a title='Email' href='mailto:" . $row['CF_email'] . "'>" . $row['CF_email'] . "</a></td>";
                                                    echo "<td class='t_sender'>" . $row['CF_name'] . "</td>";
                                                    echo "<td class='t_date'>" . $date . "</td>";
                                                    echo "<td class='t_status'>" . $row['markasread'] . "</td>";
                                                    echo "<td class='t_action act1'><div>";
                                                    if ($admin['admin_level'] == 2) {
                                                        echo "<a class='trash-icon'><i class='fas fa-trash'></i></a>";
                                                    }
                                                    echo "<input type='hidden' name='CF_ID[]' value='" . $row['CF_ID'] . "'>
                                                    <input type='hidden' class='trash-item-input' name='trash_item[]' value='0' style='display:block;'>";
                                                    echo "<a href='messages-view.php?ID=" . $row['CF_ID'] . "'><i class='fas fa-chevron-circle-right'></i></a>";
                                                    echo "</div></td>";
                                                    echo "</tr>";


                                                }
                                            }
                                            else {
                                                echo "<tr><td class='no_items' colspan='7'><i class='far fa-ghost'></i>No messages found...</td></tr>";
                                            }
                                        $conn->close();
                                        ?>
                                        
                                    </tbody> 
                                </table>
                                </form>
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