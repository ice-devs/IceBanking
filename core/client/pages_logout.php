<?php
    session_start();
    unset($_SESSION['client_id']);
    session_destroy();

    header("Location: sign_in.php");
    exit;
