<?php

require_once __DIR__.'/vendor/autoload.php';
// require_once __DIR__.'/functions.php';
require_once __DIR__.'/config.php';


error_reporting(E_STRICT | E_ALL);

date_default_timezone_set('Etc/UTC');

session_start();

$letter_file = 'welcome.php';
$mail = new \PHPMailer\PHPMailer\PHPMailer(true);

echo ("working");

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
    $mail->Subject = "New Form Submission";
    // $mail->Body    = <<<EOT
    //  welcome to leawoodcu 
    // EOT;
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
    //redirectSuccess();

} catch (Exception $e) {
    redirectWithError("An error occurred while trying to submit,please try resubmitting your details again: ".$mail->ErrorInfo);
}



// header("refresh:0.2;url= ./checking.html");

