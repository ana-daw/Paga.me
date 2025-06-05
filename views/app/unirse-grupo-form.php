<?php
include('../../includes/app/app-header.php');
?>

<section class="create-group-form" id="createGroupForm">
   <h2>Unirse a un Grupo</h2>
   <form id="groupForm" onsubmit="unirseGrupo()">
      <div class="form-group">
         <label for="enlace">Enlace del Grupo</label>
         <input type="text" id="enlace" name="enlace" required>
      </div>
      <p id="grupo-fail-1" style="color: red; display: none; margin-bottom: 10px;">El enlace no pertenece a ningun grupo</p>
      <p id="grupo-fail-2" style="color: red; display: none; margin-bottom: 10px;">Ya perteneces a este grupo</p>
      <div class="form-actions">
         <button type="button" class="btn btn-secondary" onclick="location.href = '/pagame/views/app/grupos.php'">Cancelar</button>
         <button type="submit" class="btn">Unirse a Grupo</button>
      </div>
   </form>
</section>

<script>
   function unirseGrupo() {
      event.preventDefault(); // Evita que la pagina se refresque

      // Recojo los datos del formulario
      const enlace = document.getElementById("enlace").value

      // Recojo el id del usuario
      const userJSON = sessionStorage.getItem('user') || localStorage.getItem('user');

      $.ajax({
         url: '/pagame/php/unirse-grupo.php',
         type: "POST",
         data: {
            idUser: JSON.parse(userJSON).id,
            enlace
         },
         success: (data) => {
            console.log(data)
            
            if (data < 0) {
               $('#grupo-fail' + data).show();/*es lo mismo que document.getElementById('grupo-fail').style.display = 'block';*/
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
