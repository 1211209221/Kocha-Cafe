<?php
include '../connect.php'; 
require_once('../dompdf/autoload.inc.php');
use Dompdf\Dompdf;

// Function to generate PDF
function generatePDF($htmlContent) {
    $dompdf = new Dompdf();
    $dompdf->loadHtml($htmlContent);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    // Set headers to force download the PDF
    header("Content-Type: application/pdf");
    header("Content-Disposition: inline; filename=Kocha_Cafe_Sales_Report.pdf");
    header("Cache-Control: private, max-age=0, must-revalidate");
    header("Pragma: public");
    
    // Stream the PDF
    echo $dompdf->output();
}

// Begin capturing the HTML output
ob_start(); 
?>
<?php
$reportType = isset($_GET['reportType']) ? $_GET['reportType'] : 'allTime';

// Fetch data from database
$customerCount = 0;
$orderCount = 0;
$totalIncome = 0;
$todayIncome = 0;
$weeklyIncome = 0;
$monthlyIncomeSum = 0;
$type = "";
$startDate = "";
$endDate = "";

$customerQuery = "SELECT COUNT(*) as customerCount FROM customer";
$customerResult = $conn->query($customerQuery);
if ($customerResult) {
    $row = $customerResult->fetch_assoc();
    $customerCount = $row['customerCount'];
}

$orderQuery = "SELECT COUNT(*) as orderCount FROM customer_orders";
$orderResult = $conn->query($orderQuery);
if ($orderResult) {
    $row = $orderResult->fetch_assoc();
    $orderCount = $row['orderCount'];
}

$incomeQuery = "SELECT order_date, order_contents, order_total FROM customer_orders";
$incomeResult = $conn->query($incomeQuery);
$monthlyIncome = array_fill(0, 12, 0); // Initialize an array with 12 zeros for monthly income

$todayDate = date('Y-m-d'); // Today's date
$startOfWeek = date('Y-m-d', strtotime('monday this week')); // Start of the week (Monday)
$endOfWeek = date('Y-m-d', strtotime('sunday this week')); // End of the week (Sunday)
$startOfMonth = date('Y-m-01'); // Start of the month (first day)
$endOfMonth = date('Y-m-t'); // End of the month (last day)

if ($incomeResult) {
    while ($row = $incomeResult->fetch_assoc()) {
        $item_sumprice = $row['order_total'];
        if (isset( $item_sumprice)) {
            $totalIncome += floatval($item_sumprice);
            $month = intval(date('m', strtotime($row['order_date'])));
            $monthlyIncome[$month - 1] += floatval($item_sumprice);

            $orderDate = date('Y-m-d', strtotime($row['order_date']));
            if ($orderDate == $todayDate) {
                $todayIncome += floatval($item_sumprice);
            }
            if ($orderDate >= $startOfWeek && $orderDate <= $endOfWeek) {
                $weeklyIncome += floatval($item_sumprice);
            }
            if ($orderDate >= $startOfMonth && $orderDate <= $endOfMonth) {
                $monthlyIncomeSum += floatval($item_sumprice);
            }
        }
    }
} else {
    echo "Error: " . $conn->error;
}

$monthlyOrders = array_fill(0, 12, 0);

// Loop through each month from January (1) to December (12)
for ($month = 1; $month <= 12; $month++) {
    // Calculate the start and end dates for the month
    $startOfMonth = date('Y-m-01'); // Start of the month (first day)
    $endOfMonth = date('Y-m-t'); // End of the month (last day)
    
    // Query to count orders for the specific month
    $monthlyOrderQuery = "SELECT COUNT(*) as orderCount FROM customer_orders WHERE order_date >= '$startOfMonth' AND order_date <= '$endOfMonth'";
    $monthlyOrderResult = $conn->query($monthlyOrderQuery);
    
    if ($monthlyOrderResult) {
        $row = $monthlyOrderResult->fetch_assoc();
        $monthlyOrders[$month - 1] = intval($row['orderCount']);
    }
}

// Calculate the current month index
$currentMonthIndex = intval(date('n')) - 1; // 'n' returns the month number without leading zeros

