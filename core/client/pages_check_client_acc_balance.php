<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$client_id = $_SESSION['client_id'];

?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<style>
  .bars {
    height: 100%; /* 100% Full-height */
    width: 0px; /* 0 width - change this with JavaScript */
    position: fixed; /* Stay in place */
    z-index: 1; /* Stay on top */
    top: 0; /* Stay at the top */
    left: 0;
    background-color: rgb(30, 9, 31); /* Black*/
    overflow-x: hidden; /* Disable horizontal scroll */
    /* padding-top: 60px; Place content 60px from the top */
    transition: 0.5s; /* 0.5 second transition effect to slide in the sidenav */    
  }

  /* Position and style the close button (top right corner) */
  .closebtn {
    position: absolute;
    top: 0px;
    right: 8%;
    font-size: 36px;
    margin-left: 30px;
  }

  /* On screens that are less than 400px, display the bar vertically, instead of horizontally */
  @media screen and (max-width: 450px) {
  .nav-link {
        font-size: 11px;
    }
}

</style>
<?php include("dist/_partials/nav.php"); ?>
<?php include("dist/_partials/head.php"); ?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include("dist/_partials/sidebar.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <?php
        /*  Im About to do something stupid buh lets do it
         *  get the sumof all deposits(Money In) then get the sum of all
         *  Transfers and Withdrawals (Money Out).
         * Then To Calculate Balance and rate,
         * Take the rate, compute it and then add with the money in account and 
         * Deduce the Money out
         *
         */

        //get the total amount deposited
        $account_id = $_GET['account_id'];
        $result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE  account_id = ? AND  tr_type = 'Deposit' ";
        $stmt = $mysqli->prepare($result);
        $stmt->bind_param('i', $account_id);
        $stmt->execute();
        $stmt->bind_result($deposit);
        $stmt->fetch();
        $stmt->close();

        //get total amount withdrawn
        $account_id = $_GET['account_id'];
        $result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE  account_id = ? AND  tr_type = 'Withdrawal' ";
        $stmt = $mysqli->prepare($result);
        $stmt->bind_param('i', $account_id);
        $stmt->execute();
        $stmt->bind_result($withdrawal);
        $stmt->fetch();
        $stmt->close();

        //get total amount transfered
        $account_id = $_GET['account_id'];
        $result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE  account_id = ? AND  tr_type = 'Transfer' ";
        $stmt = $mysqli->prepare($result);
        $stmt->bind_param('i', $account_id);
        $stmt->execute();
        $stmt->bind_result($Transfer);
        $stmt->fetch();
        $stmt->close();



        $account_id = $_GET['account_id'];
        $ret = "SELECT * FROM  iB_bankAccounts WHERE account_id =? ";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $account_id);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        while ($row = $res->fetch_object()) {
            //compute rate
            // $banking_rate = ($row->acc_rates) / 100;
            //compute Money out
            $money_out = $withdrawal + $Transfer;
            //compute the balance
            $money_in = $deposit;
            //get the rate
            // $rate_amt = $banking_rate * $money_in;
            //compute the intrest + balance 
            // $totalMoney = $rate_amt + $money_in;

        ?>
            <div class="content-wrapper" id="mainsy" style="margin-left: 0%; ">
      
      <button id="openNav"  style="position:fixed; top:0; overflow: hidden; z-index:1; margin:5px; border-radius: 50px 50px;" class="w3-button w3-teal w3-xlarge" onclick="w3_open()">&#9776;</button>
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1><?php echo $row->client_name; ?> LeawoodCU Account Balance</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="pages_balance_enquiries.php">Finances</a></li>
                                    <li class="breadcrumb-item"><a href="pages_balance_enquiries.php">Balances</a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->client_name; ?> Accs</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <!-- Main content -->
                                <div id="balanceSheet" class="invoice p-3 mb-3">
                                    <!-- title row -->
                                    <div class="row">
                                        <div class="col-12">
                                            <h4>
                                                <i class="fas fa-bank"></i> Leawood Credit Union Balance Enquiry
                                                <small class="float-right" style="font-size:12px;">Date: <?php echo date('d/m/Y'); ?></small>
                                            </h4>
                                        </div><br><br>
                                        <!-- /.col -->
                                    </div>
                                    <!-- info row -->
                                    <div class="row invoice-info">
                                        <div class="col-sm-6 invoice-col">
                                        <span style="color:blueviolet;">Account Holder</span>
                                            <address>
                                                <strong><?php echo $row->client_name; ?></strong><br>
                                                <?php echo $row->client_number; ?><br>
                                                <?php echo $row->client_email; ?><br>
                                                Phone: <?php echo $row->client_phone; ?><br>
                                                ID No: <?php echo $row->client_national_id; ?>
                                            </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-6 invoice-col" >
                                        <span style="color:blueviolet;">Account Details</span>
                                            <address>
                                                <strong><?php echo $row->acc_name; ?></strong><br>
                                                Acc No: <?php echo $row->account_number; ?><br>
                                                Acc Type: <?php echo $row->acc_type; ?><br>
                                                <!-- Acc Rates: <?php echo $row->acc_rates; ?> % -->
                                            </address>
                                        </div>

                                    </div>
                                    <!-- /.row -->

                                    <!-- Table row -->
                                    <div class="row" >
                                        <div class="col-12 table-responsive"">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Deposits</th>
                                                        <th>Withdrawals</th>
                                                        <th>Transfers</th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                        <td>$<?php echo $deposit; ?></td>
                                                        <td>$<?php echo $withdrawal; ?></td>
                                                        <td>$<?php echo $Transfer; ?></td>
                                                        <td>$<?php echo $row->acc_amount; ?></td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.row -->

                                    <div class="row">
                                        <!-- accepted payments column -->
                                        <!-- <div class="col-6">
                                            <p class="lead"></p>

                                            <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">

                                            </p>
                                        </div> -->
                                        <!-- /.col -->
                                        <div class="col-12">
                                            <p class="lead" style="font-size:14px; width:70%; float:right"><span style="font-size:12px; float:right""> <?php echo date('d-M-Y') . " ". date("h:i:sa"); ?></span></p>

                                            <div class="table-responsive" style="font-size:12px; width:70%; float:right">
                                                <table class="table">
                                                    <tr>
                                                        <th style="width:50%">Funds In:</th>
                                                        <td>$<?php echo $deposit; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Funds Out</th>
                                                        <td>$<?php echo $money_out; ?></td>
                                                    </tr>
                                                    <!-- <tr>
                                                        <th>Sub Total:</th>
                                                        <td>$<?php echo $money_in; ?></td>
                                                    </tr> -->
                                                    <!-- <tr>
                                                        <th>Banking Intrest:</th>
                                                        <td>$<?php echo $rate_amt; ?></td>
                                                    </tr> -->
                                                    <tr>
                                                        <th>Total Balance:</th>
                                                        <td>$<?php echo $row->acc_amount; ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.row -->

                                    <!-- this row will not appear when printing -->
                                    <div class="row no-print">
                                        <div class="col-12">

                                            <button type="button" id="print" onclick="printContent('balanceSheet');" class="btn btn-primary float-right" style="margin-right: 5px;">
                                                <i class="fas fa-print"></i> Print
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.invoice -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
        <?php } ?>
        <!-- /.content-wrapper -->
        <?php include("dist/_partials/footer.php"); ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
      <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <script src="dist/js/script.js"></script>
    
    <script src="dist/js/script.js"></script>
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="plugins/datatables/jquery.dataTables.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- page script -->
    <script>
        $(function() {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });
        });
    </script>
    <script>
        //print balance sheet
        function printContent(el) {
            var restorepage = $('body').html();
            var printcontent = $('#' + el).clone();
            $('body').empty().html(printcontent);
            window.print();
            $('body').html(restorepage);
        }
    </script>
</body>

</html>