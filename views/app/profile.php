<?php
include('../../includes/app/app-header.php');
?>

<section class="profile-section">
   <form id="profileForm" class="profile-form">
      <div class="form-group">
         <label for="nombre">Nombre:</label>
         <input type="text" id="nombre" name="nombre" readonly>
      </div>
      <div class="form-group">
         <label for="apellidos">Apellidos:</label>
         <input type="text" id="apellidos" name="apellidos" readonly>
      </div>
      <div class="form-group">
         <label for="email">Email:</label>
         <input type="email" id="email" name="email" readonly>
      </div>
      <p style="color: red; display: none;" id="update-fail">Algo ha fallado intentando actualizar los datos</p>
      <div class="form-group">
         <button type="button" id="editBtn" class="btn btn-edit">Editar Perfil</button>
         <button type="submit" id="saveBtn" class="btn btn-save" style="display: none;">Guardar Cambios</button>
      </div>
   </form>
</section>

<script>
   // Funcionalidad para editar y guardar el perfil
   const form = document.getElementById('profileForm');
   const editBtn = document.getElementById('editBtn');
   const saveBtn = document.getElementById('saveBtn');
   const inputs = form.querySelectorAll('input');

   let userId;

   editBtn.addEventListener('click', function() {
      inputs.forEach(input => input.removeAttribute('readonly'));
      editBtn.style.display = 'none';
      saveBtn.style.display = 'block';
   });

   form.addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(event.target);

      formData.append("id", userId);

      $.ajax({
         url: '../../php/update-profile.php',
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
         success: (data) => {
            if (data === -1) {
               document.getElementById("update-fail").style.display = 'block';
               return;
            }

            // Actualizamos los datos en la sesión 
            const user = {
               id: userId,
               nombre: document.getElementById("nombre").value,
               apellidos: document.getElementById("apellidos").value,
               email: document.getElementById("email").value,
            }

            // Actualizamos en sessionStorage o en localStorage, donde se encuentren los datos
            if (localStorage.getItem("user")) {
               localStorage.setItem("user", JSON.stringify(user));// pasa a cadena de texto en json
            }
            if (sessionStorage.getItem("user")) {
               sessionStorage.setItem("user", JSON.stringify(user));
            }

            // Redirijo al perfil de nuevo para bloquear el formulario
            location.href = '/pagame/views/app/profile.php'
         }
      })
   });

   document.addEventListener('DOMContentLoaded', () => {
      // Cargo los datos en el formulario
      const userJSON = sessionStorage.getItem('user') || localStorage.getItem('user');
      const user = JSON.parse(userJSON);

      document.getElementById("nombre").value = user.nombre
      document.getElementById("apellidos").value = user.apellidos
      document.getElementById("email").value = user.email
      userId = user.id;
   });
</script>

<?php
include('../../includes/app/app-footer.php');
