<?php
// MySQL server configuration
$servername = "localhost";
$user = "root";
$password = "";
$database = "kocha_cafe";

// Create connection
$conn = new mysqli($servername, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

?>
