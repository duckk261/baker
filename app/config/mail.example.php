<?php
// Copy this file to app/config/mail.php and fill in values.
return [
    // 'mail' (PHP built-in) or 'smtp' (requires PHPMailer via Composer)
    'driver' => 'smtp',

    // Common
    'from_email' => 'YOUR_GMAIL@gmail.com',
    'from_name' => 'Baker Store',

    // SMTP (only used when driver = smtp)
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'encryption' => 'tls', // 'tls' | 'ssl' | ''
    'username' => 'YOUR_GMAIL@gmail.com',
    'password' => 'YOUR_GMAIL_APP_PASSWORD',
];

