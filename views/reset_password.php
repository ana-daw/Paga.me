<?php
if (!isset($_GET['token'])) {
  die('Enlace inválido.');
}
$token = $_GET['token'];

require(__DIR__ . '/../db/connection.php');
// Obtenemos el email (y opcionalmente nombre)
$sql = "SELECT email FROM usuarios WHERE token = '$token' LIMIT 1";
$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) !== 1) {
  echo "<script>
          alert('Enlace no válido o expirado.');
          window.location.href = '/pagame/views/login.php';
        </script>";
  exit;
}
$email = mysqli_fetch_assoc($res)['email'];
include(__DIR__ . '/../includes/header.php');
?>

<div class="form-container">
  <div class="login-container">
    <h2>Restablecer contraseña</h2>
    <p>Usuario: <strong><?= htmlspecialchars($email) ?></strong></p>
    <form onsubmit="handleReset(event)">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      <input type="password" name="password" id="password" placeholder="Nueva contraseña" required>
      <input type="password" name="confirm_password" id="confirm_password" placeholder="Repite la contraseña" required>
      <input type="submit" value="Cambiar contraseña">
    </form>
    <p id="reset-nomatch" style="color: red; display: none;">Las contraseñas no coinciden</p>
    <p id="reset-fail" style="color: red; display: none;">Error al cambiar contraseña</p>
  </div>
</div>

<script>
function handleReset(e) {
  e.preventDefault();
  const formData = new FormData(e.target);
  fetch('../php/reset_password.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.text())
  .then(resp => {
    if (resp.trim() === '-1') {
      document.querySelector('#reset-nomatch').style.display = 'block';
      return;
    }
    if (resp.trim() === '-2') {
      alert('Enlace inválido o ya usado.');
      window.location.href = '/pagame/views/login.php';
      return;
    }
    // Éxito: resp es JSON con {id,nombre,apellidos,email}
    const user = JSON.parse(resp);
    sessionStorage.setItem('user', JSON.stringify(user));
    window.location.href = '/pagame/views/app/grupos.php';
  })
  .catch(() => {
    document.querySelector('#reset-fail').style.display = 'block';
  });
}
</script>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
