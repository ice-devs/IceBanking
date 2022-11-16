<?php
include("admin/conf/config.php");
/* Persisit System Settings On Brand */
$ret = "SELECT * FROM `iB_SystemSettings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($sys = $res->fetch_object()) {
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

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?php echo $sys->sys_name; ?> - <?php echo $sys->sys_tagline; ?></title>
        <link href="dist/css/robust.css" rel="stylesheet">
    </head>

    <body>

        <nav class="navbar navbar-lg navbar-expand-lg navbar-transparant navbar-dark navbar-absolute w-100">
            <div class="container">
                <a class="navbar-brand" href="index.php"><?php echo $sys->sys_name; ?></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" target="_blank" href="admin/pages_index.php">Admin Portal</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" target="_blank" href="staff/pages_staff_index.php">Staff Portal</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" target="_blank" href="client/pages_client_index.php">Client Portal</a>
                        </li>
                    </ul>
                    <a class="btn btn-outline-white" href="client/pages_client_signup.php" target="_blank">Join Us</a>
                </div>
            </div>
        </nav>

        <div class="intro py-5 py-lg-9 position-relative text-white">
            <div class="bg-overlay-primary">
                <img src="dist/bg.webp" class="img-fluid img-cover"/>
            </div>
            <div class="intro-content py-6 text-center">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-12 col-sm-10 col-md-8 col-lg-6 mx-auto text-center">
                            <h1 class="my-3 display-4 d-none d-lg-inline-block"><?php echo $sys->sys_name; ?></h1>
                            <p class="lead mb-3">
                                <?php echo $sys->sys_tagline; ?>
                            </p>
                            <br>
                            <a class="btn btn-success btn-lg mr-lg-2 my-1" target="_blank" href="client/pages_client_signup.php" role="button">Get started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="dist/js/bundle.js"></script>
    </body>

    </html>
<?php
} ?>