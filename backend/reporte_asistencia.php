<?php
// Archivo: backend/reporte_asistencia.php

require_once 'config.php'; // Incluir la conexión a la base de datos
require('../lib/fpdf/fpdf.php'); // Asegúrate de tener FPDF instalado

// Verifica si los datos se han enviado correctamente
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    die('No se recibieron datos para generar el reporte');
}

$grade = isset($data['grade']) ? $data['grade'] : null;
$section = isset($data['section']) ? $data['section'] : null;
$fromDate = isset($data['fromDate']) ? $data['fromDate'] : null;
$toDate = isset($data['toDate']) ? $data['toDate'] : null;

// Verificar si se recibieron todos los parámetros necesarios
if (empty($grade) || empty($section) || empty($fromDate) || empty($toDate)) {
    die('Faltan datos para generar el reporte');
}

// Consulta para obtener el nombre del grado
$sqlGrado = "SELECT nombreGrado FROM Grado WHERE idGrado = ?";
$stmtGrado = $conn->prepare($sqlGrado);
$stmtGrado->bind_param('i', $grade);
$stmtGrado->execute();
$resultGrado = $stmtGrado->get_result();
if ($resultGrado->num_rows > 0) {
    $rowGrado = $resultGrado->fetch_assoc();
    $nombreGrado = $rowGrado['nombreGrado'];
} else {
    die('No se encontró el grado seleccionado.');
}
$stmtGrado->close();

// Consulta para obtener el nombre de la sección
$sqlSeccion = "SELECT nombreSeccion FROM Seccion WHERE idSeccion = ?";
$stmtSeccion = $conn->prepare($sqlSeccion);
$stmtSeccion->bind_param('i', $section);
$stmtSeccion->execute();
$resultSeccion = $stmtSeccion->get_result();
if ($resultSeccion->num_rows > 0) {
    $rowSeccion = $resultSeccion->fetch_assoc();
    $nombreSeccion = $rowSeccion['nombreSeccion'];
} else {
    die('No se encontró la sección seleccionada.');
}
$stmtSeccion->close();

// Consulta SQL para obtener los estudiantes y su asistencia con nombres completos y claveAlumno
$sql = "SELECT A.primerNombre, A.segundoNombre, A.tercerNombre, 
               A.primerApellido, A.segundoApellido, A.claveAlumno,
               COUNT(Asist.idAsistencia) AS total_asistencia,
               (COUNT(Asist.idAsistencia) / 
               (SELECT COUNT(DISTINCT fecha) 
                FROM Asistencia AS AIN 
                JOIN Alumno AS AL ON AIN.idAlumno = AL.idAlumno
                WHERE AL.idGrado = ? AND AL.idSeccion = ? 
                AND AIN.fecha BETWEEN ? AND ?)) * 100 AS tasa_asistencia
        FROM Alumno A
        LEFT JOIN Asistencia Asist 
        ON A.idAlumno = Asist.idAlumno 
        AND Asist.idtipoAsistencia = 1 
        AND Asist.fecha BETWEEN ? AND ?
        WHERE A.idGrado = ? AND A.idSeccion = ?
        GROUP BY A.idAlumno, A.primerNombre, A.segundoNombre, A.tercerNombre, A.primerApellido, A.segundoApellido, A.claveAlumno";

$stmt = $conn->prepare($sql);
$stmt->bind_param('iissssii', $grade, $section, $fromDate, $toDate, $fromDate, $toDate, $grade, $section);
$stmt->execute();
$result = $stmt->get_result();

// Crear el PDF usando FPDF en orientación vertical
$pdf = new FPDF(); // Orientación vertical por defecto
$pdf->SetMargins(10, 10, 10); // Ajustar los márgenes a 10 mm
$pdf->AddPage();

// Título grande centrado y en negrita
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 15, utf8_decode('Reporte de Asistencia'), 0, 1, 'C');

// Añadir imagen en la esquina superior derecha
$pdf->Image('../frontend/resources/school_icon.png', 180, 10, 20); // Ajustar el tamaño y posición

// Información del Grado, Sección y Fechas marginado a la izquierda
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(10); // Salto de línea
$pdf->Cell(0, 10, utf8_decode('Grado: ' . $nombreGrado), 0, 1, 'L');
$pdf->Cell(0, 10, utf8_decode('Sección: ' . $nombreSeccion), 0, 1, 'L');
$pdf->Cell(0, 10, utf8_decode('Desde: ' . $fromDate), 0, 1, 'L');
$pdf->Cell(0, 10, utf8_decode('Hasta: ' . $toDate), 0, 1, 'L');

$pdf->Ln(5); // Espacio mínimo entre la información y la tabla

// Establecer los colores para la tabla
$pdf->SetFillColor(0, 0, 255); // Color de relleno azul
$pdf->SetTextColor(255, 255, 255); // Texto blanco para el encabezado
$pdf->SetDrawColor(0, 0, 255); // Color de los bordes de la tabla

// Títulos de las columnas con relleno azul y bordes
$pdf->SetFont('Arial', 'B', 10);

// Anchos de las columnas
$colWidthEstudiante = 85;
$colWidthClave = 30;
$colWidthDias = 30;
$colWidthTasa = 45; // Ajustado a 50 mm

$pdf->Cell($colWidthEstudiante, 10, utf8_decode('Estudiantes'), 1, 0, 'L', true); 
$pdf->Cell($colWidthClave, 10, utf8_decode('Clave'), 1, 0, 'C', true);
$pdf->Cell($colWidthDias, 10, utf8_decode('Días Asistidos'), 1, 0, 'C', true);
$pdf->Cell($colWidthTasa, 10, utf8_decode('Tasa de Asistencia (%)'), 1, 0, 'C', true);
$pdf->Ln();

// Restablecer los colores del texto después del encabezado
$pdf->SetTextColor(0, 0, 0);

// Llenar el reporte con los datos obtenidos y agregar bordes azules
$pdf->SetFont('Arial', '', 10); // Fuente más pequeña para contenido de la tabla
while ($row = $result->fetch_assoc()) {
    $nombreCompleto = $row['primerNombre'] . ' ' . $row['segundoNombre'] . ' ' . $row['tercerNombre'] . ' ' . $row['primerApellido'] . ' ' . $row['segundoApellido'];

    $pdf->Cell($colWidthEstudiante, 10, utf8_decode($nombreCompleto), 1, 0, 'L');
    $pdf->Cell($colWidthClave, 10, utf8_decode($row['claveAlumno']), 1, 0, 'C');
    $pdf->Cell($colWidthDias, 10, $row['total_asistencia'], 1, 0, 'C');
    $pdf->Cell($colWidthTasa, 10, round($row['tasa_asistencia'], 2), 1, 0, 'C');
    $pdf->Ln();
}

$stmt->close();
$conn->close();

// Mostrar el archivo PDF en el navegador
$pdf->Output('I', 'reporte_asistencia.pdf');
