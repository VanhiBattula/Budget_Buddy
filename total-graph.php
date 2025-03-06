<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['detsuid'] == 0)) {
    header('location:logout.php');
} else {
    $uid = $_SESSION['detsuid'];
    $query = "SELECT YEAR(ExpenseDate) as ExpenseYear, SUM(ExpenseCost) as TotalExpense 
              FROM tblexpense 
              WHERE UserId='$uid' 
              GROUP BY ExpenseYear 
              ORDER BY ExpenseYear ASC";
    $result = mysqli_query($con, $query);

    $years = [];
    $expenses = [];

    while ($row = mysqli_fetch_array($result)) {
        $years[] = $row['ExpenseYear'];
        $expenses[] = $row['TotalExpense'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Expense Tracker || Yearly Expense Report</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include_once('includes/header.php'); ?>
    <?php include_once('includes/sidebar.php'); ?>
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><em class="fa fa-home"></em></a></li>
                <li class="active">Total Expense Report</li>
            </ol>
        </div><!--/.row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Yearly Expense Report</div>
                    <div class="panel-body">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>
            </div><!-- /.col-->
            <?php include_once('includes/footer.php'); ?>
        </div><!-- /.row -->
    </div><!--/.main-->

    <script>
        var ctx = document.getElementById('expenseChart').getContext('2d');
        var expenseChart = new Chart(ctx, {
            type: 'line', // You can change this to 'bar', 'pie', etc.
            data: {
                labels: <?php echo json_encode($years); ?>,
                datasets: [{
                    label: 'Total Expenses',
                    data: <?php echo json_encode($expenses); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: 'rgb(54, 162, 235)'
                        }
                    }
                },
                elements: {
                    line: {
                        tension: 0.1 // smoothes the line
                    }
                }
            }
        });
    </script>

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>