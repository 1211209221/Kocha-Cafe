<?php
// Assuming you have established a database connection
include '../connect.php';
$fileId = isset($_GET['file_id']) ? $_GET['file_id'] : '';

if (!empty($fileId)) {
    // Sanitize the input to prevent SQL injection
    $fileId = intval($fileId);

    // SQL query to fetch file data based on file ID
    $sql = "SELECT filename, filetype, filesize, file_content FROM cf_files WHERE file_id = $fileId AND trash = 0"; // Assuming your table name is cf_files
    
    // Execute the query
    $result = $conn->query($sql);
    
    // Check if the query was successful and if a file was found
    if ($result && $result->num_rows == 1) {
        // Fetch the file data
        $row = $result->fetch_assoc();
        $fileName = $row['filename'];
        $fileSize = $row['filesize'];
        $fileType = $row['filetype'];
        $fileContent = $row['file_content'];

        if ($fileType == 'application/pdf') {
            // Display PDF in a new tab
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $fileName . '"');
        }else if (strpos($fileType, 'image/') === 0) {
            // Display image in a new tab
            header('Content-Type: ' . $fileType);
            header('Content-Disposition: inline; filename="' . $fileName . '"');
        }
         else {
            // Download other file types
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
        }
        header('Content-Length: ' . $fileSize);

        // Output file content
        echo $fileContent;
        exit;
    } else {
        // File not found
        echo "File not found";
    }
} else {
    // File ID not provided
    echo "File ID not provided";
}
// Close the database connection
$conn->close();
?>
