<?php
/*
 CÓDIGOS:
  -1  → Contraseñas no coinciden
  -2  → Token inválido
  JSON → Datos del usuario (login automático)
*/

$password         = stripslashes($_POST['password']);
$confirm_password = stripslashes($_POST['confirm_password']);
$token            = stripslashes($_POST['token']);

if ($password !== $confirm_password) {
  echo -1;
  return;
}

require __DIR__ . '/../db/connection.php';


// 1) Verificamos token
$sql = "SELECT id, nombre, apellidos, email FROM usuarios WHERE token = '$token' LIMIT 1";
$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) !== 1) {
  echo -2;
  return;
}
$user = mysqli_fetch_assoc($res);

// 2) Actualizamos contraseña, activamos cuenta si no lo estaba y borramos token
$sql2 = "
  UPDATE usuarios
     SET password = '$password',
         activo   = 1,
         token    = NULL
   WHERE id = {$user['id']}
";
mysqli_query($conn, $sql2);

// 3) Devolvemos JSON para que el front haga el login en sessionStorage
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
  'id'        => $user['id'],
  'nombre'    => $user['nombre'],
  'apellidos' => $user['apellidos'],
  'email'     => $user['email']
]);
