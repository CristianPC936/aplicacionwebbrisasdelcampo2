<?php
// Archivo: backend/verificar_acceso.php

// Comprobar si la sesión no está ya iniciada antes de iniciarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function verificar_acceso($rol_requerido) {
    if (!isset($_SESSION['nombreRol'])) {
        // Si no hay un rol en la sesión, redirigir al login
        header("Location: ../../frontend/html/login.php");
        exit();
    }

    // Verificar si el rol del usuario coincide con el rol requerido
    if ($_SESSION['nombreRol'] !== $rol_requerido) {
        // Si el usuario no tiene el rol adecuado, redirigir a la página de error
        header("Location: ../../frontend/html/error_acceso.php");
        exit();
    }
}
?>
