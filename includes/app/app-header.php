<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard - Paga.me</title>
   <link rel="stylesheet" href="/pagame/styles/app.css">

   <script>
      function logout() {
         localStorage.removeItem("user");
         sessionStorage.removeItem("user");
      }
   </script>
</head>


<body>
   <div class="dashboard">
      <!-- BARRA LATERAL -->
      <aside class="sidebar">
         <div class="logo" onclick="location.href = '/pagame/views/app/grupos.php'">Paga.me</div>
         <nav>
            <ul class="nav-menu">
               <li class="nav-item"><a href="/pagame/views/app/grupos.php" class="nav-link active">Grupos</a></li>
               <li class="nav-item"><a href="/pagame/views/app/profile.php" class="nav-link">Perfil</a></li>
               <li class="nav-item"><a href="/pagame" class="nav-link" onclick="logout()">Cerrar Sesión</a></li>
            </ul>
         </nav>
      </aside>

      <main class="main-content">

         <!-- HEADER -->
         <header class="header">
            <h1 class="page-title">Dashboard</h1>
            <div class="user-info">
               <span class="user-name"></span>
               <div class="avatar"></div>
            </div>
         </header>

         <script>
            const userJSON = localStorage.getItem("user") || sessionStorage.getItem("user");
            const user = JSON.parse(userJSON);

            document.querySelector(".user-info .user-name").textContent = user.nombre + " " + user.apellidos;
            document.querySelector(".user-info .avatar").textContent = user.nombre.charAt(0) + user.apellidos.charAt(0);
         </script>