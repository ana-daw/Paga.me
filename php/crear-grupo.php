<?php

/*
CODIGOS DE ERROR:
   1  ->> El grupo se ha creado correctamente
   -1 ->> Error al crear el grupo
*/

$nombre = stripslashes($_POST['nombre']);
$descripcion = stripslashes($_POST['descripcion']);
$id_user = stripslashes($_POST['idUser']);

function generarCodigoInvitacion($length = 32) {
   return bin2hex(random_bytes($length / 2));// pasa de binario a hexadecimal
}

$codigo_invitacion = generarCodigoInvitacion();

require('../db/connection.php');

$consulta = "INSERT INTO grupo_gastos (nombre, descripcion, codigo_invitacion) VALUES ('$nombre', '$descripcion', '$codigo_invitacion')";
if (mysqli_query($conn, $consulta)) {
   $id_grupo = mysqli_insert_id($conn); // Recogemos el id del grupo que acabamos de crear

   $consulta = "INSERT INTO usuarios_grupos VALUES ($id_user, $id_grupo)";

   mysqli_query($conn, $consulta);
   echo 1;
} else {
   echo -1;
   exit;
}
