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
    <title>Generar Reporte de Promedios</title>
    <link rel="stylesheet" href="../css/reportePromedios.css">
</head>
<body>
    <header class="top-bar">
        <h1>Generar Reporte de Promedios</h1>
    </header>
    <div class="container">
        <aside class="sidebar">
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

            <!-- Espacio para la selección de parámetros -->
            <div class="parameters-container">
                <div class="parameter">
                    <label for="grade">Grado:</label>
                    <select id="grade">
                        <!-- Agregar más opciones según sea necesario -->
                    </select>
                </div>

                <div class="parameter">
                    <label for="section">Sección:</label>
                    <select id="section">
                        <!-- Agregar más opciones según sea necesario -->
                    </select>
                </div>

                <div class="parameter">
                    <label for="bimester">Bimestre:</label>
                    <input type="number" id="bimester" value="1" min="1" max="4">
                </div>

                <!-- Botón para generar el reporte -->
                <button class="generate-button">Generar Reporte</button>
            </div>
        </main>
    </div>
    <script src="../js/reportePromedios.js"></script>
</body>
</html>
