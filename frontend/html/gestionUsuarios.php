<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brisas del Campo - Gestión de Usuarios</title>
    <link rel="stylesheet" href="../css/gestionUsuarios.css">
</head>
<body>
    <header class="top-bar">
        <h1>Gestión de Usuarios</h1>
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

            <div class="admin-management">
                <div class="admin-option">
                    <a href="registrarUsuario.php"><img src="../resources/userC.png" alt="Registrar Administrador"></a>
                </div>
                <div class="admin-option">
                    <a href="buscarEditarEliminarUsuario.php"><img src="../resources/userRUD.png" alt="Buscar/Editar/Eliminar Usuario"></a>
                </div>
                <div class="admin-option">
                    <a href="listarYAsignarDocente.php"><img src="../resources/teacherRU.png" alt="listar y asignar docente"></a>
                </div>
            </div>
        </main>
    </div>
    <script src="../js/gestionUsuarios.js"></script> <!-- Ruta actualizada -->
</body>
</html>
