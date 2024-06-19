<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin Panel</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="icon" href="../images/logo/logo_icon_2.png">
    <script src="../script.js"></script>
    <script src="../gototop.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php
    include '../connect.php';
    include '../gototopbtn.php';
    include 'navbar.php';
    ?>
    <style>
        .card {
            --bs-card-spacer-y: 1rem;
            --bs-card-spacer-x: 1rem;
            --bs-card-title-spacer-y: 0.5rem;
            --bs-card-border-width: 0;
            --bs-card-border-color: rgba(0, 0, 0, 0.125);
            --bs-card-border-radius: 0.75rem;
            --bs-card-box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --bs-card-inner-border-radius: 0.75rem;
            --bs-card-cap-padding-y: 0.5rem;
            --bs-card-cap-padding-x: 1rem;
            --bs-card-cap-bg: #fff;
            --bs-card-cap-color: ;
            --bs-card-height: ;
            --bs-card-color: ;
            --bs-card-bg: #fff;
            --bs-card-img-overlay-padding: 1rem;
            --bs-card-group-margin: 0.75rem;
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            height: var(--bs-card-height);
            word-wrap: break-word;
            background-color: var(--bs-card-bg);
            background-clip: border-box;
            border: var(--bs-card-border-width) solid var(--bs-card-border-color);
            border-radius: var(--bs-card-border-radius);
            position: relative;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2) !important;
            margin-bottom: 0px !important;
            margin: 0;
            width: 100%;
            cursor: unset;
        }
        .card-header div{
            z-index: 2;
            position: relative;
        }
        .stats_icons{
            position: absolute;
            right: 20px;
            top: 30%;
            transform: translateY(-30%);
            font-size: 70px;
            z-index: 1;
        }

        table tr .t_no {
            display: none;
        }

        table tr .t_id {
            width: 15%;
            padding-left: 15px !important;
            border-top-left-radius: 7px;
            border-bottom-left-radius: 7px;
        }

        table tbody tr .t_id {
            font-size: 16px;
        }

        table tr .t_date {
            width: 20%;
        }

        table tbody tr .t_date {
            font-size: 16px;
        }

        table tr .t_item {
            width: 35%;
            font-size: 16px;
        }

        table tr .t_item ul {
            list-style: none;
            padding: 0;
            margin: 5px 0;
        }

        table tr .t_item ul li span {
            font-weight: 400;
            color: #8a8a8a;
            padding-left: 22px;
        }

        table tr .t_price {
            width: 15%;
        }

        table tr .t_status {
            width: 15%;
            text-align: center;
        }

        .status-queue {
            color: orange;
            display: flex;
        }

        .status-prepare {
            color: blue;
            display: flex;
        }

        .status-deliver {
            color: green;
            display: flex;
        }

        .status-receive {
            color: purple;
            display: flex;
        }

        .status-icon {
            margin-right: 5px;
            font-size: 14px;
        }

        .status-text {
            font-size: 14px;
        }

        .item-details {
            margin-bottom: 8px;
        }

        .item-remark,
        .item-sauce {
            display: block;
            margin-left: 10px;
            color: #888;
            font-size: 12px;
        }

        .table-responsive::-webkit-scrollbar {
            width: 8px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background-color: #fff;
            z-index: 1;
            box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
        }

        .table-container {
            width: 100%;
            margin: 20px auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        thead {
            background-color: #f9f9f9;
        }

        thead th {
            padding: 15px;
            text-align: left;
            font-weight: bold;
            color: #333;
        }

        tbody tr {
            border-bottom: 1px solid #eee;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        tbody td {
            padding: 15px;
            color: #555;
        }
        h4{
            font-size: 35px;
            font-weight: 900;
        }
        .text-capitalize{
            font-size: 20px;
            font-weight: 900;
        }
        .top_stats{
            gap: 2%;
        }
        .chartContainer{
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            padding: 10px 30px 20px 30px;
            flex: 0 0 74%;
        }
        .chartContainer i{
            margin-right: 8px;
            top: 1px;
            position: relative;
        }
        .chartContainer{
            font-weight: 900;
        }
        .card_container{
            padding: 0;
            margin: 0;
            flex: 0 0 24%;
            gap: 3.5%;
        }

        canvas {
            height: 340px !important;
            width: 85% !important;
        }

       #yesterdayTodayOrdersChart, #weeklyOrdersChart{
            height: 180px !important;
            width: 75% !important;
        }

        .card_holder{
           display: flex;
           justify-content: center;
        }
        
        .fa-repeat-alt{
            position: absolute;
            right: 10px;
            transition: 0.15s;
            cursor: pointer;
        }

        .fa-repeat-alt:hover{
            transform: scale(1.15);
            color: #e9e9e9;
        }

        .toggle2:hover{
            color: #7cbec2;
        }

        .secondarycharts{
            gap:2%;
        }

        .secondarycharts .chartContainer{
            max-width: 49%;
            flex: 0 0 49%;
        }

        .button_1 {
            padding: 4px 10px;
            width: fit-content;
            background-color: #5a9498;
            color: white;
            border-radius: 8px;
            font-weight: 800;
            font-size: 17px;
            transition: 0.15s;
            text-decoration: none;
            align-items: center;
            display: flex;
        }
        .button_1:hover{
            color: white;
        }
        select{
            background-color: white;
            border: white 1px solid;
            border-radius: 6px;
            padding: 2px 5px;
            font-size: 18px;
            box-shadow: 0px 1px 15px rgba(0, 0, 0, 0.1);
        }
        .page_title{
            white-space: nowrap;
        }
        @media screen and (max-width: 1200px) {
           .top_stats{
                flex-direction: column-reverse;
           }
           .card_container{
                flex-direction: row;
                gap: 2%;
                margin-bottom: 15px;
            }
            .card_holder{
                flex: 0 0 32%;
                max-width: 32%;
                display: block;
                justify-content: unset;
            }
            canvas {
                height: 100% !important;
                width: 100% !important;
            }
        }
        @media screen and (max-width: 765px) {
            .text-capitalize{
                font-size:  16px;
            }
            h4{
                font-size: 25px;
            }
            .secondarycharts{
                gap:0%;
            }

            .secondarycharts .chartContainer{
                max-width: 100%;
                flex: 0 0 100%;
                margin-bottom: 15px;
            }

            #yesterdayTodayOrdersChart, #weeklyOrdersChart{
                height: 210px !important;
                width: 75% !important;
            }

            .table .t_date {
                display: none;
            }

            .table .t_id {
                display: none;
            }
        }
        @media screen and (max-width: 700px) {
            .text-capitalize{
                font-size:  20px;
            }
            h4{
                font-size: 40px;
            }
            .card_holder {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 15px;
            }
            .card_container{
                gap: 0%;
                margin-bottom: 0px;
            }
            .chartContainer{
                padding: 5px 15px 10px 15px;
            }
        }
        @media screen and (max-width: 575px) {
            #yesterdayTodayOrdersChart, #weeklyOrdersChart{
                height: 100% !important;
                width: 75% !important;
            }
        }
        @media screen and (max-width: 480px) {
            .table .t_price {
                display: none;
            }
        }
    </style>
