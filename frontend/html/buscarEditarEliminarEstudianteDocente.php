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
    <title>Buscar, Editar y Dar de Baja a Estudiantes</title>
    <link rel="stylesheet" href="../css/buscarEditarEliminarEstudiante.css">
</head>
<body>
    <header class="top-bar">
        <h1>Buscar, Editar y Dar de Baja a Estudiantes</h1>
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
                <div class="parameter">
                    <label for="grade">Grado:</label>
                    <select id="grade">
                        <!-- Opciones dinámicas -->
                    </select>
                </div>

                <div class="parameter">
                    <label for="section">Sección:</label>
                    <select id="section">
                        <!-- Opciones dinámicas -->
                    </select>
                </div>

                <!-- Botón para buscar estudiantes -->
                <button class="search-button">Buscar Estudiantes</button>
            </div>

            <!-- Tabla de estudiantes -->
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Correo</th>
                        <th>Clave</th>
                        <th></th> <!-- Columna para editar -->
                        <th></th> <!-- Columna para dar de baja -->
                    </tr>
                </thead>
                <tbody>
                    <!-- Filas generadas dinámicamente -->
                </tbody>
            </table>
        </main>
    </div>

    <!-- Ventana emergente (modal) para editar estudiante -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2>Editar Estudiante</h2>
            <form id="edit-form">
                <label for="edit-primerNombre">Primer Nombre:</label>
                <input type="text" id="edit-primerNombre" required>
                
                <label for="edit-segundoNombre">Segundo Nombre:</label>
                <input type="text" id="edit-segundoNombre">
                
                <label for="edit-tercerNombre">Tercer Nombre:</label>
                <input type="text" id="edit-tercerNombre">
                
                <label for="edit-primerApellido">Primer Apellido:</label>
                <input type="text" id="edit-primerApellido" required>
                
                <label for="edit-segundoApellido">Segundo Apellido:</label>
                <input type="text" id="edit-segundoApellido">
                
                <label for="edit-correoElectronico">Correo Electrónico:</label>
                <input type="email" id="edit-correoElectronico" required>

                <button type="button" class="cancel-button">Cancelar</button>
                <button type="button" class="save-button">Guardar cambios</button>
            </form>
        </div>
    </div>

    <!-- Nuevo modal para eliminar estudiante -->
    <div id="deleteModal" class="modal">
        <div class="modal-content-delete">
            <h2>¿Desea eliminar este estudiante?</h2>
            <div class="student-info">
                <p><strong>Primer Nombre:</strong> <span id="delete-primerNombre"></span></p>
                <p><strong>Segundo Nombre:</strong> <span id="delete-segundoNombre"></span></p>
                <p><strong>Tercer Nombre:</strong> <span id="delete-tercerNombre"></span></p>
                <p><strong>Primer Apellido:</strong> <span id="delete-primerApellido"></span></p>
                <p><strong>Segundo Apellido:</strong> <span id="delete-segundoApellido"></span></p>
                <p><strong>Correo Electrónico:</strong> <span id="delete-correoElectronico"></span></p>
                <p><strong>Clave:</strong> <span id="delete-claveAlumno"></span></p>
            </div>
            <button class="cancel-button-delete">Cancelar</button>
            <button class="delete-button-confirm">Eliminar</button>
        </div>
    </div>

    <script src="../js/buscarEditarEliminarEstudiante.js"></script>
</body>
</html>
