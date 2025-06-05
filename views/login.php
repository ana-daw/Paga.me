<?php
include('../includes/header.php');
?>

<div class="form-container">
   <div class="login-container">
      <h2>Iniciar Sesión</h2>
      <form onsubmit="handleLogin(event)">
         <input type="email" name="email" placeholder="Correo electrónico" required>
         <input type="password" name="password" placeholder="Contraseña" required>
         <div class="remember-me">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Recordar sesión</label>
         </div>
         <input type="submit" value="Iniciar Sesión">
      </form>
      <p id="login-fail" style="color: red; display: none;">Email o Contraseña incorrecto</p>
      <p id="login-no-activated" style="color: red; display: none;">El correo no ha sido activado</p>
      <div class="register-link">
         <a href="remember.php">¿Olvidaste tu contraseña?</a>
      </div>
      <div class="register-link">
         <a href="register.php">¿No tienes cuenta? Regístrate</a>
      </div>
   </div>
</div>

<script>
   function handleLogin(event) {
      event.preventDefault(); // Evita que la pagina se refresque

      // Recojo los datos del formulario
      const formData = new FormData(event.target);

      $.ajax({
         url: '../php/login.php',
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
         success: (data) => {
            // Compruebo que las contraseñas coincidan
            if (data === '-1') {
               document.querySelector('#login-fail').style.display = 'block';
               return;
            }
            
			 if (data === '-2') {
               document.querySelector('#login-no-activated').style.display = 'block';
               return;
            } 
			 
            if (document.getElementById("remember").checked) {
               localStorage.setItem("user", JSON.stringify(data));
            } else {
               sessionStorage.setItem("user", JSON.stringify(data));
            }

            console.log(JSON.parse(sessionStorage.getItem("user")))

            // Redirigimos a la aplicación
            window.location.href = '/pagame/views/app/grupos.php';
         }
      })
   }
</script>

<?php
include('../includes/footer.php');