</head>

<body>
<?php
    // Fetch data from database
    $customerCount = 0;
    $orderCount = 0;
    $totalIncome = 0;
    $todayIncome = 0;
    $weeklyIncome = 0;
    $monthlyIncomeSum = 0;

    $recentOrdersQuery = "SELECT * FROM customer_orders WHERE trash = 0 ORDER BY order_date DESC LIMIT 5";
    $recentOrdersResult = $conn->query($recentOrdersQuery);

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

    $incomeQuery = "SELECT order_date, order_contents FROM customer_orders";
    $incomeResult = $conn->query($incomeQuery);
    $monthlyIncome = array_fill(0, 12, 0); // Initialize an array with 12 zeros for monthly income
    
    $todayDate = date('Y-m-d');
    $startOfWeek = date('Y-m-d', strtotime('monday this week'));
    $startOfMonth = date('Y-m-01');
    
    if ($incomeResult) {
        while ($row = $incomeResult->fetch_assoc()) {
            $details = explode(",", $row['order_contents']);
            if (isset($details[4])) {
                $item_sumprice = trim($details[4], "()");
                $totalIncome += floatval($item_sumprice);
                $month = intval(date('m', strtotime($row['order_date'])));
                $monthlyIncome[$month - 1] += floatval($item_sumprice);

                $orderDate = date('Y-m-d', strtotime($row['order_date']));
                if ($orderDate == $todayDate) {
                    $todayIncome += floatval($item_sumprice);
                }
                if ($orderDate >= $startOfWeek && $orderDate <= $todayDate) {
                    $weeklyIncome += floatval($item_sumprice);
                }
                if ($orderDate >= $startOfMonth && $orderDate <= $todayDate) {
                    $monthlyIncomeSum += floatval($item_sumprice);
                }
            }
        }
    } else {
        echo "Error: " . $conn->error;
    }

    // Fetch orders count per month
    $monthlyOrders = array_fill(0, 12, 0); // Initialize an array with 12 zeros
    
    $orderDateQuery = "SELECT MONTH(order_date) as month, COUNT(*) as orderCount FROM customer_orders GROUP BY month";
    $orderDateResult = $conn->query($orderDateQuery);
    if ($orderDateResult) {
        while ($row = $orderDateResult->fetch_assoc()) {
            $month = intval($row['month']);
            $monthlyOrders[$month - 1] = intval($row['orderCount']);
        }
    }

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

    // Fetch weekly orders count
    $weeklyOrders = array_fill(0, 7, 0); // Initialize an array with 7 zeros for weekly orders
    
    $weeklyOrderQuery = "SELECT WEEKDAY(order_date) as weekday, COUNT(*) as orderCount FROM customer_orders WHERE order_date >= '$startOfWeek' AND order_date <= '$todayDate' GROUP BY weekday";
    $weeklyOrderResult = $conn->query($weeklyOrderQuery);
    if ($weeklyOrderResult) {
        while ($row = $weeklyOrderResult->fetch_assoc()) {
            $weekday = intval($row['weekday']);
            $weeklyOrders[$weekday] = intval($row['orderCount']);
        }
    }
