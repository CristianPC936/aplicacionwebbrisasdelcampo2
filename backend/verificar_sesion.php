<?php
// Archivo: backend/verificar_sesion.php

session_start();

// Definir tiempos
$inactividad_maxima = 30; // 15 minutos de inactividad
$duracion_maxima_sesion = 60; // 1 hora de duración total de la sesión

// Verificar si existe la variable de último acceso
if (isset($_SESSION['ultimo_acceso'])) {
    // Calcular el tiempo inactivo
    $tiempo_inactivo = time() - $_SESSION['ultimo_acceso'];

    // Si el tiempo inactivo supera el límite permitido
    if ($tiempo_inactivo > $inactividad_maxima) {
        session_unset(); // Limpiar la sesión
        session_destroy(); // Destruir la sesión
        header("Location: ../../frontend/html/login.php?error=sesion_expirada"); // Redirigir al login
        exit();
    }
}

// Actualizar el tiempo de último acceso
$_SESSION['ultimo_acceso'] = time();

// Verificar si la duración total de la sesión ha superado el tiempo permitido
if (isset($_SESSION['inicio_sesion']) && (time() - $_SESSION['inicio_sesion'] > $duracion_maxima_sesion)) {
    session_unset(); // Limpiar la sesión
    session_destroy(); // Destruir la sesión
    header("Location: ../../frontend/html/login.php?error=sesion_expirada"); // Redirigir al login
    exit();
}
?>
