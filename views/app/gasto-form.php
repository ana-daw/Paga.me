<?php
include('../../includes/app/app-header.php');
?>

<section class="expense-form-section">
   <form id="gastoForm" class="expense-form">
      <div class="form-group">
         <label for="concepto">Concepto:</label>
         <input type="text" id="concepto" name="concepto" required>
      </div>
      <div class="form-group">
         <label for="descripcion">Descripción:</label>
         <textarea id="descripcion" name="descripcion" required></textarea>
      </div>
      <div class="form-group">
         <label for="cantidad">Cantidad (€):</label>
         <input type="number" id="cantidad" name="cantidad" step="0.01" min="0" required>
      </div>
      <div class="form-group">
         <button type="submit" class="btn btn-create">Crear Gasto</button>
      </div>
   </form>
</section>

<script>
   const form = document.getElementById("gastoForm"); 
   
   form.addEventListener('submit', function(e) {
      e.preventDefault();

      const userJSON = sessionStorage.getItem('user') || localStorage.getItem('user');

      const idGrupo = new URLSearchParams(window.location.search).get('idgrupo');
      const idUser = JSON.parse(userJSON).id;

      const formData = new FormData(e.target);
      formData.append("idGrupo", idGrupo);
      formData.append("idUser", idUser);

      $.ajax({
         url: '../../php/crear-gasto.php',
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
         success: (data) => {
            console.log(data)

            
            // Redirigimos al grupo
            window.location.href = '/pagame/views/app/grupo.php?id=' + idGrupo;
         }
      })
   })
</script>

<?php
include('../../includes/app/app-footer.php');
