<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brisas del Campo - Gestión de Estudiantes</title>
    <link rel="stylesheet" href="../css/gestionEstudiantes.css">
</head>
<body>
    <header class="top-bar">
        <h1>Gestión de Estudiantes</h1>
    </header>
    <div class="container">
    <aside class="sidebar">
            <img src="../resources/school_icon.png" alt="EORM Brisas del Campo">
            <h2>Brisas del Campo</h2>
            <nav>
                <ul>
                    <li>Gestión de Asistencia</li>
                    <ul>
                        <li><a href="reporteAsistencia.php">Generar reportes</a></li>
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

            <div class="student-management">
                <div class="student-option">
                    <a href="buscarEditarEliminarEstudianteAdmin.php"><img src="../resources/studentRUD.png" alt="Buscar/Editar/Eliminar Estudiante"></a>
                </div>
            </div>
        </main>
    </div>
    <script src="../js/gestionEstudiantes.js"></script> <!-- Ruta actualizada -->
</body>
</html>
