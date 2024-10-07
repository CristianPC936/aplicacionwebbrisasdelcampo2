<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brisas del Campo - Gestión de Administradores</title>
    <link rel="stylesheet" href="../css/gestionAdministradores.css">
</head>
<body>
    <header class="top-bar">
        <h1>Gestión de Administradores</h1>
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
                    <li>Gestión de Usuarios y Docentes</li>
                    <ul>
                        <li><a href="gestionEstudiantes.php">Gestión de Estudiantes</a></li>
                        <li><a href="gestionDocentes.php">Gestión de Docentes</a></li>
                        <li><a href="gestionAdministradores.php">Gestión de Administradores</a></li>
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
                    <a href="registrarAdministrador.php"><img src="../resources/adminC.png" alt="Registrar Administrador"></a>
                </div>
                <div class="admin-option">
                    <a href="buscarEditarEliminarAdministrador.php"><img src="../resources/adminRUD.png" alt="Buscar/Editar/Eliminar Administrador"></a>
                </div>
            </div>
        </main>
    </div>
    <script src="../js/gestionAdministradores.js"></script> <!-- Ruta actualizada -->
</body>
</html>
