<?php
// Archivo: backend/autenticacion.php

session_start();
require_once 'config.php'; // Incluir la conexión a la base de datos

// Definir el límite de intentos fallidos y el tiempo de bloqueo
$limite_intentos = 5;
$tiempo_bloqueo = 300; // 300 segundos = 5 minutos

// Verificar si la sesión tiene un contador de intentos
if (!isset($_SESSION['intentos_fallidos'])) {
    $_SESSION['intentos_fallidos'] = 0;
    $_SESSION['ultimo_intento'] = time();
}

// Verificar si el usuario está bloqueado
if ($_SESSION['intentos_fallidos'] >= $limite_intentos) {
    $tiempo_restante = time() - $_SESSION['ultimo_intento'];

    if ($tiempo_restante < $tiempo_bloqueo) {
        $error = "Cuenta bloqueada temporalmente. Inténtelo de nuevo en " . ($tiempo_bloqueo - $tiempo_restante) . " segundos.";
        header("Location: ../frontend/html/login.php?error=bloqueo");
        exit();
    } else {
        // Reiniciar el contador después de que pase el tiempo de bloqueo
        $_SESSION['intentos_fallidos'] = 0;
    }
}

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreUsuario = $_POST['nombreUsuario'];
    $contrasenia = $_POST['password'];

    // Preparar la consulta SQL para validar el usuario
    $sql = "SELECT U.idUsuario, U.nombreUsuario, U.contrasenia, U.idRol, R.nombreRol 
            FROM Usuario U
            JOIN Rol R ON U.idRol = R.idRol
            WHERE U.nombreUsuario = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nombreUsuario);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si el usuario existe
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($contrasenia, $row['contrasenia'])) {
            // Restablecer el contador de intentos fallidos
            $_SESSION['intentos_fallidos'] = 0;

            // Almacenar datos en la sesión
            $_SESSION['idUsuario'] = $row['idUsuario'];
            $_SESSION['nombreUsuario'] = $row['nombreUsuario'];
            $_SESSION['nombreRol'] = $row['nombreRol'];

            // Si es docente, obtener grados y secciones
            if ($row['nombreRol'] == 'docente') {
                $sqlGradoSeccion = "SELECT G.nombreGrado, S.nombreSeccion 
                                    FROM Grado_has_Usuario_has_Seccion GUHS
                                    JOIN Grado G ON GUHS.idGrado = G.idGrado
                                    JOIN Seccion S ON GUHS.idSeccion = S.idSeccion
                                    WHERE GUHS.idUsuario = ?";
                $stmtGradoSeccion = $conn->prepare($sqlGradoSeccion);
                $stmtGradoSeccion->bind_param("i", $row['idUsuario']);
                $stmtGradoSeccion->execute();
                $resultGradoSeccion = $stmtGradoSeccion->get_result();

                // Almacenar grados y secciones en sesión
                $_SESSION['gradosSecciones'] = [];
                while ($gradoSeccion = $resultGradoSeccion->fetch_assoc()) {
                    $_SESSION['gradosSecciones'][] = [
                        'nombreGrado' => $gradoSeccion['nombreGrado'],
                        'nombreSeccion' => $gradoSeccion['nombreSeccion']
                    ];
                }
            }

            // Duración de la sesión: 1 hora
            $_SESSION['inicio_sesion'] = time();

            // Regenerar ID de sesión para mayor seguridad
            session_regenerate_id(true);

            // Redirigir al dashboard correspondiente según el rol
            if ($row['nombreRol'] == 'administrador') {
                header("Location: ../frontend/html/dashboard_admin.php");
                exit();
            } elseif ($row['nombreRol'] == 'docente') {
                header("Location: ../frontend/html/dashboard_docente.php");
                exit();
            }
        } else {
            // Contraseña incorrecta, aumentar contador de intentos fallidos
            $_SESSION['intentos_fallidos'] += 1;
            $_SESSION['ultimo_intento'] = time();

            if ($_SESSION['intentos_fallidos'] >= $limite_intentos) {
                $error = "Cuenta bloqueada temporalmente. Inténtelo de nuevo en 5 minutos.";
            } else {
                $error = "Credenciales incorrectas. Intento " . $_SESSION['intentos_fallidos'] . " de 5.";
            }
        }
    } else {
        // Usuario no encontrado
        $error = "Credenciales incorrectas.";
    }

    $stmt->close();
}
$conn->close();

// Control de duración y expiración de la sesión
$inactividad_maxima = 900; // 900 segundos = 15 minutos de inactividad

if (isset($_SESSION['ultimo_acceso'])) {
    $tiempo_inactivo = time() - $_SESSION['ultimo_acceso'];

    if ($tiempo_inactivo > $inactividad_maxima) {
        session_unset(); // Limpiar la sesión
        session_destroy(); // Destruir la sesión
        header("Location: ../frontend/html/login.php?error=sesion_expirada");
        exit();
    }
}

$_SESSION['ultimo_acceso'] = time(); // Actualizar el último acceso

// Verificar si la sesión ha durado más de 1 hora
if (isset($_SESSION['inicio_sesion']) && (time() - $_SESSION['inicio_sesion'] > 3600)) {
    session_unset(); // Limpiar la sesión
    session_destroy(); // Destruir la sesión
    header("Location: ../frontend/html/login.php?error=sesion_expirada");
    exit();
}

// Redirigir al formulario de login con un mensaje de error
header("Location: ../frontend/html/login.php?error=true");
exit();
?>
