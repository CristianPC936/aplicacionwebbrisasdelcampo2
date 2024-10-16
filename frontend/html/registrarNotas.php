<?php
require_once '../../backend/verificar_sesion.php'; // Verificar la sesión
require_once '../../backend/verificar_acceso.php'; // Verificar el rol

// Verificar que el usuario sea docente
verificar_acceso('Docente');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brisas del Campo - Registrar Notas</title>
    <link rel="stylesheet" href="../css/registrarNotas.css">
</head>
<body>
    <header class="top-bar">
        <h1>Registro de Notas</h1>
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
                        <li><a href="verYEnviarBoletines.php">Listado y envío de boletines</a></li>
                    </ul>
                    <li>Gestión de Usuarios y Estudiantes</li>
                    <ul>
                        <li><a href="gestionEstudiantesDocente.php">Gestión de Estudiantes</a></li>
                    </ul>
                </ul>
            </nav>
            <!-- Botón para el cierre de sesión -->
            <div class="user-footer">
                <button id="toggleDropdown" class="user-footer-btn"><?php echo $_SESSION['nombreUsuario']; ?></button>
            </div>
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
                    </tbody>
                </table>

                <button class="save-button">Guardar</button>
            </div>
        </main>
    </div>
    <script src="../js/registrarNotas.js"></script> <!-- Incluimos el archivo JavaScript -->
    <script>
        const userFooterBtn = document.getElementById('toggleDropdown');
        
        // Función para hacer la transición suave
        function transitionButton(newText) {
            userFooterBtn.style.opacity = '0'; // Desaparecer el botón actual
            setTimeout(() => {
                userFooterBtn.textContent = newText; // Cambiar el texto
                userFooterBtn.style.opacity = '1'; // Mostrar el nuevo texto
            }, 300); // Tiempo de la transición de 0.3 segundos
        }

        // Mostrar "Cerrar sesión" en lugar del nombre de usuario
        userFooterBtn.addEventListener('click', function (event) {
            event.stopPropagation(); // Detener la propagación del evento para no activar el cierre inmediato
            if (userFooterBtn.textContent === 'Cerrar sesión') {
                window.location.href = '../../backend/logout.php'; // Redirigir al logout
            } else {
                transitionButton('Cerrar sesión');
            }
        });

        // Si el usuario hace clic en cualquier parte de la página, restaurar el nombre del usuario
        document.addEventListener('click', function () {
            if (userFooterBtn.textContent === 'Cerrar sesión') {
                transitionButton('<?php echo $_SESSION['nombreUsuario']; ?>');
            }
        });

        // Detener la propagación del evento cuando el usuario haga clic en el botón de usuario
        userFooterBtn.addEventListener('click', function (event) {
            event.stopPropagation();
        });
    </script>
</body>
</html>
