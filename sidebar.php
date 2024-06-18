<div id="mySidenav" class="sidenav">
    <div class="buttons">
    <?php
        if(empty($user)){
            echo '<button class="signup" id="signup-btn">Sign up</button>
            <button class="login" id="login-btn"><i class="far fa-user"></i><i class="fas fa-user"></i>Log in</button>';

            
        }
        else{
            $get_name = "SELECT cust_username FROM customer WHERE cust_ID = $cust_ID AND trash = 0";
            $name_result = $conn->query($get_name);
            $name_row = $name_result->fetch_assoc();
            if($name_row && !empty($name_row['cust_username'])){
                $name = $name_row['cust_username'];
            }
            else{
                $name = "Error.";
            }
            echo '<div class="profile"><i class="far fa-user"></i>'.$name.'</div>';
        }
    ?>
        <!-- <div class="profile"><i class="far fa-user"></i>Username</div> -->
    </div>
    <a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active-menu' : ''; ?>">
        <div>
            <i class="fas fa-home-lg"></i>
        </div>
        <span>Home</span>
    </a>
    <a href="menu.php" class="<?php echo ($current_page == 'menu.php' || $current_page == 'item.php') ? 'active-menu' : ''; ?>">
        <div>
            <i class="fas fa-utensils"></i>
        </div>
        <span>Menu</span>
    </a>
    <a href="cart.php" class="<?php echo ($current_page == 'cart.php') ? 'active-menu' : ''; ?>">
        <div>
            <i class="fas fa-shopping-cart"></i>
        </div>
        <span>Cart</span>
    </a>
    <a href="profile.php" class="<?php echo ($current_page == 'profile.php') ? 'active-menu' : ''; ?>">
        <div>
            <i class="fas fa-user"></i>
        </div>
        <span>Profile</span>
    </a>
    <a href="wishlist.php" class="<?php echo ($current_page == 'wishlist.php') ? 'active-menu' : ''; ?>">
        <div>
            <i class="fas fa-heart"></i>
        </div>
        <span>Wishlist</span>
    </a>
    <a href="blog.php" class="<?php echo ($current_page == 'blog.php') ? 'active-menu' : ''; ?>">
        <div>
            <i class="fas fa-comment"></i>
        </div>
        <span>Blog</span>
    </a>
    <a href="contactus.php" class="<?php echo ($current_page == 'contactus.php') ? 'active-menu' : ''; ?>">
        <div>
            <i class="fas fa-envelope"></i>
        </div>
        <span>Contact Us</span>
    </a>
    <a href="aboutus.php" class="<?php echo ($current_page == 'aboutus.php') ? 'active-menu' : ''; ?>">
        <div>
            <i class="fas fa-info-circle"></i>
        </div>
        <span>About Us</span>
    </a>
    <a href="logout.php">
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
      document.body.classList.add('no-scroll');
      window.scrollTo(0, 0);
    }

    function closeFilters() {
        document.getElementById("menuFilters").classList.remove("shiftRight");
        document.getElementById("filter_darken").classList.remove("appear");
        document.body.classList.remove('no-scroll');
    }

    // Function to handle click on the sign up button
    document.getElementById('signup-btn').addEventListener('click', function() {
        window.location.href = 'registration.php'; // Redirect to registration.php
    });

    // Function to handle click on the log in button
    document.getElementById('login-btn').addEventListener('click', function() {
        window.location.href = 'login.php'; // Redirect to login.php
    });
</script>