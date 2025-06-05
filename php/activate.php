<?php
session_start();
require __DIR__ . '/../db/connection.php';

if (!isset($_GET['token'])) {
    die('Enlace de activación inválido.');
}

$token = $_GET['token'];

// 1) Buscamos al usuario (inactivo) por token
$sql = "
  SELECT id, nombre, apellidos, email 
    FROM usuarios 
   WHERE token = ? 
     AND activo = 0
   LIMIT 1
";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $token);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!$res || mysqli_num_rows($res) !== 1) {
    echo <<<HTML
<script>
  alert('Este enlace no es válido o ya ha sido usado.');
  window.location.href = '/pagame/views/login.php';
</script>
HTML;
    exit;
}

// 2) Recuperamos datos de usuario
$user = mysqli_fetch_assoc($res);

// 3) Activamos la cuenta y borramos el token
$upd = "
  UPDATE usuarios
     SET activo = 1,
         token  = NULL
   WHERE id = ?
";
$stmt2 = mysqli_prepare($conn, $upd);
mysqli_stmt_bind_param($stmt2, 'i', $user['id']);
mysqli_stmt_execute($stmt2);

// (Opcional) arrancar sesión en PHP
$_SESSION['user_id']   = $user['id'];
$_SESSION['user_name'] = $user['nombre'];


// 4) Emitimos un HTML+JS que inserta el usuario en sessionStorage y redirige
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Activando tu cuenta...</title>
</head>
<body>
<script>
  // Creamos el objeto usuario igual que hace el login.js
  const user = {
    id:       <?= json_encode($user['id']) ?>,
    nombre:  <?= json_encode($user['nombre']) ?>,
    apellidos: <?= json_encode($user['apellidos']) ?>,
    email:    <?= json_encode($user['email']) ?>
  };

  // Lo guardamos en sessionStorage (o localStorage si prefieres "recordarme")
  sessionStorage.setItem('user', JSON.stringify(user));

  // Redirigimos a la página de grupos ya "logueados"
  window.location.href = '/pagame/views/app/grupos.php';
</script>
</body>
</html>
