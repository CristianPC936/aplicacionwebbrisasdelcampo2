<?php
// Habilitar el acceso desde otros dominios (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

// Conectar a la base de datos a través de Serveo
$servername = "serveo.net"; // Usar el servicio de túnel Serveo
$username = "root";
$password = "";
$dbname = "pruebaproyecto";
$port = 13306; // Puerto utilizado en el túnel

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["message" => "Conexión fallida: " . $conn->connect_error]));
}

// Leer los datos enviados desde el frontend
$data = json_decode(file_get_contents("php://input"), true);

// Validar que se hayan recibido todos los datos necesarios
if (
    empty($data['primerNombre']) || empty($data['primerApellido']) ||
    empty($data['nombreUsuario']) || empty($data['contrasena']) || 
    empty($data['rolUsuario']) || empty($data['cicloEscolar']) || !isset($data['estado'])
) {
    echo json_encode(["message" => "Faltan campos obligatorios"]);
    exit();
}

// Asignar variables a los datos recibidos
$primerNombre = $data['primerNombre'];
$segundoNombre = !empty($data['segundoNombre']) ? $data['segundoNombre'] : null;
$tercerNombre = !empty($data['tercerNombre']) ? $data['tercerNombre'] : null;
$primerApellido = $data['primerApellido'];
$segundoApellido = !empty($data['segundoApellido']) ? $data['segundoApellido'] : null;
$nombreUsuario = $data['nombreUsuario'];
$contrasena = $data['contrasena']; // Contraseña sin hashear
$rolUsuario = $data['rolUsuario'];
$cicloEscolar = $data['cicloEscolar'];
$estado = $data['estado'];

// Hashear la contraseña utilizando password_hash
$hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);

// Preparar la consulta SQL para insertar el usuario en la base de datos
$stmt = $conn->prepare("
    INSERT INTO Usuario 
    (primerNombre, segundoNombre, tercerNombre, primerApellido, segundoApellido, nombreUsuario, contrasenia, idRol, cicloEscolar, estado)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    echo json_encode(["message" => "Error en la consulta: " . $conn->error]);
    exit();
}

// Vincular los parámetros a la consulta
$stmt->bind_param(
    "sssssssiii", 
    $primerNombre, $segundoNombre, $tercerNombre, $primerApellido, 
    $segundoApellido, $nombreUsuario, $hashedPassword, $rolUsuario, 
    $cicloEscolar, $estado
);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo json_encode(["message" => "Usuario registrado exitosamente"]);
} else {
    echo json_encode(["message" => "Error al registrar el usuario: " . $stmt->error]);
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conn->close();
?>
