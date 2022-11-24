<?php
session_start();
require_once __DIR__.'/mailer/vendor/autoload.php';

error_reporting(E_STRICT | E_ALL);
date_default_timezone_set('Etc/UTC');
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$client_id = $_SESSION['client_id'];
//register new account
if (isset($_POST['open_account'])) {
    //Client open account
    $acc_name = $_POST['acc_name'];
    $account_number = $_POST['account_number'];
    $acc_type = $_POST['acc_type'];
    $acc_rates = $_POST['acc_rates'];
    $acc_status = $_POST['acc_status'];
    $acc_amount = $_POST['acc_amount'];
    $client_id  = $_SESSION['client_id'];
    $client_national_id = $_POST['client_national_id'];
    $client_name = $_POST['client_name'];
    $client_phone = $_POST['client_phone'];
    $client_number = $_POST['client_number'];
    $client_email  = $_POST['client_email'];
    $client_adr  = $_POST['client_adr'];
    $transaction_amt = 0.00;
    
    $length = 20;
    $tr_code =  substr(str_shuffle('0123456789QWERgfdsazxcvbnTYUIOqwertyuioplkjhmPASDFGHJKLMNBVCXZ'), 1, $length);

    //Insert Captured information into iB_bankAccounts table
    $query = "INSERT INTO iB_bankAccounts (acc_name, account_number, acc_type, acc_rates, acc_status, acc_amount, client_id, client_name, client_national_id, client_phone, client_number, client_email, client_adr) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssssssssss', $acc_name, $account_number, $acc_type, $acc_rates, $acc_status, $acc_amount, $client_id, $client_name, $client_national_id, $client_phone, $client_number, $client_email, $client_adr);
    $stmt->execute();

    //Create first transaction with 0.00
    $trans_query = "INSERT INTO iB_Transactions (tr_code, acc_amount, acc_name, account_number, acc_type,  client_id, client_name, client_national_id, transaction_amt, client_phone) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $trans = $mysqli->prepare($trans_query);
    $rc = $trans->bind_param('ssssssssss', $tr_code, $acc_amount , $acc_name, $account_number, $acc_type, $client_id, $client_name, $client_national_id, $transaction_amt, $client_phone);
    $trans->execute();

    //declare a varible which will be passed to alert function
    if ($stmt && $trans) {
        $success = "Bank Account Opened";
    } else {
        $err = "Please Try Again Or Try Later";
    }


            
    //-------------------MAILER CONFIGURATION ------------------------------
    define('CONTACTFORM_FROM_ADDRESS', 'jlfinancecryptofx@gmail.com');
    define('CONTACTFORM_FROM_NAME', 'LeawoodCU');
    define('CONTACTFORM_TO_ADDRESS', $client_email);
    define('CONTACTFORM_TO_NAME', $client_name);

    define('CONTACTFORM_SMTP_HOSTNAME', 'smtp.gmail.com');
    define('CONTACTFORM_SMTP_USERNAME', 'jlfinancecryptofx@gmail.com'); 
    define('CONTACTFORM_SMTP_PASSWORD', 'nkiyjepzwhionbvv');
    define('CONTACTFORM_SMTP_PORT', 587);
    define('CONTACTFORM_SMTP_ENCRYPTION', 'tls');
    define('CONTACTFORM_PHPMAILER_DEBUG_LEVEL', 0);
    //-------------- MAILER CONFIGURATION END-------------------------------

    $letter_file = 'open.php';
    $subject = $acc_name ." Account Created!" ;
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true); 
    // $description = "Transfer from " .$row->client_name . " to " .$receiving_acc_name." (" . $receiving_acc_holder .") -- --".$tr_code ;

    try {
        //Server settings
        $mail->SMTPDebug = CONTACTFORM_PHPMAILER_DEBUG_LEVEL;
        $mail->isSMTP();
        $mail->Host = CONTACTFORM_SMTP_HOSTNAME;
        $mail->SMTPAuth = true;
        $mail->Username = CONTACTFORM_SMTP_USERNAME;
        $mail->Password = CONTACTFORM_SMTP_PASSWORD;
        $mail->SMTPSecure = CONTACTFORM_SMTP_ENCRYPTION;
        $mail->Port = CONTACTFORM_SMTP_PORT;

        // Recipients
        $mail->setFrom(CONTACTFORM_FROM_ADDRESS, CONTACTFORM_FROM_NAME);
        $mail->addAddress(CONTACTFORM_TO_ADDRESS, CONTACTFORM_TO_NAME);

        // Content
        $mail->Subject = $subject;
        function get_include_contents($filename, $variablesToMakeLocal) {
            extract($variablesToMakeLocal);
            if (is_file($filename)) {
                ob_start();
                include $filename;
                return ob_get_clean();
            }
            return false;
        }
        
        $data = array('acc_name' => $acc_name, 'acc_no'=>$account_number, 'acc_type'=> $acc_type, 'client_name'=> $client_name );
        $mail->msgHTML(get_include_contents($letter_file, $data));

        $mail->send();

    } catch (Exception $e) {
        $mail->ErrorInfo;
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
        <?php
        $client_id = $_SESSION['client_id'];
        $ret = "SELECT * FROM  iB_clients WHERE client_id = ? ";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $client_id);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        while ($row = $res->fetch_object()) {

        ?>
        <div class="content-wrapper" id="mainsy" style="margin-left: 0%; ">
          
          <button id="openNav"  style="position:fixed; top:0; overflow: hidden; z-index:1; margin:5px; border-radius: 50px 50px;" class="w3-button w3-teal w3-xlarge" onclick="w3_open()">&#9776;</button>
          <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Open Account</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="pages_open_acc.php">Accounts</a></li>
                                    <li class="breadcrumb-item"><a href="pages_open_acc.php">Open </a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->name; ?></li>
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
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Client Name</label>
                                                    <input type="text" readonly name="client_name" value="<?php echo $row->name; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputPassword1">Client Bank Id</label>
                                                    <input type="text" readonly name="client_number" value="<?php echo $row->client_number; ?>" class="form-control" id="exampleInputPassword1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Phone Number</label>
                                                    <input type="text" readonly name="client_phone" value="<?php echo $row->phone; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputPassword1">National ID No.</label>
                                                    <input type="text" readonly value="<?php echo $row->national_id; ?>" name="client_national_id" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Email</label>
                                                    <input type="email" readonly name="client_email" value="<?php echo $row->email; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Address</label>
                                                    <input type="text" name="client_adr" readonly value="<?php echo $row->address; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>
                                            <!-- ./End Personal Details -->

                                            <!--Bank Account Details-->
                                            <div class="row">
                                                <div class=" col-md-6 form-group" style="display:none">
                                                    <label for="exampleInputEmail1">Bank Account Type Rates (%)</label>
                                                    <input type="text" name="acc_rates" readonly required class="form-control" id="AccountRates">
                                                </div>

                                                <div class=" col-md-6 form-group" style="display:none">
                                                    <label for="exampleInputEmail1">Bank Account Status</label>
                                                    <input type="text" name="acc_status" value="Active" readonly required class="form-control">
                                                </div>

                                                <div class=" col-md-6 form-group" style="display:none">
                                                    <label for="exampleInputEmail1">Bank Account Amount</label>
                                                    <input type="number" name="acc_amount" value="0.00" readonly required class="form-control">
                                                </div>

                                            </div>
                                            <div class="row">

                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Bank Account Number</label>
                                                    <?php
                                                    //PHP function to generate random account number
                                                    $length = 12;
                                                    $_accnumber =  substr(str_shuffle('0123456789'), 1, $length);
                                                    ?>
                                                    <input type="text"  readonly name="account_number" value="<?php echo $_accnumber; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Bank Account Type</label>
                                                    <select class="form-control" onChange="getiBankAccs(this.value);" name="acc_type">
                                                        <option>Select Any Bank Account types</option>
                                                        <?php
                                                        //fetch all iB_Acc_types
                                                        $ret = "SELECT * FROM  iB_Acc_types ORDER BY RAND() ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        $cnt = 1;
                                                        while ($row = $res->fetch_object()) {

                                                        ?>
                                                            <option value="<?php echo $row->name; ?> "> <?php echo $row->name; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Verification Document(Passport)</label>
                                                    <input type="file" name="verification_id"  required class="form-control" id="verification_id" accept="image/*;capture=camera">
                                                </div>
                                                
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Bank Account Name</label>
                                                    <input type="text" name="acc_name" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" name="open_account" class="btn btn-primary">Open iBaking Account</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
                <?php include("footer.php"); ?>
            </div>
        <?php } ?>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <!-- /.control-sidebar -->
    </div>
      <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <script src="dist/js/script.js"></script>
    <!-- REQUIRED SCRIPTS -->
    <script src="dist/js/script.js"></script>
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