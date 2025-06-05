<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';

function enviar_mail_registro($email, $nombre, $token)
{

  require('config.php');

  $mail = new PHPMailer();

  $urlActivacion = "http://anabelendaw.canterodev.es/pagame/php/activate.php?token=" . urlencode($token);
try{
  $mail->SMTPDebug = 2;
  $mail->IsHTML();
  $mail->SMTPAuth = true;
  // $mail->Username = "tucorreo@gmail.com"; 
  // $mail->Password = "tu_contraseña_de_aplicacion_no_segura";
  $mail->Username = $Username;
  $mail->Password = $Password;
  
  // para que el receptor sepa quien envía el correo
  //$mail->From = "tucorreo@gmail.com";
  //$mail->FromName = "tu nombre y apellidos";
  $mail->From = "anabelendaw@gmail.com";
  $mail->FromName = "Paga.me";

  //******************* CONFIGURACIÓN **************************
  $mail->Mailer = "smtp";
  $mail->Host = "smtp.gmail.com";
  $mail->SMTPSecure = 'ssl';
  $mail->Port = 465;    // para 'ssl'
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );

  // asunto del mensaje
  $mail->Subject = "Bienvenido a Paga.me";
  $mail->Timeout = 30;

  //******************* DESTINATARIOS **************************    

  $mail->AddAddress($email);



  //****************** CONTENIDO CORREO ***********************  

  $mail->Body = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Paga.me</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333333;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin-top: 20px;">
        <!-- ENCABEZADO -->
        <tr>
            <td align="center" bgcolor="#2c3e50" style="padding: 30px 0;">
                <h1 style="color: #ecf0f1; margin: 0; font-size: 28px;">Paga.me</h1>
            </td>
        </tr>
        
        <!-- CONTENIDO PRINCIPAL -->
        <tr>
            <td bgcolor="#ffffff" style="padding: 40px 30px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="padding-bottom: 20px;">
                            <h2 style="margin: 0; color: #2c3e50;">¡Hola {$nombre}!</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 20px; line-height: 1.6;">
                            <p>¡Bienvenido/a a <strong>Paga.me</strong>! Estamos encantados de que te hayas unido a nuestra comunidad.</p>
                            <p>Con Paga.me podrás:</p>
                            <ul style="padding-left: 20px;">
                                <li style="margin-bottom: 10px;">Crear grupos para gestionar gastos compartidos</li>
                                <li style="margin-bottom: 10px;">Registrar gastos fácilmente</li>
                                <li style="margin-bottom: 10px;">Ver en tiempo real quién debe a quién</li>
                                <li style="margin-bottom: 10px;">Simplificar las cuentas en viajes, eventos y gastos del hogar</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 20px 0;">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" bgcolor="#3498db" style="border-radius: 5px;">
                                        <a href="{$urlActivacion}" target="_blank" style="display: inline-block; padding: 15px 25px; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 5px;">Activar mi cuenta</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="line-height: 1.6;">
                            
                            <p>Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos respondiendo a este correo.</p>
                            <p>¡Gracias por unirte a nosotros!</p>
                            <p style="margin-bottom: 0;">El equipo de Paga.me</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <!-- PIE DE PÁGINA -->
        <tr>
            <td bgcolor="#f4f4f4" style="padding: 20px 30px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="color: #666666; font-size: 14px; text-align: center;">
                            <p style="margin: 0;">&copy; Paga.me. Todos los derechos reservados.</p>
                            <p style="margin: 10px 0 0 0;">
                                <a href="https://pagame.com/privacidad" style="color: #3498db; text-decoration: none;">Política de Privacidad</a> | 
                                <a href="https://pagame.com/terminos" style="color: #3498db; text-decoration: none;">Términos de Servicio</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;

          // 4) Envía y devuelve resultado
        return $mail->send();
    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
