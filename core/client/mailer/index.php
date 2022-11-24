<?php
require_once __DIR__.'/config.php';

session_start();

if (!empty($_SESSION['_contact_form_error'])) {
    $error = $_SESSION['_contact_form_error'];
    unset($_SESSION['_contact_form_error']);
}

if (!empty($_SESSION['_contact_form_success'])) {
    $success = true;
    unset($_SESSION['_contact_form_success']);
}

?>
<!doctype html>
<html lang="en">
<head>
    <!--  meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Registration</title>
</head>
<body >
<div class="container" >
    <div class="row">
        <div class="col-md-8 offset-md-2" >
            <div class="card mt-5">
                <div class="card-body" >
                    <h4 class="card-title">
                        Registration Form
                    </h4>

                    <?php
                    if (!empty($success)) {
                        ?>
                        <div class="alert alert-success">Your Registration was successful!</div>
                        <?php
                    }
                    ?>

                    <?php
                    if (!empty($error)) {
                        ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                        <?php
                    }
                    ?>

                    <form method="post" action="submit.php">
                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text" name="firstname" id="name"  placeholder="Enter first name" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" name="lastname" id="name"  placeholder="Enter last name" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email"  placeholder="Enter email" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="country_or_region">Country or Region(Present)</label>
                            <input type="text" name="country_or_region" id="name"  placeholder="Enter country or region" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="amount">Amount Invested</label>
                            <input type="number" id="quantity" name="amount"  placeholder="Enter amount" required class="form-control"> 
                        </div>

                        <div class="form-group">
                            <label for="mode">Mode of receiving</label>
                            <input type="text" name="mode" id="name"  placeholder="" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="recipient">Recipient Bank Account and Details</label>
                            <textarea name="recipient" id="recipient"  placeholder="" required class="form-control" rows="3"></textarea>
                        </div>
                        
                        <button class="btn btn-primary btn-block">Submit</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
