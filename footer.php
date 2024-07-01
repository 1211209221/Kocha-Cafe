<footer class="footer">
    <div class="desktop">
        <div class="container-fluid container">
            <div class="col-12 m-auto">
                <div class="d-flex justify-content-between">
                    <img src="images/logo/logo_2.png" class="footer_logo">
                    <div class="footer_socials f1">
                        <a href="#" class="pr-2">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="pr-2">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="pr-2">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="pr-2">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="pr-2">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
                <div class="row d-flex">
                    <div class="col-md-5 col-12 mx-auto pb-md-0 pb-4">
                        <h4>Trust Us With Your Meals</h4>
                        <span class="description"> With our dedicated team of chefs meticulously selecting the finest ingredients, we ensure each dish bursts with flavor and freshness. Trust us to deliver not just a meal, but an exceptional culinary journey that keeps you coming back for more.</span>
                        <div class="footer_socials f2">
                            <a href="#" class="pr-2">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="pr-2">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="pr-2">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="pr-2">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="#" class="pr-2">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3 col-5 mx-auto">
                        <h4>Categories</h4>
                        <ul class="m-0 categories">
                            <li class="m-auto"><a href="aboutus.php">About Us</a></li>
                            <li class="m-auto"><a href="item.php">Menu</a></li>
                            <li class="m-auto"><a href="contactus.php">Contact Us</a></li>
                            <li class="m-auto"><a href="blog.php">Blog</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4 col-7  mx-auto">
                        <h4>Where to Find Us</h4>
                        <span class="address"> No. 44, Jalan Desa Melur 4/1, Taman Bandar Connaught, 56000 Cheras,
                            Kuala Lumpur, Malaysia<br><br>
                            Phone: +6017 412 4250<br>
                            Email: kochacafe8@gmail.com
                        </span>
                    </div>
                </div>
                <hr style="border-color: white;">
            </div>
            <div class="copyright">
                <span>&copy; Copyright Kocha Cafe. All Rights Reserved(1098054)</span>
            </div>
        </div>
    </div>
    <div class="mobile">
        <div class="container-fluid container" style="padding-top: 3px !important;">
            <div class="col-12 m-auto icon-container">
                <div class="icon">
                    <a href="index.php" class="underline-animation <?php echo ($current_page == 'index.php') ? 'active-menu' : ''; ?>">
                        <i class="far fa-home-lg"></i>
                        <span>Home</span>
                    </a>
                </div>
                <div class="icon">
                    <a href="menu.php" class="underline-animation <?php echo ($current_page == 'menu.php' || $current_page == 'item.php') ? 'active-menu' : ''; ?>">
                        <i class="far fa-utensils"></i>
                        <span>Menu</span>
                    </a>
                </div>
                <div class="icon">
                    <a href="cart.php" class="underline-animation <?php echo ($current_page == 'cart.php') ? 'active-menu' : ''; ?>">
                        <i class="far fa-shopping-cart">
                            <?php 
                                $sql_get_cart_no = "SELECT cust_cart FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
                                $result_get_cart_no = $conn->query($sql_get_cart_no);
                                if ($result_get_cart_no->num_rows > 0) {
                                    while ($row_get_cart_no = $result_get_cart_no->fetch_assoc()) {
                                        $items[] = "";
                                        $items = explode("},{", $row_get_cart_no['cust_cart']);
                                        $items = array_filter($items, 'strlen');

                                        if ((count($items)) > 0){
                                            echo '<div class="notification_circle">'.(count($items)).'</div>';
                                        }
                                    }
                                }
                            ?>
                        </i>
                        <span>Cart</span>
                    </a>
                </div>
                <div class="icon">
                    <a href="profile.php" class="underline-animation  <?php echo ($current_page == 'profile.php') ? 'active-menu' : ''; ?>">
                        <i class="far fa-user"></i>
                        <span>Profile</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('searchInput');
        const searchResultsContainer = document.getElementById('searchResultsContainer');

        const allResultsString = document.getElementById('all_results').value;
        const allResults = allResultsString.split(',');

        // Function to filter allResults based on input value
        function filterallResults(inputValue) {
            return allResults.filter(item => {
                const [name, itemID, typeID] = item.split('|');
                return name.toLowerCase().includes(inputValue.toLowerCase());
            });
        }

        // Function to render search results
        function renderResults(results) {
            searchResultsContainer.innerHTML = ''; // Clear previous results
            if (results.length === 0) {
                searchResultsContainer.style.display = 'none'; // Hide container if no results
                return;
            }
            searchResultsContainer.style.display = 'flex'; // Show container
            
            // Limiting to first 10 results
            const maxResults = Math.min(results.length, 10);
            for (let i = 0; i < maxResults; i++) {
                const [name, itemID, typeID] = results[i].split('|');
                const anchorElement = document.createElement('a');
                anchorElement.classList.add('search_result'); // Adding class to the anchor
                anchorElement.href = 'item.php?ID=' + itemID;

                // Setting the result category based on typeID
                let resultCategory = '';
                if (typeID === '1') {
                    resultCategory = 'Menu item';
                } else if (typeID === '') {
                    resultCategory = 'None';
                }

                // Setting innerHTML with the result category
                anchorElement.innerHTML = `<div class="result_container"><i class="far fa-search"></i><div class="result_name"> ${name} </div></div><span>—${resultCategory}</span>`;
                searchResultsContainer.appendChild(anchorElement);
            }
        }

        // Event listener for input
        searchInput.addEventListener('input', function() {
            const inputValue = this.value.trim();
            const filteredallResults = filterallResults(inputValue);
            renderResults(filteredallResults);
        });

        // Event listener to show results when clicking on search input
        searchInput.addEventListener('focus', function() {
            const inputValue = this.value.trim();
            const filteredallResults = filterallResults(inputValue);
            renderResults(filteredallResults);
        });

        // Event listener to hide results when clicking outside search input
        document.body.addEventListener('click', function(event) {
            if (!event.target.matches('#searchInput')) {
                searchResultsContainer.style.display = 'none';
            }
        });
    });
</script>

<?php
    $sql_search = "SELECT * FROM menu_items WHERE trash = 0";
    $result_search = $conn->query($sql_search);

    $string = "";

    if ($result_search->num_rows > 0) {
        while ($row_search = $result_search->fetch_assoc()) {
            $string .= "" . $row_search['item_name'] . "|" . $row_search['item_ID'] . "|1,";
        }
        // Remove the trailing comma
        $string = rtrim($string, ",");
    }

    echo '<div><input type="hidden" id="all_results" value="'.$string.'"></div>';
?>
