<?php
ob_start();
include '../connect.php';

date_default_timezone_set('Asia/Kuala_Lumpur');
$date = date('Y-m-d H:i:s');

// Handle form submission
if (isset($_POST['submit'])) {
    $subject = $_POST['subject'];
    $content = $_POST['content'];
    $date = $_POST['date'];

    // Handle image upload
    $image = '';
    $filename = $_FILES["image"]["name"];
    $tempname = $_FILES["image"]["tmp_name"];
    $data = file_get_contents($tempname);
    $mime_type = mime_content_type($tempname);
    $image_data = "data:$mime_type;base64," . base64_encode($data);



    $blog_type = isset($_POST['blog_type']) ? $_POST['blog_type'] : '';

    $query = "INSERT INTO blog (blog_title, blog_contents, date, blog_type, image) 
              VALUES ('$subject', '$content', '$date', '$blog_type', '$image_data')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['addBlog_success'] = true;
        echo '<script>';
        echo 'window.location.href = "blogs-add.php";';
        echo '</script>';

        ob_end_flush(); // Flush and end the output buffer
        exit();
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Blog | Admin Panel</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="icon" href="../images/logo/logo_icon_2.png">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="../script.js"></script>
    <script src="../gototop.js"></script>
    <?php
        include '../gototopbtn.php';
        include 'navbar.php';

        if (isset($_SESSION['addBlog_success']) && $_SESSION['addBlog_success'] === true) {
            echo '<div class="toast_container">
                    <div id="custom_toast" class="custom_toast true fade_in">
                        <div class="d-flex align-items-center message">
                            <i class="fas fa-check-circle"></i> Blog successfully!
                        </div>
                        <div class="timer"></div>
                    </div>
                </div>';

            unset($_SESSION['addBlog_success']);
        }
    ?>
    <style>
        h1 {
            text-align: center;
            color: #343a40;
            margin-bottom: 30px;
        }
        h2 {
            text-align: center;
            color: #343a40;
            margin-bottom: 30px;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 1100px;
            margin: auto;
        }
        label {
            font-size: 18px;
            font-weight: 800;
            color: #5a9498;
            white-space: nowrap;
            margin: 1px 0;
        }
        input[type="text"], input[type="datetime-local"], select, #editor {
            width: 100%;
            border-radius: 4px;
            border: #e9ecef 1px solid !important;
            background-color: #e9ecef !important;
            border-radius: 7px !important;
            font-size: 18px !important;
            padding: 6px 5px !important;
            color: black !important;
            box-shadow: unset !important;
        }
        #editor {
            border-radius: unset !important;
            border-bottom-right-radius: 10px !important;
            border-bottom-left-radius: 10px !important;
        }
        input[type="file"] {
            margin-bottom: 20px;
        }
        input[type="submit"], input[type="reset"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        input[type="submit"]:hover, input[type="reset"]:hover {
            background-color: #0056b3;
        }

        a:hover {
            text-decoration: underline;
        }
        #editor {
            height: 200px;
            background: #fff;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        #blog-image {
            display: none;
            max-width: 100%;
            margin-top: 20px;
        }
        .page_title {
            width: 100%;
            color: #495057;
            font-weight: 800;
            font-size: 28px;
            border-radius: 10px;
            margin: 10px 0px 5px 0px;
        }
        .page_title i {
            padding-left: 7px;
            font-size: 21px;
            bottom: 1px;
        }
        .breadcrumbs {
            position: absolute;
            top: -55px;
            left: 0px;
            margin: 10px 0px 0px 0px;
            width: fit-content;
            font-size: 18px;
        }
        .item_details{
            margin-top: 55px;
            position: relative;
        }
        .admin_page{
            margin-bottom: 120px;
        }
        .admin_page .button_1 {
            padding: 4px 20px;
            width: fit-content;
            background-color: #5a9498;
            color: white;
            border-radius: 8px;
            font-weight: 800;
            font-size: 18px;
            transition: 0.15s;
            text-decoration: none;
            align-items: center;
            display: flex;
        }
        .ql-toolbar {
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
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
            top: 70px;
            left:-23px;
        }
        .back_button:hover {
            background-color: #36676A;
            transform: scale(1.05) !important;
            text-decoration: none;
            color: white !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid admin_page">
        <div class="form-container item_details">
            <div class="breadcrumbs">
                <a>Admin</a> &gt; <a>Blog</a> &gt; <a href="blogs-all.php">Blog List</a> &gt; <a class="active">New Blog</a>
            </div>
            <div class="page_title">New Blog<i class="fas fa-pen"></i></div>
            <form method="POST" action="" enctype="multipart/form-data">
                <label for="subject">Subject</label><br>
                <input type="text" id="subject" name="subject" required><br><br>
                <!-- Replace textarea with a div for Quill editor -->
                <label for="blog_content">Content</label><br>
                <div id="editor" name="blog_contents"></div><br><br>
                <input type="hidden" id="content" name="content">
                <label for="date">Date</label><br>
                <input type="datetime-local" id="date" name="date" value="<?php echo $date ?>" readonly required><br><br>
                <label for="image">Image</label><br>
                <input type="file" id="image" name="image" accept="image/jpeg, image/png, image/gif" required><br><br>
                <img id="blog-image" src="" alt="Blog Image">
                <input type="hidden" id="imagePath" name="imagePath">
                <label for="blog_type">Blog Type</label><br>
                <select id="blog_type" name="blog_type">
                    <option value="Discount">Discount</option>
                    <option value="News">News</option>
                    <option value="Update">Update</option>
                    <!-- Add more options as needed -->
                </select><br><br>
                <div class="d-flex align-items-center justify-content-end">
                    <input type="submit" class="button_1" name="submit" value="Add Blog">
                </div>
            </form>
            <a href="blogs-all.php" class="back_button">Back To List</a>
        </div>
    </div>

    <!-- Include Quill library -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'], // Bold, Italic, Underline, Strikethrough
            [{ 'color': [] }, { 'background': [] }], // Text Color, Background Color
            [{ 'align': [] }], // Text Alignment
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }], // Headers
            [{ 'list': 'ordered' }, { 'list': 'bullet' }], // Ordered and Unordered list
            ['blockquote', 'code-block'], // Blockquote, Code block
            ['link', 'image', 'video'], // Insert Link, Image, Video
            ['clean'], // Remove Formatting
            [{ 'script': 'sub' }, { 'script': 'super' }], // Subscript, Superscript
            [{ 'size': ['small', false, 'large', 'huge'] }], // Text Size
            [{ 'indent': '-1' }, { 'indent': '+1' }]
        ];

        var quill = new Quill('#editor', {
            modules: {
                toolbar: toolbarOptions,
            },
            theme: 'snow'
        });

        quill.on('text-change', function () {
            var html = quill.root.innerHTML;
            document.getElementById('content').value = html;
        });
    </script>
</body>

</html>
