<?php
session_start();


if(!isset($_SESSION["username"]))
{
	header("location:index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<h1>Dashboard</h1><?php echo $_SESSION["username"] ?>

<a href="admin_logout.php">Logout</a>

</body>
</html>