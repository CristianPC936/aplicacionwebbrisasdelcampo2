<?php
require_once 'config.php'; // Incluir la conexión a la base de datos
require('../lib/fpdf/fpdf.php'); // Asegúrate de tener FPDF instalado

// Verifica si los datos se han enviado correctamente
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => 'No se recibieron datos para generar el reporte']);
    exit;
}

$idAlumno = isset($data['idAlumno']) ? $data['idAlumno'] : null;
$idGrado = isset($data['idGrado']) ? $data['idGrado'] : null;
$cicloEscolar = isset($data['cicloEscolar']) ? $data['cicloEscolar'] : null;

if (empty($idAlumno) || empty($idGrado) || empty($cicloEscolar)) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => 'Faltan datos para generar el reporte']);
    exit;
}

// Obtener el nombre completo del estudiante, grado, sección
$sqlEstudiante = "
    SELECT A.primerNombre, A.segundoNombre, A.tercerNombre, A.primerApellido, A.segundoApellido, G.nombreGrado, S.nombreSeccion 
    FROM Alumno A 
    JOIN Grado G ON A.idGrado = G.idGrado
    JOIN Seccion S ON A.idSeccion = S.idSeccion
    WHERE A.idAlumno = ?
";
$stmtEstudiante = $conn->prepare($sqlEstudiante);
$stmtEstudiante->bind_param('i', $idAlumno);
$stmtEstudiante->execute();
$resultEstudiante = $stmtEstudiante->get_result();
$estudiante = $resultEstudiante->fetch_assoc();

$nombreEstudiante = $estudiante['primerNombre'] . ' ' . $estudiante['segundoNombre'] . ' ' . $estudiante['tercerNombre'] . ' ' . $estudiante['primerApellido'] . ' ' . $estudiante['segundoApellido'];
$nombreGrado = $estudiante['nombreGrado'];
$nombreSeccion = $estudiante['nombreSeccion'];
$stmtEstudiante->close();

// Consulta SQL para obtener los cursos y las notas del estudiante en los 4 bimestres
$sqlNotas = "
    SELECT C.nombreCurso, N.bimestre, N.nota 
    FROM Notas N 
    JOIN curso C ON N.idCurso = C.idCurso 
    JOIN Alumno A ON N.idAlumno = A.idAlumno 
    WHERE N.idAlumno = ? AND N.cicloEscolar = ? AND A.idGrado = ?
";
$stmtNotas = $conn->prepare($sqlNotas);
$stmtNotas->bind_param('iii', $idAlumno, $cicloEscolar, $idGrado);
$stmtNotas->execute();
$resultNotas = $stmtNotas->get_result();

$notasPorCurso = [];

while ($row = $resultNotas->fetch_assoc()) {
    $nombreCurso = $row['nombreCurso'];
    $bimestre = $row['bimestre'];
    $nota = $row['nota'];

    if (!isset($notasPorCurso[$nombreCurso])) {
        $notasPorCurso[$nombreCurso] = ['notas' => []];
    }

    $notasPorCurso[$nombreCurso]['notas'][$bimestre] = $nota;
}
$stmtNotas->close();

// Crear el PDF usando FPDF en orientación horizontal
$pdf = new FPDF('L', 'mm', 'A4'); // 'L' para landscape
$pdf->AddPage();

// Título grande centrado y en negrita
$pdf->SetFont('Arial', 'B', 20); // Tamaño más grande para el título
$pdf->Cell(0, 15, utf8_decode('Boletín de Calificaciones'), 0, 1, 'C');

// Añadir imagen en la esquina superior derecha con un margen de 10mm
$pdf->Image('../frontend/resources/school_icon.png', 240, 10, 45); // La imagen está ahora en la derecha con 10mm de margen

// Información del Estudiante, Grado y Sección marginado a la izquierda
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(10); // Salto de línea
$pdf->Cell(0, 10, utf8_decode('Estudiante: ' . $nombreEstudiante), 0, 1, 'L'); // Todos los nombres y apellidos
$pdf->Cell(0, 10, utf8_decode('Grado: ' . $nombreGrado), 0, 1, 'L');
$pdf->Cell(0, 10, utf8_decode('Sección: ' . $nombreSeccion), 0, 1, 'L');

// Espacio antes de la tabla
$pdf->Ln(0);

// Ajuste de las columnas para no exceder los márgenes
$anchoAsignatura = 82; // Ancho para la columna "Asignatura"
$anchoBimestre = 30; // Ancho para cada bimestre
$anchoTotal = 20; // Ancho para la columna "Total"
$anchoAprobado = 55; // Ancho para la columna "Aprobado / No Aprobado"

