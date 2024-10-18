<?php
require_once '../../backend/verificar_sesion.php'; // Verificar la sesión
require_once '../../backend/verificar_acceso.php'; // Verificar el rol

// Verificar que el usuario sea administrador
verificar_acceso('Administrador');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador - Brisas del Campo</title>
    <link rel="stylesheet" href="../css/main_screen.css">
</head>
<body>
    <header class="top-bar">
        <h1>Bienvenido/a al Sistema - Administrador</h1>
    </header>
    <div class="container">
        <aside class="sidebar">
            <!-- Botón para el cierre de sesión -->
            <div class="user-footer">
                <button id="toggleDropdown" class="user-footer-btn" data-username="<?php echo $_SESSION['nombreUsuario']; ?>">
                    <div class="user-img-container">
                        <img src="../resources/userIMG.png" alt="Icono de usuario">
                    </div>
                    <span class="user-text"><?php echo $_SESSION['nombreUsuario']; ?></span>
                </button>
            </div>
            <img src="../resources/school_icon.png" alt="EORM Brisas del Campo">
            <h2>Brisas del Campo</h2>
            <nav>
                <ul>
                    <li>Generación de reportes</li>
                    <ul>
                        <li><a href="reporteAsistencia.php">Reporte de Asistencia</a></li>
                        <li><a href="reportePromedios.php">Reporte de Promedios</a></li>
                    </ul>
                    <li>Gestión de Usuarios y Estudiantes</li>
                    <ul>
                        <li><a href="gestionEstudiantesAdmin.php">Gestión de Estudiantes</a></li>
                        <li><a href="gestionUsuarios.php">Gestión de Usuarios</a></li>  
                    </ul>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <!-- Botón de navegación -->
            <button class="nav-toggle" id="nav-toggle">
                &#9776;
            </button>
            <div class="welcome-message">
                <p>
                    Estamos encantados de tenerte con nosotros en esta innovadora plataforma educativa. Esta herramienta ha sido cuidadosamente diseñada para simplificar y optimizar las tareas diarias de nuestra institución, asegurando que cada proceso se lleve a cabo de manera más eficiente. Como profesor, descubrirás una amplia gama de funciones intuitivas y fáciles de usar que te facilitarán la gestión de la asistencia y las notas de los alumnos. Además, hemos incorporado medidas de seguridad avanzadas para garantizar que toda la información se maneje de manera segura y confiable. Con esta herramienta, podrás administrar los datos de los estudiantes de manera precisa y efectiva, permitiéndote dedicar más tiempo a enriquecer la experiencia educativa.
                </p>
            </div>
        </main>
    </div>
    <script src="../js/main_screen.js"></script>
</body>
</html>
