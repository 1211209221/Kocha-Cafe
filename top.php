<?php
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
            <div class="navbar_search">
                <i class="far fa-search"></i>
                <input placeholder="Search...">
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
                    <li class="m-auto col-md-4"><a href="menu.php" class="underline-animation <?php echo ($current_page == 'menu.php') ? 'active-menu' : ''; ?>">Menu</a></li>
                    <li class="m-auto col-md-4"><a href="contactus.php" class="underline-animation <?php echo ($current_page == 'contact.php') ? 'active-menu' : ''; ?>">Contact Us</a></li>
                    <li class="m-auto col-md-4"><a href="#" class="underline-animation <?php echo ($current_page == 'blog.php') ? 'active-menu' : ''; ?>">Blog</a></li>
                </ul>
            </div>
            
            <div class="m-0" style="width: 300px; text-align: right;">
                <div class="icons">
                    <a href="#" class="<?php echo ($current_page == 'profile.php') ? 'active-menu' : ''; ?>">
                        <i class="fas fa-user"><span style="padding-left: 5px;">Username</span></i>
                    </a>
                    <a href="#" class="<?php echo ($current_page == 'cart.php') ? 'active-menu' : ''; ?>">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                    <a href="#" class="<?php echo ($current_page == 'wishlist.php') ? 'active-menu' : ''; ?>">
                        <i class="fas fa-heart"></i>
                    </a>
                    <a href="#" class="<?php echo ($current_page == 'notifications.php') ? 'active-menu' : ''; ?>">
                        <i class="fas fa-bell"></i>
                    </a>
                </div>
                <!-- <div class="buttons">
                    <button class="login"><i class="far fa-user"></i><i class="fas fa-user"></i>Log in</button>
                    <button class="signup">Sign up</button>
                </div> -->
                <a onclick="openNav()">
                    <i class="fas fa-bars"></i>
                </a>
            </div>
        </div>
    </div>
</nav>