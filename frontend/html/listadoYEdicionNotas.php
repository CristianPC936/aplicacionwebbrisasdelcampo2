<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brisas del Campo - Listado y Edición de Notas</title>
    <link rel="stylesheet" href="../css/listadoYEdicionNotas.css">
</head>
<body>
    <header class="top-bar">
        <h1>Listado y Edición de Notas</h1>
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

                <label for="grade">Grado:</label>
                <select id="grade">
                    <!-- Agregar más opciones según sea necesario -->
                </select>

                <label for="section">Sección:</label>
                <select id="section">
                    <!-- Agregar más opciones según sea necesario -->
                </select>
                <label for="course">Curso:</label>
                <select id="course">
                    <!-- Agregar más opciones según sea necesario -->
                </select>

                <label for="bimester">Bimestre:</label>
                <input type = "text" id="bimester">
                    <!-- Agregar más opciones según sea necesario -->
                </select>

                <button class="search-button">Buscar</button>
            </div>

            <!-- Espacio para la tabla de notas y botón de guardar -->
            <div class="attendance-form">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th>Estudiantes</th>
                            <th>Clave</th>
                            <th>Nota Bimestral</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Diego Alejandro Ruiz Soto</td>
                            <td><input type="text"></td>
                        </tr>
                        <tr>
                            <td>María Fernanda Castillo Pérez</td>
                            <td><input type="text"></td>
                        </tr>
                        <tr>
                            <td>José Miguel Álvarez Fuentes</td>
                            <td><input type="text"></td>
                        </tr>
                        <tr>
                            <td>Sofía Gabriela López Cáceres</td>
                            <td><input type="text"></td>
                        </tr>
                        <tr>
                            <td>Carlos Esteban Morales Cáceres</td>
                            <td><input type="text"></td>
                        </tr>
                        <tr>
                            <td>Ana Lucía Gómez Martínez</td>
                            <td><input type="text"></td>
                        </tr>
                        <tr>
                            <td>Manuel Eduardo Navarro Barrios</td>
                            <td><input type="text"></td>
                        </tr>
                    </tbody>
                </table>

                <button class="save-button">Guardar</button>
            </div>
        </main>
    </div>
    <script src="../js/listadoYEdicionNotas.js"></script> <!-- Incluimos el archivo JavaScript -->
</body>
</html>