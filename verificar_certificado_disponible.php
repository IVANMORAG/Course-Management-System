<?php
// Inicia la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'Conexion/conectar.php';

header('Content-Type: application/json'); // Asegúrate de que la respuesta sea JSON

if (!isset($_GET['curso_id']) || empty($_GET['curso_id'])) {
    echo json_encode(['certificado_disponible' => false, 'error' => 'IDCurso no está definido o es inválido.']);
    exit();
}

// Asegúrate de que el usuario esté autenticado antes de continuar
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['certificado_disponible' => false, 'error' => 'Usuario no autenticado.']);
    exit();
}

$IDCurso = $_GET['curso_id'];
$IDUsuario = $_SESSION['user_id'];

// Prepara la llamada al procedimiento almacenado con dos parámetros de entrada y uno de salida
$sql = "CALL VerificarCertificado(?, ?, @certificado_disponible)";
$stmt = $cn->prepare($sql);
if (!$stmt) {
    echo json_encode(['certificado_disponible' => false, 'error' => 'Error en la preparación del statement: ' . $cn->error]);
    exit();
}
$stmt->bind_param("ii", $IDCurso, $IDUsuario); // "ii" indica que son dos parámetros enteros
if (!$stmt->execute()) {
    echo json_encode(['certificado_disponible' => false, 'error' => 'Error en la ejecución del statement: ' . $stmt->error]);
    $stmt->close();
    exit();
}
$stmt->close();

// Recupera el resultado del procedimiento almacenado
$sql_select = "SELECT @certificado_disponible AS certificado_disponible";
$result = $cn->query($sql_select);
if (!$result) {
    echo json_encode(['certificado_disponible' => false, 'error' => 'Error en la consulta del resultado: ' . $cn->error]);
    exit();
}
$row = $result->fetch_assoc();

$certificado_disponible = (bool) $row['certificado_disponible'];

echo json_encode(['certificado_disponible' => $certificado_disponible]);
