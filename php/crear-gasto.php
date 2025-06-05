<?php
/*
CODIGOS DE ERROR:
   1  ->> El grupo se ha creado correctamente
   -1 ->> Error al crear el grupo
*/

$concepto = stripslashes($_POST['concepto']);
$descripcion = stripslashes($_POST['descripcion']);
$cantidad = stripslashes($_POST['cantidad']);
$idUser = $_POST['idUser'];
$idGrupo = $_POST['idGrupo'];

require('../db/connection.php');

$consulta = "INSERT INTO gastos (concepto, descripcion, cantidad, id_grupo, id_pagador) VALUES ('$concepto', '$descripcion', '$cantidad', $idGrupo, $idUser)";
if (mysqli_query($conn, $consulta)) {
   echo 1;
} else {
   echo -1;
   exit;
}
