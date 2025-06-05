<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/src/Exception.php';
require __DIR__ . '/src/PHPMailer.php';
require __DIR__ . '/src/SMTP.php';



function enviar_mail_reset($email, $nombre, $token)
{
    $urlReset = "http://anabelendaw.canterodev.es/pagame/views/reset_password.php?token=" . urlencode($token);
    
    require('config.php');
    $mail = new PHPMailer(true);
    try {
        // SMTP
        $mail->isSMTP();
        $mail->SMTPDebug  = 0;
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username = $Username;
        $mail->Password = $Password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ];

        // Remitente y destinatario
        $mail->setFrom('anabelendaw@gmail.com', 'Paga.me');
        $mail->addAddress($email, $nombre);

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = 'Recuperar contraseña en Paga.me';
        $mail->Body    = <<<HTML
<!DOCTYPE html>
<html lang="es"><body style="font-family:Arial,sans-serif;color:#333">
  <h2>¡Hola {$nombre}!</h2>
  <p>Hemos recibido una petición para cambiar tu contraseña. 
     Si fuiste tú, haz clic en el siguiente enlace:</p>
  <p><a href="{$urlReset}" target="_blank">Restablecer mi contraseña</a></p>
  <p>Si no solicitaste esto, ignora este correo.</p>
  <hr>
  <p>— El equipo de Paga.me</p>
</body></html>
HTML;
        $mail->AltBody = "Visita $urlReset para restablecer tu contraseña.";

        return $mail->send();
    } catch (Exception $e) {
        error_log("PHPMailer Reset Error: " . $mail->ErrorInfo);
        return false;
    }
}