// Títulos de las columnas
$pdf->SetFillColor(0, 0, 255); // Color de relleno azul
$pdf->SetTextColor(255, 255, 255); // Texto blanco para el encabezado
$pdf->SetDrawColor(0, 0, 255); // Color de los bordes de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($anchoAsignatura, 8, utf8_decode('Asignatura'), 1, 0, 'L', true);
$pdf->Cell($anchoBimestre, 8, utf8_decode('1er. Bimestre'), 1, 0, 'C', true);
$pdf->Cell($anchoBimestre, 8, utf8_decode('2do. Bimestre'), 1, 0, 'C', true);
$pdf->Cell($anchoBimestre, 8, utf8_decode('3er. Bimestre'), 1, 0, 'C', true);
$pdf->Cell($anchoBimestre, 8, utf8_decode('4to. Bimestre'), 1, 0, 'C', true);
$pdf->Cell($anchoTotal, 8, utf8_decode('Total'), 1, 0, 'C', true);
$pdf->Cell($anchoAprobado, 8, utf8_decode('Aprobado / No Aprobado'), 1, 1, 'C', true);

// Restablecer los colores del texto después del encabezado
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 10); // Ajustar tamaño de la fuente a 10

// Variables para calcular el promedio por bimestre
$promedios = [0, 0, 0, 0];
$totalCursos = count($notasPorCurso);
$totalPromedios = [];

foreach ($notasPorCurso as $curso => $detalle) {
    $notas = $detalle['notas'];
    $sumaNotas = 0;
    $cantidadNotas = 0;

    // Agregar fila de asignatura
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell($anchoAsignatura, 8, utf8_decode($curso), 1);

    // Calcular y mostrar notas de los 4 bimestres
    for ($i = 1; $i <= 4; $i++) {
        $nota = isset($notas[$i]) ? $notas[$i] : '';

        if ($nota !== '') {
            if ($nota < 60) {
                $pdf->SetTextColor(255, 0, 0); // Rojo para notas menores a 60
            } else {
                $pdf->SetTextColor(0, 0, 0); // Negro para notas normales
            }

            $sumaNotas += $nota;
            $cantidadNotas++;
            $promedios[$i - 1] += $nota; // Acumular para el promedio de bimestre
        }

        // Mostrar nota
        $pdf->Cell($anchoBimestre, 8, $nota, 1, 0, 'C');
    }

    // Calcular total y promedio por asignatura
    $promedioAsignatura = $cantidadNotas > 0 ? round($sumaNotas / $cantidadNotas, 2) : '';
    $totalPromedios[] = $promedioAsignatura;

    // Mostrar el total de la asignatura
    if ($promedioAsignatura < 60 && $promedioAsignatura !== '') {
        $pdf->SetTextColor(255, 0, 0); // Rojo si el promedio es menor a 60
    } else {
        $pdf->SetTextColor(0, 0, 0); // Negro para otros casos
    }

    $pdf->Cell($anchoTotal, 8, $promedioAsignatura, 1, 0, 'C');

    // Mostrar si el estudiante aprobó o no
    if ($cantidadNotas === 4) {
        if ($promedioAsignatura < 60) {
            $pdf->SetTextColor(255, 0, 0); // Rojo para no aprobado
            $pdf->Cell($anchoAprobado, 8, 'No Aprobado', 1, 0, 'C');
        } else {
            $pdf->SetTextColor(0, 0, 0); // Negro para aprobado
            $pdf->Cell($anchoAprobado, 8, 'Aprobado', 1, 0, 'C');
        }
    } else {
        $pdf->Cell($anchoAprobado, 8, '', 1, 0, 'C');
    }

    $pdf->Ln();
}

// Agregar la fila de promedio
$pdf->SetTextColor(0, 0, 0); // Mantener el texto en negro para el promedio
$pdf->Cell($anchoAsignatura, 8, 'PROMEDIO', 1);
for ($i = 0; $i < 4; $i++) {
    $promedioBimestre = $promedios[$i] > 0 ? round($promedios[$i] / $totalCursos, 2) : '';

    if ($promedioBimestre < 60 && $promedioBimestre !== '') {
        $pdf->SetTextColor(255, 0, 0); // Rojo para promedios menores a 60
    } else {
        $pdf->SetTextColor(0, 0, 0); // Negro para otros casos
    }

    $pdf->Cell($anchoBimestre, 8, $promedioBimestre, 1, 0, 'C');
}

// Calcular el promedio total final
$totalPromedioFinal = round(array_sum($totalPromedios) / count($totalPromedios), 2);

if ($totalPromedioFinal < 60) {
    $pdf->SetTextColor(255, 0, 0); // Rojo si el promedio total es menor a 60
} else {
    $pdf->SetTextColor(0, 0, 0); // Negro para otros casos
}

$pdf->Cell($anchoTotal, 8, $totalPromedioFinal, 1, 0, 'C');

// No se necesita mostrar "Aprobado / No Aprobado" en la fila de promedio
$pdf->Cell($anchoAprobado, 8, '', 1, 0, 'C');

// Salida del PDF al navegador
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="Boletin.pdf"');
$pdf->Output('I', 'Boletin.pdf');
?>
