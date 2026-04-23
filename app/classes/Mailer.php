<?php
class Mailer {
    private $config;

    public function __construct() {
        $this->config = $this->loadConfig();
    }

    private function loadConfig() {
        $path = __DIR__ . '/../config/mail.php';
        if (file_exists($path)) {
            $cfg = require $path;
            if (is_array($cfg)) return $cfg;
        }

        return [
            'driver' => 'mail',
            'from_email' => 'no-reply@baker.local',
            'from_name' => 'Baker Store',
        ];
    }

    public function send($toEmail, $toName, $subject, $htmlBody) {
        $driver = $this->config['driver'] ?? 'mail';
        if ($driver === 'smtp') {
            return $this->sendSmtp($toEmail, $toName, $subject, $htmlBody);
        }
        return $this->sendMail($toEmail, $toName, $subject, $htmlBody);
    }

    private function sendMail($toEmail, $toName, $subject, $htmlBody) {
        $fromEmail = $this->config['from_email'] ?? 'no-reply@baker.local';
        $fromName = $this->config['from_name'] ?? 'Baker Store';

        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . $this->encodeHeaderName($fromName) . " <{$fromEmail}>";

        $toHeader = $toEmail;
        if ($toName !== '') {
            $toHeader = $this->encodeHeaderName($toName) . " <{$toEmail}>";
        }

        return @mail($toHeader, $subject, $htmlBody, implode("\r\n", $headers));
    }

    // Optional: SMTP via PHPMailer (requires vendor/autoload.php and phpmailer/phpmailer)
    private function sendSmtp($toEmail, $toName, $subject, $htmlBody) {
        $autoload = __DIR__ . '/../../vendor/autoload.php';
        if (!file_exists($autoload)) {
            return $this->sendMail($toEmail, $toName, $subject, $htmlBody);
        }
        require_once $autoload;

        if (!class_exists('\\PHPMailer\\PHPMailer\\PHPMailer')) {
            return $this->sendMail($toEmail, $toName, $subject, $htmlBody);
        }

        $fromEmail = $this->config['from_email'] ?? 'no-reply@baker.local';
        $fromName = $this->config['from_name'] ?? 'Baker Store';

        $host = $this->config['host'] ?? '';
        $port = (int)($this->config['port'] ?? 587);
        $encryption = $this->config['encryption'] ?? 'tls';
        $username = $this->config['username'] ?? '';
        $password = $this->config['password'] ?? '';

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->Port = $port;
            $mail->SMTPAuth = true;
            $mail->Username = $username;
            $mail->Password = $password;

            if ($encryption === 'ssl') {
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($encryption === 'tls') {
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->CharSet = 'UTF-8';
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($toEmail, $toName);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;

            return $mail->send();
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function encodeHeaderName($name) {
        // Prevent header issues with UTF-8 names
        return '=?UTF-8?B?' . base64_encode($name) . '?=';
    }
}

