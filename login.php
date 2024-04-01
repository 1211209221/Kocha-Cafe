<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
   exit(); // terminate script execution after redirect
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Login</title>
</head>
<body>
  <div class="wrapper">
    <div class="container main">
        <div class="row">
            <div class="col-md-6 side-image">
                <img src="images/white.png" alt="">
                <div class="text">
                    <p>Welcome to Kocha <i>Explore our iconic beverages and meals</i></p>
                </div>
            </div>
            <div class="col-md-6 right">
                <div class="input-box">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <header>Login</header>
                        <?php
                        if (isset($_POST["login"])) {
                            $email = $_POST["email"];
                            require_once "connect.php";
                            $sql = "SELECT * FROM customer WHERE cust_email = '$email'";
                            $result = mysqli_query($conn, $sql);
                            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
                            if ($user) {
                                if (password_verify($_POST["password"], $user["cust_pass"])) {
                                    $_SESSION["user"] = $user["cust_ID"];
                                    header("Location: index.php");
                                    exit(); // terminate script execution after redirect
                                } else {
                                    echo "<div class='alert alert-danger'>Password does not match</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Email does not match</div>";
                            }
                        }
                        ?>
                        <div class="input-field">
                            <input type="email" class="input" id="email" name="email" required="" autocomplete="off">
                            <label for="email">Email</label>
                        </div>
                        <div class="input-field">
                            <input type="password" class="input" id="password" name="password" required="">
                            <label for="password">Password</label>
                        </div>
                        <div class="input-field">
                            <input type="submit" class="submit" value="Login" name="login">
                        </div>
                    </form>
                    <div class="signin">
                        <span>Don't have an account? <a href="registration.php">Sign up here</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
