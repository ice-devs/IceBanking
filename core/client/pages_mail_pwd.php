<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$client_id = $_SESSION['client_id'];
if (isset($_POST['reset_password'])) {
    $email = $_GET['email'];
    $password = sha1(md5($_GET['password']));
    //update password resets table 
    $reset_status = $_GET['reset_status'];
    $id = $_GET['id'];

    //Insert Captured information to a database table
    //update password
    $query = "UPDATE  iB_clients SET password=? WHERE email=? ";
    $update_password = "UPDATE iB_password_resets SET reset_status=? WHERE id =? ";
    $stmt = $mysqli->prepare($query);
    $pwdreset = $mysqli->prepare($update_password);
    //bind paramaters
    $rc = $stmt->bind_param('ss', $password, $email);
    $rc = $pwdreset->bind_param('si', $reset_status, $id);
    $stmt->execute();
    $pwdreset->execute();

    //declare a varible which will be passed to alert function
    if ($stmt && $pwdreset) {
        $success = "Password Reset";
    } else {
        $err = "Please Try Again Or Try Later";
    }
}

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
            <?php
            $email = $_GET['email'];
            $ret = "SELECT * FROM  iB_clients WHERE email = ?  ";
            $stmt = $mysqli->prepare($ret);
            $stmt->bind_param('s', $email);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($row = $res->fetch_object()) {
                //trim timestamp to DD/MM/YYY
                //$created_at = $row->created_at;

            ?>
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Reset iBank User <?php echo $row->name; ?> Password</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="pages_manage_reset_pwd.php">Password Resets</a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->email; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- general form elements -->
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Fill All Fields</h3>
                                    </div>
                                    <!-- form start -->
                                    <form method="post" enctype="multipart/form-data" role="form">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class=" col-md-12 form-group">
                                                    <label for="exampleInputEmail1">Email</label>
                                                    <input type="email" name="email" readonly value="<?php echo $row->email; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" name="reset_password" class="btn btn-primary">Reset Password</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            <?php } ?>
        </div>
        <!-- /.content-wrapper -->
        <?php include("dist/_partials/footer.php"); ?>

        <!-- Control Sidebar -->
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
    <!-- bs-custom-file-input -->
    <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            bsCustomFileInput.init();
        });
    </script>
</body>

</html>