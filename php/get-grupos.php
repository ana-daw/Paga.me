<?php
$id_user = stripslashes($_POST['idUser']);

require('../db/connection.php');

$consulta = "SELECT * FROM grupo_gastos WHERE id IN (SELECT id_grupo FROM usuarios_grupos WHERE id_usuario = $id_user)";

$resultado = mysqli_query($conn, $consulta);

$grupos = [];
while ($grupo = mysqli_fetch_assoc($resultado)) {
   $consulta = "SELECT COUNT(*) as miembros FROM usuarios_grupos WHERE id_grupo = " . $grupo['id'];
   $resultadoMiembros = mysqli_query($conn, $consulta);
   $miembros = mysqli_fetch_assoc($resultadoMiembros);

   $grupo['miembros'] = $miembros['miembros'];

   $grupos[] = $grupo;
}

echo json_encode($grupos);