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

// Verificar si se recibió un parámetro ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de curso no válido.");
}

$curso_id = $_GET['id'];

// Consultar y eliminar la miniatura del curso si existe
$stmt = mysqli_prepare($cn, "SELECT MiniaturaCurso FROM cursos WHERE ID = ?");
mysqli_stmt_bind_param($stmt, "i", $curso_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $miniatura_curso);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (!empty($miniatura_curso)) {
    $miniatura_path = "../Recursos/Cursos/{$curso_id}/miniatura.png";
    if (file_exists($miniatura_path)) {
        unlink($miniatura_path);
    }
}

// Eliminar el curso de la base de datos
$stmt = mysqli_prepare($cn, "DELETE FROM cursos WHERE ID = ?");
mysqli_stmt_bind_param($stmt, "i", $curso_id);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo "<script>alert('Curso eliminado correctamente.'); window.location.href = 'admin_cursos.php';</script>";
} else {
    echo "<script>alert('Error al eliminar curso.'); window.location.href = 'admin_cursos.php';</script>";
}
mysqli_stmt_close($stmt);

// Cerrar la conexión
mysqli_close($cn);
