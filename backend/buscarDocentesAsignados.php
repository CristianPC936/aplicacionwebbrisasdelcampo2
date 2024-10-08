<?php
// Conexión a la base de datos
$host = 'serveo.net';
$user = 'root';
$password = '';
$database = 'pruebaproyecto';
$port = 13306;

$connection = new mysqli($host, $user, $password, $database, $port);

if ($connection->connect_error) {
    die(json_encode([
        'statusCode' => 500,
        'message' => 'Error en la conexión a la base de datos: ' . $connection->connect_error
    ]));
}

$cicloEscolar = date('Y');

// Consulta para docentes asignados
$sql = "
    SELECT u.primerNombre, u.segundoNombre, u.tercerNombre, u.primerApellido, u.segundoApellido, u.nombreUsuario, g.nombreGrado, s.nombreSeccion
    FROM Usuario u
    JOIN Rol r ON u.idRol = r.idRol
    JOIN Grado_has_Usuario_has_Seccion guhs ON u.idUsuario = guhs.idUsuario
    JOIN Grado g ON guhs.idGrado = g.idGrado
    JOIN Seccion s ON guhs.idSeccion = s.idSeccion
    WHERE r.idRol = 1 AND u.cicloEscolar = ? AND u.estado = 1
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
