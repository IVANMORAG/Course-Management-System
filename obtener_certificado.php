<?php
// Incluir el archivo de conexión a la base de datos
include 'Conexion/conectar.php';

// Obtener los datos del formulario
$IDUsuario = $_POST['IDUsuario'];
$IDCurso = $_POST['IDCurso'];

// Consulta para obtener los detalles del usuario y el curso
$sql = "SELECT t.ID AS IDTransportista, c.ID AS IDCurso, t.Nombre AS UsuarioNombre, t.Apellido AS UsuarioApellido, c.Nombre AS CursoNombre
        FROM transportistas t
        JOIN cursos c ON c.ID = ?
        WHERE t.ID = ?";
$stmt = $cn->prepare($sql);
$stmt->bind_param("ii", $IDCurso, $IDUsuario);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die('Error: No se encontró el usuario o el curso.');
}

$IDTransportista = $row['IDTransportista'];
$IDCurso = $row['IDCurso'];
$UsuarioNombre = $row['UsuarioNombre'];
$UsuarioApellido = $row['UsuarioApellido'];
$CursoNombre = $row['CursoNombre'];

// Verificar si ya existe un certificado para este usuario y curso
$sql_verificar = "SELECT ID FROM certificados WHERE IDTransportista = ? AND IDCurso = ?";
$stmt_verificar = $cn->prepare($sql_verificar);
$stmt_verificar->bind_param("ii", $IDTransportista, $IDCurso);
$stmt_verificar->execute();
$result_verificar = $stmt_verificar->get_result();
$stmt_verificar->close();

if ($result_verificar->num_rows > 0) {
    // Si existe, actualizar el certificado (opcional en tu caso)
    echo "Ya existe un certificado para este usuario y curso. Puedes implementar la lógica de actualización aquí si es necesario.";
} else {
    // Generar el certificado en PDF usando FPDF
    require('fpdf/fpdf.php');

    // Crear una instancia de FPDF en orientación horizontal (Landscape)
    class PDF extends FPDF
    {
        // Cabecera de página
        function Header()
        {
            // No hay logo en la cabecera
        }

        // Pie de página
        function Footer()
        {
            // Nada en el pie de página para este certificado
        }
    }

    // Crear una instancia de PDF
    $pdf = new PDF('L', 'mm', 'A4');
    $pdf->SetMargins(25, 25); // Establecer márgenes de 25 mm (2.5 cm) en cada lado
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    // Cargar la imagen de fondo (ajusta la ruta según corresponda)
    $backgroundImage = 'Recursos/imagenes/certificado.png';
    $pdf->Image($backgroundImage, 0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight());

    $pdf->Ln(60);

    // Nombre del usuario centrado y en grande
    $pdf->SetFont('Courier', 'B', 48);
    $pdf->Cell(0, 30, utf8_decode($UsuarioNombre . ' ' . $UsuarioApellido), 0, 1, 'C');

    $pdf->Ln(10);

    // Título del curso centrado y un poco más pequeño
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Cell(0, 20, utf8_decode($CursoNombre), 0, 1, 'C');

    $pdf->Ln(21);

    // Ajustar la ruta y el nombre del archivo de certificado
    $nombreCertificado = "certificado_" . $IDTransportista . "_" . $IDCurso . ".pdf";
    $rutaCertificado = "Certificados/" . $nombreCertificado; // Carpeta donde guardarás los certificados

    // Guardar el certificado en el servidor
    $pdf->Output('F', $rutaCertificado);

    // Insertar o actualizar el certificado en la base de datos
    $sql_insertar = "INSERT INTO certificados (IDTransportista, IDCurso, Certificado) VALUES (?, ?, ?)
                     ON DUPLICATE KEY UPDATE Certificado = ?";
    $stmt_insertar = $cn->prepare($sql_insertar);
    $stmt_insertar->bind_param("iiss", $IDTransportista, $IDCurso, $rutaCertificado, $rutaCertificado);
    $stmt_insertar->execute();

    // Mostrar el PDF directamente en una nueva ventana del navegador
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $nombreCertificado . '"');
    readfile($rutaCertificado);

    // Terminar el script después de enviar el archivo
    exit;

    $stmt_insertar->close();
}

$stmt->close();
$cn->close();
