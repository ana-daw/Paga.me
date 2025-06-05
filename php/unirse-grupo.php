<?php
/*
CODIGOS DE ERROR:
   1  ->> Se ha unido al grupo
   -1 ->> No existe el grupo
   -2 ->> El usuario ya pertenece al grupo
*/

$enlace = stripslashes($_POST['enlace']);
$id_user = stripslashes($_POST['idUser']);

require('../db/connection.php');

// Comprobar que el grupo existe
$consulta = "SELECT id FROM grupo_gastos WHERE codigo_invitacion = '$enlace'";
$resultado = mysqli_query($conn, $consulta);
$id_grupo;

if (mysqli_num_rows($resultado) > 0) {
   $fila = mysqli_fetch_assoc($resultado);
   $id_grupo = $fila['id'];
} else {
   echo -1;
   exit;
}

// Comprobar que el usuario no pertenece ya al grupo
$consulta = "SELECT * FROM usuarios_grupos WHERE id_usuario = $id_user AND id_grupo = $id_grupo";
$resultado = mysqli_query($conn, $consulta);

if (mysqli_num_rows($resultado) > 0) {
   echo -2; // El usuario ya pertenece al grupo
   exit;
}

// Añado el usuario al grupo
$consulta = "INSERT INTO usuarios_grupos VALUES ($id_user, $id_grupo)";
if (mysqli_query($conn, $consulta)) {
   echo 1;
}
