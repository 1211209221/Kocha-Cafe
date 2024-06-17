<?php
include 'connect.php'; 
require_once('./dompdf/autoload.inc.php');
use Dompdf\Dompdf;

if (isset($_GET['ID'])) {
    // Retrieve the value of the ID parameter
    $order_id = $_GET['ID'];

    // Function to generate PDF
    function generatePDF($htmlContent, $order_id) {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Set headers to force download the PDF
        header("Content-Type: application/pdf");
        header("Content-Disposition: inline; filename=Kocha_Cafe_Report_{$order_id}.pdf");
        header("Pragma: public");
        
        // Stream the PDF
        echo $dompdf->output();
    }

    // Ensure $order_id is properly escaped to avoid SQL injection
    $order_id = $conn->real_escape_string($order_id);

    // Fetch order details
    $sql_get_order = "SELECT * FROM customer_orders WHERE order_ID = '$order_id' AND trash = 0";
    $result_get_order = $conn->query($sql_get_order);

    if ($result_get_order === false) {
        echo "Error: " . $conn->error;
        exit;
    }

    // Fetch customer details
    $order_row = $result_get_order->fetch_assoc();
    $cust_ID = $order_row['cust_ID'];
    $cust_query = "SELECT cust_username FROM customer WHERE cust_ID = $cust_ID";
    $cust_result = $conn->query($cust_query);
    $cust_row = $cust_result->fetch_assoc();
    $username = $cust_row['cust_username'];
    $order_date = $order_row['order_date'];

    // Start output buffering for HTML content
    ob_start();
    echo '<style>
        body {
            font-family: Helvetica, Arial, sans-serif;
        }
        .accordion {
            background-color: #50a5951f;
            color: #5a9498;
            cursor: pointer;
            padding: 10px;
            margin: 4px 0;
            width: 100%;
            border: none;
            border-radius: 5px;
            text-align: left;
            font-weight: 600;
            outline: none;
            font-size: 17px;
            transition: 0.4s;
            position: relative;
        }
        .order_date {
            font-size: 14px;
            position: absolute;
            right: 10px;
            top: 10px;
            font-weight: 400;
        }
        .accordion p {
            margin: unset;
            font-weight: 800;
        }
        .panel .simple_area {
            display: flex;
            background-color: #eeeeee;
            padding: 10px;
            border-radius: 8px;
        }
        .panel .simple_area .small_area {
            width: 100%;
            display: flex;
        }
        .small_area .o_title {
            font-weight: 800;
            font-size: 15px;
        }
        .small_area .o_title i {
            margin-right: 5px;
        }
        .small_area .o_content {
            font-size: 22px;
            font-weight: 800;
            color: #364657;
        }
        .panel .bottom_area {
            margin-top: 5px;
        }
        .left-box .bottom_area {
            margin-top: 10px;
            margin-bottom: 20px;
            display: flex;
            border: 1px solid darkgrey;
            padding: 8px;
            border-radius: 7px;
        }
        .bottom_area .paymentprt {
            width: 100%;
            margin-right: 5px;
        }
        .extra {
            color: #8a8a8a;
        }
        .bottom_area .text {
            font-weight: 800;
            color: #364657;
            font-size: 17px;
        }
        .bottom_area .attribute {
            display: flex;
            flex-direction: column;
            margin-bottom: 12px;
            margin-left: 5px;
        }
        .bottom_area .attribute .payment_title {
            font-weight: 800;
            font-size: 14px;
            color: #8a8a8a;
            display: block;
        }
        .bottom_area .order_table {
            box-shadow: none;
            border-radius: revert;
            border: 1px solid #eeeeee;
            width: 100%;
            font-weight: 400;
        }
        table thead .desc, thead .amount {
            font-size: 17px;
            color: white;
            padding: 6px 10px;
            font-weight: 800;
            background-color: #4d5464;
        }
        tbody tr {
            background-color: #f4f8fb;
        }
        tbody .desc, tbody .amount {
            padding: 10px;
            font-size: 16px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #f4f4f4;
            padding: 20px;
            box-sizing: border-box;
            border-top: 1px solid #ddd;
            text-align: center;
        }
        .main-content {
            margin-bottom: 60px;
        }
    </style>';

    echo '<h1 style="color:#364657;text-transform: uppercase;">INVOICE  <span style="font-size:18px;">FROM Kocha Café</span></h1>';
    echo '<p>Email  : kochacafe8@gmail.com</p>';
    echo '<p>Tel    : +6017 412 4250</p>';
    echo '<p>Address: No. 44, Jalan Desa Melur 4/1, Taman Bandar Connaught, 56000 Cheras, Kuala Lumpur, Malaysia</p>';
    echo '<hr>';
    echo '<div class="main-content"><div class="panel">
            <div>';
    echo '<p>User <b style="color:#5a9498;font-weight:700;font-size:17px;">'.$username.'</b>, Thank you for your purchase! Your invoice details are below:</p>
            <div class="simple_area">
                <div class="small_area">
                    <span class="o_title">TOTAL COST</span>
                    <span class="o_content" style="margin-left:50px;">RM '.$order_row['order_total'].'</span>
                
                    <span class="o_title" style="margin-left:90px;">ORDERED FROM</span>
                    <span class="o_content" style="margin-left:22px;">Kocha Café</span>
                </div>
            </div>';

    echo '<div class="bottom_area">
            <div class="orderprt"><p class="text">ORDER DETAILS</p>
                <div>
                    <table class="order_table">
                        <thead class="thead-light">
                            <tr style="background-color: #e9ecef;">
                                <th class="desc">Description</th>
                                <th class="amount">Amount</th>
                            </tr>
                        </thead>
                        <tbody border="transparent">';

    $items[] = "";
    $items = explode("},{", $order_row['order_contents']);
    $items = array_filter($items, 'strlen');
    if (count($items) != 0) {
        foreach ($items as $item) {
            $item = trim($item, "{}");
            $details = explode(",", $item);

            $item_ID = trim($details[0], "()");
            $item_name = trim($details[1], "()");
            $item_price = trim($details[2], "()");
            $item_qty = trim($details[3], "()");
            $item_sumprice = trim($details[4], "()");
            $item_request = trim($details[5], "()");
            $item_custom = implode(',', array_slice($details, 6));
            preg_match_all('/\(\[([^\]]+)\]\)/', $item_custom, $matches);

            echo '<tr class="noborder"><td class="desc">
                '.$item_qty.'x <span>'.$item_name.'</span>';
                if (!empty($matches[1])) {
                    foreach ($matches[1] as $match) {
                        $customs = explode(',', $match);
                        if (count($customs) >= 2) {
                            $custom_key = trim($customs[0]);
                            $custom_value = trim($customs[1]);
                            if (!empty($custom_key) && !empty($custom_value)) {
                                echo '<br><span class="extra">' . $custom_value . '</span>';
                            }
                        }
                    }
                }
                if(!empty($item_request)){
                    echo '<br><span class="extra">' . $item_request . '</span>';

                }
            echo '</td><td class="amount">RM '.$item_sumprice.'</td></tr>';
        }
    }

    echo '<tr style="background-color:#fffcf0;">
            <td class="desc">Subtotal</td><td class="amount">RM '.$order_row['order_total'].'</td>
          </tr>
        </tbody>
    </table>
    </div>
    </div>
    </div>
    </div>';

    echo '<div style="text-align:center;margin-top:15px;">~ ENJOY YOUR MEAL AND HAVE A NICE DAY ^_^ ~</div>';

    $htmlContent = ob_get_clean(); // Get the buffered content

    // Generate PDF
    generatePDF($htmlContent, $order_id);
}
?>
