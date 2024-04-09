<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Style Guide | Admin Panel</title>
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
        <div class="container-fluid">
            <div class="col-12 m-auto">
                <div class="admin_page">
                     <div class="breadcrumbs">
                        <a>Admin</a> > <a>Category Path</a> > <a class="active">Current Page Title</a>
                    </div>
                    <div class="page_title">Current Page Title</div>
                    <div class="big_container">
                        <div class="container_header">
                           <i class="fas fa-cog"></i><span>Example Header</span>
                        </div>
                        <hr>
                        <div>
                            <div>
                                <label>Label</label>
                                <input type="text" placeholder="Input style #1">
                            </div>
                            <div>
                                <label>Label</label>
                                <select class="select1">
                                    <option>Option Style #1</option>
                                </select>
                            </div>
                            <div class="d-flex align-items-start flex-column">
                                <label>Label</label>
                                <textarea placeholder="Textarea..."></textarea>
                            </div>
                            <div class="d-flex py-1">
                                <div class="button_1">Button Style #1</div>
                                <div class="button_2">Button Style #2</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex">
                            <select id="perPage" class="select2">
                                <option value="1">1</option>
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            <label for="perPage" id="perPageLabel">Shown per page</label>
                        </div>
                    </div>
                    <div class="big_container">
                        <div>
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap mb-0 rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No.</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Categories</th>
                                            <th>Price</th>
                                            <th>Discount</th>
                                            <th>Availability</th>
                                            <th class="d-flex justify-content-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody border="transparent">
                                        <tr class="review_container">
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td class="d-flex justify-content-center">.</td>
                                        </tr>
                                        <tr class="review_container">
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td class="d-flex justify-content-center">.</td>
                                        </tr>
                                        <tr class="review_container">
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td class="d-flex justify-content-center">.</td>
                                        </tr>
                                        <tr class="review_container">
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td class="d-flex justify-content-center">.</td>
                                        </tr>
                                        <tr>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td class="d-flex justify-content-center">.</td>
                                        </tr>
                                        <tr>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td class="d-flex justify-content-center">.</td>
                                        </tr>
                                        <tr>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td class="d-flex justify-content-center">.</td>
                                        </tr>
                                        <tr>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td class="d-flex justify-content-center">.</td>
                                        </tr>
                                        <tr>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td class="d-flex justify-content-center">.</td>
                                        </tr>
                                        <tr>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td>.</td>
                                            <td class="d-flex justify-content-center">.</td>
                                        </tr>
                                    </tbody> 
                                </table>
                            </div>
                        </div>
                        <div class="navigation_container">
                            <div id="pagination"></div>
                            <div class="no_results_page">
                                <span>Showing to of results</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <input type="text" class="input2" placeholder="Input style #2">
                        <select class="select2">
                            <option>Option Style #2</option>
                        </select>
                    </div>
                    <div class="d-flex">
                        <a class="icon_button1"><i class="fa fa-pen"></i></a>
                        <a class="icon_button2"><i class="fa fa-pen"></i></a>
                    </div>
                </div>
             </div>
         </div>
    </body>
</html>