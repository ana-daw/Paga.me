<?php

/*
CODIGOS DE ERROR:
   -1     ->> El usuario no pertenece al grupo.
*/

$idGrupo = stripslashes($_POST['idGrupo']);
$idUser = stripslashes($_POST['idUser']);

echo "<pre>";
print_r($_POST);
echo "</pre>";

require('../db/connection.php');

// Comprobamos que el usuario es miembro del grupo
$consulta = "SELECT * FROM usuarios_grupos WHERE id_usuario = $idUser AND id_grupo = $idGrupo";
$resultado = mysqli_query($conn, $consulta);

if (mysqli_num_rows($resultado) < 1) {
   echo -1;
   return;
}

// Eliminamos al usuario del grupo
$consulta = "DELETE FROM usuarios_grupos WHERE id_usuario = $idUser AND id_grupo = $idGrupo";
mysqli_query($conn, $consulta);

// Comprobamos si el grupo se queda sin miembros
$consulta = "SELECT * FROM usuarios_grupos WHERE id_grupo = $idGrupo";
$resultado = mysqli_query($conn, $consulta);

if (mysqli_num_rows($resultado) < 1) {
   // Eliminamos los gastos del grupo
   $consulta = "DELETE FROM gastos WHERE id_grupo = $idGrupo";
   mysqli_query($conn, $consulta);

   // Eliminamos el grupo
   $consulta = "DELETE FROM grupo_gastos WHERE id = $idGrupo";
   mysqli_query($conn, $consulta);
}
