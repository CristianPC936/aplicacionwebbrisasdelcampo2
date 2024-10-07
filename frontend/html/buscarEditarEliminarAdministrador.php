<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar, Editar y Dar de Baja a Usuarios</title>
    <link rel="stylesheet" href="../css/buscarEditarEliminarAdministrador.css">
</head>
<body>
    <header class="top-bar">
        <h1>Buscar, Editar y Dar de Baja a Usuarios</h1>
    </header>
    <div class="container">
        <aside class="sidebar">
            <img src="../resources/school_icon.png" alt="EORM Brisas del Campo">
            <h2>Brisas del Campo</h2>
            <nav>
                <ul>
                    <li>Gestión de Asistencia</li>
                    <ul>
                        <li><a href="registrarAsistencia.php">Registrar</a></li>
                        <li><a href="listadoYEdicionAsistencia.php">Listar o Editar</a></li>
                        <li><a href="reporteAsistencia.php">Generar reportes</a></li>
                    </ul>
                    <li>Gestión de Notas</li>
                    <ul>
                        <li><a href="registrarNotas.php">Registrar</a></li>
                        <li><a href="listadoYEdicionNotas.php">Listar o Editar</a></li>
                    </ul>
                    <li>Gestión de Usuarios y Estudiantes</li>
                    <ul>
                        <li><a href="gestionEstudiantes.php">Gestión de Estudiantes</a></li>
                        <li><a href="gestionDocentes.php">Gestión de Docentes</a></li>
                        <li><a href="gestionAdministradores.php">Gestión de Administradores</a></li>
                    </ul>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <button class="nav-toggle" id="nav-toggle">
                &#9776;
            </button>

            <!-- Tabla de usuarios -->
            <table class="attendance-table" id="usuariosTable">
                <thead>
                    <tr>
                        <th>Nombres y Apellidos</th>
                        <th>Nombre de Usuario</th>
                        <th>Rol</th>
                        <th>Editar</th>
                        <th>Dar de Baja</th>
                    </tr>
                </thead>
                <tbody id="usuariosTableBody">
                    <!-- Aquí se insertarán las filas con los usuarios -->
                </tbody>
            </table>
        </main>
    </div>

    <!-- Modal para editar usuario -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2>Editar Usuario</h2>
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

                <label for="edit-nombreUsuario">Nombre de Usuario:</label>
                <input type="text" id="edit-nombreUsuario" required>

                <label for="edit-contrasena">Contraseña:</label>
                <input type="password" id="edit-contrasena" placeholder="Dejar en blanco para no cambiarla">
                
                <button type="button" class="save-button">Guardar cambios</button>
                <button type="button" class="cancel-button">Cancelar</button>
            </form>
        </div>
    </div>

    <!-- Modal para eliminar usuario -->
<div id="deleteModal" class="modal">
    <div class="modal-content-delete">
        <h2>¿Desea dar de baja a este usuario?</h2>
        <div class="user-info">
        <input type="hidden" id="delete-idUsuario">
            <p><strong>Primer Nombre:</strong> <span id="delete-primerNombre"></span></p>
            <p><strong>Segundo Nombre:</strong> <span id="delete-segundoNombre"></span></p>
            <p><strong>Tercer Nombre:</strong> <span id="delete-tercerNombre"></span></p>
            <p><strong>Primer Apellido:</strong> <span id="delete-primerApellido"></span></p>
            <p><strong>Segundo Apellido:</strong> <span id="delete-segundoApellido"></span></p>
            <p><strong>Nombre de Usuario:</strong> <span id="delete-nombreUsuario"></span></p>
            <p><strong>Rol:</strong> <span id="delete-rolUsuario"></span></p>
        </div>
        <button class="cancel-button-delete">Cancelar</button>
        <button class="delete-button-confirm">Dar de Baja</button>
    </div>
</div>


    <script src="../js/buscarEditarEliminarAdministrador.js"></script>
</body>
</html>