// Get the number of orders for the current month
$currentMonthOrders = $monthlyOrders[$currentMonthIndex];

// Fetch yesterday's and today's orders count
$yesterdayOrders = 0;
$todayOrders = 0;

$yesterdayDate = date('Y-m-d', strtotime('-1 day'));

$yesterdayQuery = "SELECT COUNT(*) as orderCount FROM customer_orders WHERE DATE(order_date) = '$yesterdayDate'";
$todayQuery = "SELECT COUNT(*) as orderCount FROM customer_orders WHERE DATE(order_date) = '$todayDate'";

$yesterdayResult = $conn->query($yesterdayQuery);
$todayResult = $conn->query($todayQuery);

if ($yesterdayResult) {
    $row = $yesterdayResult->fetch_assoc();
    $yesterdayOrders = $row['orderCount'];
}

if ($todayResult) {
    $row = $todayResult->fetch_assoc();
    $todayOrders = $row['orderCount'];
}

// Define the start of the week (assuming week starts on Monday)
$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));

// Initialize an array with 7 zeros for weekly orders (Monday to Sunday)
$weeklyOrders = array_fill(0, 7, 0);

for ($i = 0; $i < 7; $i++) {
    // Calculate the date for each day of the week
    $date = date('Y-m-d', strtotime("$startOfWeek +$i days"));
    
    // Query to count orders for each specific day
    $dailyOrderQuery = "SELECT COUNT(*) as orderCount FROM customer_orders WHERE DATE(order_date) = '$date'";
    $dailyOrderResult = $conn->query($dailyOrderQuery);
    
    if ($dailyOrderResult) {
        $row = $dailyOrderResult->fetch_assoc();
        $weeklyOrders[$i] = intval($row['orderCount']);
    }
}

// Prepare data for the selected report type
$income = 0;
$orderCountReport = 0;
$orders = [];

