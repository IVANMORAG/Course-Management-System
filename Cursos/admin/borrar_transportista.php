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

// Verificar si se ha enviado el ID del transportista a eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $transportista_id = $_GET['id'];

    // Preparar la consulta para eliminar el transportista
    $stmt = mysqli_prepare($cn, "DELETE FROM transportistas WHERE ID = ?");
    mysqli_stmt_bind_param($stmt, "i", $transportista_id);

    // Ejecutar la consulta
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Transportista eliminado correctamente.'); window.location.href = 'admin_transportistas.php';</script>";
    } else {
        echo "<script>alert('Ocurrió un error al intentar eliminar el transportista.'); window.location.href = 'admin_transportistas.php';</script>";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<script>alert('ID de transportista no válido.'); window.location.href = 'admin_transportistas.php';</script>";
}

mysqli_close($cn);
