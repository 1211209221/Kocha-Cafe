<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="icon" href="images/logo/logo_icon.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
    <script src="gototop.js"></script>
    <style>

    .blog_container{
        width: 800px;
        margin: auto;
        margin-bottom: 80px;
    }
    .image-container {
        margin-bottom: 20px;
        border-radius: 5px;
        overflow: hidden;
        display: flex;
        justify-content: center;
    }

    .image-container img {
        max-width: 100%;
        height: auto;
        display: block;
        border-radius: 5px; /* Optional: Rounded corners for images */
    }

    .text {
        text-align: left; /* Align text content to the left */
    }

    h2 {
        font-size: 48px;
        margin-top: 10px; /* Adjust spacing as needed */
        margin-bottom: 10px;
        color: #333;
    }

    .text p {
        font-size: 16px;
        line-height: 1.8;
        color: #555;
    }

    @media (max-width: 992px) {
        image-container img {
            width: 100%;
        }
        .blog_container{
            width: 90%;
        }
    }
    </style>

</head>
<?php
include 'connect.php';
include 'top.php';
include 'sidebar.php';
?>
<?php
if (isset($_GET['ID'])) {
    $blog_id = $_GET['ID'];

    // Fetch blog details based on blog_id
    $sql = "SELECT blog_ID, blog_title, blog_contents, image, file, date, blog_type FROM blog WHERE blog_ID = $blog_id AND trash = 0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Display blog details
        echo "<div class='container'><div class='blog_container'>";
        echo '<div class="breadcrumbs">
                        <a href="index.php">Home</a> &gt; <a href="blog.php">Blog</a> &gt; <a class="active">'.$row["blog_title"].'</a>
                    </div>';
        echo "<h2>" . $row["blog_title"] . "</h2>";
        echo "<div class='image-container'>";
        echo "<img src='" . $row["image"] . "' alt='Blog Image'>";
        echo "</div>";
        echo "<div class='text'>";
        echo "<p>" . $row["blog_contents"] . "</p>";
        echo "</div>";
        echo "</div></div>";
        // Display other content as needed
    } else {
        echo "Blog post not found.";
    }
} else {
    echo "Invalid request.";
}
?>
