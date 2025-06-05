<?php
include('../../includes/app/app-header.php');
?>

<section class="group-detail">
   <div class="group-header">
      <div class="group-title-container">
         <h2 class="group-title"></h2>
      </div>
      <div class="group-actions">
         <div class="invitation-code">
            <span class="code" id="invitationCode"></span>
            <button class="copy-btn" id="copyCodeBtn">
               <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                  <path d="M360-240q-33 0-56.5-23.5T280-320v-480q0-33 23.5-56.5T360-880h360q33 0 56.5 23.5T800-800v480q0 33-23.5 56.5T720-240H360Zm0-80h360v-480H360v480ZM200-80q-33 0-56.5-23.5T120-160v-560h80v560h440v80H200Zm160-240v-480 480Z" />
               </svg>
            </button>
         </div>
         <button class="leave-group-btn" id="leaveGroupBtn">Salir del grupo</button>
         <button class="export-pdf" id="exportPdf">Exportar PDF</button>
      </div>
   </div>
   <!-- SPINNER DE CARGA -->
   <div class="spinner-container" style="display: flex; justify-content: center; align-items: center; width: 100%; min-height: 200px;">
      <div class="spinner">
      </div>
   </div>
   <p class="group-description"></p>

   <div class="group-members">
      <h3>Miembros del grupo</h3>
      <!-- SPINNER DE CARGA -->
      <div class="spinner-container" style="display: flex; justify-content: center; align-items: center; width: 100%; min-height: 200px;">
         <div class="spinner">
         </div>
      </div>
      <div class="member-list">
      </div>
   </div>

   <div class="expenses">
      <h3>Gastos del grupo</h3>
      <!-- SPINNER DE CARGA -->
      <div class="spinner-container" style="display: flex; justify-content: center; align-items: center; width: 100%; min-height: 200px;">
         <div class="spinner">
         </div>
      </div>
      <ul class="expenses-list">
      </ul>
   </div>
</section>

<div class="section-header">
   <button class="btn" id="addExpenseBtn" onclick="abrirGastoForm()">+ Añadir Gasto</button>
</div>

<div class="balance-section">
   <h3>Balance del grupo</h3>
   <!-- SPINNER DE CARGA -->
   <div class="spinner-container" style="display: flex; justify-content: center; align-items: center; width: 100%; min-height: 200px;">
      <div class="spinner">
      </div>
   </div>
   <div class="balance-container">
      <div class="balance-header">
         <span class="balance-title">Usuario</span>
         <span class="balance-title">Balance</span>
      </div>
      <ul class="balance-list">
         <!-- Los balances se cargarán dinámicamente aquí -->
      </ul>
   </div>
</div>

<!-- Modal de confirmación para salir del grupo -->
<div id="leaveGroupModal" class="modal-overlay" style="display: none;">
   <div class="modal-content">
      <h3>¿Estás seguro de que quieres salir del grupo?</h3>
      <p>Esta acción no se puede deshacer.</p>
      <div class="modal-buttons">
         <button id="cancelLeaveGroup" class="modal-btn modal-btn-cancel">Cancelar</button>
         <button id="confirmLeaveGroup" class="modal-btn modal-btn-confirm">Salir del grupo</button>
      </div>
   </div>
</div>

