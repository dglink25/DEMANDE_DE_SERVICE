<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php'; 

class Mailer {
    public static function sendMail($to, $subject, $body, $attachmentPath = null) {
        $mail = new PHPMailer(true);

        try {
            // Config SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'dglink25@gmail.com'; 
            $mail->Password   = 'odhqfblvolodhsbi'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Expéditeur
            $mail->setFrom('dglink25@gmail.com', 'DGLINK');

            // Destinataire
            $mail->addAddress($to);

            // Fichier joint (si fourni)
            if ($attachmentPath && file_exists($attachmentPath)) {
                $mail->addAttachment($attachmentPath);
            }

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur Mailer : {$mail->ErrorInfo}");
            return false;
        }
    }
}
