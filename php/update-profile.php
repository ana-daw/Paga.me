<?php

/*
CODIGOS DE ERROR:
   0        ->> UPDATE CORRECTO
   -1       ->> UPDATE INCORRECTO
*/


$id = $_POST['id'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];

require('../db/connection.php');
$consulta = "UPDATE usuarios SET nombre = '$nombre', apellidos = '$apellidos', email = '$email' WHERE ID = $id";

// Compruebo que funcione el update
if (!mysqli_query($conn, $consulta)) {
   echo -1;
   return;
}

echo 0;