?>

    <div class="container-fluid">
        <div class="col-12 m-auto">
            <div class="admin_page">
                <div class="breadcrumbs">
                    <a>Admin</a> > <a>Home</a> > <a class="active">Dashboard</a>
                </div>
                <div class="d-flex">
                    <div class="page_title">Site Analytics</div>
                </div>
                <div class="d-flex top_stats">
                    <div class="col-12 chartContainer">
                        <div class="menu">
                            <div class="filter_header">
                                <div class="d-flex flex-row align-items-baseline justify-content-between">
                                    <div>
                                        <i class="fas fa-chart-line"></i><span id="chartTitle">Monthly Revenue Chart</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-repeat-alt toggle2" style="cursor: pointer;" onclick="toggleCharts()"></i>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-1">
                        </div>
                        <div class="d-flex justify-content-center">
                            <canvas id="incomeChart"></canvas>
                            <canvas id="orderChart" style="display: none;"></canvas>
                        </div>
                    </div>
                    <div class="row card_container col-12">
                        <div class="col-xl-12 p-0 card_holder">
                            <div class="card mb-4" style="background-color: #4b939a; color: white;">
                                <div class="card-header py-2 px-3 bg-transparent">
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Customers Registered</p>
                                        <h4 class="mb-0"><?php echo $customerCount; ?></h4>
                                    </div>
                                </div>
                                <hr class="horizontal my-0 dark">
                                <div class="card-footer py-1 px-3">
                                    <p class="mb-0">All time</p>
                                </div>
                                <i class="fas fa-users stats_icons" style="color: #2e6c71;"></i>
                            </div>
                        </div>

                        <div class="col-xl-12 p-0 card_holder">
                            <div class="card mb-4" style="background-color: #E2857B; color: white;">
                                <div class="card-header py-2 px-3 bg-transparent">
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize" id="orderLabel">Total Orders</p>
                                        <h4 id="orderCountToday" class="mb-0"><?php echo $todayOrders; ?></h4>
                                        <h4 id="orderCountWeek" class="mb-0" style="display: none;"><?php echo array_sum($weeklyOrders); ?></h4>
                                        <h4 id="orderCountMonth" class="mb-0" style="display: none;"><?php echo array_sum($monthlyOrders); ?></h4>
                                        <h4 id="orderCountAllTime" class="mb-0" style="display: none;"><?php echo $orderCount; ?></h4>
                                    </div>
                                </div>
                                <hr class="horizontal my-0 dark">
                                <div class="card-footer py-1 px-3">
                                    <p class="mb-0 d-flex align-items-center"><span id="currentLabel">Today's Orders</span> <i class="fas fa-repeat-alt" onclick="cycleCounts()"></i></p>
                                </div>
                                <i class="fas fa-clipboard-list-check stats_icons" style="color: #bb6157;"></i>
                            </div>
                        </div>

                        <div class="col-xl-12 p-0 card_holder">
                            <div class="card mb-4" style="background-color: #51aa7b; color: white;">
                                <div class="card-header py-2 px-3 bg-transparent">
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize" id="incomeLabel">Total Revenue</p>
                                        <h4 id="incomeToday" class="mb-0"><?php echo 'RM' . number_format($todayIncome, 2); ?></h4>
                                        <h4 id="incomeWeek" class="mb-0" style="display: none;"><?php echo 'RM' . number_format($weeklyIncome, 2); ?></h4>
                                        <h4 id="incomeMonth" class="mb-0" style="display: none;"><?php echo 'RM' . number_format($monthlyIncomeSum, 2); ?></h4>
                                        <h4 id="incomeAllTime" class="mb-0" style="display: none;"><?php echo 'RM' . number_format($totalIncome, 2); ?></h4>
                                    </div>
                                </div>
                                <hr class="horizontal my-0 dark">
                                <div class="card-footer py-1 px-3">
                                    <p class="mb-0 d-flex align-items-center"><span id="currentIncomeLabel">Today's Revenue</span> <i class="fas fa-repeat-alt" onclick="cycleIncome()"></i></p>
                                </div>
                                <i class="fas fa-sack-dollar stats_icons" style="color: #3a805b;"></i>
                            </div>
                        </div>

                        <script>
                            let countIndex = 0;
                            const counts = [
                                {element: document.getElementById('orderCountToday'), label: "Today's Orders"},
                                {element: document.getElementById('orderCountWeek'), label: "This Week's Orders"},
                                {element: document.getElementById('orderCountMonth'), label: "This Month's Orders"},
                                {element: document.getElementById('orderCountAllTime'), label: "All-Time Orders"}
                            ];
                            const currentLabel = document.getElementById('currentLabel');

                            function cycleCounts() {
                                counts[countIndex].element.style.display = 'none';
                                countIndex = (countIndex + 1) % counts.length;
                                counts[countIndex].element.style.display = 'block';
                                currentLabel.textContent = counts[countIndex].label;
                            }

                            document.addEventListener('DOMContentLoaded', (event) => {
                                // Chart.js configuration and data initialization

                                // Initialize both charts
                                const incomeChart = new Chart(
                                    document.getElementById('incomeChart').getContext('2d'),
                                    initialIncomeChartConfig
                                );

                                const orderChart = new Chart(
                                    document.getElementById('orderChart').getContext('2d'),
                                    initialOrderChartConfig
                                );
                            });

                            let currentChart = 'income';

                            function toggleCharts() {
                                const incomeChartElement = document.getElementById('incomeChart');
                                const orderChartElement = document.getElementById('orderChart');
                                const chartTitleElement = document.getElementById('chartTitle');

                                if (currentChart === 'income') {
                                    incomeChartElement.style.display = 'none';
                                    orderChartElement.style.display = 'block';
                                    chartTitleElement.textContent = 'Monthly Orders Chart';
                                    currentChart = 'sales';
                                } else {
                                    orderChartElement.style.display = 'none';
                                    incomeChartElement.style.display = 'block';
                                    chartTitleElement.textContent = 'Monthly Income Chart';
                                    currentChart = 'income';
                                }
                            }

                            let incomeIndex = 0;
                            const incomes = [
                                {element: document.getElementById('incomeToday'), label: "Today's Income"},
                                {element: document.getElementById('incomeWeek'), label: "This Week's Income"},
                                {element: document.getElementById('incomeMonth'), label: "This Month's Income"},
                                {element: document.getElementById('incomeAllTime'), label: "All-Time Income"}
                            ];
                            const currentIncomeLabel = document.getElementById('currentIncomeLabel');

                            function cycleIncome() {
                                incomes[incomeIndex].element.style.display = 'none';
                                incomeIndex = (incomeIndex + 1) % incomes.length;
                                incomes[incomeIndex].element.style.display = 'block';
                                currentIncomeLabel.textContent = incomes[incomeIndex].label;
                            }
                        </script>
                    </div>
                </div>

                <div class="row mt-3 mx-0 secondarycharts">
                    <div class="col-6 chartContainer">
                        <div class="filter_header">
                            <div class="d-flex flex-row align-items-baseline justify-content-between">
                                <div>
                                    <i class="fas fa-chart-bar"></i><span id="chartTitle">Yesterday/Today Order Comparison</span>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-1">
                        <div class="d-flex justify-content-center">
                            <canvas id="yesterdayTodayOrdersChart"></canvas>
                        </div>
                    </div>
                    <div class="col-6 chartContainer">
                        <div class="filter_header">
                            <div class="d-flex flex-row align-items-baseline justify-content-between">
                                <div>
                                    <i class="fas fa-chart-bar"></i><span id="chartTitle">Weekly Orders Chart</span>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-1">
                        <div class="d-flex justify-content-center">
                            <canvas id="weeklyOrdersChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="page_title mt-4">Recent Orders</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" style="box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);">
                            <table class="table" id="recentOrderTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="t_id">Order ID</th>
                                        <th class="t_name">User</th>
                                        <th class="t_date">Order Date</th>
                                        <th class="t_item">Item</th>
                                        <th class="t_price">Total Price</th>
                                        <th class="t_status">Status</th>
                                    </tr>
                                </thead>
                                <tbody border="transparent">
                                    <?php
                                    if ($recentOrdersResult && mysqli_num_rows($recentOrdersResult) > 0) {
                                        while ($row = $recentOrdersResult->fetch_assoc()) {
                                            $order_id = $row['order_ID'];
                                            $date = date('Y-m-d H:i:s', strtotime($row['order_date']));
                                            $total_sumprice = 0;
                                            $order_contents = $row['order_contents'];
                                            $items = explode("},{", $order_contents);
                                            $items = array_filter($items, 'strlen');

                                            echo "<tr>";
                                            echo "<td class='t_id'>" . $order_id . "</td>";
                                            $cust_query = "SELECT cust_username FROM customer WHERE trash = 0 AND cust_ID = " . $row['cust_ID'];
                                            $query_result = $conn->query($cust_query);
                                            $query_row = $query_result->fetch_assoc();
                                            if ($query_row && !empty($query_row['cust_username'])) {
                                                $username = $query_row['cust_username'];
                                            } else {
                                                $username = "User is disabled.";
                                            }
                                            echo "<td class='t_name'>" . $username . "</td>";
                                            echo "<td class='t_date'>" . $date . "</td>";
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
                                            echo "<td class='t_price'>RM" . number_format($total_sumprice, 2) . "</td>";

                                            $tracking_stage = intval($row['tracking_stage']);
                                            $status = '';
                                            switch ($tracking_stage) {
                                                case 0:
                                                    $status = "<span class='status-queue'><i class='fas fa-boxes status-icon'></i><span class='status-text'>Queueing</span></span>";
                                                    break;
                                                case 1:
                                                    $status = "<span class='status-prepare'><i class='fas fa-box-full status-icon'></i><span class='status-text'>Preparing</span></span>";
                                                    break;
                                                case 2:
                                                    $status = "<span class='status-pickup'><i class='fas fa-truck-pickup status-icon'></i><span class='status-text'>Ready for Pickup</span></span>";
                                                    break;
                                                case 3:
                                                    $status = "<span class='status-deliver'><i class='fas fa-truck status-icon'></i><span class='status-text'>Delivering</span></span>";
                                                    break;
                                                case 4:
                                                    $status = "<span class='status-complete'><i class='fas fa-truck-ramp-box status-icon'></i><span class='status-text'>Delivered</span></span>";
                                                    break;
                                                default:
                                                    $status = "<span class='status-unknown'>Unknown</span>";
                                            }
                                            echo "<td>" . $status . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No recent orders found.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end mt-3 mb-4">
                        <select id="reportType" class="form-select">
                            <option value="today">Today</option>
                            <option value="thisWeek">This Week</option>
                            <option value="thisMonth">This Month</option>
                            <option value="allTime">All Time</option>
                        </select>
                        <button class="btn button_1 ml-2" onclick="generatePDF()">Generate Sales Report</button>
                    </div>
                </div>
                <script>
                    function generatePDF() {
                        var reportType = document.getElementById('reportType').value;
                        // Redirect to Kocha_Cafe_Report.php with the selected report type
                        window.location.href = 'Kocha_Cafe_Report.php?reportType=' + reportType;
                    }
                </script>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', (event) => {
                        const monthlyOrders = <?php echo json_encode($monthlyOrders); ?>;
                        const monthlyIncome = <?php echo json_encode($monthlyIncome); ?>;
                        const weeklyOrders = <?php echo json_encode($weeklyOrders); ?>;
                        const yesterdayOrders = <?php echo $yesterdayOrders; ?>;
                        const todayOrders = <?php echo $todayOrders; ?>;

                        const monthLabels = [
                            'January', 'February', 'March', 'April', 'May', 'June',
                            'July', 'August', 'September', 'October', 'November', 'December'
                        ];

                        const dayLabels = [
                            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
                        ];

                        // Order chart
                        const orderData = {
                            labels: monthLabels,
                            datasets: [{
                                label: 'Orders per Month',
                                data: monthlyOrders,
                                backgroundColor: 'rgb(54, 162, 235)',
                                borderColor: 'rgb(54, 162, 235)',
                                borderWidth: 1,
                                fill: false
                            }]
                        };

                        const orderConfig = {
                            type: 'line',
                            data: orderData,
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        };

                        const orderChart = new Chart(
                            document.getElementById('orderChart'),
                            orderConfig
                        );

                        // Income chart
                        const incomeData = {
                            labels: monthLabels,
                            datasets: [{
                                label: 'Revenue per Month (RM)',
                                data: monthlyIncome,
                                backgroundColor: 'rgb(75, 192, 192)',
                                borderColor: 'rgb(75, 192, 192)',
                                borderWidth: 1,
                                fill: false
                            }]
                        };

                        const incomeConfig = {
                            type: 'line',
                            data: incomeData,
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        };

                        const incomeChart = new Chart(
                            document.getElementById('incomeChart'),
                            incomeConfig
                        );

                        // Yesterday vs Today Orders Data
                        const yesterdayTodayOrdersData = {
                            labels: ['Yesterday', 'Today'],
                            datasets: [{
                                label: 'Number of Orders',
                                data: [yesterdayOrders, todayOrders],
                                backgroundColor: [
                                    'rgb(255, 99, 132)',
                                    'rgb(54, 162, 235)'
                                ],
                                borderColor: [
                                    'rgb(255, 99, 132)',
                                    'rgb(54, 162, 235)'
                                ],
                                borderWidth: 1
                            }]
                        };

                        const yesterdayTodayOrdersConfig = {
                            type: 'bar',
                            data: yesterdayTodayOrdersData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                    },
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        };

                        const yesterdayTodayOrdersChart = new Chart(
                            document.getElementById('yesterdayTodayOrdersChart'),
                            yesterdayTodayOrdersConfig
                        );

                        // Weekly orders chart
                        const weeklyOrdersData = {
                            labels: dayLabels,
                            datasets: [{
                                label: 'Orders per Day',
                                data: weeklyOrders,
                                backgroundColor: 'rgb(153, 102, 255)',
                                borderColor: 'rgb(153, 102, 255)',
                                borderWidth: 1
                            }]
                        };

                        const weeklyOrdersConfig = {
                            type: 'bar',
                            data: weeklyOrdersData,
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        };

                        const weeklyOrdersChart = new Chart(
                            document.getElementById('weeklyOrdersChart'),
                            weeklyOrdersConfig
                        );
                    });

                    function printReport() {
                        window.print();
                    }
                </script>
            </div>
        </div>
    </div>
</body>


</html>


</html>