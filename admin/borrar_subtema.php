<?php
session_start();
require_once '../Conexion/conectar.php';

// Verificar si el usuario ha iniciado sesión y si es administrador
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Consultar si el usuario es administrador
$stmt = mysqli_prepare($cn, "SELECT EsAdmin FROM transportistas WHERE ID = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $es_admin);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (!$es_admin) {
    echo "<script>alert('Necesitas ser administrador para acceder a esta página.'); window.location.href = 'home.php';</script>";
    exit();
}

// Verificar si se recibió un ID válido por GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_subtemas.php");
    exit();
}

$id_subtema = $_GET['id'];

// Preparar la consulta para eliminar el subtema
$stmt = mysqli_prepare($cn, "DELETE FROM subtemas WHERE ID = ?");
mysqli_stmt_bind_param($stmt, "i", $id_subtema);

// Ejecutar la consulta
if (mysqli_stmt_execute($stmt)) {
    $msg = "Subtema eliminado correctamente.";
} else {
    $error = "Error al eliminar el subtema: " . mysqli_error($cn);
}

// Cerrar la consulta
mysqli_stmt_close($stmt);

// Redireccionar de vuelta a la página de administración de subtemas
header("Location: admin_subtemas.php?msg=" . urlencode($msg) . "&error=" . urlencode($error));
exit();