<script>
   let grupoDatos;
   let balanceGrupo;

   // Con esta función vamos a recuperar los datos del grupo
   function getGrupo() {
      const id = new URLSearchParams(window.location.search).get('id');//busca a partir de ?, crea un objeto con los parametros de la url

      $.ajax({
         url: '/pagame/php/get-grupo.php',
         type: "POST",
         data: {
            id
         },
         success: (data) => {
            const grupo = JSON.parse(data);

            document.querySelectorAll(".spinner-container").forEach(spinner => spinner.style.display = 'none');
            cargarGrupo(grupo);

            // Calculamos y mostramos los balances
            const balance = cargarBalance(grupo);
            balanceGrupo = balance;
            mostrarBalances(balance);
         }
      })
   }

   function cargarGrupo(grupo) {
      grupoDatos = grupo;

      const title = document.querySelector('.group-title');
      const description = document.querySelector('.group-description');
      const invitationCode = document.querySelector('#invitationCode');
      const memberList = document.querySelector('.member-list');
      const expensesList = document.querySelector('.expenses-list');

      // Cargamos los datos del grupo
      title.textContent = grupo.nombre;
      description.textContent = grupo.descripcion;
      invitationCode.textContent = grupo.codigo_invitacion;

      // Cargamos los miembros del grupo
      grupo.miembros.forEach(miembro => {
         const memberItem = document.createElement('span');
         memberItem.classList.add('member-item');
         memberItem.textContent = miembro.nombre + " " + miembro.apellidos;

         memberList.appendChild(memberItem);
      });

      // Cargamos los gastos del grupo
      grupo.gastos.forEach(gasto => {
         const expenseItem = document.createElement('li');
         expenseItem.classList.add('expense-item');

         const expenseTitle = document.createElement('div');
         expenseTitle.classList.add('expense-title');
         expenseTitle.textContent = gasto.concepto;

         const expenseDescription = document.createElement('div');
         expenseDescription.classList.add('expense-description');
         expenseDescription.textContent = gasto.descripcion;

         const expenseDetails = document.createElement('div');
         expenseDetails.classList.add('expense-details');

         const expenseUser = document.createElement('span');
         expenseUser.classList.add('expense-user');
         expenseUser.textContent = `Pagado por: ${gasto.usuario}`;

         const expenseAmount = document.createElement('span');
         expenseAmount.classList.add('expense-amount');
         expenseAmount.textContent = `${gasto.cantidad}€`;

         expenseDetails.appendChild(expenseUser);
         expenseDetails.appendChild(expenseAmount);

         expenseItem.appendChild(expenseTitle);
         expenseItem.appendChild(expenseDescription);
         expenseItem.appendChild(expenseDetails);

         expensesList.appendChild(expenseItem);
      });
   }


   function cargarBalance(grupo) {
      //se extraen los datos del grupo
      const gastos = grupo.gastos;
      const miembros = grupo.miembros;
      const balances = {};

      // Inicializamos balances en 0 para cada miembro
      miembros.forEach(miembro => {
         balances[miembro.id] = {
            id: miembro.id,
            nombre: miembro.nombre + " " + miembro.apellidos,
            balance: 0
         };
      });

      // Calcular el total de gastos
      //reduce es un metodo que recorre un array y devuelve un unico valor
      //parseFloat convierte un string a un numero decimal
      const totalGastos = gastos.reduce((total, gasto) => total + parseFloat(gasto.cantidad), 0);

      // Calcular lo que debería pagar cada miembro (división equitativa)
      const pagoEquitativo = totalGastos / miembros.length;

      // recorremos para saber quien ha pagado y cuanto
      gastos.forEach(gasto => {
         const idPagador = gasto.id_pagador;
         const cantidad = parseFloat(gasto.cantidad);

         // Sumar al balance del pagador
         //busca por id si esta en el objeto balances y si esta suma la cantidad al balance
         if (balances[idPagador]) {
            balances[idPagador].balance += cantidad;
         }
      });

      // Restar lo que debería haber pagado cada miembro
      Object.keys(balances).forEach(id => {
         balances[id].balance -= pagoEquitativo;
      });

      return Object.values(balances);
   }

   // Función para mostrar los balances en la UI
   function mostrarBalances(balances) {
      const balanceList = document.querySelector('.balance-list');
      balanceList.innerHTML = '';

      balances.forEach(balance => {
         const balanceItem = document.createElement('li');
         balanceItem.classList.add('balance-item');

         const balanceUser = document.createElement('div');
         balanceUser.classList.add('balance-user');

         const userAvatar = document.createElement('div');
         userAvatar.classList.add('balance-user-avatar');
         const iniciales = obtenerAvatar(balance.nombre);
         userAvatar.textContent = iniciales;

         const userName = document.createElement('span');
         userName.textContent = balance.nombre;

         balanceUser.appendChild(userAvatar);
         balanceUser.appendChild(userName);

         const balanceAmount = document.createElement('span');
         balanceAmount.classList.add('balance-amount');

         // Formatear el balance y asignar clase según sea positivo o negativo
         const balanceFormateado = balance.balance.toFixed(2) + '€';
         if (balance.balance > 0) {
            balanceAmount.classList.add('balance-positive');
            balanceAmount.textContent = '+' + balanceFormateado + ' (a recibir)';
         } else if (balance.balance < 0) {
            balanceAmount.classList.add('balance-negative');
            balanceAmount.textContent = balanceFormateado + ' (a pagar)';
         } else {
            balanceAmount.classList.add('balance-zero');
            balanceAmount.textContent = balanceFormateado + ' (saldado)';
         }

         balanceItem.appendChild(balanceUser);
         balanceItem.appendChild(balanceAmount);

         balanceList.appendChild(balanceItem);
      });
   }

   function obtenerAvatar(nombre) {
      return nombre
         .split(' ')
         .map(n => n.charAt(0))
         .join('')
         .toUpperCase()
         .substring(0, 2);
   }

   // Función para copiar el código de invitación
   document.getElementById("copyCodeBtn").addEventListener('click', () => {
      const invitationCode = document.querySelector('#invitationCode');
      const code = invitationCode.textContent;

      navigator.clipboard.writeText(code);
   });

   document.addEventListener('DOMContentLoaded', (event) => {
      getGrupo();
   });

   // Función para salir del grupo
   document.getElementById('leaveGroupBtn').addEventListener('click', function() {
      document.getElementById('leaveGroupModal').style.display = 'flex';
   });

   // Función para exportar PDF
   document.getElementById('exportPdf').addEventListener('click', function() {
      $.ajax({
         url: '/pagame/pdf/exportar-grupo.php',
         type: "POST",
         data: {
            balance: balanceGrupo,
            nombreGrupo: grupoDatos.nombre,
            descripcionGrupo: grupoDatos.descripcion
         },
         xhrFields: {
            responseType: 'blob' // Recibe datos binarios (PDF)
         },
         success: (data) => {
            const blob = new Blob([data], {
               type: 'application/pdf'
            });
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'balance_grupo.pdf';
            link.click();
         },
         error: (err) => {
            console.error('Error al generar el PDF:', err);
         }
      });
   });

   document.getElementById('cancelLeaveGroup').addEventListener('click', function() {
      document.getElementById('leaveGroupModal').style.display = 'none';
   });

   document.getElementById('confirmLeaveGroup').addEventListener('click', function() {
      const userJSON = sessionStorage.getItem('user') || localStorage.getItem('user');
      const idUser = JSON.parse(userJSON).id;

      $.ajax({
         url: '/pagame/php/leave-grupo.php',
         type: "POST",
         data: {
            idGrupo: new URLSearchParams(window.location.search).get('id'),
            idUser
         },
         success: (data) => {
            window.location.href = '/pagame/views/app/grupos.php';
         }
      })
   });

   function abrirGastoForm() {
      const idGrupo = new URLSearchParams(window.location.search).get('id');

      location.href = '/pagame/views/app/gasto-form.php?idgrupo=' + idGrupo;
   }
</script>

<?php
include('../../includes/app/app-footer.php');
