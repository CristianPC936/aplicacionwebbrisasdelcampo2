<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brisas del Campo - Registrar Estudiante</title>
    <link rel="stylesheet" href="../css/registrarEstudiante.css">
</head>
<body>
    <header class="top-bar">
        <h1>Registrar Estudiante</h1>
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
            <button class="nav-toggle" id="nav-toggle">&#9776;</button>
            <form class="register-form">
                <div class="input-group">
                    <label for="primerNombre">Primer Nombre:</label>
                    <input type="text" id="primerNombre" name="primerNombre" required>
                </div>
                <div class="input-group">
                    <label for="segundoNombre">Segundo Nombre:</label>
                    <input type="text" id="segundoNombre" name="segundoNombre">
                </div>
                <div class="input-group">
                    <label for="tercerNombre">Tercer Nombre:</label>
                    <input type="text" id="tercerNombre" name="tercerNombre">
                </div>
                <div class="input-group">
                    <label for="primerApellido">Primer Apellido:</label>
                    <input type="text" id="primerApellido" name="primerApellido" required>
                </div>
                <div class="input-group">
                    <label for="segundoApellido">Segundo Apellido:</label>
                    <input type="text" id="segundoApellido" name="segundoApellido">
                </div>
                <div class="input-group">
                    <label for="grado">Grado:</label>
                    <select id="grado" name="grado">
                    </select>
                </div>
                <div class="input-group">
                    <label for="seccion">Sección:</label>
                    <select id="seccion" name="seccion">
                    </select>
                </div>
                <div class="input-group">
                    <label for="clave">Clave:</label>
                    <input type="text" id="clave" name="clave" required>
                </div>
                <div class="input-group">
                    <label for="correoElectronico">Correo Electrónico:</label>
                    <input type="email" id="correoElectronico" name="correoElectronico" required>
                </div>
                <button class="register-button" type="submit">Registrar</button>
            </form>
        </main>
    </div>
    <script src="../js/registrarEstudiante.js"></script>
</body>
</html>
