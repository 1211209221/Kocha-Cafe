<?php
session_start(); // Starting the session

// MySQL server configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "kocha_cafe";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM adminlogin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($row["admintype"] == "superadmin") {
            $_SESSION["username"] = $username;
            header("location: superadmin_home.php");
            exit(); // Terminate script after redirection
        } elseif ($row["admintype"] == "admin") {
            $_SESSION["username"] = $username;
            header("location: admin_home.php");
            exit(); // Terminate script after redirection
        }
    } else {
        echo "Username or password incorrect";
    }
}
?>









<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<center>

	<h1>Login Form</h1>
	<br><br><br><br>
	<div style="background-color: grey; width: 500px;">
		<br><br>


		<form action="#" method="POST">

	<div>
		<label>username</label>
		<input type="text" name="username" required>
	</div>
	<br><br>

	<div>
		<label>password</label>
		<input type="password" name="password" required>
	</div>
	<br><br>

	<div>
		
		<input type="submit" value="Login">
	</div>


	</form>


	<br><br>
 </div>
</center>

</body>
</html>