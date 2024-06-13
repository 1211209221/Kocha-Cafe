<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Message | Admin Panel</title>
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
        <link rel="icon" href="../images/logo/logo_icon_2.png">
        <script src="../script.js"></script>
        <script src="../gototop.js"></script>
    </head>
    <body>
        <style>
        .replybtn{
            width: 50%;
            margin: 6px 6px 0px 6px;
            background: #5a9498;
            color: #fff;
            font-weight: 600;
            border: #e9ecef 1px solid;
            border-radius: 7px;
            font-size: 18px;
            padding: 3px 5px;
            display: inline-block;
            text-align: center;
            text-decoration: none;
        }
        .replybtn i{
            margin-left:8px;
            font-size:16px;
        }
        .replybtn:hover{
            text-decoration:none;
            background-color:;
            color:white;
            background-color: #36676A;
            transform: scale(1.1);
            transition:0.15s;
        }
        .file-box{
            min-width: 46%;
            margin: 5px;
            background: #ffffff;
            padding: 8px;
            border-radius: 5px;
            font-size: 16px;
            overflow-wrap: anywhere;
        }
        .file-box a{
            color: #5a9498;
            font-weight: 600;
        }
        .file-box a:active{
            color: #000;
        }

        @media (max-width: 500px) {
            .file-box{
            width: 100%;;
        }
    }
        </style>

    <?php
        include '../connect.php';
        include '../gototopbtn.php';
        include 'navbar.php';

        //session_start();


        if (isset($_GET['ID'])) {
            // Retrieve the value of the ID parameter
            $CF_ID = $_GET['ID'];
                $sqlread = "UPDATE contact_message SET markasread = 1 WHERE CF_ID = $CF_ID";
                if ($conn->query($sqlread) === TRUE) {
                    // The update was successful
                } else {
                    // There was an error with the SQL query
                    echo "Error updating record: " . $conn->error;
                }
        }
            
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['edit_submit'])){
                // Retrieve form data
                $readstatus = $_POST['readstatus'];
                $sql = "UPDATE contact_message SET markasread = '$readstatus' WHERE CF_ID = $CF_ID";
                

                if ($conn->query($sql) === TRUE) {
                    $_SESSION['editmsg_success'] = true;
                    echo '<script>';
                    echo 'window.location.href = "messages-all.php";';
                    echo '</script>';
                    //header("Location: messages-all.php");
                    exit();
                } else {
                    $_SESSION['editmsg_error'] = "Error: " . $sql . "<br>" . $conn->error;
                    echo '<script>';
                    echo 'window.location.href = "messages-all.php";';
                    echo '</script>';
                    exit();
                }
            }
            if (isset($_POST['delete'])){
                $trash = $_POST['delete'];
                $sql = "UPDATE contact_message SET trash = 1, markasread = 1 WHERE CF_ID = $CF_ID";

                if ($conn->query($sql) === TRUE) {
                    $_SESSION['delmsg_success'] = true;
                    header("Location: messages-all.php");
                    exit();
                } else {
                    $_SESSION['delmsg_error'] = "Error: " . $sql . "<br>" . $conn->error;
                    header("Location: messages-all.php");
                    exit();
                }
            }
        }

        

        $sqlall = "SELECT * FROM contact_message WHERE CF_ID = $CF_ID";

        
        $result = $conn->query($sqlall);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){  
    ?>
    <div class="container-fluid container">
        <div class="col-12 m-auto">
            <div class="edit_items add_items">
                <form method="post" class="item_edit_form">
                    <div class="big_container" style="position: relative;">
                        <div class="breadcrumbs">
                            <a>Admin</a> > <a>Inbox</a> > <a href="messages-all.php">Message List</a> > <a class="active"><?php echo $row['CF_subject']; ?></a>
                        </div>
                        <span style="position: absolute;right: 20px;top: 10px;font-size: 16px;color: gray;"><?php echo $row['CF_time']; ?></span>
                        <div class='item_details'>
                            <div class="page_title">Inbox Message<i class="fas fa-envelope-open"></i></div>
                            <div class='item_detail_container'>
                                <label for="subject">Subject</label>
                                <input type="text" title="Unable to edit" name="subject" id="subject" value="<?php echo $row['CF_subject']; ?>" readonly>
                            </div>
                            <div class="address-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                            <div class='item_detail_container'>
                                <label for="sname">Sender Name</label>
                                <input type="text" title="Unable to edit" name="sname" id="sname" value="<?php echo $row['CF_name']; ?>" readonly>
                            </div>
                            <div class="address-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                            <div class='item_detail_container'>
                                <label for="semail">Sender Email Address</label>
                                <input type="email" title="Unable to edit" name="semail" id="semail" value="<?php echo $row['CF_email']; ?>" readonly>
                            </div>
                            <div class="address-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                            <div class='item_detail_container'>
                                <label for="sphno">Sender Phone Number</label>
                                <input type="tel" title="Unable to edit" name="sphno" id="sphno" value="<?php echo $row['CF_phno']; ?>" readonly>
                            </div>
                            <div class='item_detail_container'>
                                <label for="message">Message</label>
                                <textarea rows="10" title="Unable to edit" name="message" id="message" readonly><?php echo $row['CF_message']; ?></textarea>
                            </div>
                            <div class="address-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                            <div class='submit_buttons' style="display: flex;justify-content: center;">
                            <a class="replybtn" title='Email' href='mailto:<?php echo $row['CF_email']; ?>'>Email<i class="fas fa-at"></i></a>
                            <a class="replybtn" title='Call' href='tel:<?php echo $row['CF_phno']; ?>'>Call<i class="fas fa-phone"></i></a>
                        </div>
                            <hr style="width:100%;">
                            <div class="address-error" style="margin-left:185px;margin-bottom:5px;font-size:12px;color:red;"></div>
                        <div class='item_detail_container'>
                            <label for="delivery">Attachments</label>
                            <div name="delivery" id="delivery" style="width:100%;border: #e9ecef 1px solid;background-color: #e9ecef;border-radius: 7px;font-size: 18px;padding: 5px 5px;display:flex;flex-wrap:wrap;justify-content: space-between;">
                                <?php
                                    //take out addresses
                                    $get_attachment = "SELECT * FROM cf_files WHERE CF_ID = $CF_ID AND trash = 0";
                                        $resultfile = $conn->query($get_attachment);

                                        if ($resultfile && $resultfile->num_rows > 0) {
                                            // Loop through each row in the result set
                                            while ($rowf = $resultfile->fetch_assoc()) {
                                                // Access the file information from the row
                                                $fileId = $rowf['file_id'];
                                                $filename = $rowf['filename'];
                                                $fileSize = $rowf['filesize'];
                                                $fileType = $rowf['filetype'];
                                                $uploadDate = $rowf['upload_date'];
                                                // Assuming CF_ID is a foreign key linking to another table, you can access it as well if needed
                                        
                                                // Output the file information with a link to open the file
                                                echo "<div class='file-box'><i class='fas fa-file' style='margin-right:5px;'></i>";
                                                echo "<a title='Download' href='download.php?file_id=" . urlencode($fileId) . "&filename=" . urlencode($filename) . "'>$filename</a><br>"; // Link to the file location
                                                echo "<span style='font-size:15px;'>$fileSize btyes</span><br>";
                                                echo "</div>";
                                            }
                                        }
                                        else{
                                            echo '
                                            <span><i class="fas fa-file" style="margin-right:8px;"></i>No attachments...</span>
                                            ';
                                        }
                                ?>
                            </div>
                        </div>
                        <hr style="width:100%;">
                        <div class='item_detail_container'>
                            <label for="readstatus">Read Status</label>
                            <select name="readstatus" id="readstatus" style="width:100%;">
                                <option value="0" <?php if ($row['markasread'] == 0) echo "selected"; ?>>Mark as Unread</option>
                                <option value="1" <?php if ($row['markasread'] == 1) echo "selected"; ?>>Mark as Read</option>
                            </select>
                        </div>
                        <div class='submit_buttons'>
                            <input type="submit" id="edit-submit" name="edit_submit" class="edit_submit" value="Save" onclick="return confirmAction('mark this message as unread');">
                            <?php
                                if ($admin['admin_level'] == 2) {
                                    echo '<input type="submit" name="delete" class="delete" value="Delete" onclick="return confirmAction(\'delete this message\');">';
                                }                                
                            ?>
                        </div>
                    </div>
                    <a href="messages-all.php" class="back_button2">Back To List</a>
                </div>
            </div>
                </form>
                <script>
                    function confirmAction(message) {
                        return confirm("Are you sure you want to " + message + "?");
                    }
                  
                </script>
            <?php
                }
                } else {
                    echo "No inbox found";
                }

                $conn->close();
                ?>
    </body>
</html>