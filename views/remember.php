<?php include('../includes/header.php'); ?>

<div class="form-container">
  <div class="login-container">
    <h2>Recuperar contraseña</h2>
    <form onsubmit="handleRemember(event)">
      <input type="email" name="email" id="email" placeholder="Tu correo electrónico" required>
      <input type="submit" value="Enviar enlace de recuperación">
    </form>
    <p id="remember-success" style="color: green; display: none;">
      Si existe ese correo, te hemos enviado un enlace para restablecer la contraseña.
    </p>
    <p id="remember-fail" style="color: red; display: none;">
      Ha ocurrido un error. Inténtalo de nuevo más tarde.
    </p>
  </div>
</div>

<script>
function handleRemember(e) {
  e.preventDefault();
  const formData = new FormData(e.target);
  fetch('../php/remember.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.text())
  .then(code => {
    if (code.trim() === '0') {
      document.querySelector('#remember-success').style.display = 'block';
      document.querySelector('#remember-fail').style.display = 'none';
    } else {
      document.querySelector('#remember-fail').style.display = 'block';
      document.querySelector('#remember-success').style.display = 'none';
    }
  });
}
</script>

<?php include('../includes/footer.php'); ?>
