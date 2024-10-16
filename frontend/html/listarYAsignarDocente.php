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
    <title>Asignar a Docentes</title>
    <link rel="stylesheet" href="../css/listarYAsignarDocente.css">
</head>
<body>
    <header class="top-bar">
        <h1>Asignar a Docentes</h1>
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
            <button class="nav-toggle" id="nav-toggle">
                &#9776;
            </button>

            <!-- Espacio para la selección de parámetros -->
            <div class="parameters-container">
                <div class="parameter">
                    <label for="search-options">Opciones de búsqueda:</label>
                    <select id="search-options">
                        <option value="asignados">Buscar docentes asignados</option>
                        <option value="noAsignados">Buscar docentes no asignados</option>
                    </select>
                </div>

                <!-- Botón para buscar docentes -->
                <button class="search-button">Buscar Docentes</button>
            </div>

            <!-- Tabla de docentes -->
            <table class="attendance-table" id="docentesTable">
                <thead>
                    <tr>
                        <th>Nombres y Apellidos</th>
                        <th>Nombre de Usuario</th>
                        <th>Rol</th>
                        <th>Editar</th>
                        <th>Dar de Baja</th>
                    </tr>
                </thead>
                <tbody id="docentesTableBody">
                    <!-- Aquí se insertarán las filas con los docentes -->
                </tbody>
            </table>
        </main>
    </div>

    <!-- Modal para asignar docente -->
    <div id="assignModal" class="modal">
        <div class="modal-content-delete">
            <h2>Asignar Grado y Sección</h2>
            <div class="user-info">
                <p><strong>Primer Nombre:</strong> <span id="assign-primerNombre"></span></p>
                <p><strong>Segundo Nombre:</strong> <span id="assign-segundoNombre"></span></p>
                <p><strong>Tercer Nombre:</strong> <span id="assign-tercerNombre"></span></p>
                <p><strong>Primer Apellido:</strong> <span id="assign-primerApellido"></span></p>
                <p><strong>Segundo Apellido:</strong> <span id="assign-segundoApellido"></span></p>
                <p><strong>Nombre de Usuario:</strong> <span id="assign-nombreUsuario"></span></p>
            </div>

            <!-- Div contenedor para ambos select -->
            <div class="select-group">
                <div class="select-container">
                    <label for="grade">Grado:</label>
                    <select id="grade" class="styled-select"></select>
                </div>

                <div class="select-container">
                    <label for="section">Sección:</label>
                    <select id="section" class="styled-select"></select>
                </div>
            </div>

            <!-- Botones para asignar o cancelar -->
            <div class="button-container">
                <button class="cancel-button-assign search-button" onclick="closeAssignModal()">Cancelar</button>
                <button class="assign-button-confirm search-button">Asignar Docente</button>
            </div>
        </div>
    </div>

    <script src="../js/listarYAsignarDocente.js"></script>
</body>
</html>
