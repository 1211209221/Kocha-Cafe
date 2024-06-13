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
            width: 700px;
            /* Adjust the width as needed */
            height: auto;
            /* Maintain aspect ratio */
            padding-bottom: 35px;
            position: relative;
        }
        .button_1, .button_2{
            color: white !important;
        }
        .item_details {
            background-color: white;
            padding: 30px 20px 30px 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }
        label{
            font-size: 18px;
            font-weight: 800;
            color: #5a9498;
            white-space: nowrap;
            margin: 1px 0;
        }
        label.upload_image_label{
            color: white;
            font-family: 'Afacad' !important;
            font-size: 20px;
            border-radius: 50px;
            width: 50px;
            height: 50px;
            text-align: center;
            cursor: pointer;
            transition: 0.2s transform, 0.2s color;
            bottom: 55px;
            align-items: center;
            display: flex;
            justify-content: center;
            background-color: #5a9498;
            position: absolute;
            right: 20px;
        }
        .upload_image_label:hover {
            background-color: #36676A;
            transform: scale(1.1);
        }
        i.fa-camera{
            font-size: 26px;
        }
        .back_button {
            padding: 4px 18px;
            width: fit-content;
            background-color: #5a9498;
            border: #5a9498 1px solid;
            color: white;
            border-radius: 7px;
            font-weight: 800;
            font-size: 17px;
            outline: none;
            transition: 0.15s;
            box-shadow: 0px 17px 17px -20px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
            position: relative;
            top: 20px;
        }
        .back_button:hover{
            background-color: #36676A;
            transform: scale(1.05) !important;
            text-decoration: none;
            color: white !important;
        }

        select{
            border: #e9ecef 1px solid !important;
            background-color: #e9ecef !important;
            border-radius: 7px !important;
            font-size: 18px !important;
            padding: 2px 5px !important;
            color: black !important;
            box-shadow: unset !important;
        }

        input{
            border: #e9ecef 1px solid !important;
            background-color: #e9ecef !important;
            border-radius: 7px !important;
            font-size: 18px !important;
            padding: 2px 5px !important;
            color: black !important;
            box-shadow: unset !important;
        }

        .ql-toolbar{
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
        }

        .ql-editor{
            border: #e9ecef 1px solid !important;
            background-color: #e9ecef !important;
            border-bottom-right-radius: 10px;
            border-bottom-left-radius: 10px;
            outline: none !important;
        }

        input[type=file] {
            pointer-events: none;
            text-align-last: center;
        }

        ::-webkit-file-upload-button {
            display: none;
        }

        .disabled-editor {
            background-color: #f9f9f9;
            cursor: not-allowed;
            pointer-events: none;
        }

        @media screen and (max-width: 998px) {
            label.upload_image_label{
                right: 20px;
            }
        }

        @media screen and (max-width: 768px) {
            .fixed-image {
                width: 100%;
                /* Adjust the width as needed */
                height: auto;
                /* Maintain aspect ratio */
            }
        }

        @media screen and (max-width: 480px) {
            label.upload_image_label {
                width: 40px;
                height: 40px;
            }
            label.upload_image_label i{
                font-size: 19px;
            }
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
            <div class='container admin_page' style="padding-bottom: 100px;">
                <div class='header'>
                    <div class="breadcrumbs">
                        <a>Blog</a> &gt; <a ref="blogs-all.php">Blog List</a> &gt; <a class="active"><?php echo $row['blog_title']; ?></a>
                    </div>
                </div>
                <form action="blogs-update.php" method="post" enctype="multipart/form-data">
                <?php
                    echo "<div style='margin-top: 20px; display:flex; justify-content: center; position: relative;'>
                        <div style='position: relative;'>
                            <img src='" . $row["image"] . "' alt='Blog Image' class='fixed-image' id='blog-image'>
                            <label class='upload_image_label' for='image'" . ($admin['admin_level'] == 1 ? " style='pointer-events: none'" : "") . ">
                                <i class='fas fa-camera'></i>
                            </label>
                        </div>
                        <input type='file' name='image' class='upload_image' id='image' accept='image/jpeg, image/png' style='position: absolute; bottom: 0;'>
                    </div>";
                ?>

                    <div class="item_details">
                        <div class="page_title">Edit Blog<i class="fas fa-pen"></i></div>
                        <input type="hidden" name="blog_id" value="<?php echo $row['blog_ID']; ?>">
                        <div class="form-group">
                            <label for="blog_title">Blog Title</label>
                            <input type="text" class="form-control" id="blog_title" name="blog_title"
                                value="<?php echo $row['blog_title']; ?>" <?php if($admin['admin_level'] == 1){echo 'disabled';} ?>>
                        </div>
                        <div class="form-group">
                            <label for="blog_contents">Blog Contents</label>
                            <div id="form-control" style="height: 300px; border: 0;"><?php echo $row['blog_contents']; ?></div>
                            <input type="hidden" id="content" name="blog_contents" />
                        </div>
                        <div class="form-group">
                            <label for="blog_type">Blog Type</label>
                            <select class="form-control" id="blog_type" name="blog_type">
                                <option value="Discount" <?php if ($row['blog_type'] == "Discount")
                                    echo "selected"; ?> <?php if($admin['admin_level'] == 1){echo 'disabled';} ?>>Discount
                                </option>
                                <option value="Updates" <?php if ($row['blog_type'] == "Updates")
                                    echo "selected"; ?> <?php if($admin['admin_level'] == 1){echo 'disabled';} ?>>Updates</option>
                                <option value="News" <?php if ($row['blog_type'] == "News")
                                    echo "selected"; ?> <?php if($admin['admin_level'] == 1){echo 'disabled';} ?>>News</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center justify-content-end">
                        <button type="submit" class="btn button_1 mr-2" <?php if ($admin['admin_level'] == 1) { echo 'style="pointer-events: none; background-color:#919ba0 !important;"'; } ?>>Update Blog</button>
                            <?php
                                if ($admin['admin_level'] == 2) {
                                    echo '<button type="button" class="btn button_2" id="trash-btn">Move to Trash</button>';
                                }
                            ?>
                        </div>
                    </div>
                    <a href="blogs-all.php" class="back_button">Back To List</a>
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
        <?php if ($admin['admin_level'] == 1): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const editor = document.querySelector('.ql-editor');

            // Prevent typing and pasting
            editor.addEventListener('keypress', function(event) {
                event.preventDefault();
            });

            editor.addEventListener('paste', function(event) {
                event.preventDefault();
            });

            editor.addEventListener('input', function(event) {
                event.preventDefault();
            });
        });
        <?php endif; ?>

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