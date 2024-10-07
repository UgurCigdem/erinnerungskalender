<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../services/LoggerService.php';
require_once __DIR__ . '/../classes/vendor/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__ . '/../classes/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../classes/vendor/phpmailer/phpmailer/src/SMTP.php';

class EmailService {

    private $config;
    private $loggerService;

    // Konstruktor
    public function __construct() {
        $this->config = require __DIR__ . '/../config/config.php';
        $this->loggerService = new LoggerService("EmailService.php", $this->config['LOG_PATH']);
    }

    // E-Mail senden
    public function emailSender1($toEmail, $subject, $body, $isHTML = false) {
        $fromEmail = $this->config['ABSENDER_EMAIL'];
        $fromName = $this->config['ABSENDER_NAME'];
        return $this->emailSender2($fromEmail, $fromName, $toEmail, $subject, $body, $isHTML);
    }

    // E-Mail senden
    public function emailSender2($fromEmail, $fromName, $toEmail, $subject, $body, $isHTML = false) {
        $mail = new PHPMailer(true);

        try {

            // Servereinstellungen laden aus der Config-Datei
            $mail->Host       = $this->config['SMTP_HOST'];
            $mail->Username   = $this->config['SMTP_USERNAME'];
            $mail->Password   = $this->config['SMTP_PASSWORD'];
            $mail->Port       = $this->config['SMTP_PORT'];

            $mail->isSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            // Absender und Empfänger
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($toEmail);
        
            // Codierung setzen
            $mail->CharSet = 'UTF-8';

            // Inhalt der E-Mail
            $mail->Subject = $subject;

            // Unterscheide zwischen HTML- und Klartext-E-Mail
            if ($isHTML) {
                // Inhalt der E-Mail als HTML setzen
                $mail->isHTML(true);
                $mail->Body    = $body;

                // HTML-Inhalt in Klartext konvertieren
                $plainTextBody = strip_tags($body);
                $plainTextBody = str_replace(["<br>", "<br/>", "<p>"], "\n", $plainTextBody);
                $mail->AltBody = $plainTextBody;
            } else {
                // Inhalt der E-Mail als Klartext setzen
                $mail->isHTML(false);
                $mail->Body = $body;
            }

            // E-Mail senden
            $mail->send();

            // Loggen
            $this->loggerService->logEreignis("E-Mail erfolgreich gesendet an: " . $toEmail . ", Betreff: " . $subject);

            return ["message" => "E-Mail wurde erfolgreich gesendet."];

        } catch (Exception $e) {

            // Loggen
            $this->loggerService->logEreignis("Fehler beim Senden der E-Mail an: " . $toEmail . ". Fehler: " . $mail->ErrorInfo);

            return ["error" => "Fehler beim Senden der E-Mail: {$mail->ErrorInfo}"];

        }
    }
}
