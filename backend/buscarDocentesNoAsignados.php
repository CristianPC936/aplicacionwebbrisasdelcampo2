<?php
$servername = "cristian.clceaygw4k9c.us-east-1.rds.amazonaws.com"; // Reemplaza por el endpoint de tu instancia de RDS
$username = "admin"; // Reemplaza con el usuario de la base de datos
$password = "uy2McxjaXinFXgF"; // Reemplaza con la contraseña de la base de datos
$dbname = "pruebaproyecto"; // El nombre de la base de datos que creaste en RDS
$port = 3306; // El puerto, normalmente es 3306 para MySQL

// Crear conexión
$connection = new mysqli($servername, $username, $password, $dbname, $port);

if ($connection->connect_error) {
    die(json_encode([
        'statusCode' => 500,
        'message' => 'Error en la conexión a la base de datos: ' . $connection->connect_error
    ]));
}

$cicloEscolar = date('Y');

// Consulta para docentes no asignados
$sql = "
    SELECT u.idUsuario, u.primerNombre, u.segundoNombre, u.tercerNombre, u.primerApellido, u.segundoApellido, u.nombreUsuario
    FROM Usuario u
    JOIN Rol r ON u.idRol = r.idRol
    LEFT JOIN Grado_has_Usuario_has_Seccion guhs ON u.idUsuario = guhs.idUsuario
    WHERE r.idRol = 1 AND u.cicloEscolar = ? AND u.estado = 1 AND guhs.idUsuario IS NULL
";

$stmt = $connection->prepare($sql);
$stmt->bind_param('i', $cicloEscolar);
$stmt->execute();
$result = $stmt->get_result();

$docentes = [];
while ($row = $result->fetch_assoc()) {
    $docentes[] = $row;
}

echo json_encode($docentes);

$stmt->close();
$connection->close();
?>
