<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['detsuid'] == 0)) {
    header('location:logout.php');
} else {
    $uid = $_SESSION['detsuid'];
    $query = "SELECT ExpenseDate, SUM(ExpenseCost) as TotalExpense 
              FROM tblexpense 
              WHERE UserId='$uid' 
              GROUP BY ExpenseDate";
    $result = mysqli_query($con, $query);

    $dates = [];
    $expenses = [];

    while ($row = mysqli_fetch_array($result)) {
        $dates[] = $row['ExpenseDate'];
        $expenses[] = $row['TotalExpense'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Expense Tracker || Total Expense Report</title>
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
                    <div class="panel-heading">Total Expense Report</div>
                    <div class="panel-body">
                        <canvas id="totalExpenseChart"></canvas>
                    </div>
                </div>
            </div><!-- /.col-->
            <?php include_once('includes/footer.php'); ?>
        </div><!-- /.row -->
    </div><!--/.main-->

    <script>
        var ctx = document.getElementById('totalExpenseChart').getContext('2d');
        var totalExpenseChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($dates); ?>,
                datasets: [{
                    label: 'Total Expenses',
                    data: <?php echo json_encode($expenses); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
