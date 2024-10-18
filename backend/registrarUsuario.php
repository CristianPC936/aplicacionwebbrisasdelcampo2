<?php
// Habilitar el acceso desde otros dominios (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$servername = "cristian.clceaygw4k9c.us-east-1.rds.amazonaws.com"; // Reemplaza por el endpoint de tu instancia de RDS
$username = "admin"; // Reemplaza con el usuario de la base de datos
$password = "uy2McxjaXinFXgF"; // Reemplaza con la contraseña de la base de datos
$dbname = "pruebaproyecto"; // El nombre de la base de datos que creaste en RDS
$port = 3306; // El puerto, normalmente es 3306 para MySQL

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    http_response_code(500); // Código HTTP 500 para error del servidor
    echo json_encode(["message" => "Conexión fallida: " . $conn->connect_error]);
    exit();
}

// Leer los datos enviados desde el frontend
$data = json_decode(file_get_contents("php://input"), true);

// Validar que se hayan recibido todos los datos necesarios
if (
    empty($data['primerNombre']) || empty($data['primerApellido']) ||
    empty($data['nombreUsuario']) || empty($data['contrasena']) || 
    empty($data['rolUsuario']) || empty($data['cicloEscolar']) || !isset($data['estado'])
) {
    http_response_code(400); // Código HTTP 400 para solicitud incorrecta
    echo json_encode(["message" => "Faltan campos obligatorios"]);
    exit();
}

// Función para convertir el primer carácter en mayúscula y el resto en minúscula
function formatNombre($nombre) {
    return ucwords(strtolower($nombre));
}

// Asignar variables a los datos recibidos y aplicar el formato de nombres
$primerNombre = formatNombre($data['primerNombre']);
$segundoNombre = !empty($data['segundoNombre']) ? formatNombre($data['segundoNombre']) : null;
$tercerNombre = !empty($data['tercerNombre']) ? formatNombre($data['tercerNombre']) : null;
$primerApellido = formatNombre($data['primerApellido']);
$segundoApellido = !empty($data['segundoApellido']) ? formatNombre($data['segundoApellido']) : null;
$nombreUsuario = $data['nombreUsuario'];
$contrasena = $data['contrasena']; // Contraseña sin hashear
$rolUsuario = $data['rolUsuario'];
$cicloEscolar = $data['cicloEscolar'];
$estado = $data['estado'];

// Validar si ya existe un usuario con el mismo primer nombre y primer apellido en el mismo ciclo escolar
$stmt = $conn->prepare("
    SELECT COUNT(*) as count FROM Usuario 
    WHERE primerNombre = ? AND primerApellido = ? AND cicloEscolar = ?
");
$stmt->bind_param("ssi", $primerNombre, $primerApellido, $cicloEscolar);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] > 0) {
    http_response_code(409); // Código HTTP 409 para conflicto (usuario ya registrado)
    echo json_encode(["message" => "Este usuario ya está registrado en el ciclo escolar actual"]);
    $stmt->close();
    $conn->close();
    exit();
}

// Validar si el nombre de usuario ya existe en la base de datos
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM Usuario WHERE nombreUsuario = ?");
$stmt->bind_param("s", $nombreUsuario);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] > 0) {
    http_response_code(409); // Código HTTP 409 para conflicto (nombre de usuario ya existe)
    echo json_encode(["message" => "El nombre de usuario ya existe"]);
    $stmt->close();
    $conn->close();
    exit();
}

// Hashear la contraseña utilizando password_hash
$hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);

// Preparar la consulta SQL para insertar el usuario en la base de datos
$stmt = $conn->prepare("
    INSERT INTO Usuario 
    (primerNombre, segundoNombre, tercerNombre, primerApellido, segundoApellido, nombreUsuario, contrasenia, idRol, cicloEscolar, estado)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    http_response_code(500); // Código HTTP 500 para error del servidor
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
    http_response_code(500); // Código HTTP 500 para error del servidor
    echo json_encode(["message" => "Error al registrar el usuario: " . $stmt->error]);
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conn->close();
?>
