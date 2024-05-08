<?php
include '../connect.php'; // Include your database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['blog_id'], $_POST['blog_title'], $_POST['blog_contents'], $_POST['blog_type'])) {
        // Sanitize and store the form data
        $blog_id = $_POST['blog_id'];
        $blog_title = $_POST['blog_title'];
        $blog_contents = $_POST['blog_contents'];
        $blog_type = $_POST['blog_type'];

        // Check if a file is uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $filename = $_FILES["image"]["name"];
            $tempname = $_FILES["image"]["tmp_name"];
            $data = file_get_contents($tempname);
            $mime_type = mime_content_type($tempname);

            // Encode image data and MIME type together
            $image_data = "data:$mime_type;base64," . base64_encode($data);

            // Update blog data including image content and MIME type
            $stmt = $conn->prepare("UPDATE blog SET blog_title = ?, blog_contents = ?, blog_type = ?, image = ? WHERE blog_ID = ?");
            $stmt->bind_param("ssssi", $blog_title, $blog_contents, $blog_type, $image_data, $blog_id); // Assuming the blog ID is an integer
            $stmt->execute();

            // Check if the update was successful
            if ($stmt->affected_rows > 0) {
                // Redirect to the blogs-all page
                header("Location: blogs-all.php?success=1");
                exit();
            } else {
                // Redirect back to the edit page with an error message if no rows were affected
                header("Location: blogs-edit.php?id=$blog_id&error=1");
                exit();
            }
        } else {
            // If no image is uploaded, update other blog data excluding image
            $stmt = $conn->prepare("UPDATE blog SET blog_title = ?, blog_contents = ?, blog_type = ? WHERE blog_ID = ?");
            $stmt->bind_param("sssi", $blog_title, $blog_contents, $blog_type, $blog_id); // Assuming the blog ID is an integer
            $stmt->execute();

            // Check if the update was successful
            if ($stmt->affected_rows > 0) {
                // Redirect to the blogs-all page
                header("Location: blogs-all.php?success=1");
                exit();
            } else {
                // Redirect back to the edit page with an error message if no rows were affected
                header("Location: blogs-edit.php?id=$blog_id&error=1");
                exit();
            }
        }
    } else {
        // Redirect back to the edit page with an error message if required fields are not set
        header("Location: blogs-edit.php?id=$blog_id&error=1");
        exit();
    }
} else {
    // Redirect back to the edit page if the form was not submitted
    header("Location: blogs-edit.php?id=$blog_id");
    exit();
}
?>
