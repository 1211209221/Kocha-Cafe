<?php
session_start();


if(!isset($_SESSION["username"]))
{
	header("location:admin_login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<h1>THIS IS SUPERADMIN HOME PAGE</h1><?php echo $_SESSION["username"] ?>

<a href="admin_logout.php">Logout</a>

</body>
</html>