<?php
$servername = "cristian.clceaygw4k9c.us-east-1.rds.amazonaws.com"; // Reemplaza por el endpoint de tu instancia de RDS
$username = "admin"; // Reemplaza con el usuario de la base de datos
$password = "uy2McxjaXinFXgF"; // Reemplaza con la contraseña de la base de datos
$dbname = "pruebaproyecto"; // El nombre de la base de datos que creaste en RDS
$port = 3306; // El puerto, normalmente es 3306 para MySQL

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener los datos del POST
$data = json_decode(file_get_contents("php://input"), true);
$idUsuario = $data['idUsuario'];
$idGrado = $data['idGrado'];
$idSeccion = $data['idSeccion'];

// Validar que los datos no estén vacíos
if (isset($idUsuario, $idGrado, $idSeccion)) {
    // Preparar la consulta
    $query = $conn->prepare("INSERT INTO Grado_has_Usuario_has_Seccion (idUsuario, idGrado, idSeccion) VALUES (?, ?, ?)");
    $query->bind_param("iii", $idUsuario, $idGrado, $idSeccion);

    // Ejecutar la consulta
    if ($query->execute()) {
        echo json_encode(['success' => true, 'message' => 'Docente asignado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al asignar docente']);
    }

    $query->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}

// Cerrar conexión
$conn->close();
?>
