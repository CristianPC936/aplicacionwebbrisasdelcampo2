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
                        <li><a href="registrarAsistencia.php">Registrar</a></li>
                        <li><a href="listadoYEdicionAsistencia.php">Listar o Editar</a></li>
                    </ul>
                    <li>Gestión de Notas</li>
                    <ul>
                        <li><a href="registrarNotas.php">Registrar</a></li>
                        <li><a href="listadoYEdicionNotas.php">Listar o Editar</a></li>
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

            <div class="student-management">
                <div class="student-option">
                    <a href="registrarEstudiante.php"><img src="../resources/studentC.png" alt="Registrar Estudiante"></a>
                </div>
                <div class="student-option">
                    <a href="buscarEditarEliminarEstudianteDocente.php"><img src="../resources/studentRUD.png" alt="Buscar/Editar/Eliminar Estudiante"></a>
                </div>
            </div>
        </main>
    </div>
    <script src="../js/gestionEstudiantes.js"></script> <!-- Ruta actualizada -->
</body>
</html>
