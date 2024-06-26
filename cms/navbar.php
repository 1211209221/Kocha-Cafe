<?php
$current_page = basename($_SERVER['PHP_SELF']);
session_start();
    if(isset($_SESSION["admin"])) {
        $admin_ID = $_SESSION["admin"];
        $sql0 = "SELECT * FROM admin WHERE admin_ID = '$admin_ID'";
        $result0 = mysqli_query($conn, $sql0);
        $admin = mysqli_fetch_array($result0, MYSQLI_ASSOC);
        $updatestatus = "UPDATE admin SET admin_active = 1 WHERE admin_ID = '$admin_ID'";
        if ($conn->query($updatestatus) === TRUE){
            //nothing
            $admin_name = $admin['admin_username'];
        }
        else{
            header("Location:admin.php");
            exit();
        }
    } else {
        header("Location:admin.php");
        exit();
        // If "user" key is not set, set $user to an empty array
        $admin = array();
        $admin_ID = 0;
        $admin_name = "Admin";
    }

?>
<style>
    body{
        background-color: #e9ecef !important;
        font-size: 18px !important;
        color: #495057 !important;
    }

    @media (max-width: 480px){
        body{
            padding-bottom: 0px !important;
        }
    }
</style>
<div onclick="toggleNav()" id="filter_darken">
</div>
<div class="d-flex">
<div class="sidenav_container">
    <div id="adminSidenav" class="admin_sidenav">
        <div class="image_container">
            <img src="../images/logo/logo_2.png" class="nav_logo">
            <img src="../images/logo/logo_icon_2.png" class="nav_logo_expand">
        </div>
        <div class="page_container" onclick="openNav()">
            <a class="<?php echo ($current_page == 'dashboard.php') ? 'active-menu' : ''; ?>" onclick="toggleSubPages(this, 0)">
                <div>
                    <i class="fas fa-home-lg"></i>
                </div>
                <span>Home</span>
            </a>
            <div class="sub_pages_container <?php echo ($current_page == 'dashboard.php') ? 'toggled' : ''; ?>" id="subPages0">
                <a href="dashboard.php" class="subPage <?php echo ($current_page == 'dashboard.php') ? 'active-menu' : ''; ?>">
                    <div>
                        <i class="fas fa-chart-pie-alt"></i>
                    </div>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
        <div class="page_container" onclick="openNav()">
            <a class="<?php echo ($current_page == 'customers-all.php' || $current_page == 'customers-add.php' || $current_page == 'customers-edit.php' || $current_page == 'admins-all.php' || $current_page == 'admins-add.php' || $current_page == 'admins-edit.php') ? 'active-menu' : ''; ?>" onclick="toggleSubPages(this, 1)">
                <div>
                    <i class="fas fa-user"></i>
                </div>
                <span>Users</span>
            </a>
            <div class="sub_pages_container <?php echo ($current_page == 'customers-all.php' || $current_page == 'customers-add.php' || $current_page == 'customers-edit.php' || $current_page == 'admins-all.php' || $current_page == 'admins-add.php' || $current_page == 'admins-edit.php') ? 'toggled' : ''; ?>" id="subPages1">
                <a href="customers-all.php" class="subPage <?php echo ($current_page == 'customers-all.php' || $current_page == 'customers-add.php' || $current_page == 'customers-edit.php') ? 'active-menu' : ''; ?>">
                    <div>
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <span>Customer List</span>
                </a>
                <a href="admins-all.php" class="subPage <?php echo ($current_page == 'admins-all.php' || $current_page == 'admins-add.php' || $current_page == 'admins-edit.php') ? 'active-menu' : ''; ?>">
                    <div>
                       <i class="fas fa-user-cog"></i>
                    </div>
                    <span>Admin List</span>
                </a>
            </div>
        </div>
        <div class="page_container" onclick="openNav()">
            <a class="<?php echo ($current_page == 'items-all.php' || $current_page == 'items-add.php' || $current_page == 'items-edit.php' || $current_page == 'categories.php' || $current_page == 'customization.php') ? 'active-menu' : ''; ?>" onclick="toggleSubPages(this, 2)" >
                <div>
                    <i class="fas fa-utensils"></i>
                </div>
                <span>Menu</span>
            </a>
            <div class="sub_pages_container <?php echo ($current_page == 'items-all.php' || $current_page == 'items-add.php' || $current_page == 'items-edit.php' || $current_page == 'categories.php' || $current_page == 'customization.php') ? 'toggled' : ''; ?>" id="subPages2">
                <a href="items-all.php" class="subPage <?php echo ($current_page == 'items-all.php' || $current_page == 'items-add.php' || $current_page == 'items-edit.php') ? 'active-menu' : ''; ?>">
                    <div>
                        <i class="fas fa-burger-soda"></i>
                    </div>
                    <span>Item List</span>
                    <?php
                        $sql_get_cart_no = "SELECT * FROM customer_reviews WHERE review_approve = 0 AND trash = 0";
                        $result_get_cart_no = $conn->query($sql_get_cart_no);
                        if ($result_get_cart_no->num_rows > 0) {
                            echo '<div class="notification_circle">'.$result_get_cart_no->num_rows.'</div>';
                        }
                    ?>
                </a>
                <?php
                    if ($admin['admin_level'] == 2) {
                        echo '<a href="categories.php" class="subPage ' . ($current_page == 'categories.php' ? 'active-menu' : '') . '">
                            <div>
                                <i class="fas fa-border-all"></i>
                            </div>
                            <span>Item Categories</span>
                        </a>';
                        
                        echo '<a href="customization.php" class="subPage ' . ($current_page == 'customization.php' ? 'active-menu' : '') . '">
                            <div>
                                <i class="fas fa-sliders-v-square"></i>
                            </div>
                            <span>Item Customizations</span>
                        </a>';
                    }
                ?>
            </div>
        </div>
        <div class="page_container" onclick="openNav()">
            <a class="<?php echo ($current_page == 'orders-all.php' || $current_page == 'orders-view.php') ? 'active-menu' : ''; ?>" onclick="toggleSubPages(this, 3)">
                <div>
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <span>Orders</span>
            </a>
            <div class="sub_pages_container <?php echo ($current_page == 'orders-all.php' || $current_page == 'orders-view.php') ? 'toggled' : ''; ?>" id="subPages3">
                <a href="orders-all.php" class="subPage <?php echo ($current_page == 'orders-all.php' || $current_page == 'orders-view.php') ? 'active-menu' : ''; ?>">
                    <div>
                        <i class="fas fa-clipboard-list-check"></i>
                    </div>
                    <span>Order List</span>
                    <?php
                        $sql_get_order_no = "SELECT * FROM customer_orders WHERE tracking_stage != 3 AND trash = 0";
                        $result_get_order_no = $conn->query($sql_get_order_no);
                        if ($result_get_order_no->num_rows > 0) {
                            echo '<div class="notification_circle">'.$result_get_order_no->num_rows.'</div>';
                        }
                    ?>
                </a>
            </div>
        </div>
        <?php
            echo '<div class="page_container" onclick="openNav()">';
            echo '<a class="' . (($current_page == "blogs-all.php" || $current_page == "blogs-edit.php" || $current_page == "blogs-add.php") ? "active-menu" : '') . '" onclick="toggleSubPages(this, 4)">';
            echo '<div><i class="fas fa-comment"></i></div>';
            echo '<span>Blog</span>';
            echo '</a>';
            echo '<div class="sub_pages_container ' . (($current_page == "blogs-all.php" || $current_page == "blogs-edit.php" || $current_page == "blogs-add.php") ? "toggled" : '') . '" id="subPages4">';
            echo '<a href="blogs-all.php" class="subPage ' . (($current_page == "blogs-all.php" || $current_page == "blogs-edit.php" || $current_page == "blogs-add.php") ? "active-menu" : '') . '">';
            echo '<div><i class="fas fa-comments"></i></div>';
            echo '<span>Blog List</span>';
            echo '</a>';
            echo '</div>';
            echo '</div>';

            echo '<div class="page_container" onclick="openNav()">';
            ?>
            <a class="<?php echo ($current_page == 'messages-all.php' || $current_page == 'messages-view.php') ? 'active-menu' : ''; ?>" onclick="toggleSubPages(this, 5)">
                <div>
                    <i class="fas fa-envelope"></i>
                </div>
                <span>Inbox</span>
            </a>
            <div class="sub_pages_container <?php echo ($current_page == 'messages-all.php' || $current_page == 'messages-view.php') ? 'toggled' : ''; ?>" id="subPages5">
                <a href="messages-all.php" class="subPage <?php echo ($current_page == 'messages-all.php' || $current_page == 'messages-view.php') ? 'active-menu' : ''; ?>">
                    <div>
                        <i class="fas fa-mail-bulk"></i>
                    </div>
                    <span>Message List</span>
                    <?php
                    $sql_get_msg_no = "SELECT * FROM contact_message WHERE markasread = 0 AND trash = 0";
                    $result_get_msg_no = $conn->query($sql_get_msg_no);
                    if ($result_get_msg_no->num_rows > 0) {
                        echo '<div class="notification_circle">' . $result_get_msg_no->num_rows . '</div>';
                    }
                    ?>
                </a>
            </div>
            </div>

        <div class="page_container">
            <a href = "admin_logout.php">
                <div>
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <span>Log Out</span>
            </a>
        </div>
    </div>
