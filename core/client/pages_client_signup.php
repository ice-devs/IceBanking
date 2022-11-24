<?php
include('conf/config.php');
require_once __DIR__.'/mailer/vendor/autoload.php';

error_reporting(E_STRICT | E_ALL);
date_default_timezone_set('Etc/UTC');
session_start();

//register new account
if (isset($_POST['create_account'])) {
  //Register  Client
  $name = $_POST['name'];
  $national_id = $_POST['national_id'];
  $client_number = $_POST['client_number'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $password = sha1(md5($_POST['password']));
  $address  = $_POST['address'];

  // $profile_pic  = $_FILES["profile_pic"]["name"];
  //move_uploaded_file($_FILES["profile_pic"]["tmp_name"],"dist/img/".$_FILES["profile_pic"]["name"]);

  //Insert Captured information to a database table
  $query = "INSERT INTO iB_clients (name, national_id, client_number, phone, email, password, address) VALUES (?,?,?,?,?,?,?)";
  $stmt = $mysqli->prepare($query);
  //bind paramaters
  $rc = $stmt->bind_param('sssssss', $name, $national_id, $client_number, $phone, $email, $password, $address);
  $stmt->execute();

  //declare a varible which will be passed to alert function
  if ($stmt) {
    $success = "Account Created";
  } else {
    $err = "Please try again";
  }

  //-------------------MAILER CONFIGURATION ------------------------------
  define('CONTACTFORM_FROM_ADDRESS', 'jlfinancecryptofx@gmail.com');
  define('CONTACTFORM_FROM_NAME', 'leawoodCU');
  define('CONTACTFORM_TO_ADDRESS', $email);
  define('CONTACTFORM_TO_NAME', $name);

  define('CONTACTFORM_SMTP_HOSTNAME', 'smtp.gmail.com');
  define('CONTACTFORM_SMTP_USERNAME', 'jlfinancecryptofx@gmail.com');
  define('CONTACTFORM_SMTP_PASSWORD', 'nkiyjepzwhionbvv');
  define('CONTACTFORM_SMTP_PORT', 587);
  define('CONTACTFORM_SMTP_ENCRYPTION', 'tls');
  define('CONTACTFORM_PHPMAILER_DEBUG_LEVEL', 0);
  //-------------- MAILER CONFIGURATION END-------------------------------

  $letter_file = 'welcome.php';
  $subject = "Welcome to Leawoodcu";
  $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

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
    
    $data = array('client_name' => $name);
    $mail->msgHTML(get_include_contents($letter_file, $data));

    $mail->send();

  } catch (Exception $e) {
    $mail->ErrorInfo;
  }
}

/* Persisit System Settings On Brand */
$ret = "SELECT * FROM `iB_SystemSettings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($auth = $res->fetch_object()) {
?>
  <!DOCTYPE html>
  <html>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

</style>
  <?php include("dist/_partials/nav.php"); ?>
<?php include("dist/_partials/head.php"); ?>

  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <p><?php echo $auth->sys_name; ?> - <?php echo $auth->sys_tagline; ?></p>
      </div>
      <!-- /.login-logo -->
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Sign In To Start Client Session</p>

          <form method="post">
            <div class="input-group mb-3">
              <input type="text" name="name" required class="form-control" placeholder="Enter Full Name">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="text" required name="national_id" class="form-control" placeholder="Verification ID Number">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-tag"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3" style="display:none">
              <?php
              //PHP function to generate random
              $length = 4;
              $_Number =  substr(str_shuffle('0123456789'), 1, $length); ?>
              <input type="text" name="client_number" value="iBank-CLIENT-<?php echo $_Number; ?>" class="form-control" placeholder="Client ID">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="text" name="phone" required class="form-control" placeholder="Enter Phone Number">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-phone"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="text" name="address" required class="form-control" placeholder="Enter Residential Address">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-map-marker"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="text" name="city" required class="form-control" placeholder="Enter City">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-map-marker"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="email" name="email" required class="form-control" pattern="[^ @]*@[^ @]*" placeholder="Enter Email">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" name="password" required class="form-control" placeholder="Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" name="password" required class="form-control" placeholder="Retype Password">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class=" form-control ">Image / Profile picture</div>
            <div class="input-group mb-3">
              <input type="file" name="profile_pic" required class="form-control" >
              <div class="input-group-append">
                <div class="input-group-text">
                </div>
              </div>
            </div>
            
            <div class=" form-control ">Verification Document</div>
            <div class="input-group mb-3">
              <input type="file" name="profile_pic" required class="form-control" >
              <div class="input-group-append">
                <div class="input-group-text">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-8">
              </div>
              <!-- /.col -->
              <div class="col-4">
                <button type="submit" name="create_account" class="btn btn-primary btn-block">Register</button>
              </div>
              <!-- /.col -->
            </div>
          </form>

          <p class="mb-0">
            <a href="pages_client_index.php" class="text-center">Login</a>
          </p>

        </div>
        <!-- /.login-card-body -->
      </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>

  </body>

  </html>
<?php
} ?>