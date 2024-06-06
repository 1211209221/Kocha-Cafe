<?php
session_start();
include '../connect.php';
$admin_ID = $_SESSION["admin"];
$updatestatus = "UPDATE admin SET admin_active = 0 WHERE admin_ID = '$admin_ID'";
if($conn->query($updatestatus)){
    session_destroy();
    header("location:admin.php");
    exit();
}
else{
    header("location:dashboard.php");
    exit();
}

?>