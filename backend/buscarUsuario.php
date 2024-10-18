<?php
$servername = "cristian.clceaygw4k9c.us-east-1.rds.amazonaws.com"; // Reemplaza por el endpoint de tu instancia de RDS
$username = "admin"; // Reemplaza con el usuario de la base de datos
$password = "uy2McxjaXinFXgF"; // Reemplaza con la contraseña de la base de datos
$dbname = "pruebaproyecto"; // El nombre de la base de datos que creaste en RDS
$port = 3306; // El puerto, normalmente es 3306 para MySQL

// Crear conexión
$connection = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar conexión
if ($connection->connect_error) {
    die(json_encode([
        'statusCode' => 500,
        'message' => 'Error en la conexión a la base de datos: ' . $connection->connect_error
    ]));
}

// Verificar que el ciclo escolar esté presente
$cicloEscolar = $_GET['cicloEscolar'] ?? null;
if (!$cicloEscolar) {
    die(json_encode([
        'statusCode' => 400,
        'message' => 'El parámetro cicloEscolar es obligatorio'
    ]));
}

// Consulta SQL para obtener los usuarios activos del ciclo escolar
$sql = "SELECT u.idUsuario, u.primerNombre, u.segundoNombre, u.tercerNombre, 
        u.primerApellido, u.segundoApellido, u.nombreUsuario, r.nombreRol 
        FROM Usuario u 
        INNER JOIN Rol r ON u.idRol = r.idRol
        WHERE u.cicloEscolar = ? AND u.estado = 1
        ORDER BY r.idRol, u.primerNombre";

$stmt = $connection->prepare($sql);
if (!$stmt) {
    die(json_encode([
        'statusCode' => 500,
        'message' => 'Error en la preparación de la consulta SQL: ' . $connection->error
    ]));
}

$stmt->bind_param("i", $cicloEscolar);
$stmt->execute();
$result = $stmt->get_result();

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

$stmt->close();
$connection->close();

// Enviar la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($usuarios);
?>
