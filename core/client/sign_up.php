<?php
include('conf/config.php');
require_once __DIR__.'/mailer/vendor/autoload.php';

error_reporting(E_STRICT | E_ALL);
date_default_timezone_set('Etc/UTC');
session_start();

//register new account
if (isset($_POST['create_account'])) {
  //Register  Client
  $name = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $username = $_POST['username'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $address  = $_POST['address'];
  $state  = $_POST['state'];
  $city  = $_POST['city'];
  $national_id = $_POST['ssn'];
  $password = sha1(md5($_POST['password']));
  $client_number = $_POST['client_number'];

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
    header("refresh:3;url= ./sign_in.php");

  } catch (Exception $e) {
    $mail->ErrorInfo;
  }
  include("dist/_partials/head.php");
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Standard Meta -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="chrome=1">
        <title>Equity Fx</title>
        <meta name="description" content="&amp;lt;p&amp;gt;Equity finance and investment, your premium choice for trading currencies &amp;amp;...">
        <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
        <!-- Favicon and apple icon -->
        <link rel="shortcut icon" href="" type="image/x-icon">
        <!-- css -->
        <link rel="stylesheet" href="dist/css/uikit.min.css">
        <link rel="stylesheet" href="dist/css/style.css">
        
    </head>

    <body class="loaded"  style="background-color: black; padding-bottom:50px;">
        <header>
            <!-- header content begin -->
            <div class="uk-section uk-padding-remove-vertical">
                <nav class="uk-navbar-container uk-navbar-transparent" style="background-color: #2d2b2b;" data-uk-sticky="show-on-up: true; animation: uk-animation-slide-top;" >
                    <div class="uk-container" data-uk-navbar="">
                        <div class="uk-navbar-left">
                            <div class="uk-navbar-item">
                                <!-- logo begin -->
                                <a class="uk-logo" href="#">
                                <img src="dist/img/leaw.png" data-src="dist/img/leaw.png" alt="logo" data-uk-img="" width="146" height="39">
                                </a>
                                <!-- logo end -->
                                <!-- navigation begin -->
                                <ul class="uk-navbar-nav uk-visible@m">
                                    <li><a href="#">Home</a>
                                    </li>
                                </ul>
                                <!-- navigation end -->
                            </div>
                        </div>
                        <div class="uk-navbar-right">
                            <div class="uk-navbar-item uk-visible@m in-optional-nav">
                                <a href="./sign_in.php" class="uk-button uk-button-text">Log in<i class="fas fa-arrow-circle-right uk-margin-small-left"></i></a>
                                <a href="./sign_up.php" class="uk-button uk-button-primary uk-border-rounded">Register<i class="fas fa-arrow-circle-right uk-margin-small-left"></i></a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
            <!-- header content end -->
        </header>
        <main>
            <!-- section content begin -->
            <div class="uk-section uk-padding-remove-vertical">
                <div class="uk-container uk-container-expand">
                    <div class="uk-grid" data-uk-height-viewport="expand: true" style="min-height: 472px;">
                        <div class="uk-width-3-5@m uk-background-cover uk-background-center-right uk-visible@m uk-box-shadow-xlarge" style="background-image: url(&quot;in-signin-image.jpg&quot;);">
                        </div>
                        <div class="uk-width-expand@m uk-flex uk-flex-middle">
                            <div class="uk-grid uk-flex-center">
                                <div class="uk-width-3-5@m">
                                    <div class="uk-text-center in-padding-horizontal@s">
                                        <p class="uk-text-lead uk-margin-small-top uk-margin-medium-bottom">Create an account</p>
                                        <!-- login form begin -->
                                        <form class="uk-grid uk-form" method="post">
                                            <div class="uk-margin-small uk-width-1-1 uk-inline">
                                                <span class="uk-form-icon uk-form-icon-flip fas fa-user fa-sm"></span>
                                                <input class="uk-input uk-border-rounded" id="firstname" name="firstname" value="" required type="text" placeholder="Firstname">
                                            </div>
                                            <div class="uk-margin-small uk-width-1-1 uk-inline">
                                                <span class="uk-form-icon uk-form-icon-flip fas fa-user fa-sm"></span>
                                                <input class="uk-input uk-border-rounded" id="lastname" name="lastname" value="" required type="text" placeholder="Lastname">
                                            </div>
                                            <div class="uk-margin-small uk-width-1-1 uk-inline">
                                                <span class="uk-form-icon uk-form-icon-flip fas fa-user fa-sm"></span>
                                                <input class="uk-input uk-border-rounded" id="username" name="username" value="" required type="text" placeholder="Username">
                                            </div>
                                            <div class="uk-margin-small uk-width-1-1 uk-inline">
                                                <span class="uk-form-icon uk-form-icon-flip fas fa-phone fa-sm"></span>
                                                <input class="uk-input uk-border-rounded" id="phone" name="phone" value="" required type="number" placeholder="Phone Number ">
                                            </div>
                                            <div class="uk-margin-small uk-width-1-1 uk-inline">
                                                <span class="uk-form-icon uk-form-icon-flip fas fa-envelope fa-sm"></span>
                                                <input class="uk-input uk-border-rounded" id="email" name="email" value="" required type="email" placeholder="Email">
                                            </div>
                                            <div class="uk-margin-small uk-width-1-1 uk-inline">
                                                <span class="uk-form-icon uk-form-icon-flip fas fa-map-marker-alt fa-sm"></span>
                                                <input class="uk-input uk-border-rounded" id="address" name="address" value="" required type="text" placeholder="Residential Address">
                                            </div>
                                            <div class="uk-margin-small uk-width-1-1 uk-inline">
                                                <span class="uk-form-icon uk-form-icon-flip fas fa-map-marker-alt fa-sm"></span>
                                                <input class="uk-input uk-border-rounded" id="city" name="city" value="" required type="text" placeholder="City">
                                            </div>
                                            <div class="uk-margin-small uk-width-1-1 uk-inline">
                                                <span class="uk-form-icon uk-form-icon-flip fas fa-map-marker-alt fa-sm"></span>
                                                <input class="uk-input uk-border-rounded" id="state" name="state" value="" required type="text" placeholder="State">
                                            </div>
                                            <div class="uk-margin-small uk-width-1-1 uk-inline">
                                                <span class="uk-form-icon uk-form-icon-flip fas fa-tag fa-sm"></span>
                                                <input class="uk-input uk-border-rounded" id="ssn" name="ssn" value="" required type="number" placeholder="Social Security Number">
                                            </div>
                                            <div class="uk-margin-small uk-width-1-1 uk-inline">
                                                <span class="uk-form-icon uk-form-icon-flip fas fa-lock fa-sm"></span>
                                                <input class="uk-input uk-border-rounded" id="password" name="password" value="" type="password" placeholder="Password">
                                            </div>
                                            <div class="uk-margin-small uk-width-1-1 uk-inline">
                                                <span class="uk-form-icon uk-form-icon-flip fas fa-lock fa-sm"></span>
                                                <input class="uk-input uk-border-rounded" id="password" name="confirm_password" value="" type="password" placeholder="Confirm Password">
                                            </div>
                                            <!-- <div class="uk-margin-small uk-width-1-1 uk-inline">
                                                <div class=" form-control ">Verification Document(Passport)</div>
                                                <input class=" uk-border-rounded" id="document" name="document" type="file" accept="image/*;capture=camera">
                                            </div> -->
                                            <div class="uk-margin-small uk-width-1-1 uk-inline">
                                                <div class=" form-control ">Image / Profile picture</div>
                                                <input class=" uk-border-rounded" id="profile_pic" name="profile_pic" type="file" accept="image/*;capture=camera">
                                            </div>
                                            <div class="uk-margin-small uk-width-auto uk-text-small">
                                                <label><input class="uk-checkbox uk-border-rounded" type="checkbox"> Remember me</label>
                                            </div>
                                            <div class="uk-margin-small uk-width-1-1 uk-inline" style="display:none">
                                                <?php
                                                //PHP function to generate random numbers
                                                $length = 6;
                                                $_Number =  substr(str_shuffle('0123456789'), 1, $length); 
                                                ?>
                                                <span class="uk-form-icon uk-form-icon-flip fas fa-user fa-sm"></span>
                                                <input class="uk-input uk-border-rounded" id="client_number" name="client_number" value="LCU-CLIENT-<?php echo $_Number; ?>" type="text" placeholder="Client ID">
                                            </div>
                                            <div class=" col-md-4 form-group" style="display:none">
                                                <label for="exampleInputPassword1">Transaction Status</label>
                                                <input type="text" name="tr_status" value="Success " required class="form-control" id="exampleInputEmail1">
                                            </div>
                                            <div class="uk-margin-small uk-width-1-1">
                                                <button class="uk-button uk-width-1-1 uk-button-primary uk-border-rounded uk-float-left" type="submit" name="create_account">Register</button>
                                            </div>
                                        </form>
                                        <!-- login form end -->
                                        <p class="uk-heading-line"><span>Or sign in instead</span></p>
                                        
                                        <span class="uk-text-small">Already have an account? <a class="uk-button uk-button-text" href="./sign_in.php">Sign in here</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- section content end -->
        </main>
        
        <!-- javascript -->
        <script src="dist/js/uikit.min.js"></script>
        <script src="dist/js/utilities.min.js"></script>
        <script src="dist/js/config-theme.js"></script>
        <script src="plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.min.js"></script> 


    </body>
    
</html>