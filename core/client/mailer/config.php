<?php

// The name and address which should be used for the sender details.
define('CONTACTFORM_FROM_ADDRESS', 'jlfinancecryptofx@gmail.com');
define('CONTACTFORM_FROM_NAME', 'Mailer');

// The name and address to which the contact message should be sent.
define('CONTACTFORM_TO_ADDRESS', $name);
define('CONTACTFORM_TO_NAME', 'mailer');

// The details of your SMTP service, e.g. Gmail.
define('CONTACTFORM_SMTP_HOSTNAME', 'smtp.gmail.com');
define('CONTACTFORM_SMTP_USERNAME', 'jlfinancecryptofx@gmail.com');
define('CONTACTFORM_SMTP_PASSWORD', 'nkiyjepzwhionbvv');

// The debug level for PHPMailer. Default is 0 (off), but can be increased from 1-4 for more verbose logging.
define('CONTACTFORM_PHPMAILER_DEBUG_LEVEL', 0);

// Which SMTP port and encryption type to use. The default is probably fine for most use cases.
define('CONTACTFORM_SMTP_PORT', 587);
define('CONTACTFORM_SMTP_ENCRYPTION', 'tls');
