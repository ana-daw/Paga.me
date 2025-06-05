<?php
include('../../includes/app/app-header.php');
?>

<section class="create-group-form" id="createGroupForm">
   <h2>Crear Nuevo Grupo</h2>
   <form id="groupForm" onsubmit="crearGrupo(event)">
      <div class="form-group">
         <label for="nombre">Nombre del Grupo</label>
         <input type="text" id="nombre" name="nombre" required>
      </div>
      <div class="form-group">
         <label for="descripcion">Descripción</label>
         <textarea id="descripcion" name="descripcion"></textarea>
      </div>
      <p id="grupo-fail" style="color: red; display: none;">Error al crear el grupo</p>
      <div class="form-actions">
         <button type="button" class="btn btn-secondary" onclick="location.href = '/pagame/views/app/grupos.php'">Cancelar</button>
         <button type="submit" class="btn">Crear Grupo</button>
      </div>
   </form>
</section>

<script>
   function crearGrupo(event) {
      event.preventDefault(); // Evita que la pagina se refresque

      // Recojo los datos del formulario
      const formData = new FormData(event.target);

      // Recojo el id del usuario
      const userJSON = sessionStorage.getItem('user') || localStorage.getItem('user');

      formData.append('idUser', JSON.parse(userJSON).id); // Añadimos el id del user al formData

      $.ajax({
         url: '/pagame/php/crear-grupo.php',
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
         success: (data) => {
            if (data === -1) {
               $('#grupo-fail').show(); //es lo mismo que document.getElementById('grupo-fail').style.display = 'block';
            } else {
               // Redirigimos a la aplicación
               window.location.href = '/pagame/views/app/grupos.php';
            }
         }
      })
   }
</script>

<?php
include('../../includes/app/app-footer.php');
