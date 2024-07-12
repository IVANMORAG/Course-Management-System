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

// Verificar si se recibió un ID de video válido por GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_videos.php");
    exit();
}

$id_video = $_GET['id'];

// Consultar datos del video
$stmt_video = mysqli_prepare($cn, "SELECT Titulo, Descripcion, VideoURL, MiniaturaVideo, Orden, IDSubtema FROM videos WHERE ID = ?");
mysqli_stmt_bind_param($stmt_video, "i", $id_video);
mysqli_stmt_execute($stmt_video);
mysqli_stmt_bind_result($stmt_video, $titulo, $descripcion, $video_url, $miniatura_video, $orden, $id_subtema);
mysqli_stmt_fetch($stmt_video);
mysqli_stmt_close($stmt_video);

// Consultar ID del curso
$stmt_curso = mysqli_prepare($cn, "SELECT IDCurso FROM subtemas WHERE ID = ?");
mysqli_stmt_bind_param($stmt_curso, "i", $id_subtema);
mysqli_stmt_execute($stmt_curso);
mysqli_stmt_bind_result($stmt_curso, $id_curso);
mysqli_stmt_fetch($stmt_curso);
mysqli_stmt_close($stmt_curso);

// Procesar formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $video_url = $_POST['video_url'];
    $orden = $_POST['orden'];

    // Procesar la subida de la miniatura
    if (isset($_FILES['miniatura']) && $_FILES['miniatura']['error'] == UPLOAD_ERR_OK) {
        $miniatura_tmp = $_FILES['miniatura']['tmp_name'];
        $miniatura_nombre = basename($_FILES['miniatura']['name']);
        $miniatura_ruta = "../Cursos/" . $miniatura_nombre;

        if (move_uploaded_file($miniatura_tmp, $miniatura_ruta)) {
            $miniatura_video = "Cursos/" . $miniatura_nombre;
        }
    }

    // Actualizar datos del video en la base de datos
    $stmt_update = mysqli_prepare($cn, "UPDATE videos SET Titulo = ?, Descripcion = ?, VideoURL = ?, MiniaturaVideo = ?, Orden = ? WHERE ID = ?");
    mysqli_stmt_bind_param($stmt_update, "sssisi", $titulo, $descripcion, $video_url, $miniatura_video, $orden, $id_video);
    mysqli_stmt_execute($stmt_update);

    // Verificar si se actualizó correctamente
    if (mysqli_stmt_affected_rows($stmt_update) > 0) {
        echo "<script>alert('Video actualizado correctamente.'); window.location.href = 'admin_videos.php?id=" . $id_curso . "';</script>";
    } else {
        echo "<script>alert('No se pudo actualizar el video.');</script>";
    }
    mysqli_stmt_close($stmt_update);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Video</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin/styles/styles.css">
    <style>
        body {
            background-color: #202124;
            color: #ffffff;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .navbar {
            background-color: #343a40 !important;
        }

        .container {
            background-color: #2d2d2d;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        }

        .form-control {
            background-color: #373737;
            color: #ffffff;
            border: none;
        }

        .btn {
            border-radius: 4px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">Panel Administrativo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../home.php"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin.php"><i class="fas fa-user-shield"></i> Admin</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <h2 class="mb-4">Editar Video</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $titulo; ?>" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo $descripcion; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="video_url" class="form-label">URL del Video</label>
                <input type="text" class="form-control" id="video_url" name="video_url" value="<?php echo $video_url; ?>" required>
            </div>
            <div class="mb-3">
                <label for="orden" class="form-label">Orden</label>
                <input type="number" class="form-control" id="orden" name="orden" value="<?php echo $orden; ?>" required>
            </div>
            <div class="mb-3">
                <label for="miniatura" class="form-label">Miniatura del Video</label>
                <input type="file" class="form-control" id="miniatura" name="miniatura">
                <?php if ($miniatura_video) : ?>
                    <img src="../<?php echo $miniatura_video; ?>" alt="Miniatura actual" class="mt-2" style="max-width: 200px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="admin_videos.php?id=<?php echo $id_curso; ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>