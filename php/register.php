<?php

/*
CODIGOS DE ERROR:
   0  ->> Usuario registrado correctamente
   -1 ->> Contraseñas no coinciden
   -2 ->> Email repetido
*/

// Recojo los datos del formulario
$nombre = stripslashes($_POST['nombre']);
$apellidos = stripslashes($_POST['apellidos']);
$email = stripslashes($_POST['email']);
$password = stripslashes($_POST['password']);
$confirm_password = stripslashes($_POST['confirm_password']);

// Compruebo que las contraseñas coincidan
if ($password !== $confirm_password) {
   echo -1;
   return;
}

require('../db/connection.php');
// Genera un token aleatorio
$token = bin2hex(random_bytes(16));

$consulta = "INSERT INTO usuarios (nombre, apellidos, email, password, activo, token) VALUES ('$nombre', '$apellidos', '$email', '$password', 0, '$token')";

try {
   $result = mysqli_query($conn, $consulta);
}
// Comprobamos que el email no esté repetido
catch (Exception $e) {
   echo -2;
   return;
}

// Llamo al archivo enviar_mail_registro.php enviándole los datos de $email y $nombre
include('../mail/enviar_mail_registro.php');
if (!enviar_mail_registro($email, $nombre, $token)) {
    // Si falla el envío, puedes hacer un rollback o al menos avisar
    error_log("Error enviando mail de verificación a $email");
    // …y decidir si devuelves un error distinto…
}
echo 0;