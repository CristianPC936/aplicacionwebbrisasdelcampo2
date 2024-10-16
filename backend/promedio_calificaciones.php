<?php
// Archivo: backend/promedio_calificaciones.php

require_once 'config.php'; // Incluir la conexión a la base de datos
require('../lib/fpdf/fpdf.php'); // Asegúrate de tener FPDF instalado

// Verifica si los datos se han enviado correctamente
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    die('No se recibieron datos para generar el reporte');
}

$grade = isset($data['grade']) ? $data['grade'] : null;
$section = isset($data['section']) ? $data['section'] : null;
$bimester = isset($data['bimester']) ? $data['bimester'] : null;
$cicloEscolar = isset($data['cicloEscolar']) ? $data['cicloEscolar'] : null;

// Verificar si se recibieron todos los parámetros necesarios
if (empty($grade) || empty($section) || empty($bimester) || empty($cicloEscolar)) {
    die("Faltan datos para generar el reporte");
}

// Consulta para obtener el nombre del Grado
$sqlGrado = "SELECT nombreGrado FROM Grado WHERE idGrado = ?";
$stmtGrado = $conn->prepare($sqlGrado);
$stmtGrado->bind_param('i', $grade);
$stmtGrado->execute();
$resultGrado = $stmtGrado->get_result();
$nombreGrado = $resultGrado->fetch_assoc()['nombreGrado'];
$stmtGrado->close();

// Consulta para obtener el nombre de la Sección
$sqlSeccion = "SELECT nombreSeccion FROM Seccion WHERE idSeccion = ?";
$stmtSeccion = $conn->prepare($sqlSeccion);
$stmtSeccion->bind_param('i', $section);
$stmtSeccion->execute();
$resultSeccion = $stmtSeccion->get_result();
$nombreSeccion = $resultSeccion->fetch_assoc()['nombreSeccion'];
$stmtSeccion->close();

// Consulta SQL para obtener los estudiantes y sus promedios de calificaciones
$sql = "SELECT 
            A.primerNombre, A.segundoNombre, A.tercerNombre, 
            A.primerApellido, A.segundoApellido, A.claveAlumno,
            AVG(N.nota) AS promedio_calificaciones
        FROM 
            Alumno A
        LEFT JOIN 
            Notas N ON A.idAlumno = N.idAlumno
        WHERE 
            A.idGrado = ? AND A.idSeccion = ? 
            AND N.cicloEscolar = ? 
            AND N.bimestre = ?
        GROUP BY 
            A.idAlumno, A.primerNombre, A.segundoNombre, A.tercerNombre, 
            A.primerApellido, A.segundoApellido, A.claveAlumno";

$stmt = $conn->prepare($sql);
$stmt->bind_param('iiii', $grade, $section, $cicloEscolar, $bimester);
$stmt->execute();
$result = $stmt->get_result();

// Crear el PDF usando FPDF en orientación vertical
$pdf = new FPDF('P', 'mm', 'A4'); // 'P' para portrait, 'mm' para milímetros, 'A4' para tamaño de página
$pdf->AddPage();

// Título grande centrado y en negrita
$pdf->SetFont('Arial', 'B', 14); // Tamaño de fuente más pequeño
$pdf->Cell(0, 10, utf8_decode('Reporte de Promedios'), 0, 1, 'C');

// Definir el margen
$margen = 10;

// Añadir imagen en la esquina superior derecha, ajustada al margen
$pdf->Image('../frontend/resources/school_icon.png', $pdf->GetPageWidth() - $margen - 30, 10, 30); // Ajustada a 30mm de ancho

// Información del Grado, Sección, Ciclo Escolar y Bimestre marginado a la izquierda
$pdf->SetFont('Arial', '', 12); // Fuente más pequeña para la información del grado, sección, etc.
$pdf->Ln(5); // Salto de línea
$pdf->Cell(0, 5, utf8_decode('Grado: ' . $nombreGrado), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode('Sección: ' . $nombreSeccion), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode('Ciclo Escolar: ' . $cicloEscolar), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode('Bimestre: ' . $bimester), 0, 1, 'L');

$pdf->Ln(5); // Espacio antes de la tabla

// Establecer los colores para la tabla
$pdf->SetFillColor(0, 0, 255); // Color de relleno azul
$pdf->SetTextColor(255, 255, 255); // Texto blanco para el encabezado
$pdf->SetDrawColor(0, 0, 255); // Color de los bordes de la tabla

// Títulos de las columnas con relleno azul y bordes
$pdf->SetFont('Arial', 'B', 12); // Tamaño más pequeño para las celdas de encabezado
$pdf->SetX($margen); // Establecer el margen izquierdo
$pdf->Cell(130, 8, utf8_decode('Nombre Completo'), 1, 0, 'L', true); // Columna de 130mm ajustada para margen
$pdf->Cell(30, 8, utf8_decode('Clave'), 1, 0, 'C', true); // Columna de 30mm
$pdf->Cell(30, 8, utf8_decode('Promedio'), 1, 0, 'C', true); // Columna de 30mm
$pdf->Ln();

// Restablecer los colores del texto después del encabezado
$pdf->SetTextColor(0, 0, 0);

// Llenar el reporte con los datos obtenidos y agregar bordes azules
$pdf->SetFont('Arial', '', 12); // Reducir el tamaño de la fuente para el contenido de la tabla
while ($row = $result->fetch_assoc()) {
    $nombreCompleto = $row['primerNombre'] . ' ' . $row['segundoNombre'] . ' ' . $row['tercerNombre'] . ' ' . $row['primerApellido'] . ' ' . $row['segundoApellido'];
    $pdf->SetX($margen); // Mantener el margen para cada fila
    $pdf->Cell(130, 8, utf8_decode($nombreCompleto), 1, 0, 'L'); // Columna de 130mm ajustada para margen
    $pdf->Cell(30, 8, utf8_decode($row['claveAlumno']), 1, 0, 'C');
    $pdf->Cell(30, 8, round($row['promedio_calificaciones'], 2), 1, 0, 'C');
    $pdf->Ln();
}

$stmt->close();
$conn->close();

// Mostrar el archivo PDF en el navegador con el nombre adecuado
$pdf->Output('I', 'reporte_promedios.pdf');
