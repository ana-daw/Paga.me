<?php
/*
 CÓDIGOS:
   0  → Solicitud registrada (si existe el email, se envía correo)
  -1  → Error genérico
*/

$email = stripslashes($_POST['email']);
require(__DIR__ . '/../db/connection.php');

// 1) Buscamos al usuario
$sql = "SELECT id, nombre, apellidos FROM usuarios WHERE email = '$email' LIMIT 1";
$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) !== 1) {
  // No exponemos si no existe el correo
  echo 0;
  return;
}
$user = mysqli_fetch_assoc($res);

// 2) Generamos nuevo token y lo guardamos
$token = bin2hex(random_bytes(16));
$sql2 = "UPDATE usuarios SET token = '$token' WHERE id = {$user['id']}";
if (!mysqli_query($conn, $sql2)) {
  echo -1;
  return;
}

// 3) Enviamos el correo de recuperación
include(__DIR__ . '/../mail/enviar_mail_reset.php');
if (!enviar_mail_reset($email, $user['nombre'], $token)) {
  // falló el envío, pero devolvemos éxito para no filtrar info
  error_log("Error enviando reset a $email");
}

echo 0;
