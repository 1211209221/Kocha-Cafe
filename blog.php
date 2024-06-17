<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Blog | Kocha Cafe</title>
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
        body {
            font-family: Arial, sans-serif;
            background-image: url('Blog-Writing.jpg');
            background-size: cover;
            filter: blur(1.5);
            background-position: center;
        }

        .container {
            margin: 0 auto;
            position: relative;
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
            width: 100%;
            height: 350px;
            overflow: hidden;
            box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 8px;
            box-sizing: border-box;
            background-color: white;
            border-radius: 10px;
            position: relative;
            flex: 0 0 32%;
            max-width: 32%;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .post img {
            width: 100%;
            height: 200px;
            /* Set a fixed height for the image */
            object-fit: cover;
            /* Ensure the image covers the entire area without distortion */
            border-radius: 20px 20px 0 0;
        }

        .post h2 {
            margin-top: 0;
            font-size: 1.25rem;
        }

        .post p {
            margin-top: 5px;
            flex-grow: 1;
            /* Allow the content to grow and fill the available space */
        }

        .blog-type-button {
            font-weight: 800;
            padding: 4px 15px;
            background-color: #E2857B;
            border: none;
            cursor: pointer;
            color: white;
            border-radius: 5px;
            width: 100px;
            text-align: center;
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1;
        }

        .blog-type-button:hover {
            background-color: #E2857B;
            opacity: 1;
            transform: scale(1.035);
            transition: 0.5s;
        }
    </style>
</head>

<body>
    <?php
    include 'connect.php';
    include 'top.php';
    include 'sidebar.php';
    ?>
    <div class='container'>
        <div class='header'>
            <h1>Blog</h1>
        </div>

        <div class="row">

            <?php
            // Fetch and display posts
            $sql = "SELECT blog_ID, blog_title, blog_contents, image, file, date, blog_type FROM blog WHERE trash = 0";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="post">
                        <img src="<?php echo $row["image"]; ?>" alt="Blog Image">
                        <h2><?php echo $row["blog_title"]; ?></h2>
                        <p><?php echo $row["blog_contents"]; ?></p>
                        <?php
                        // Generate unique ID for each post
                        $post_id = $row["blog_ID"];

                        // Add conditional logic for button generation
                        switch ($row["blog_type"]) {
                            case "Discount":
                                echo "<a href='post.php?ID=$post_id'><button id='button_$post_id' class='blog-type-button'>Discount</button></a>";
                                break;
                            case "News":
                                echo "<a href='post.php?ID=$post_id'><button id='button_$post_id' class='blog-type-button'>News</button></a>";
                                break;
                            case "Updates":
                                echo "<a href='post.php?ID=$post_id'><button id='button_$post_id' class='blog-type-button'>Updates</button></a>";
                                break;
                            default:
                                // Handle any other cases here
                                break;
                        }
                        ?>
                    </div>
                    <?php
                }
            } else {
                echo "0 results";
            }
            ?>

        </div>
    </div>
</body>


</html>