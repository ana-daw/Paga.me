<?php
include('includes/header.php');
?>

<!-- Hero section -->
<section class="hero">
   <div class="container hero-content">
      <div class="hero-text">
         <h1>Gestiona gastos grupales sin complicaciones</h1>
         <p>Paga.me te ayuda a dividir gastos, crear grupos y calcular deudas entre amigos de manera sencilla y rápida.</p>
         <a href="/pagame/views/register.php" class="btn">Comienza gratis</a>
      </div>
      <div class="hero-image">
         <img src="/pagame/img/logo.jpeg" alt="Paga.me app" style="max-width: 100%; height: auto; margin-left: 80px">
      </div>
   </div>
</section>

<!-- Características -->
<section id="caracteristicas" class="features">
   <div class="container">
      <h2>Características principales</h2>
      <div class="feature-grid">
         <div class="feature">
            <div class="feature-icon">👥</div>
            <h3>Crea grupos</h3>
            <p>Invita a tus amigos y organiza tus gastos por grupos.</p>
         </div>
         <div class="feature">
            <div class="feature-icon">🧾</div>
            <h3>Añade gastos</h3>
            <p>Registra fácilmente los gastos compartidos en cada grupo.</p>
         </div>
         <div class="feature">
            <div class="feature-icon">🧮</div>
            <h3>Calcula deudas</h3>
            <p>Obtén un resumen claro de quién debe a quién y cuánto.</p>
         </div>
      </div>
   </div>
</section>

<!-- Cómo funciona -->
<section id="como-funciona" class="how-it-works">
   <div class="container">
      <h2>Cómo funciona Paga.me</h2>
      <div class="steps">
         <div class="step">
            <h3>1. Regístrate</h3>
            <p>Crea tu cuenta gratuita en Paga.me en segundos.</p>
         </div>
         <div class="step">
            <h3>2. Crea un grupo</h3>
            <p>Invita a tus amigos y comienza a registrar los gastos compartidos.</p>
         </div>
         <div class="step">
            <h3>3. Divide los gastos</h3>
            <p>Paga.me calcula automáticamente quién debe a quién y cuánto.</p>
         </div>
      </div>
   </div>
</section>

<!-- Call to Action -->
<section class="cta">
   <div class="container">
      <h2>¿Listo para simplificar tus gastos grupales?</h2>
      <a href="/pagame/views/register.php" class="btn">Regístrate ahora</a>
   </div>
</section>

<script>
   if (localStorage.getItem("user")) {
      window.location.href = 'views/dashboard.php';
   }
</script>

<?php
include('includes/footer.php');
?>