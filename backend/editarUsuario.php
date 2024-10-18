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

// Obtener el cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

// Validaciones del lado del servidor
$required_fields = ['idUsuario', 'primerNombre', 'primerApellido', 'nombreUsuario'];
foreach ($required_fields as $field) {
    if (empty($data[$field])) {
        die(json_encode([
            'statusCode' => 400,
            'message' => "El campo $field es obligatorio."
        ]));
    }
}

// Expresión regular para validar nombres y apellidos (solo letras, tildes y espacios)
$nombreRegex = "/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/";
$validarNombre = function($nombre) use ($nombreRegex) {
    return preg_match($nombreRegex, $nombre);
};

// Validar nombres y apellidos
if (!($validarNombre($data['primerNombre']) && $validarNombre($data['primerApellido']))) {
    die(json_encode([
        'statusCode' => 400,
        'message' => 'Los nombres y apellidos solo deben contener letras del abecedario español.'
    ]));
}

// Actualización del usuario en la base de datos
$idUsuario = $connection->real_escape_string($data['idUsuario']);
$primerNombre = $connection->real_escape_string($data['primerNombre']);
$segundoNombre = $connection->real_escape_string($data['segundoNombre'] ?? '');
$tercerNombre = $connection->real_escape_string($data['tercerNombre'] ?? '');
$primerApellido = $connection->real_escape_string($data['primerApellido']);
$segundoApellido = $connection->real_escape_string($data['segundoApellido'] ?? '');
$nombreUsuario = $connection->real_escape_string($data['nombreUsuario']);

// Si se proporciona una contraseña, aplicarle hash
if (!empty($data['contrasena'])) {
    $contrasena = password_hash($data['contrasena'], PASSWORD_BCRYPT);
    $sql = "UPDATE Usuario SET primerNombre='$primerNombre', segundoNombre='$segundoNombre', tercerNombre='$tercerNombre', 
            primerApellido='$primerApellido', segundoApellido='$segundoApellido', nombreUsuario='$nombreUsuario', contrasenia='$contrasena' 
            WHERE idUsuario='$idUsuario'";
} else {
    // Si no se proporciona contraseña, actualizar solo los otros campos
    $sql = "UPDATE Usuario SET primerNombre='$primerNombre', segundoNombre='$segundoNombre', tercerNombre='$tercerNombre', 
            primerApellido='$primerApellido', segundoApellido='$segundoApellido', nombreUsuario='$nombreUsuario' 
            WHERE idUsuario='$idUsuario'";
}

// Ejecutar la consulta
if ($connection->query($sql) === TRUE) {
    echo json_encode([
        'statusCode' => 200,
        'message' => 'Usuario actualizado exitosamente'
    ]);
} else {
    echo json_encode([
        'statusCode' => 500,
        'message' => 'Error al actualizar el usuario: ' . $connection->error
    ]);
}

$connection->close();
?>