</div>
<style>
</style>
<div class="d-flex flex-column" style="flex: 1; padding-bottom: 30px;">
    <div class="adminTopnav">
        <div class="container-fluid">
            <div class="col-12 m-auto d-flex justify-content-between">
                <div class="d-flex align-items-end">
                    <span class="openNav" onclick="toggleNav()">
                        <i class="fas fa-bars"></i>
                    </span>
                    <div class="title">
                        <?php
                            echo ($current_page == 'dashboard.php') ? 'Dashboard' : '';
                            echo ($current_page == 'customers-all.php' || $current_page == 'customers-add.php' || $current_page == 'customers-edit.php' || $current_page == 'admins-all.php' || $current_page == 'admins-add.php' || $current_page == 'admins-edit.php') ? 'Users' : '';
                            echo ($current_page == 'orders-all.php' || $current_page == 'orders-view.php') ? 'Orders' : '';
                            echo ($current_page == 'blogs-all.php' || $current_page == 'blogs-edit.php' || $current_page == 'blogs-add.php') ? 'Blog' : '';
                            echo ($current_page == 'messages-all.php' || $current_page == 'messages-view.php') ? 'Inbox' : '';
                            echo ($current_page == 'vouchers-all.php') ? 'Vouchers' : '';
                            echo ($current_page == 'items-all.php' || $current_page == 'items-edit.php' || $current_page == 'items-add.php' || $current_page == 'customization.php' || $current_page == 'categories.php') ? 'Menu' : '';
                        ?>
                    </div>
                </div>
                <div class="icons">
                    <a class="profile"><i class="far fa-user"></i><span><?php echo $admin_name; ?></span></a>
                </div>
            </div>
        </div>
    </div>
