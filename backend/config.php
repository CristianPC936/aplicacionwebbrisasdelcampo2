<?php
// Configuración de la base de datos

$servername = "cristian.clceaygw4k9c.us-east-1.rds.amazonaws.com"; // Reemplaza por el endpoint de tu instancia de RDS
$username = "admin"; // Reemplaza con el usuario de la base de datos
$password = "uy2McxjaXinFXgF"; // Reemplaza con la contraseña de la base de datos
$dbname = "pruebaproyecto"; // El nombre de la base de datos que creaste en RDS
$port = 3306; // El puerto, normalmente es 3306 para MySQL

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}



