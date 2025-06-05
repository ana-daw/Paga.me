<?php

$servidor = "localhost";
$usuario = "anabelendaw_pagame";
$contraseña = "Lafilo89.";
$shema = "anabelendaw_pagame";

$conn = new mysqli($servidor, $usuario, $contraseña, $shema);
$conn->query("SET NAMES 'utf8'");