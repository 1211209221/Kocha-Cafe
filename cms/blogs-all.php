<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog List | Admin Panel</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="icon" href="../images/logo/logo_icon_2.png">
    <script src="../script.js"></script>
    <script src="../gototop.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('Blog-Writing.jpg');
            background-size: cover;
            filter: blur(1.5);
            background-position: center;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            color: black;
            text-shadow: black;
        }

        .row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .post {
            width: calc(33.33% - 20px);
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 30px;
            box-sizing: border-box;
            background-color: white;
            border-radius: 20px;
            position: relative;
            /* Added relative positioning */
        }

        .post h2 {
            margin-top: 0;
            font-size: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .post p {
            margin-top: 5px;
        }

        .post img {
            width: 100%;
            height: auto;
        }

        .breadcrumbs {
            margin-bottom: 20px;
        }

        .page_title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .big_container {
            margin-bottom: 20px;
        }

        .container_header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .container_header i {
            margin-right: 10px;
        }

        .navigation_container {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .no_results_page {
            font-size: 14px;
        }

        .select1,
        .select2 {
            width: 200px;
            margin-bottom: 10px;
        }

        .input2 {
            width: 200px;
            margin-bottom: 10px;
        }

        .button_1,
        .button_2 {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            cursor: pointer;
            color: white;
            margin-right: 10px;
        }

        .icon_button1,
        .icon_button2 {
            padding: 5px;
            background-color: #007bff;
            border: none;
            cursor: pointer;
            color: white;
            margin-right: 10px;
            border-radius: 50%;
            font-size: 16px;
            /* Adjusted font size for the pen icon */
        }

        .admin_page {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        /* New Style for Blog Type Button */
        .blog_type_button {
            padding: 10px 20px;
            /* Increased padding for better button appearance */
            background-color: #36676A;
            /* Green color */
            border: none;
            cursor: pointer;
            color: white;
            margin-top: 10px;
            border-radius: 5px;
            width: 100%;
            /* Make the button fill the width of its container */
            text-align: center;
            /* Center-align the text */
            box-sizing: border-box;
            /* Include padding and border in button's total width */
            position: absolute;
            bottom: 0;
            left: 0;
        }

        .blog_type_button:hover {
            background-color: #E2857B;
            opacity: 1;
            transform: scale(1.035);
            transition: 0.5s;
        }

        .post img {
            width: 100%;
            height: 200px;
            /* Adjust the height as needed */
            object-fit: cover;
            /* Ensure the image covers the entire container */
        }
    </style>
</head>

<body>
    <?php
    include '../connect.php';
    include '../gototopbtn.php';
    include 'navbar.php';
    ?>
    <div class='container'>
        <div class='header'>
            <h1>Added Blog</h1>
        </div>

        <div class="row">

            <?php

            $sql = "SELECT blog_ID, blog_title, blog_contents, image, file, date, blog_type FROM blog";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='post'>";
                    echo "<div><img src='" . $row["image"] . "' alt='Blog Image'></div>"; // Adjusted image size
                    echo "<h2><span>" . $row["blog_title"] . "</span><span><a href='blogs-edit.php?id=" . $row["blog_ID"] . "'><i class='fas fa-pen icon_button1'></i></a></span></h2>";
                    echo "<p>" . $row["blog_contents"] . "</p>";
                    echo "<p>" . $row["date"] . "</p>";
                    echo "<button class='blog_type_button'>" . $row["blog_type"] . "</button>"; // Converted blog type to button
                    echo "</div>"; // Closing individual post container
                }
            } else {
                echo "0 results";
            }

            ?>

        </div> <!-- Closing row -->

    </div> <!-- Closing container -->
</body>

</html>