<script>
    function toggleNav() {
        var sidenav = document.getElementById("adminSidenav");
        var subPagesContainers = document.querySelectorAll("[id*=subPages]");
        var filter_darken = document.getElementById("filter_darken");
        var isOpen = sidenav.classList.contains("expand");

        subPagesContainers.forEach(function(subPages) {
            var isToggled = subPages.classList.contains("toggled");

            if (!isOpen && isToggled) {
                setTimeout(function() {
                    subPages.style.maxHeight = subPages.scrollHeight + "px";
                }, 1);
            }
        });

        if (!isOpen) {
            sidenav.classList.add("expand");
            filter_darken.classList.add("appear");
        } else {
            sidenav.classList.remove("expand");
            filter_darken.classList.remove("appear");
        }
    }

    function openNav() {
        var sidenav = document.getElementById("adminSidenav");
        var subPagesContainers = document.querySelectorAll("[id*=subPages]");
        var filter_darken = document.getElementById("filter_darken");

        // Only run if the sidenav is not already expanded
        if (!sidenav.classList.contains("expand")) {
            subPagesContainers.forEach(function(subPages) {
                if (subPages.classList.contains("toggled")) {
                    setTimeout(function() {
                        subPages.style.maxHeight = subPages.scrollHeight + "px";
                    }, 1);
                }
            });

            sidenav.classList.add("expand");
            filter_darken.classList.add("appear");
        }
    }

    window.onload = function() {
        var subPages = document.querySelector("[id*=subPages]");
        var activeMenuItem = subPages.querySelector(".subPage.active-menu");
        var isToggled = subPages.classList.contains("toggled");
        if(isToggled){
            subPages.style.maxHeight = "auto";
        }
        else if (activeMenuItem) {
            subPages.style.maxHeight = subPages.scrollHeight + "px";
        }
        else{
            subPages.style.maxHeight = null;

        }
    }

    window.onload = function() {
        var subPagesContainers = document.querySelectorAll("[id^=subPages]");
        
        subPagesContainers.forEach(function(subPages) {
            var activeMenuItem = subPages.querySelector(".subPage.active-menu");
            if (activeMenuItem) {
                subPages.style.maxHeight = subPages.scrollHeight + "px";
            } else {
                subPages.style.maxHeight = null;
            }
        });
    }

    function toggleSubPages(anchor, containerIndex) {
        var sidenav = anchor.closest(".admin_sidenav");
        var subPages = sidenav.querySelector("#subPages" + containerIndex);
        var isOpen = sidenav.classList.contains("expand");
        var isToggled = subPages.classList.contains("toggled");
        
        if (subPages.style.maxHeight || isToggled) {
            subPages.style.maxHeight = null;
            subPages.classList.remove("toggled");
        } else {
            subPages.style.maxHeight = subPages.scrollHeight + "px";
            subPages.classList.add("toggled");
        }
    }
</script>
