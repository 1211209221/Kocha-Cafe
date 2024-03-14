<div id="mySidenav" class="sidenav">
    <div class="buttons">
        <!-- <button class="signup">Sign up</button>
        <button class="login"><i class="far fa-user"></i><i class="fas fa-user"></i>Log in</button> -->

        <div class="profile"><i class="far fa-user"></i>Username</div>
    </div>
    <a href="#" class="<?php echo ($current_page == 'index.php') ? 'active-menu' : ''; ?>">
        <div>
            <i class="fas fa-home-lg"></i>
        </div>
        <span>Home</span>
    </a>
    <a href="#" class="<?php echo ($current_page == 'menu.php') ? 'active-menu' : ''; ?>">
        <div>
            <i class="fas fa-utensils"></i>
        </div>
        <span>Menu</span>
    </a>
    <a href="#" class="<?php echo ($current_page == 'blog.php') ? 'active-menu' : ''; ?>">
        <div>
            <i class="fas fa-comment"></i>
        </div>
        <span>Blog</span>
    </a>
    <a href="#" class="<?php echo ($current_page == 'contact.php') ? 'active-menu' : ''; ?>">
        <div>
            <i class="fas fa-envelope"></i>
        </div>
        <span>Contact Us</span>
    </a>
    <a href="#" class="<?php echo ($current_page == 'profile.php') ? 'active-menu' : ''; ?>">
        <div>
            <i class="fas fa-user"></i>
        </div>
        <span>Profile</span>
    </a>
    <a href="#" class="<?php echo ($current_page == 'cart.php') ? 'active-menu' : ''; ?>">
        <div>
            <i class="fas fa-shopping-cart"></i>
        </div>
        <span>Cart</span>
    </a>
    <a href="#" class="<?php echo ($current_page == 'wishlist.php') ? 'active-menu' : ''; ?>">
        <div>
            <i class="fas fa-heart"></i>
        </div>
        <span>Wishlist</span>
    </a>
    <a href="#" class="<?php echo ($current_page == 'notifications.php') ? 'active-menu' : ''; ?>">
        <div>
            <i class="fas fa-bell"></i>
        </div>
        <span>Notifications</span>
    </a>
    <a href="#">
        <div>
            <i class="fas fa-sign-out-alt"></i>
        </div>
        <span>Log Out</span>
    </a>
</div>
<div id="sidenav_darken" onclick="closeNav()">
</div>

<script>
    function openNav() {
      document.getElementById("mySidenav").style.width = "210px";
      document.getElementById("sidenav_darken").classList.add("appear");
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("sidenav_darken").classList.remove("appear");
    }
    function openFilters() {
      document.getElementById("menuFilters").classList.add("shiftRight");
      document.getElementById("filter_darken").classList.add("appear");
    }

    function closeFilters() {
        document.getElementById("menuFilters").classList.remove("shiftRight");
        document.getElementById("filter_darken").classList.remove("appear");
}
</script>