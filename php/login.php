<?php

/*
CODIGOS DE ERROR:
   Usuario  ->> Login correcto
   -2       ->> Usuario sin activar
   -1       ->> Login incorrecto
*/

$email = stripslashes($_POST['email']);
$password = stripslashes($_POST['password']);

require('../db/connection.php');

$consulta = "SELECT id, nombre, apellidos, email, activo FROM usuarios WHERE email = '$email' AND password = '$password'";

$result = mysqli_query($conn, $consulta);

// Comprobamos que el login sea correcto
if (mysqli_num_rows($result) < 1) {
   echo -1;
   return;
}

// Usuario no ha verificado o está desactivado
$usuario = mysqli_fetch_assoc($result);
if ((int)$usuario['activo'] === 0) {
   echo -2;
   return;
}

// Recojo los datos del usuario quitando el campo activo y devolviendo el JSON
unset($usuario['activo']);
header("Content-type:application/json; charset = utf-8");
echo json_encode($usuario);