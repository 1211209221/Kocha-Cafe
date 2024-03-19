<style>
    .custom_option_container{
        margin-left: 20px;
    }

    .custom_option_container .option_name{
        width: 120px;
    }

    .custom_option_container .option_price{
        width: 60px;
    }
</style>
<?php
include '../../../connect.php';

// Check if form is submitted for update, then update the record
if(isset($_POST['update'])) {
    $custom_IDs = $_POST['custom_ID'];
    $custom_names = $_POST['custom_name'];
    $option_names = $_POST['option_name'];
    $option_prices = $_POST['option_price'];

    $success_count = 0;

    // Loop through each entry and update
    for($i = 0; $i < count($custom_IDs); $i++) {
        $custom_ID = $custom_IDs[$i];
        $custom_name = $custom_names[$i];
        $options = [];

        // Construct options array
        if (isset($option_names[$custom_ID]) && isset($option_prices[$custom_ID])) {
            for ($j = 0; $j < count($option_names[$custom_ID]); $j++) {
                // Format the price to two decimal places
                $formatted_price = number_format($option_prices[$custom_ID][$j], 2);
                $options[] = sprintf('("%s",%s)', $option_names[$custom_ID][$j], $formatted_price);
            }
        }

        if (!empty($options)){
            $custom_options = "{" . implode(",", $options) . "}";
        }
        else{
            $custom_options = "";
        }

        // Update query
        $sql = "UPDATE menu_customization SET custom_name=?, custom_options=? WHERE custom_ID=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssi', $custom_name, $custom_options, $custom_ID);

        if(mysqli_stmt_execute($stmt)) {
            $success_count++;
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }

    // Display confirmation message after all updates
    if($success_count == count($custom_IDs)) {
        echo "All records updated successfully";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

if(isset($_POST['addOption'])) {
    $custom_ID = $_POST['addOption']; // Assuming this value holds the ID of the custom option being modified
    $emptyOption = '("New",0)'; // Define the empty option
    // Fetch the current custom_options
    $sql_select = "SELECT custom_options FROM menu_customization WHERE custom_ID=?";
    $stmt_select = mysqli_prepare($conn, $sql_select);
    mysqli_stmt_bind_param($stmt_select, 'i', $custom_ID);
    mysqli_stmt_execute($stmt_select);
    $result = mysqli_stmt_get_result($stmt_select);
    $row = mysqli_fetch_assoc($result);
    $custom_options = $row['custom_options'];

    // Append the empty option to custom_options
    if (!empty($custom_options)) {
        // Remove outer brackets
        $custom_options = trim($custom_options, "{}");
        $custom_options .= ",$emptyOption";
    } else {
        // If custom_options is empty, set it to the empty option
        $custom_options = "{$emptyOption}";
    }

    // Update the database with the modified custom_options
    $sql_update = "UPDATE menu_customization SET custom_options=? WHERE custom_ID=?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, 'si', $custom_options, $custom_ID);
    if(mysqli_stmt_execute($stmt_update)) {
        echo "Empty option has been added successfully.";
    } else {
        echo "Error adding empty option: " . mysqli_error($conn);
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

if(isset($_POST['add'])) {
    $sql_add = "INSERT INTO menu_customization (custom_name) VALUES (?)";
    $stmt_add = mysqli_prepare($conn, $sql_add);

    if ($stmt_add) {
        $custom_options = "New Option";
        mysqli_stmt_bind_param($stmt_add, 's', $custom_options);

        if (mysqli_stmt_execute($stmt_add)) {
            echo "Empty option has been added successfully.";
        } else {
            echo "Error adding empty option: " . mysqli_error($conn);
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt_add);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}


function deleteOption($custom_ID, $optionIndex, $conn) {
    // Fetch the current custom_options
    $sql_select = "SELECT custom_options FROM menu_customization WHERE custom_ID=?";
    $stmt_select = mysqli_prepare($conn, $sql_select);
    mysqli_stmt_bind_param($stmt_select, 'i', $custom_ID);
    mysqli_stmt_execute($stmt_select);
    $result = mysqli_stmt_get_result($stmt_select);
    $row = mysqli_fetch_assoc($result);
    $custom_options = $row['custom_options'];

   // Remove the specified option
    preg_match_all('/\("([^"]+)",(\d+(\.\d+)?)\)/', $custom_options, $matches, PREG_SET_ORDER);
    $options = [];

    foreach ($matches as $match) {
        // Format the price to two decimal places
        $formatted_price = number_format($match[2], 2);
        $options[] = sprintf('("%s",%s)', $match[1], $formatted_price);
    }

    unset($options[$optionIndex]); // Remove the specified option

    // Reconstruct custom_options with remaining options
    if (empty($options)) {
        // No options left, set custom_options to empty string
        $custom_options = '';
    } else {
        $optionStrings = [];
        foreach ($options as $option) {
            $optionStrings[] = "$option";
        }
        $custom_options = "{" . implode(",", $optionStrings) . "}";
    }

    // Update the database with the modified custom_options
    $sql_update = "UPDATE menu_customization SET custom_options=? WHERE custom_ID=?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, 'si', $custom_options, $custom_ID);
    if(mysqli_stmt_execute($stmt_update)) {
        echo "Option has been deleted successfully.";
    } else {
        echo "Error deleting option: " . mysqli_error($conn);
    }
}

// Handle trashOption button click
if(isset($_POST['trashOption'])) {
    list($custom_ID, $optionIndex) = explode("-", $_POST['trashOption']);
    deleteOption($custom_ID, $optionIndex, $conn);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Define deleteCategories function outside of the if block
function deleteCust($custom_ID, $conn) {

    // Delete query
    $sql2 = "DELETE FROM menu_customization WHERE custom_ID=?";
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, 'i', $custom_ID);

    if(mysqli_stmt_execute($stmt2)) {
        echo "Option with ID $custom_ID has been deleted successfully.";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

if(isset($_POST['trashCustom'])) {
    $custom_ID = $_POST['trashCustom'];
    deleteCust($custom_ID, $conn);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
// Display form
$sql = "SELECT * FROM menu_customization";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<input type='hidden' name='custom_ID[]' value='" . $row['custom_ID'] . "'>";
        echo "<input type='text' name='custom_name[]' value='" . $row['custom_name'] . "' maxlength='15'>";
        echo "<input type='hidden' name='custom_options[]' value='" . $row['custom_options'] . "'>";
        echo "<button type='submit' name='trashCustom' value='" . $row['custom_ID'] . "'>x</button>";
        echo "<br>";

        // Check if custom_options is not null and not empty
        if (!empty($row['custom_options'])) {
            $optionsString = $row['custom_options'];
            // Remove outer brackets
            $optionsString = trim($optionsString, "{}");
            // Split options by comma
            $options = explode("),(", $optionsString);

            echo "<div class='custom_option_container'>";
            $optionIndex = 0;
            foreach ($options as $optionIndex => $option) {
            // Remove inner brackets and split by comma
            list($optionName, $optionPrice) = explode(",", trim($option, "()"));
            // Remove quotations from option name
            $optionName = trim($optionName, '"');
            echo "<input type='text' name='option_name[" . $row['custom_ID'] . "][]' value='" . $optionName . "' class='option_name' maxlength='15'>";
            echo "<input type='number' name='option_price[" . $row['custom_ID'] . "][]' step='0.10' value='" . $optionPrice . "' class='option_price'>";
            echo "<button type='submit' name='trashOption' value='" . $row['custom_ID'] . "-" . $optionIndex . "'>x</button>";
            echo "<br>";
        }
             echo "</div>";
        }
        echo "<button name='addOption' value='" . $row['custom_ID'] . "' style='margin-left:20px;'>+</button>";
        echo "<br>";
    }
} else {
    echo "No items found";
}
echo "<input type='submit' name='add' value='Add'><input type='submit' name='update' value='Update'></form>";
?>
