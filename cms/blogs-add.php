<?php
ob_start(); 

date_default_timezone_set('Asia/Kuala_Lumpur');
$date = date('Y-m-d H:i:s');

// Handle form submission
if (isset($_POST['submit'])) {
    $subject = $_POST['subject'];
    $content = $_POST['content'];
    $date = $_POST['date'];

    // Handle image upload
    $image = '';
    if (isset($_POST['imagePath'])) {
        $image = $_POST['imagePath'];
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
        echo "Blog Type: $blog_type<br>";
        header("Location: blogs-add.php");
        ob_end_flush(); // Flush and end the output buffer
        exit();
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}

// Handle image upload via AJAX
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES['image']['name']);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $response = array();
    echo json_encode($response);
    exit();
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
        include '../connect.php';
        include '../gototopbtn.php';
        include 'navbar.php';
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
        .container-fluid {
            padding: 0 30px;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        label {
            font-weight: bold;
            color: #495057;
        }
        input[type="text"], input[type="datetime-local"], select, #editor {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ced4da;
            border-radius: 4px;
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
    </style>
</head>

<body>
    <div class="container-fluid">
        <h1>Add Blog</h1>
        <div class="form-container">
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
                <img id="blog-image" src="" alt="Blog Image">
                <input type="hidden" id="imagePath" name="imagePath">
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

        document.getElementById('image').addEventListener('change', function (e) {
            var file = e.target.files[0];
            var reader = new FileReader();

            reader.onload = function (e) {
                document.getElementById('blog-image').style.display = 'block';
                document.getElementById('blog-image').setAttribute('src', e.target.result);
            }

            reader.readAsDataURL(file);

            var formData = new FormData();
            formData.append('image', file);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'blogs-add.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        document.getElementById('imagePath').value = response.imageUrl;
                    } else {
                        alert('Image upload failed: ' + response.message);
                    }
                } else {
                    alert('An error occurred while uploading the image.');
                }
            };
            xhr.send(formData);
        });
    </script>
</body>

</html>
