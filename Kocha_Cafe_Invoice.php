<?php
include 'connect.php'; 
require_once('./dompdf/autoload.inc.php');
use Dompdf\Dompdf;

if (isset($_GET['ID'])) {
    // Retrieve the value of the ID parameter
    $payment_ID = $_GET['ID'];

    // Function to generate PDF
    function generatePDF($htmlContent, $payment_ID) {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Set headers to force download the PDF
        header("Content-Type: application/pdf");
        header("Content-Disposition: inline; filename=Kocha_Cafe_{$payment_ID}.pdf");
        header("Cache-Control: private, max-age=0, must-revalidate");
        header("Pragma: public");
        
        // Stream the PDF
        echo $dompdf->output();
    }

    // Ensure $payment_ID is properly escaped to avoid SQL injection
    $payment_ID = $conn->real_escape_string($payment_ID);

    // Fetch payment details
    $sql_get_payments = "SELECT * FROM payment WHERE payment_ID = '$payment_ID' AND trash = 0";
    $result_get_payments = $conn->query($sql_get_payments);

    if ($result_get_payments === false) {
        echo "Error: " . $conn->error;
        exit;
    }

    // Your existing code to generate HTML content
    while ($row = $result_get_payments->fetch_assoc()) { 
        $date = $row['payment_time'];   
        ob_start(); // Start output buffering
        echo '<style>
            body{
                 font-family: Helvetica, Arial, sans-serif;
            }
            .accordion{
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
            
            
            .order_date{
                font-size: 14px;
                position: absolute;
                right: 10px;
                top: 10px;
                font-weight: 400;
            }
            .accordion p{
                margin:unset;
                font-weight:800;
            }
            
            
            .panel .simple_area{
                display: flex;
                background-color: #eeeeee;
                padding: 10px;
                border-radius: 8px;
            }
            .panel .simple_area .small_area{
                width: 100%;
                display: flex;
            }
            .small_area .o_title{
                font-weight: 800;
                font-size: 15px;
            }
            .small_area .o_title i{
                margin-right: 5px;
            }
            .small_area .o_content{
                font-size: 22px;
                font-weight: 800;
                color: #364657;
            }
            .panel .bottom_area{
                margin-top:5px;
            }
            .left-box .bottom_area{
                margin-top: 10px;
                margin-bottom: 20px;
                display: flex;
                border: 1px solid darkgrey;
                padding: 8px;
                border-radius: 7px;
            }
            .bottom_area .paymentprt{
                width: 100%;
                margin-right: 5px;
            }
            .extra{
                color: #8a8a8a;
            }
            .bottom_area .text{
                font-weight: 800;
                color: #364657;
                font-size:17px;
            }
            .bottom_area .attribute{
                display: flex;
                flex-direction: column;
                margin-bottom: 12px;
                margin-left:5px;
            }
            .bottom_area .attribute .payment_title{
                font-weight: 800;
                font-size: 14px;
                color: #8a8a8a;
                display:block;
            } 
            .bottom_area .order_table{
                box-shadow:none;
                border-radius:revert;
                border:1px solid #eeeeee;
                width:100%;
                font-weight:400;
            }
            
            table thead .desc, thead .amount{
                font-size: 17px;
                color: white;
                padding: 6px 10px;
                font-weight:800;
                background-color:#4d5464;
            }
            tbody tr{
                background-color:#f4f8fb;
            }
            tbody .desc, tbody .amount{
                padding: 10px;
                font-size: 16px;
            }
            .footer {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background-color: #f4f4f4; /* Adjust as needed */
                padding: 20px; /* Adjust as needed */
                box-sizing: border-box;
                border-top: 1px solid #ddd; /* Adjust as needed */
                text-align: center; /* Adjust as needed */
            }
            .main-content {
                margin-bottom: 60px; 
            }
            
        </style>';
        echo '<button class="accordion" title="Expand"><p>'.$payment_ID.'</p>
                <span class="order_date" style="font-size: 15px;">'.$date.'</span>
                </button>';

        // Fetch order details
        $sql_get_order = "SELECT * FROM customer_orders WHERE order_date = '$date' AND trash = 0";
        $result = $conn->query($sql_get_order);

        if ($result === false) {
            echo "Error: " . $conn->error;
            exit;
        }

        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                $cust_ID = $rows['cust_ID'];
                $sql = "SELECT cust_username FROM customer WHERE cust_ID = $cust_ID";
                $re = $conn->query($sql);
                $name = $re->fetch_assoc();
                echo '<h1 style="color:#364657;text-transform: uppercase;">INVOICE  <span style="font-size:18px;">FROM Kocha Café</span></h1>';
                echo '<p>Email  : kochacafe8@gmail.com</p>';
                echo '<p>Tel    : +6017 412 4250</p>';
                echo '<p>Address: No. 44, Jalan Desa Melur 4/1, Taman Bandar Connaught, 56000 Cheras, Kuala Lumpur, Malaysia</p>';
                echo '<hr>';
                echo '<div class="main-content"><div class="panel">
                    <div>';
                
                echo '<p>User <b style="color:#5a9498;font-weight:700;font-size:17px;">'.$name['cust_username'].'</b>, Thank you for your purchase! Your invoice details are below:</p>
                    <div class="simple_area">
                        <div class="small_area">
                            <span class="o_title">TOTAL COST</span>
                            <span class="o_content" style="margin-left:50px;">RM '.$rows['order_total'].'</span>
                        
                            <span class="o_title" style="margin-left:90px;">ORDERED FROM</span>
                            <span class="o_content" style="margin-left:22px;">Kocha Café</span>
                        </div>
                    </div>';

                $ID = $rows['order_ID'];
                $nID = "K_".$ID;
                $cardnum = $row['payment_cardnum'];
                $point_redeem = ($row['payment_subtotal'] - $row['payment_total'])/0.1;
                $pointconvert = round($point_redeem * 0.1);

                echo '<div class="bottom_area">
                        <div class="paymentprt"><p class="text">PAYMENT DETAILS</p>
                            <div class="attribute">
                                <span class="payment_title">INVOICE ID</span>
                                <span class="payment_det">'.$payment_ID.'</span>
                            </div>
                            <div class="attribute">
                                <span class="payment_title">PAYMENT METHOD</span>
                                <span class="payment_det"><i class="fas fa-credit-card"></i> Credit/ Debit Card ('.$cardnum.')</span>
                            </div>
                            <div class="attribute">
                                <span class="payment_title">DELIVERY LOCATION</span>
                                <span class="payment_det">'.$rows['order_address'].'</span>
                            </div>
                            <div class="attribute">
                                <span class="payment_title">ORDER TIME</span>
                                <span class="payment_det">'.$rows['order_date'].'</span>
                            </div>
                        </div>
                        <hr style="border-top:1px;border-color:gray;">
                        <div class="orderprt"><p class="text">ORDER DETAILS</p>
                            <div>
                                <table class="order_table">
                                    <thead class="thead-light">
                                        <tr style="background-color: #e9ecef;">
                                            <th class="desc">Description</th>
                                            <th class="amount">Amount</th>
                                        </tr>
                                    <thead>
                                    <tbody border="transparent">';
                                        
                                        $items[] = "";
                                        $items = explode("},{", $row['payment_items']);
                                        $items = array_filter($items, 'strlen');
                                        if (count($items) != 0){
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
                                        <td class="desc">Subtotal</td><td class="amount">RM '.$row['payment_subtotal'].'</td>
                                    </tr>
                                    <tr class="noborder" style="background-color:#fffcf0;">
                                        <td class="desc">Point Redeem</td><td class="amount">-RM '.number_format($pointconvert, 2).' ('.$point_redeem.')</td>
                                    </tr>
                                    <tr class="noborder" style="background-color: #fffae4;font-weight:bold;">
                                        <td class="desc">TOTAL (INCL.TAX)</td><td class="amount">RM '.$row['payment_total'].'</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div></div></div>';
                
                echo '<div style="text-align:center;margin-top:15px;">~ ENJOY YOUR MEAL AND HAVE A NICE DAY ^_^ ~</div>';
            }
        }

        $htmlContent = ob_get_clean(); // Get the buffered content

        // Generate PDF
        generatePDF($htmlContent, $payment_ID);
    }
}
?>