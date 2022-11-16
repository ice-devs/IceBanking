<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$client_id = $_SESSION['client_id'];

/*
    get all dashboard analytics 
    and numeric values from distinct 
    tables
    */

//return total number of ibank clients
$result = "SELECT count(*) FROM iB_clients";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iBClients);
$stmt->fetch();
$stmt->close();

//return total number of iBank Staffs
$result = "SELECT count(*) FROM iB_staff";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iBStaffs);
$stmt->fetch();
$stmt->close();

//return total number of iBank Account Types
$result = "SELECT count(*) FROM iB_Acc_types";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iB_AccsType);
$stmt->fetch();
$stmt->close();

//return total number of iBank Accounts
$result = "SELECT count(*) FROM iB_bankAccounts";
$stmt = $mysqli->prepare($result);
$stmt->execute();
$stmt->bind_result($iB_Accs);
$stmt->fetch();
$stmt->close();

//return total number of iBank Deposits
$client_id = $_SESSION['client_id'];
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE  client_id = ? AND tr_type = 'Deposit' ";
$stmt = $mysqli->prepare($result);
$stmt->bind_param('i', $client_id);
$stmt->execute();
$stmt->bind_result($iB_deposits);
$stmt->fetch();
$stmt->close();

//return total number of iBank Withdrawals
$client_id = $_SESSION['client_id'];
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE  client_id = ? AND tr_type = 'Withdrawal' ";
$stmt = $mysqli->prepare($result);
$stmt->bind_param('i', $client_id);
$stmt->execute();
$stmt->bind_result($iB_withdrawal);
$stmt->fetch();
$stmt->close();



//return total number of iBank Transfers
$client_id = $_SESSION['client_id'];
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE  client_id = ? AND tr_type = 'Transfer' ";
$stmt = $mysqli->prepare($result);
$stmt->bind_param('i', $client_id);
$stmt->execute();
$stmt->bind_result($iB_Transfers);
$stmt->fetch();
$stmt->close();

//return total number of  iBank initial cash->balances
$client_id = $_SESSION['client_id'];
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions  WHERE client_id =?";
$stmt = $mysqli->prepare($result);
$stmt->bind_param('i', $client_id);
$stmt->execute();
$stmt->bind_result($acc_amt);
$stmt->fetch();
$stmt->close();
//Get the remaining money in the accounts
$TotalBalInAccount = ($iB_deposits)  - (($iB_withdrawal) + ($iB_Transfers));


//ibank money in the wallet
$client_id = $_SESSION['client_id'];
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions  WHERE client_id = ?";
$stmt = $mysqli->prepare($result);
$stmt->bind_param('i', $client_id);
$stmt->execute();
$stmt->bind_result($new_amt);
$stmt->fetch();
$stmt->close();
//Withdrawal Computations

?>

<!DOCTYPE html>
<html lang="en">
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
    <div class="content-wrapper" id="mainsy" style="margin-left: 0%; ">
      
      <button id="openNav"  style="position:fixed; top:0; overflow: hidden; z-index:1; margin:5px; border-radius: 50px 50px;" class="w3-button w3-teal w3-xlarge" onclick="w3_open()">&#9776;</button>
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Client Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content" ">
        <div class="container-fluid">
          
          <div class="row">
            <!--iBank Deposits -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-upload"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Deposits</span>
                  <span class="info-box-number">
                    $ <?php echo $iB_deposits; ?>
                  </span>
                </div>
              </div>
            </div>
            <!----./ iBank Deposits-->

            <!--iBank Withdrwals-->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-download"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Withdrawals</span>
                  <span class="info-box-number">$ <?php echo $iB_withdrawal; ?> </span>
                </div>
              </div>
            </div>
            <!-- Withdrawals-->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <!--Transfers-->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-random"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Transfers</span>
                  <span class="info-box-number">$ <?php echo $iB_Transfers; ?></span>
                </div>
              </div>
            </div>
            <!-- /.Transfers-->

            <!--Balances-->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-money-bill-alt"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Book Balance</span>
                  <span class="info-box-number">$<?php echo $TotalBalInAccount; ?></span>
                </div>
              </div>
            </div>
            <!-- ./Balances-->
          </div>


          <!-- /.row -->

          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <div class="col-md-12">
              <!-- TABLE: Transactions -->
              <div class="card">
                <div class="card-header border-transparent">
                  <h3 class="card-title">Latest Transactions</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table m-0">
                      <thead>
                        <tr>
                          <th>Transaction Checksum</th>
                          <th>Acc Number</th>
                          <th>Transaction Type</th>
                          <th>Transaction Amount</th>
                          <th>Acc Owner</th>
                          <th>Timestamp</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        //Get latest transactions ;
                        $client_id = $_SESSION['client_id'];
                        $ret = "SELECT * FROM iB_Transactions WHERE  client_id = ?  ORDER BY iB_Transactions. created_at DESC ";
                        $stmt = $mysqli->prepare($ret);
                        $stmt->bind_param('i', $client_id);
                        $stmt->execute(); //ok
                        $res = $stmt->get_result();
                        $cnt = 1;
                        while ($row = $res->fetch_object()) {
                          /* Trim Transaction Timestamp to 
                            *  User Uderstandable Formart  DD-MM-YYYY :
                            */
                          $transTstamp = $row->created_at;
                          //Perfom some lil magic here
                          if ($row->tr_type == 'Deposit') {
                            $alertClass = "<span class='badge badge-success'>$row->tr_type</span>";
                          } elseif ($row->tr_type == 'Withdrawal') {
                            $alertClass = "<span class='badge badge-danger'>$row->tr_type</span>";
                          } else {
                            $alertClass = "<span class='badge badge-warning'>$row->tr_type</span>";
                          }
                        ?>
                          <tr>
                            <td><?php echo $row->tr_code; ?></a></td>
                            <td><?php echo $row->account_number; ?></td>
                            <td><?php echo $alertClass; ?></td>
                            <td>USD <?php echo $row->transaction_amt; ?></td>
                            <td><?php echo $row->client_name; ?></td>
                            <td><?php echo date("d-M-Y h:m:s ", strtotime($transTstamp)); ?></td>
                          </tr>

                        <?php } ?>

                      </tbody>
                    </table>
                  </div>
                  <!-- /.table-responsive -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                  <a href="pages_transactions_engine.php" class="btn btn-sm btn-info float-left">View All</a>
                </div>
                <!-- /.card-footer -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!--/. container-fluid -->
      </section>
      <!-- /.content -->
    <?php include("index.php"); ?>
    <?php include("dist/_partials/footer.php"); ?>
    </div>
    <!-- /.content-wrapper -->


    <!-- /.control-sidebar -->

    <!-- Main Footer -->

  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <script src="dist/js/script.js"></script>
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.js"></script>

  <!-- OPTIONAL SCRIPTS -->
  <script src="dist/js/demo.js"></script>

  <!-- PAGE PLUGINS -->
  <!-- jQuery Mapael -->
  <script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
  <script src="plugins/raphael/raphael.min.js"></script>
  <script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
  <script src="plugins/jquery-mapael/maps/usa_states.min.js"></script>
  <!-- ChartJS -->
  <script src="plugins/chart.js/Chart.min.js"></script>

  <!-- PAGE SCRIPTS -->
  <script src="dist/js/pages/dashboard2.js"></script>

  <!--Load Canvas JS -->
  <script src="plugins/canvasjs.min.js"></script>
  <!--Load Few Charts-->


</body>

</html>