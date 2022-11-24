<?php
session_start();
include('conf/config.php'); //get configuration file
if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = sha1(md5($_POST['password'])); //double encrypt to increase security
  $stmt = $mysqli->prepare("SELECT email, password, client_id  FROM iB_clients   WHERE email=? AND password=?"); //sql to log in user
  $stmt->bind_param('ss', $email, $password); //bind fetched parameters
  $stmt->execute(); //execute bind
  $stmt->bind_result($email, $password, $client_id); //bind result
  $rs = $stmt->fetch();
  $_SESSION['client_id'] = $client_id; //assaign session toc lient id
  //$uip=$_SERVER['REMOTE_ADDR'];
  //$ldate=date('d/m/Y h:i:s', time());
  if ($rs) { //if its sucessfull
    header("location:pages_dashboard.php");
  } else {
    #echo "<script>alert('Access Denied Please Check Your Credentials');</script>";
    $err = "Access Denied Please Check Your Credentials";
  }
}?>

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
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link rel="apple-touch-icon-precomposed" href="https://www.indonez.com/html-demo/Equity/apple-touch-icon.png">
        <!-- css -->
        <link rel="stylesheet" href="dist/css/uikit.min.css">
        <link rel="stylesheet" href="dist/css/style.css">
    </head>

    <body class="loaded"  style="background-color: black;">
        <!-- preloader begin -->
        <div class="in-loader">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <header>
            <!-- header content begin -->
            <div class="uk-section uk-padding-remove-vertical">
                <nav class="uk-navbar-container uk-navbar-transparent" style="background-color: #2d2b2b;" data-uk-sticky="show-on-up: true; animation: uk-animation-slide-top;" >
                    <div class="uk-container" data-uk-navbar="">
                        <div class="uk-navbar-left">
                            <div class="uk-navbar-item">
                                <!-- logo begin -->
                                <a class="uk-logo" href="./index.html">
                                    <img src="dist/img/leaw.png" data-src="dist/img/leaw.png" alt="logo" data-uk-img="" width="146" height="39">
                                </a>
                                <!-- logo end -->
                                <!-- navigation begin -->
                                <ul class="uk-navbar-nav uk-visible@m">
                                    <li><a href="./index.html">Home</a>
                                        <!-- <div class="uk-navbar-dropdown">
                                            <ul class="uk-nav uk-navbar-dropdown-nav">
                                                <li><a href="https://www.indonez.com/html-demo/Equity/homepage2.html">Homepage 2</a></li>
                                                <li><a href="https://www.indonez.com/html-demo/Equity/homepage3.html">Homepage 3</a></li>
                                                <li><a href="https://www.indonez.com/html-demo/Equity/homepage4.html">Homepage 4</a></li>
                                            </ul>
                                        </div> -->
                                    </li>
                                </ul>
                                <!-- navigation end -->
                            </div>
                        </div>
                        <div class="uk-navbar-right">
                            <div class="uk-navbar-item uk-visible@m in-optional-nav">
                                <a href="sign_in.php" class="uk-button uk-button-text">Log in<i class="fas fa-arrow-circle-right uk-margin-small-left"></i></a>
                                <a href="./sign_up.php" class="uk-button uk-button-primary uk-border-rounded">Register<i class="fas fa-arrow-circle-right uk-margin-small-left"></i></a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
            <!-- header content end -->
        </header>
        <!-- preloader end -->
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
                                        <!-- logo begin -->
                                        <a class="uk-logo" href="./index.html">
                                    <img src="dist/img/leaw.png" data-src="dist/img/leaw.png" alt="logo" data-uk-img="" width="146" height="39">
                                        </a>
                                        <!-- logo end -->
                                        <p class="uk-text-lead uk-margin-small-top uk-margin-medium-bottom">Log into your account</p>
                                        <!-- login form begin -->
                                        <form class="uk-grid uk-form" method="post">
                                            <div class="uk-margin-small uk-width-1-1 uk-inline">
                                                <span class="uk-form-icon uk-form-icon-flip fas fa-user fa-sm"></span>
                                                <input class="uk-input uk-border-rounded" id="email" name="email" value="" type="email" placeholder="Email">
                                            </div>
                                            <div class="uk-margin-small uk-width-1-1 uk-inline">
                                                <span class="uk-form-icon uk-form-icon-flip fas fa-lock fa-sm"></span>
                                                <input class="uk-input uk-border-rounded" id="password" value="" type="password" name="password" placeholder="Password">
                                            </div>
                                            <div class="uk-margin-small uk-width-auto uk-text-small">
                                                <label><input class="uk-checkbox uk-border-rounded" type="checkbox" name="remember"> Remember me</label>
                                            </div>
                                            <div class="uk-margin-small uk-width-expand uk-text-small">
                                                <label class="uk-align-right"><a class="uk-link-reset" href="pages_reset_pwd.php">Forgot password?</a></label>
                                            </div>
                                            <div class="uk-margin-small uk-width-1-1">
                                                <button class="uk-button uk-width-1-1 uk-button-primary uk-border-rounded uk-float-left" type="submit" name="login">Sign in</button>
                                            </div>
                                        </form>
                                        <!-- login form end -->
                                        <p class="uk-heading-line"><span>Or sign up instead</span></p>
                                        
                                        <span class="uk-text-small">Don't have an account? <a class="uk-button uk-button-text" href="./sign_up.php">Register here</a></span>
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


    </body>
</html>