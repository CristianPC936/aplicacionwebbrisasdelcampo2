<?php
// Archivo: backend/config.php

$host = "serveo.net";
$user = "root";
$password = ""; // Contraseña vacía
$dbname = "pruebaproyecto";
$port = 13306;

$conn = new mysqli($host, $user, $password, $dbname, $port);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
