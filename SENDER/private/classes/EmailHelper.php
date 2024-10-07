<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$config = require __DIR__ . '/../config/config.php';

require __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';
require __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';

class EmailHelper {

    public static function emailSender($fromEmail, $fromName, $toEmail, $subject, $body, $isHTML = false) {

        global $config;

        $mail = new PHPMailer(true);

        try {

            // Servereinstellungen
            $mail->Host       = $config['SMTP_HOST'];
            $mail->Username   = $config['SMTP_USERNAME'];
            $mail->Password   = $config['SMTP_PASSWORD'];
            $mail->Port       = $config['SMTP_PORT'];

            $mail->isSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            // Absender und Empfänger
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($toEmail);
            //$mail->addAddress($toEmail, 'Ugur CIGDEM');
        
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
                $mail->isHTML(false);  // Klartext-Modus
                $mail->Body = $body;   // Der Body als Klartext senden
            }

            // E-Mail senden
            $mail->send();

            return ["message" => "E-Mail wurde erfolgreich gesendet."];
            
        } catch (Exception $e) {
            
            // Fehler loggen
            $data = [
                'SMTP_HOST' => $config['SMTP_HOST'],
                'SMTP_USERNAME' => $config['SMTP_USERNAME'],
                'SMTP_PORT' => $config['SMTP_PORT'],
                'Fehlermeldung' => $mail->ErrorInfo
            ];
            error_log(print_r($data, true));

            return ["error" => "Fehler beim Senden der E-Mail: {$mail->ErrorInfo}"];
        }
    }

}