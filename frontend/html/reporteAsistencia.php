<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Reportes de Asistencia</title>
    <link rel="stylesheet" href="../css/reporteAsistencia.css">
</head>
<body>
    <header class="top-bar">
        <h1>Generar Reportes de Asistencia</h1>
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
            <!-- Botón de navegación -->
            <button class="nav-toggle" id="nav-toggle">
                &#9776;
            </button>

            <!-- Espacio para la selección de parámetros -->
            <div class="parameters-container">
                <div class="parameter">
                    <label for="grade">Grado:</label>
                    <select id="grade">
                        <option>Tercero</option>
                        <!-- Agregar más opciones según sea necesario -->
                    </select>
                </div>

                <div class="parameter">
                    <label for="section">Sección:</label>
                    <select id="section">
                        <option>A</option>
                        <!-- Agregar más opciones según sea necesario -->
                    </select>
                </div>

                <div class="parameter">
                    <label for="from">Desde:</label>
                    <input type="date" id="from" value="2024-05-01">
                </div>

                <div class="parameter">
                    <label for="to">Hasta:</label>
                    <input type="date" id="to" value="2024-05-31">
                </div>

                <!-- Botón para generar el reporte -->
                <button class="generate-button">Generar Reporte</button>
            </div>
        </main>
    </div>
    <script src="../js/reporteAsistencia.js"></script>
</body>
</html>