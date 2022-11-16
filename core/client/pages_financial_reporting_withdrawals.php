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
    <div class="content-wrapper" id="mainsy" style="margin-left: 0%; ">
      
      <button id="openNav"  style="position:fixed; top:0; overflow: hidden; z-index:1; margin:5px; border-radius: 50px 50px;" class="w3-button w3-teal w3-xlarge" onclick="w3_open()">&#9776;</button>
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>iBanking Advanced Reporting : Withdrawal</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="pages_financial_reporting_withdrawals.php">Advanced Reporting</a></li>
                <li class="breadcrumb-item active">Withdrawal</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>All Transactions Under Withdrawal Category</h4>
              </div>
              <div class="card-body">
                <table id="export" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Transaction ID</th>
                      <th>Acc Number</th>
                      <th>Transaction Amount</th>
                      <th>Acc Owner</th>
                      <th>Timestamp</th>

                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    //Get latest deposits transactions 
                    $client_id = $_SESSION['client_id'];
                    $ret = "SELECT * FROM  iB_Transactions  WHERE tr_type = 'Withdrawal' AND client_id = ? ";
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
                        <td><?php echo $cnt; ?></td>
                        <td><?php echo $row->tr_code; ?></a></td>
                        <td><?php echo $row->account_number; ?></td>
                        <td>Ksh <?php echo $row->transaction_amt; ?></td>
                        <td><?php echo $row->client_name; ?></td>
                        <td><?php echo date("d-M-Y h:m:s ", strtotime($transTstamp)); ?></td>
                      </tr>
                    <?php $cnt = $cnt + 1;
                    } ?>
                    </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </section>
    <?php include("dist/_partials/footer.php"); ?>
    </div>
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

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
  <!-- Data Tables V2.01 -->
  <!-- NOTE TO Use Copy CSV Excel PDF Print Options You Must Include These Files  -->
  <script src="plugins/datatable/button-ext/dataTables.buttons.min.js"></script>
  <script src="plugins/datatable/button-ext/jszip.min.js"></script>
  <script src="plugins/datatable/button-ext/buttons.html5.min.js"></script>
  <script src="plugins/datatable/button-ext/buttons.print.min.js"></script>
  <script>
    $('#export').DataTable({
      dom: '<"row"<"col-md-12"<"row"<"col-md-6"B><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
      buttons: {
        buttons: [{
            extend: 'copy',
            className: 'btn'
          },
          {
            extend: 'csv',
            className: 'btn'
          },
          {
            extend: 'excel',
            className: 'btn'
          },
          {
            extend: 'print',
            className: 'btn'
          }
        ]
      },
      "oLanguage": {
        "oPaginate": {
          "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
          "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
        },
        "sInfo": "Showing page _PAGE_ of _PAGES_",
        "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
        "sSearchPlaceholder": "Search...",
        "sLengthMenu": "Results :  _MENU_",
      },
      "stripeClasses": [],
      "lengthMenu": [7, 10, 20, 50],
      "pageLength": 7
    });
  </script>
</body>

</html>