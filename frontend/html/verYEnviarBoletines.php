<?php
require_once '../../backend/verificar_sesion.php'; // Verificar la sesión
require_once '../../backend/verificar_acceso.php'; // Verificar el rol

// Verificar que el usuario sea docente
verificar_acceso('Docente');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brisas del Campo - Ver y Enviar Boletines</title>
    <link rel="stylesheet" href="../css/registrarAsistencia.css">
</head>
<body>
    <header class="top-bar">
        <h1>Listar y Enviar Boletines</h1>
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
                    <li>Gestión de Asistencia</li>
                    <ul>
                        <li><a href="registrarAsistencia.php">Registrar</a></li>
                        <li><a href="listadoYEdicionAsistencia.php">Listar o Editar</a></li>
                    </ul>
                    <li>Gestión de Notas</li>
                    <ul>
                        <li><a href="registrarNotas.php">Registrar</a></li>
                        <li><a href="listadoYEdicionNotas.php">Listar o Editar</a></li>
                        <li><a href="verYEnviarBoletines.php">Listado y envío de boletines</a></li>
                    </ul>
                    <li>Gestión de Usuarios y Estudiantes</li>
                    <ul>
                        <li><a href="gestionEstudiantesDocente.php">Gestión de Estudiantes</a></li>
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
                <label for="grade">Grado:</label>
                <select id="grade">
                    <!-- Agregar más opciones según sea necesario -->
                </select>

                <label for="section">Sección:</label>
                <select id="section">
                    <!-- Agregar más opciones según sea necesario -->
                </select>

                <label for="student">Estudiante:</label>
                <select id="student">
                    <!-- Agregar más opciones según sea necesario -->
                </select>

                <button class="search-button">Buscar</button>
            </div>

            <!-- Espacio para la tabla de boletines y botón de enviar -->
            <div class="attendance-form">
                <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Curso</th>
                        <th>Bimestre 1</th>
                        <th>Bimestre 2</th>
                        <th>Bimestre 3</th>
                        <th>Bimestre 4</th>
                    </tr>
                </thead>
                    <tbody>
                    </tbody>
                </table>

                <button class="save-button">Enviar Boletín</button>
            </div>
        </main>
    </div>
    <script src="../js/verYEnviarBoletines.js"></script> <!-- Incluimos el archivo JavaScript -->
</body>
</html>
