<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog | Admin Panel</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="icon" href="../images/logo/logo_icon_2.png">
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">
    <?php
        include '../connect.php';
        include '../gototopbtn.php';
        include 'navbar.php';
    ?>
    <style>
        .fa {
            font-size: 1rem;
            /* Adjust the size as needed */
        }

        /* Fixed size for the image */
        .fixed-image {
            width: 300px;
            /* Adjust the width as needed */
            height: auto;
            /* Maintain aspect ratio */
        }
    </style>
    <script src="../script.js"></script>
    <script src="../gototop.js"></script>
</head>

<body>
    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['blog_id']) && isset($_POST['action']) && $_POST['action'] == 'move_to_trash') {
            $blog_id = $_POST['blog_id'];

            $stmt = $conn->prepare("UPDATE blog SET trash = 1 WHERE blog_ID = ?");
            $stmt->bind_param("i", $blog_id);

            if ($stmt->execute()) {
                echo "<script>alert('Blog moved to trash successfully.'); window.location.href='blogs-all.php';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            exit();
        }
    }

    // Check if the ID parameter is set in the URL
    if (isset($_GET['id'])) {
        $blog_id = $_GET['id'];

        $stmt = $conn->prepare("SELECT * FROM blog WHERE blog_ID = ?");
        $stmt->bind_param("i", $blog_id); // Assuming the blog ID is an integer
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the blog data
            $row = $result->fetch_assoc();
            // Display form to edit the blog entry
            ?>
            <div class='container'>
                <div class='header'>
                    <h1>Edit Blog</h1>
                </div>
                <form action="blogs-update.php" method="post" enctype="multipart/form-data">
                    <?php
                    echo "<div><img src='" . $row["image"] . "' alt='Blog Image' class='fixed-image' id='blog-image'></div>";
                    ?>
                    <input type="hidden" name="blog_id" value="<?php echo $row['blog_ID']; ?>">
                    <div class="form-group">
                        <label for="blog_title">Blog Title</label>
                        <input type="text" class="form-control" id="blog_title" name="blog_title"
                            value="<?php echo $row['blog_title']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="blog_contents">Blog Contents</label>
                        <div id="form-control" style="height: 300px;"><?php echo $row['blog_contents']; ?></div>
                        <input type="hidden" id="content" name="blog_contents" />
                    </div>
                    <div class="form-group">
                        <label for="blog_type">Blog Type</label>
                        <select class="form-control" id="blog_type" name="blog_type">
                            <option value="Discount" <?php if ($row['blog_type'] == "Discount")
                                echo "selected"; ?>>Discount
                            </option>
                            <option value="Updates" <?php if ($row['blog_type'] == "Updates")
                                echo "selected"; ?>>Updates</option>
                            <option value="News" <?php if ($row['blog_type'] == "News")
                                echo "selected"; ?>>News</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="file" name="image" class="upload_image" id="image" accept="image/jpeg, image/png, image/gif">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Blog</button>
                    <button type="button" class="btn btn-danger" id="trash-btn">Move to Trash</button>
                </form>
            </div>
            <?php
        } else {
            // Handle the case where the blog with the given ID is not found
            echo "Blog not found";
        }

        $stmt->close();
    } else {
        // If ID parameter is not set, display an error message or redirect to another page
        echo "Blog ID not provided";
    }
    ?>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'], // Bold, Italic, Underline, Strikethrough
            [{ 'color': [] }, { 'background': [] }], // Text Color, Background Color
            [{ 'align': [] }], // Text Alignment
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }], // Headers
            [{ 'list': 'ordered' }, { 'list': 'bullet' }], // Ordered and Unordered list
            ['blockquote', 'code-block'], // Blockquote, Code block
            ['clean'], // Remove Formatting
            [{ 'script': 'sub' }, { 'script': 'super' }], // Subscript, Superscript
            [{ 'size': ['small', false, 'large', 'huge'] }], // Text Size
            [{ 'indent': '-1' }, { 'indent': '+1' }] // Indent, Outdent
        ];

        var quill = new Quill('#form-control', {
            modules: {
                toolbar: toolbarOptions,
            },
            theme: 'snow'
        });

        quill.on('text-change', function () {
            var html = quill.root.innerHTML;
            document.getElementById('content').value = html;
        });

        // Handle image change event
        document.getElementById('image').addEventListener('change', function (e) {
            var file = e.target.files[0];
            var reader = new FileReader();

            reader.onload = function (e) {
                document.getElementById('blog-image').setAttribute('src', e.target.result);
            }

            reader.readAsDataURL(file);
        });
        document.getElementById('trash-btn').addEventListener('click', function () {
            if (confirm('Are you sure you want to move this blog to the trash?')) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = 'blogs-edit.php?id=<?php echo $blog_id; ?>';
                form.innerHTML = '<input type="hidden" name="blog_id" value="<?php echo $row['blog_ID']; ?>"><input type="hidden" name="action" value="move_to_trash">';
                document.body.appendChild(form);
                form.submit();
            }
        });
    </script>

</body>

</html>