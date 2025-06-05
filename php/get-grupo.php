<?php
/*
CODIGOS DE ERROR:
   Grupo  ->> El grupo existe.
   -1     ->> El grupo no existe.
*/

$id = stripslashes($_POST['id']);

require('../db/connection.php');

// Recojo los datos principales del grupo
$consulta = "SELECT * FROM grupo_gastos WHERE id = $id";
$resultado = mysqli_query($conn, $consulta);

if (mysqli_num_rows($resultado) < 1) {
   echo -1;
   return;
}
$grupo = $resultado->fetch_assoc();// Recojo los datos del grupo y devuelve array clave valor

// Recojo los participantes del grupo
$consulta = "SELECT id, nombre, apellidos FROM usuarios WHERE id IN (SELECT id_usuario FROM usuarios_grupos WHERE id_grupo = $id)";
$resultado = mysqli_query($conn, $consulta);

// Añado los participantes al grupo
$grupo['miembros'] = [];
while ($miembro = mysqli_fetch_assoc($resultado)) {
   $grupo['miembros'][] = $miembro;
}

// Recojo los gastos del grupo
$consulta = "SELECT * FROM gastos WHERE id_grupo = $id";
$resultado = mysqli_query($conn, $consulta);


// Añado los gastos al grupo
$grupo['gastos'] = [];
while ($gasto = mysqli_fetch_assoc($resultado)) {
   $id_pagador = $gasto['id_pagador'];

   $consulta_usuario = "SELECT nombre, apellidos FROM usuarios WHERE id = $id_pagador";
   $resultado_usuario = mysqli_query($conn, $consulta_usuario);
   $usuario = mysqli_fetch_assoc($resultado_usuario);

   // Añadimos el nombre del usuario al gasto
   $gasto['usuario'] = $usuario['nombre'] . ' ' . $usuario['apellidos'];
   $grupo['gastos'][] = $gasto;
}

echo json_encode($grupo);