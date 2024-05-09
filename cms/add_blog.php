<?php

include ("connect.php"); // Include the database connection file

date_default_timezone_set('Asia/Kuala_Lumpur');
$date = date('Y-m-d H:i:s');

if (isset($_POST['submit'])) {

    $subject = $_POST['subject'];
    $content = $_POST['content'];
    $date = $_POST['date'];

    // Handle file upload
    $file = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $fileData = file_get_contents($_FILES['file']['tmp_name']);
        $file = base64_encode($fileData);
    }

    // Handle image upload
// Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imagePath = 'uploads/' . $_FILES['image']['name']; // Modify the path as needed
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        $image = $imagePath;
    }


    $blog_type = isset($_POST['blog_type']) ? $_POST['blog_type'] : '';

    $query = "INSERT INTO blog (blog_title, blog_contents, file, date, blog_type, image) 
    VALUES ('$subject', '$content', '$file', '$date', '$blog_type', '$image')";

    if (mysqli_query($conn, $query)) {
        echo "<script>document.getElementById('content').value = '" . $_POST['content'] . "';</script>";
        echo "Record inserted successfully!<br>";
        echo "Subject: $subject<br>";
        echo "Content: $content<br>";
        echo "Date: $date<br>";
        echo "File: $file<br>";
        echo "Blog Type: $blog_type<br>";
        header("Location: add_blog.php");
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
    <title>Edit Blog</title>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="datetime-local"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="file"] {
            margin-top: 5px;
        }

        input[type="submit"],
        input[type="reset"] {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover,
        input[type="reset"]:hover {
            background-color: #45a049;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #333;
        }

        #editor {
            height: 200px;
            width: 100%;
            /* Adjust the height as needed */
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        .ql-toolbar {
            width: 100%;
            /* Same width as the editor */
        }
    </style>
</head>

<body>
    <h1>Add Blog</h1>
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
        <input type="file" id="image" name="image" accept="image/jpeg, image/png, image/gif"><br><br>
        <label for="file">File</label><br>
        <input type="file" id="file" name="file"><br><br>
        <label for="blog_type">Blog Type</label><br>
        <select id="blog_type" name="blog_type">
            <option value="Discount">Discount</option>
            <option value="News">News</option>
            <option value="Update">Update</option>
            <!-- Add more options as needed -->
        </select><br><br>
        <input type="submit" name="submit" value="Upload">
        <input type="reset">
    </form>


    <br><a href="blogs-all.php">View Announcements</a>

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
            [{ 'indent': '-1' }, { 'indent': '+1' }] // Indent, Outdent
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