<?php
// Configuración de la base de datos
$host = 'serveo.net';
$user = 'root';
$password = '';
$database = 'pruebaproyecto';
$port = 13306;

// Crear la conexión
$connection = new mysqli($host, $user, $password, $database, $port);

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