switch ($reportType) {
    case 'today':
        $type = "today's";
        $income = $todayIncome;
        $orderCountReport = $todayOrders;
        $startDate = $endDate = $todayDate;

        $orderQuery = "SELECT * FROM customer_orders WHERE DATE(order_date) = '$todayDate'";
        $orderResult = $conn->query($orderQuery);
        if ($orderResult) {
            while ($row = $orderResult->fetch_assoc()) {
                $orders[] = $row;
            }
        }
        break;

    case 'thisWeek':
        $type = "this week's";
        $income = $weeklyIncome;
        $orderCountReport = array_sum($weeklyOrders);
        $startDate = $startOfWeek;
        $endDate = $endOfWeek;

        $orderQuery = "SELECT * FROM customer_orders WHERE order_date >= '$startOfWeek' AND order_date <= '$endOfWeek'";
        $orderResult = $conn->query($orderQuery);
        if ($orderResult) {
            while ($row = $orderResult->fetch_assoc()) {
                $orders[] = $row;
            }
        }
        break;

    case 'thisMonth':
        $type = "this month's";
        $income = $monthlyIncomeSum;
        $orderCountReport = $currentMonthOrders;
        $startDate = $startOfMonth;
        $endDate = $endOfMonth;

        $orderQuery = "SELECT * FROM customer_orders WHERE order_date >= '$startOfMonth' AND order_date <= '$endOfMonth'";
        $orderResult = $conn->query($orderQuery);
        if ($orderResult) {
            while ($row = $orderResult->fetch_assoc()) {
                $orders[] = $row;
            }
        }
        break;

    case 'allTime':
    default:
        $type = "all-time";
        $income = $totalIncome;
        $orderCountReport = $orderCount;

        $orderQuery = "SELECT * FROM customer_orders ORDER BY order_date ASC";
        $orderResult = $conn->query($orderQuery);
        if ($orderResult) {
            while ($row = $orderResult->fetch_assoc()) {
                $orders[] = $row;
            }
        }

        $startDateQuery = "SELECT MIN(order_date) as startDate FROM customer_orders";
        $endDateQuery = "SELECT MAX(order_date) as endDate FROM customer_orders";
        
        $startDateResult = $conn->query($startDateQuery);
        $endDateResult = $conn->query($endDateQuery);

        if ($startDateResult) {
            $row = $startDateResult->fetch_assoc();
            $startDate = $row['startDate'];
        }

        if ($endDateResult) {
            $row = $endDateResult->fetch_assoc();
            $endDate = $row['endDate'];
        }
        break;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales Report | Admin Panel</title>
    <link rel="icon" href="../images/logo/logo_icon_2.png">
</head>
<body>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
        }
        table{
            font-size: 14px;
            width: 100%;
            border: 0;
        }
        table tr td{
            padding: 0 5px;
            margin: 0;
            border: 0;
        }
        ul li{
            list-style: none;
        }
        ul{
            padding-left: 0;
            margin: 7px 10px;
        }
        table th {
            color: #495057;
            background-color: #e9ecef;
            border-color: #dee2e6;
            border: #dee2e6 1px solid;
        }
        table tr:nth-child(even) {
            border-top-right-radius: 7px;
            background-color: #f4f8fa;
        }
    </style>
    <h1 style="color:#364657;text-transform: uppercase;">SALES REPORT  <span style="font-size:18px;">FROM Kocha Café</span></h1>
    <p>Email  : kochacafe8@gmail.com</p>
    <p>Tel    : +6017 412 4250</p>
    <p>Address: No. 44, Jalan Desa Melur 4/1, Taman Bandar Connaught, 56000 Cheras, Kuala Lumpur, Malaysia</p>
    <hr>
    <p>Displaying <?php echo $type; ?> sales results for Kocha Café: </p>
    <div>
        <p style="font-size: 14px; margin: 0; line-height: 1.4; padding: 0;">Start Date: <?php echo $startDate; ?></p>
        <p style="font-size: 14px; margin: 0; line-height: 1.4; padding: 0; margin-bottom: 10px;">End Date: <?php echo $endDate; ?></p>
    </div>
    <h3 style="margin-bottom: 3px; color:#364657;">Overview</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Category</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Revenue</td>
                <td>RM <?php echo number_format($income, 2); ?></td>
            </tr>
            <tr>
                <td>Total Orders</td>
                <td><?php echo $orderCountReport; ?></td>
            </tr>
        </tbody>
    </table>

    <h3 style="margin-bottom: 3px; margin-top: 25px; color:#364657;">Order Details</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Order Contents</th>
                <th>Total Price</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($orders as $order) {
                    $order_id = $order['order_ID'];
                    $date = date('Y-m-d H:i:s', strtotime($order['order_date']));
                    $total_sumprice = 0;
                    $order_contents = $order['order_contents'];
                    $items = explode("},{", $order_contents);
                    $items = array_filter($items, 'strlen');

                    echo "<tr>";
                    echo "<td class='t_id'>K_" . $order_id . "</td>";
                    $cust_query = "SELECT cust_username FROM customer WHERE trash = 0 AND cust_ID = " . $order['cust_ID'];
                    $query_result = $conn->query($cust_query);
                    $query_row = $query_result->fetch_assoc();
                    if ($query_row && !empty($query_row['cust_username'])) {
                        $username = $query_row['cust_username'];
                    } else {
                        $username = "User is disabled.";
                    }
                    echo "<td class='t_name'>" . $username . "</td>";
                    echo "<td class='t_item'><ul>";

                    foreach ($items as $item) {
                        $item = trim($item, "{}");
                        $details = explode(",", $item);
                        $item_name = trim($details[1], "()");
                        $item_qty = trim($details[3], "()");
                        $item_sumprice = trim($details[4], "()");
                        $total_sumprice += floatval($item_sumprice);
                        echo '<li>' . $item_qty . ' x ' . $item_name . '</li>';
                    }

                    echo "</ul></td>";
                    echo "<td class='t_price'>RM " . number_format($total_sumprice, 2) . "</td>";
                    echo "<td class='t_date'>" . $date . "</td>";
                }                       
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
$htmlContent = ob_get_clean(); // Get the buffered content

// Generate PDF
generatePDF($htmlContent);
?>
