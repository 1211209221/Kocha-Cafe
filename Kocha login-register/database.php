<?php

$hostName = "localhost";
$dbUser = "root";
$password = "";
$database = "kocha_cafe";
$conn = mysqli_connect($hostName, $dbUser, $password, $database);
if (!$conn) {
    die("Something went wrong;");
}

?>