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

// Verificar si se recibió un parámetro curso_id válido
if (!isset($_POST['curso_id']) || !is_numeric($_POST['curso_id'])) {
    die("ID de curso no válido.");
}

$curso_id = $_POST['curso_id'];

// Consultar el estado actual del curso
$stmt = mysqli_prepare($cn, "SELECT Estado FROM cursos WHERE ID = ?");
mysqli_stmt_bind_param($stmt, "i", $curso_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $estado_actual);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Determinar el nuevo estado a actualizar
$nuevo_estado = ($estado_actual == 'Activo') ? 'Inactivo' : 'Activo';

// Actualizar el estado del curso en la base de datos
$stmt = mysqli_prepare($cn, "UPDATE cursos SET Estado = ? WHERE ID = ?");
mysqli_stmt_bind_param($stmt, "si", $nuevo_estado, $curso_id);
mysqli_stmt_execute($stmt);

// Verificar si la actualización fue exitosa
if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo "<script>alert('Estado del curso actualizado correctamente.'); window.location.href = 'admin_cursos.php';</script>";
} else {
    echo "<script>alert('Error al actualizar estado del curso.'); window.location.href = 'admin_cursos.php';</script>";
}
mysqli_stmt_close($stmt);
mysqli_close($cn);
