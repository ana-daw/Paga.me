💸 PAGA.ME — Gestión de Gastos Compartidos
PAGA.ME es una aplicación web desarrollada como parte del Trabajo de Fin de Ciclo de Desarrollo de Aplicaciones Web. Su objetivo es facilitar el control y reparto de gastos entre miembros de un grupo de forma automática, intuitiva y rápida. Pensada especialmente para estudiantes, amigos o compañeros que comparten piso, viajes o actividades comunes.

🔗 Acceder al proyecto desplegado

📋 Funcionalidades principales
👤 Gestión de usuarios:
Registro y login con validación

Recuperación de contraseña mediante token

Inicio de sesión automático tras activación

Almacenamiento de sesión en sessionStorage

👥 Grupos y gastos:
Creación de grupos con código único de invitación

Unirse a grupos existentes mediante código

Visualización de grupos y detalle individual

Registro de nuevos gastos (importe, descripción, fecha, pagador)

Resumen de deudas con cálculo automático

Eliminación de grupo si no quedan miembros

📤 Otros:
Rediseño responsive para dispositivos móviles

Exportación de datos de grupo en PDF

Validaciones de formularios y controles de acceso

🧪 Tecnologías utilizadas
Frontend: HTML5, CSS3, JavaScript, jQuery

Backend: PHP (estructurado)

Base de datos: MySQL

Entorno local: XAMPP

Despliegue: proveedor STRATO y el panel de control para servidores PLESK

Librerías externas: FPDF (para exportar a PDF), PHPmailer (para correos)

🗃️ Modelo de base de datos
El sistema se basa en cuatro tablas principales:

usuarios: datos personales y de acceso

grupo_gastos: contiene nombre, descripción y código del grupo

usuarios_grupos: relación N:M entre usuarios y grupos

gastos: información detallada de cada gasto realizado

📁 Estructura del proyecto
```
PAGA.ME/
        │
        ├── db/
        │   └── connection.php                  # Conexión a la base de datos
        │
        ├── img/
        │   └── logo.jpeg                       # Logotipo de la aplicación
        │
        ├── includes/
        │   ├── head.php                        # Cabecera HTML compartida
        │   ├── header.php                      # Menú superior
        │   └── app/footer.php                  # Pie de página común
        │
        ├── mail/
        │   ├── config.php                      # Configuración del envío de correos
        │   ├── enviar_mail_registro.php       # Mail de activación
        │   └── enviar_mail_reset.php          # Mail de recuperación
        │
        ├── pdf/
        │   ├── fpdf/                           # Librería FPDF
        │   └── exportar-grupo.php             # Exporta información del grupo a PDF
        │
        ├── php/                                # Scripts principales del backend
        │   ├── activate.php
        │   ├── crear-gasto.php
        │   ├── crear-grupo.php
        │   ├── get-grupo.php
        │   ├── get-grupos.php
        │   ├── leave-grupo.php
        │   ├── login.php
        │   ├── register.php
        │   ├── remember.php
        │   ├── reset_password.php
        │   ├── unirse-grupo.php
        │   └── update-profile.php
        │
        ├── styles/
        │   ├── styles.css                      # Estilos generales
        │   └── app.css                         # Estilos específicos de vistas
        │
        ├── views/
        │   ├── app/
        │   │   └── grupos.php                  # Página principal del usuario logueado
        │   ├── login.php
        │   ├── register.php
        │   ├── remember.php
        │   └── reset_password.php
        │
        ├── index.php                           # Página de inicio
        └── README.md                           # Este archivo
```
👩‍💻 Autora
Ana Belén García Milla
Este proyecto ha sido desarrollado como Trabajo de Fin de Ciclo para el título de Técnico Superior en Desarrollo de Aplicaciones Web.
