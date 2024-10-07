<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar, Editar y Dar de Baja a Administradores</title>
    <link rel="stylesheet" href="../css/buscarEditarEliminarAdministrador.css">
</head>
<body>
    <header class="top-bar">
        <h1>Buscar, Editar y Dar de Baja a Administradores</h1>
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

            <!-- Tabla de administradores -->
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Administrador</th>
                        <th></th> <!-- Columna sin nombre -->
                        <th></th> <!-- Columna sin nombre -->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Juan Pérez Rodríguez</td>
                        <td><button class="edit-button">Editar</button></td>
                        <td><button class="delete-button">Dar de Baja</button></td>
                    </tr>
                    <tr>
                        <td>María López García</td>
                        <td><button class="edit-button">Editar</button></td>
                        <td><button class="delete-button">Dar de Baja</button></td>
                    </tr>
                    <tr>
                        <td>Carlos Gómez Fernández</td>
                        <td><button class="edit-button">Editar</button></td>
                        <td><button class="delete-button">Dar de Baja</button></td>
                    </tr>
                </tbody>
            </table>
        </main>
    </div>
    <script src="../js/buscarEditarEliminarAdministrador.js"></script>
</body>
</html>