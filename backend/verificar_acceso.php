<?php
// Archivo: backend/verificar_acceso.php

session_start();

function verificar_acceso($rol_requerido) {
    if (!isset($_SESSION['nombreRol'])) {
        // Si no hay un rol en la sesión, redirigir al login
        header("Location: ../frontend/html/login.php");
        exit();
    }

    // Verificar si el rol del usuario coincide con el rol requerido
    if ($_SESSION['nombreRol'] !== $rol_requerido) {
        // Si el usuario no tiene el rol adecuado, redirigir a una página de error o al login
        header("Location: ../frontend/html/login.php");
        exit();
    }
}
?>
