<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Customer List | Admin Panel</title>
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
            .admin_page .t_level a {
                text-decoration: none;
                color: #495057;
            }
            .admin_page .t_level a:hover {
                color: #5a9498;
            }
        </style>
        <?php
            include '../connect.php';
            include '../gototopbtn.php';

            session_start();
        

            include 'navbar.php';
        ?>
        <script>

        
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

                    // Filter cust based on search term
                    if (searchTerm) {
                        filteredContainers = filteredContainers.filter(container => {
                            const itemName = container.querySelector('.t_name').textContent.toLowerCase();
                            return itemName.includes(searchTerm.toLowerCase());
                        });
                    }

                    // Filter cust based on level
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

                    // Filter cust based on presence
                    if (selectedpresenceFilter !== 'all') {
                        filteredContainers = filteredContainers.filter(container => {
                            const presence = container.querySelector('.t_presence i')
                            if (selectedpresenceFilter === '1') {
                                return presence.classList.contains('fa-ban') && presence.classList.contains('color-red');
                            } else if (selectedpresenceFilter === '0') {
                                return presence.classList.contains('fa-check-circle')  && presence.classList.contains('color-green');
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
                        <a>Admin</a> > <a>Users</a> > <a class="active">Customer List</a>
                    </div>
                    <div class="page_title">All Customer</div>
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
                                <label for="levelFilter">Filter by Empty Record</label>
                                <select id="levelFilter">
                                    <option value="all">All</option>
                                    <option value="1">No record</option>
                                    <option value="2">Superadmin</option>
                                </select>
                            </div>
                            <div class="filter_type">
                                <label for="presenceFilter">Filter by Access</label>
                                <select id="presenceFilter">
                                    <option value="all">All</option>
                                    <option value="1">Disabled</option>
                                    <option value="0">Accessible</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="search_container">
                        <div class="item_search">
                            <i class="fas fa-search"></i>
                            <input type="text" class="search_bar" name="keywordSearch" id="keywordSearch" placeholder="Search user...">
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
                        </div>
                    </div>
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap mb-0 rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="t_no">No.</th>
                                            <th class="t_name">Username</th>
                                            <th class="t_email">Email</th>
                                            <th class="t_level">Phone</th>
                                            <th class="t_presence">Access</th>
                                            <th class="t_action act1">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody border="transparent">
                                        <?php
                                            $no_count = 0;
                                            $admin_query = "SELECT * FROM customer";
                                            $result = $conn->query($admin_query);
                                            if($result && $result->num_rows > 0){
                                                while ($row = $result->fetch_assoc()) {
                                                    $no_count++;
                                                    echo "<tr";
                                                    echo"><td class='t_no'>".$no_count."</td>";
                                                    echo "<td class='t_name'>".$row['cust_username']."</td>";
                                                    echo "<td class='t_email'><a title='Email' href='mailto:" . $row['cust_email'] . "'>" . $row['cust_email'] . "</a></td>";
                                                    echo "<td class='t_level'>";
                                                    if(!empty($row['cust_phone'])){
                                                        echo "<a title='Call' href='tel:" . $row['cust_phone'] . "'>" . $row['cust_phone'] . "</a>";
                                                    }else{
                                                        echo "-";
                                                    }
                                                    echo "</td>";
                                                    echo "<td class='t_presence'>";
                                                    if($row['trash'] == 0){
                                                        echo "<i class='fas fa-check-circle color-green' style='color: #5a9498;'></i>";
                                                    }else{
                                                        echo "<i class='fas fa-ban color-red' style='color: #e77468;'></i>";
                                                    }
                                                    echo "</td>";
                                                    echo '<td class="t_action act1"><div><a href="customers-edit.php?ID=' . $row['cust_ID'] . '"><i class="fas fa-pen"></i></a><a style="position: relative;">';
                                                    echo "</i></a>
                                                        <input type='hidden' name='cust_ID[]' value='".$row['cust_ID']."'>
                                                        </div>";
                                                     echo "</tr>";
                                                }
                                            }
                                            else {
                                                echo "<tr><td class='no_items' colspan='7'><i class='far fa-ghost'></i>No customers found...</td></tr>";
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