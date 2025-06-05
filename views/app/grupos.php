<?php
include('../../includes/app/app-header.php');
?>

<section class="groups-section">
   <div class="section-header">
      <h2>Mis Grupos</h2>
      <div>
         <button class="btn" onclick="location.href = '/pagame/views/app/unirse-grupo-form.php'">+ Unirse a un Grupo</button>
         <button class="btn" onclick="location.href = '/pagame/views/app/grupo-form.php'">+ Crear Nuevo Grupo</button>
      </div>
   </div>
   <ul class="groups-list">
      <!-- SPINNER DE CARGA -->
      <div style="display: flex; justify-content: center; align-items: center; width: 100%; min-height: 200px;">
         <div class="spinner">
         </div>
      </div>
   </ul>

   <!-- Controles de paginación -->
   <div class="pagination-controls">
      <button id="prev-page" class="pagination-btn" disabled>
         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"></polyline>
         </svg>
         Anterior
      </button>
      <div class="pagination-info">
         Página <span id="current-page">1</span> de <span id="total-pages"></span>
      </div>
      <button id="next-page" class="pagination-btn">
         Siguiente
         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
         </svg>
      </button>
   </div>
</section>

<script>
   let todosLosGrupos = [];
   let paginaActual = 1;
   let gruposPorPagina = 5;
   let totalPaginas = 1;

   function getGrupos() {
      const userJSON = sessionStorage.getItem('user') || localStorage.getItem('user');
      const idUser = JSON.parse(userJSON).id;

      $.ajax({
         url: '/pagame/php/get-grupos.php',
         type: "POST",
         data: {
            idUser
         },
         success: (data) => {
            todosLosGrupos = JSON.parse(data);

            // Si no hay ningun grupo mostrar mensaje y ocultar paginación
            if (todosLosGrupos.length === 0) {
               const groupsList = document.querySelector('.groups-list');
               groupsList.innerHTML = '';
               groupsList.innerHTML = '<p style="text-align: center; width: 100%;">No perteneces a ningun grupo</p>';

               // Ocultar controles de paginación
               document.querySelector('.pagination-controls').style.display = 'none';
               return;
            }

            // Calcular el total de páginas
            totalPaginas = Math.ceil(todosLosGrupos.length / gruposPorPagina);
            document.getElementById('total-pages').textContent = totalPaginas;

            actualizarBotonesPaginacion();

            // Mostrar la primera página
            mostrarGruposPaginados();
         }
      });
   }

   // Calcular los grupos que se mostrarán en la página actual
   function mostrarGruposPaginados() {
      // Calcular índices para la página actual
      const inicio = (paginaActual - 1) * gruposPorPagina;
      const fin = Math.min(inicio + gruposPorPagina, todosLosGrupos.length);

      // Obtener los grupos para la página actual
      const gruposPagina = todosLosGrupos.slice(inicio, fin);

      // Crear elementos para cada grupo
      imprimirGrupos(gruposPagina);

      // Actualizar el indicador de página actual
      document.getElementById('current-page').textContent = paginaActual;
   }

   // Impime los grupos que reciba por parametro
   function imprimirGrupos(gruposPagina) {
      const groupsList = document.querySelector('.groups-list');
      groupsList.innerHTML = ''; // Eliminamos todos los grupos que se mostraban

      gruposPagina.forEach(grupo => {
         const groupItem = document.createElement('li');
         groupItem.classList.add('group-item');
         groupItem.addEventListener('click', () => location.href = "/pagame/views/app/grupo.php?id=" + grupo.id);

         const groupInfo = document.createElement('div');

         const groupName = document.createElement('h3');
         groupName.classList.add('group-name');
         groupName.textContent = grupo.nombre;

         const groupMembers = document.createElement('span');
         groupMembers.classList.add('group-members');
         groupMembers.textContent = `${grupo.miembros} miembros`;

         groupInfo.appendChild(groupName);
         groupInfo.appendChild(groupMembers);

         const groupLink = document.createElement('a');
         groupLink.classList.add('btn');
         groupLink.textContent = 'Ver detalles';
         groupLink.href = `/pagame/views/app/grupo.php?id=${grupo.id}`;

         groupItem.appendChild(groupInfo);
         groupItem.appendChild(groupLink);

         groupsList.appendChild(groupItem);
      });
   }

   // Llamar a esta función cada vez que se cambie de página
   function actualizarBotonesPaginacion() {
      const prevBtn = document.getElementById('prev-page');
      const nextBtn = document.getElementById('next-page');

      // Deshabilitar botón "Anterior" si estamos en la primera página
      prevBtn.disabled = paginaActual === 1;

      // Deshabilitar botón "Siguiente" si estamos en la última página
      nextBtn.disabled = paginaActual === totalPaginas || totalPaginas === 0;
   }

   // Evento para ir a la página anterior
   document.getElementById('prev-page').addEventListener('click', () => {
      if (paginaActual > 1) {
         paginaActual--;
         mostrarGruposPaginados();
         actualizarBotonesPaginacion();
      }
   });

   // Evento para ir a la página siguiente
   document.getElementById('next-page').addEventListener('click', () => {
      if (paginaActual < totalPaginas) {
         paginaActual++;
         mostrarGruposPaginados();
         actualizarBotonesPaginacion();
      }
   });

   // Este evento se ejecuta cuando carga el HTML
   document.addEventListener('DOMContentLoaded', (event) => {
      getGrupos();
   });
</script>

<?php
include('../../includes/app/app-footer.php');
