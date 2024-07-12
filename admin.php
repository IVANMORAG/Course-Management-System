<?php
session_start();

// Verificar si el usuario ha iniciado sesión y si es administrador
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Conectar a la base de datos
require_once 'Conexion/conectar.php';

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
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin/styles/styles.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Panel Administrativo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php"><i class="fas fa-user-shield"></i> Admin</a>
                    </li>
                    <!-- Añadir más enlaces según sea necesario -->
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="content">
            <h2>Bienvenido al Panel Administrativo</h2>
            <p>Selecciona una opción del menú para empezar.</p>
        </div>
        <div class="sidebar">
            <ul class="list-group">
                <li class="list-group-item"><a href="admin/admin_transportistas.php"><i class="fas fa-truck"></i> Transportistas</a></li>
                <li class="list-group-item"><a href="admin/admin_revision.php"><i class="fas fa-truck"></i>Revisiones</a></li>
                <li class="list-group-item"><a href="admin/admin_cursos.php"><i class="fas fa-graduation-cap"></i> Cursos</a></li>
                <li class="list-group-item"><a href="admin/admin_pruebas.php"><i class="fas fa-question"></i> Pruebas</a></li>
                <li class="list-group-item"><a href="admin/admin_subtemas.php"><i class="fas fa-book"></i> Subtemas</a></li>
                <li class="list-group-item"><a href="admin/admin_preguntas.php"><i class="fas fa-question"></i> Preguntas</a></li>
                <li class="list-group-item"><a href="admin/admin_certificados.php"><i class="fas fa-certificate"></i> Certificados</a></li>
                <li class="list-group-item"><a href="admin/admin_videos.php"><i class="fas fa-video"></i> Videos</a></li>
                <li class="list-group-item"><a href="admin/admin_resultados_quiz.php"><i class="fas fa-poll"></i> Resultados de Quiz</a></li>
                <li class="list-group-item"><a href="admin/admin_preguntasmental.php"><i class="fas fa-poll"></i> Preguntas pruebas</a></li>
                <li class="list-group-item"><a href="admin/admin_respuestas.php"><i class="fas fa-poll"></i> respuestas pruebas</a></li>
                <li class="list-group-item"><a href="admin/admin_resultados.php"><i class="fas fa-poll"></i> Resultados pruebas</a></li>

            </ul>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>