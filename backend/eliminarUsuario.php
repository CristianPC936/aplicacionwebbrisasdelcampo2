<?php
header('Content-Type: application/json');

$servername = "cristian.clceaygw4k9c.us-east-1.rds.amazonaws.com"; // Reemplaza por el endpoint de tu instancia de RDS
$username = "admin"; // Reemplaza con el usuario de la base de datos
$password = "uy2McxjaXinFXgF"; // Reemplaza con la contraseña de la base de datos
$dbname = "pruebaproyecto"; // El nombre de la base de datos que creaste en RDS
$port = 3306; // El puerto, normalmente es 3306 para MySQL

// Crear conexión
$connection = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar conexión
if ($connection->connect_error) {
    echo json_encode([
        'statusCode' => 500,
        'message' => 'Error en la conexión a la base de datos: ' . $connection->connect_error
    ]);
    exit();
}

// Obtener el cuerpo JSON de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

// Verificar que se haya enviado un idUsuario
if (!isset($data['idUsuario'])) {
    echo json_encode([
        'statusCode' => 400,
        'message' => 'El idUsuario es obligatorio'
    ]);
    exit();
}

$idUsuario = $data['idUsuario'];

// Consulta SQL para cambiar el estado a 0 (dar de baja al usuario)
$sql = "UPDATE Usuario SET estado = 0 WHERE idUsuario = ?";

$stmt = $connection->prepare($sql);
if (!$stmt) {
    echo json_encode([
        'statusCode' => 500,
        'message' => 'Error en la preparación de la consulta SQL: ' . $connection->error
    ]);
    exit();
}

$stmt->bind_param("i", $idUsuario);
$stmt->execute();

// Verificar si se afectó alguna fila
if ($stmt->affected_rows > 0) {
    echo json_encode([
        'statusCode' => 200,
        'message' => 'Usuario dado de baja exitosamente'
    ]);
} else {
    echo json_encode([
        'statusCode' => 404, // Cambiado a 404 ya que es más apropiado si no se encuentra el usuario
        'message' => 'Usuario no encontrado o ya dado de baja'
    ]);
}

// Cerrar la declaración y la conexión
$stmt->close();
$connection->close();
?>
