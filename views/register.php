<?php
include('../includes/header.php');
?>

<div class="form-container">
   <div class="register-container">
      <h2>Registro</h2>
      <form onsubmit="handleRegister(event)">
         <input type="text" name="nombre" id="nombre" placeholder="Nombre" required>
         <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos" required>
         <input type="email" name="email" id="email" placeholder="Correo electrónico" required>
         <input type="password" name="password" id="password" placeholder="Contraseña" required>
         <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmar contraseña" required>
         <input type="submit" value="Registrarse">
      </form>
      <p id="password-nomatch" style="color: red; display: none;">Las contraseñas no coinciden</p>
      <p id="repeated-email" style="color: red; display: none;">Ya hay un usuario con ese email</p>
      <div class="login-link">
         <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
      </div>
   </div>
</div>

<script>
   function handleRegister(event) {
      event.preventDefault(); // Evita que la pagina se refresque

      // Recojo los datos del formulario
      const formData = new FormData(event.target);

      $.ajax({
         url: '../php/register.php',
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
         success: (data) => {
            // Compruebo que las contraseñas coincidan
            if (data === '-1') {
               document.querySelector('#password-nomatch').style.display = 'block';
               document.querySelector('#repeated-email').style.display = 'none';
               return;
            }
            // Compruebo que el email este repetido
            if (data === '-2') {
               document.querySelector('#repeated-email').style.display = 'block';
               document.querySelector('#password-nomatch').style.display = 'none';
               return;
            }

            // Redirijo al login
            window.location.href = '/pagame/views/login.php';
         }
      })
   }
</script>

<?php
include('../includes/footer.php');
