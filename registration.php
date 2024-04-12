<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>
<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Kocha Registration</title>
    <link rel="icon" href="register/logo_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="register/register.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .title {
            display: flex;
            flex-direction: column; /* Align items in a column */
            align-items: center; /* Align items horizontally */
            margin-top: 20px; /* Add some margin to the top */
        }

        .title img {
            height: 60px; /* Adjust the height of the logo as needed */
            margin-bottom: 10px; /* Add some spacing between the logo and text */
        }
        .alert-success {
            color: green;
        }
    </style>
   </head>
<body>
<div class="container">
    <div class="title">
        <img src="register/logo_1.png" alt="Logo" class="logo">Registration
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
           $fullName = $_POST["fullname"];
           $email = $_POST["email"];
           $password = $_POST["password"];
           $passwordRepeat = $_POST["repeat_password"];
           
           $passwordHash = password_hash($password, PASSWORD_DEFAULT);

           $errors = array();
           
           if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat)) {
            array_push($errors,"All fields are required");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
           }
           if (strlen($password)<8) {
            array_push($errors,"Password must be at least 8 characters long");
           }
           if ($password !== $passwordRepeat) {
            array_push($errors,"Password does not match");
           }
           require_once "connect.php";
           $sql = "SELECT * FROM customer WHERE cust_email = ?";
           $stmt = mysqli_stmt_init($conn);
           if (mysqli_stmt_prepare($stmt, $sql)) {
               mysqli_stmt_bind_param($stmt, "s", $email);
               mysqli_stmt_execute($stmt);
               mysqli_stmt_store_result($stmt);
               if (mysqli_stmt_num_rows($stmt) > 0) {
                   array_push($errors,"Email already exists!");
               }
           } else {
               array_push($errors,"Database error");
           }
           if (count($errors) > 0) {
               foreach ($errors as $error) {
                   echo "<div class='alert alert-danger' style='color: red;'>$error</div>";
               }
           } else {
               $sql = "INSERT INTO customer (cust_username, cust_email, cust_pass) VALUES (?, ?, ?)";
               $stmt = mysqli_stmt_init($conn);
               if (mysqli_stmt_prepare($stmt, $sql)) {
                   mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
                   mysqli_stmt_execute($stmt);
                   echo "<div class='alert alert-success'>You are registered successfully.</div>";
               } else {
                   echo "<div class='alert alert-danger' style='color: red;'>Something went wrong</div>";
               }
           }
        }
        ?>
        <div class="content">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="user-details">
        <div class="input-box">
            <span class="details">Username</span>
            <input type="text" name="fullname" placeholder="Enter your username" required>
          </div>
          <div class="input-box">
            <span class="details">Email</span>
            <input type="email" name="email" placeholder="Enter your email" required>
          </div>
          <div class="input-box">
            <span class="details">Password</span>
            <input type="password" name="password" placeholder="Enter your password" required>
          </div>
          <div class="input-box">
            <span class="details">Confirm Password</span>
            <input type="password" name="repeat_password" placeholder="Confirm your password" required>
          </div>
        </div>
            <div class="button">
            <input type="submit" name="submit" value="Register">
            </div>
        </form>
        <div>
        <div><p>Already Registered <a href="login.php">Login Here</a></p></div>
      </div>
    </div>
</body>
</html>
