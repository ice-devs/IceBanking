<?php
session_start();
require_once __DIR__.'/mailer/vendor/autoload.php';

error_reporting(E_STRICT | E_ALL);
date_default_timezone_set('Etc/UTC');

include('conf/config.php');
include('conf/checklogin.php');
check_login();
$client_id = $_SESSION['client_id'];

if (isset($_POST['deposit'])) {
    $tr_code = $_POST['tr_code'];
    $account_id = $_GET['account_id'];
    $acc_name = $_POST['acc_name'];
    $account_number = $_GET['account_number'];
    $acc_type = $_POST['acc_type'];
    //$acc_amount  = $_POST['acc_amount'];
    $tr_type  = $_POST['tr_type'];
    $tr_status = $_POST['tr_status'];
    $client_id  = $_GET['client_id'];
    $client_name  = $_POST['client_name'];
    $client_national_id  = $_POST['client_national_id'];
    $transaction_amt = $_POST['transaction_amt']. '.00';
    $client_phone = $_POST['client_phone'];

    //Few fields to hold funds transfers
    $receiving_acc_no = $_POST['receiving_acc_no'];
    $receiving_acc_name = $_POST['receiving_acc_name'];
    $receiving_acc_holder = $_POST['receiving_acc_holder'];

    //Notication
    $notification_details = "$client_name Has Transfered Ksh $transaction_amt From Bank Account $account_number To Bank Account $receiving_acc_no";

    // Total Deposit
    $result = "SELECT SUM(transaction_amt) FROM  iB_Transactions  WHERE account_id=? AND tr_type= 'Deposit'";
    $stmt = $mysqli->prepare($result);
    $stmt->bind_param('i', $account_id);
    $stmt->execute();
    $stmt->bind_result($deposits);
    $stmt->fetch();
    $stmt->close();

    // Total Withdrawl and Transfer
    $result = "SELECT SUM(transaction_amt) FROM  iB_Transactions  WHERE account_id=? AND NOT tr_type= 'Deposit' ";
    $stmt = $mysqli->prepare($result);
    $stmt->bind_param('i', $account_id);
    $stmt->execute();
    $stmt->bind_result($with_and_trans);
    $stmt->fetch();
    $stmt->close();
    
    // All transactions [Deposits, Withdrawals and Transfers]
    $result = "SELECT SUM(transaction_amt) FROM  iB_Transactions  WHERE account_id=?";
    $stmt = $mysqli->prepare($result);
    $stmt->bind_param('i', $account_id);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();

    // Previous Balance
    $result = "SELECT GROUP_CONCAT(acc_amount) FROM  iB_Transactions WHERE account_id=? OR account_number=$account_number";
    $stmt = $mysqli->prepare($result);
    $stmt->bind_param('i', $account_id);
    $stmt->execute();
    $stmt->bind_result($amt);
    $stmt->fetch();
    $stmt->close();
    $str_arr = preg_split('/,/', $amt );
    $prev_bal = end($str_arr);


    // Cuurent Balance
    $new_bal = $prev_bal - $transaction_amt .'.00';


    if ($transaction_amt > $new_bal) {
        $transaction_error  =  "You Do Not Have Sufficient Funds In Your Account For Transfer Your Current Account Balance Is $$new_bal";
    } else {


        //Insert Captured information to a database table
        $query = "INSERT INTO iB_Transactions (tr_code, acc_amount, account_id, acc_name, account_number, acc_type,  tr_type, tr_status, client_id, client_name, client_national_id, transaction_amt, client_phone, receiving_acc_no, receiving_acc_name, receiving_acc_holder) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
        $notification = "INSERT INTO  iB_notifications (notification_details) VALUES (?)";
        
        $iB_bankAccounts = "UPDATE iB_bankAccounts SET acc_amount = $new_bal WHERE account_number = $account_number";

        $stmt = $mysqli->prepare($query);
        $notification_stmt = $mysqli->prepare($notification);
        $iB_bankAccounts_stmt = $mysqli->prepare($iB_bankAccounts);

        //bind paramaters
        $rc = $stmt->bind_param('ssssssssssssssss', $tr_code, $new_bal, $account_id, $acc_name, $account_number, $acc_type, $tr_type, $tr_status, $client_id, $client_name, $client_national_id, $transaction_amt, $client_phone, $receiving_acc_no, $receiving_acc_name, $receiving_acc_holder);
        $rc = $notification_stmt->bind_param('s', $notification_details);
        // $iB_bankAccounts_stmt->bind_param('s', $client_id);
        $stmt->execute();
        $notification_stmt->execute();
        $iB_bankAccounts_stmt->execute();


        //declare a varible which will be passed to alert function
        if ($stmt && $notification_stmt) {
            $success = "Money Transfered";
        } else {
            $err = "Please Try Again Or Try Later";
        }

        $account_id = $_GET['account_id'];
        $ret = "SELECT * FROM  ib_bankaccounts WHERE account_id = ? OR account_number=$account_number";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $account_id);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        while ($row = $res->fetch_object()) {
    
                    
            //-------------------MAILER CONFIGURATION ------------------------------
            define('CONTACTFORM_FROM_ADDRESS', 'jlfinancecryptofx@gmail.com');
            define('CONTACTFORM_FROM_NAME', 'LeawoodCU');
            define('CONTACTFORM_TO_ADDRESS', $row->client_email);
            define('CONTACTFORM_TO_NAME', $row->client_name);
    
            define('CONTACTFORM_SMTP_HOSTNAME', 'smtp.gmail.com');
            define('CONTACTFORM_SMTP_USERNAME', 'jlfinancecryptofx@gmail.com'); 
            define('CONTACTFORM_SMTP_PASSWORD', 'nkiyjepzwhionbvv');
            define('CONTACTFORM_SMTP_PORT', 587);
            define('CONTACTFORM_SMTP_ENCRYPTION', 'tls');
            define('CONTACTFORM_PHPMAILER_DEBUG_LEVEL', 0);
            //-------------- MAILER CONFIGURATION END-------------------------------
    
            $letter_file = 'debit.php';
            $subject = "Debit Alert! ".$account_number ;
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true); 
            $description = "Transfer from " .$row->client_name . " to " .$receiving_acc_name." (" . $receiving_acc_holder .") -- --".$tr_code ;
    
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
                
                $data = array('client_name' => $row->client_name, 'description' => $description, 'acc_no'=>$row->account_number, 'trans_amt'=> $transaction_amt, 'acc_ammount'=> $new_bal );
                $mail->msgHTML(get_include_contents($letter_file, $data));
    
                $mail->send();
    
            } catch (Exception $e) {
                $mail->ErrorInfo;
            }
        }
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
        $account_id = $_GET['account_id'];
        $ret = "SELECT * FROM  iB_bankAccounts WHERE account_id = ?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $account_id);
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
                                <h1>Transfer Money</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="pages_transfer_money.php">Finances</a></li>
                                    <li class="breadcrumb-item"><a href="pages_transfer_money.php">Transfer</a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->acc_name; ?></li>
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
                                    <div class="card-header" style="width:90%; background-color:white;">
                                        <h3 class="card-title" style="color:grey; font-size:20px;">Available Balance: $<?php echo $row->acc_amount; ?></h3>
                                    </div>
                                    <div class="card-header">
                                        <h3 class="card-title">Fill All Fields</h3>
                                    </div>
                                    <!-- form start -->
                                    <form method="post" enctype="multipart/form-data" role="form">
                                        <div class="card-body">

                                            <div class="row">
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Client Name</label>
                                                    <input type="text" readonly name="client_name" value="<?php echo $row->client_name; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Client National ID No.</label>
                                                    <input type="text" readonly value="<?php echo $row->client_national_id; ?>" name="client_national_id" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Client Phone Number</label>
                                                    <input type="text" readonly name="client_phone" value="<?php echo $row->client_phone; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Account Name</label>
                                                    <input type="text" readonly name="acc_name" value="<?php echo $row->acc_name; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Account Number</label>
                                                    <input type="text" readonly value="<?php echo $row->account_number; ?>" name="account_number" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Account Type | Category</label>
                                                    <input type="text" readonly name="acc_type" value="<?php echo $row->acc_type; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Transaction Code</label>
                                                    <?php
                                                    //PHP function to generate random account number
                                                    $length = 20;
                                                    $_transcode =  substr(str_shuffle('0123456789QWERgfdsazxcvbnTYUIOqwertyuioplkjhmPASDFGHJKLMNBVCXZ'), 1, $length);
                                                    ?>
                                                    <input type="text" name="tr_code" readonly value="<?php echo $_transcode; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>

                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputPassword1">Amount (USD)</label>
                                                    <input type="text" name="transaction_amt" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Receiving Account Number</label>
                                                    <select name="receiving_acc_no" onChange="getiBankAccs(this.value);" required class="form-control">
                                                        <option>Select Receiving Account</option>
                                                        <?php
                                                        //fetch all iB_Accs
                                                        $ret = "SELECT * FROM  iB_bankAccounts ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        $cnt = 1;
                                                        while ($row = $res->fetch_object()) {

                                                        ?>
                                                            <option><?php echo $row->account_number; ?></option>

                                                        <?php } ?>

                                                    </select>
                                                </div> -->
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Receiving Account Number</label>
                                                    <input type="number" name="receiving_acc_no" required class="form-control" id="ReceivingAcc">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Receiving Bank</label>
                                                    <input type="text" name="receiving_acc_holder" required class="form-control" id="AccountHolder">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Receiving Account Name</label>
                                                    <input type="text" name="receiving_acc_name" required class="form-control" id="ReceivingAcc">
                                                </div>

                                                <div class=" col-md-4 form-group" style="display:none">
                                                    <label for="exampleInputPassword1">Transaction Type</label>
                                                    <input type="text" name="tr_type" value="Transfer" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group" style="display:none">
                                                    <label for="exampleInputPassword1">Transaction Status</label>
                                                    <input type="text" name="tr_status" value="Success " required class="form-control" id="exampleInputEmail1">
                                                </div>

                                            </div>

                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" name="deposit" class="btn btn-primary">Transfer Funds</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
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