<?php
session_start();
// Check if the "user" key is set in the session
if(isset($_SESSION["user"])) {
    $cust_ID = $_SESSION["user"];
    $sql = "SELECT * FROM customer WHERE cust_ID = '$cust_ID'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
} else {
    // If "user" key is not set, set $user to an empty array
    $user = array();
    $cust_ID = 0;
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="top_banner">
    <div class="container-fluid container">
        <div class="col-12 m-auto justify-content-between d-flex">
            <div class="navbar_socials">
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
            <div id="navbar_search" class="navbar_search">
                <i class="far fa-search"></i>
                <input id="searchInput" placeholder="Search...">
                <div class="search_results">
                    <div id="searchResultsContainer" style="border-radius: 8px; display: none; flex-direction: column;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<nav class="navbar">
    <div class="container-fluid container">
        <div class="col-12 justify-content-between d-flex align-items-center">
            <div class="m-0">
                <a href="index.php" style="display: inline;">
                    <img src="images/logo/logo_1.png" class="nav_logo">
                </a>
                <ul class="m-0" style="display: inline;">
                    <li class="m-auto col-md-4"><a href="#" class="underline-animation <?php echo ($current_page == 'about.php') ? 'active-menu' : ''; ?>">About Us</a></li>
                    <li class="m-auto col-md-4"><a href="menu.php" class="underline-animation <?php echo ($current_page == 'menu.php' || $current_page == 'item.php') ? 'active-menu' : ''; ?>">Menu</a></li>
                    <li class="m-auto col-md-4"><a href="contactus.php" class="underline-animation <?php echo ($current_page == 'contactus.php') ? 'active-menu' : ''; ?>">Contact Us</a></li>
                    <li class="m-auto col-md-4"><a href="#" class="underline-animation <?php echo ($current_page == 'blog.php') ? 'active-menu' : ''; ?>">Blog</a></li>
                </ul>
            </div>
            

            <div class="m-0" style="width: 300px; text-align: right;">
            <?php
            if (is_array($user) && !empty($user)) {
                echo '<div class="icons">
                        <a href="#" class="' . ($current_page == 'profile.php' ? 'active-menu' : '') . '">
                            <i class="fas fa-user"><span style="padding-left: 5px;">' . $user["cust_username"] . '</span></i>
                        </a>
                        <a href="cart.php" class="' . ($current_page == 'cart.php' ? 'active-menu' : '') . '">
                            <i class="fas fa-shopping-cart">';

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

                            echo'</i>
                        </a>
                        <a href="wishlist.php" class="' . ($current_page == 'wishlist.php' ? 'active-menu' : '') . '">
                            <i class="fas fa-heart"></i>
                        </a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
                    </div>';
                } else {
                    echo '
                        <div class="buttons">
                            <button class="login" onclick="window.location.href = \'login.php\';"><i class="far fa-user"></i><i class="fas fa-user"></i>Log in</button>
                            <button class="signup" onclick="window.location.href = \'registration.php\';">Sign up</button>
                        </div>';
                }                
            ?>
            <span onclick="openNav()"><i class="fas fa-bars"></i></span>
            </div>
        </div>
    </div>
</nav>