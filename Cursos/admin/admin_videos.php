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

// Consulta para obtener la lista de cursos y subtemas para el formulario
$query_cursos_subtemas = "SELECT c.ID AS IDCurso, c.Nombre AS NombreCurso, s.ID AS IDSubtema, s.Nombre AS NombreSubtema 
                          FROM cursos c 
                          INNER JOIN subtemas s ON c.ID = s.IDCurso 
                          ORDER BY c.ID, s.ID";
$result_cursos_subtemas = mysqli_query($cn, $query_cursos_subtemas);

// Procesamiento del formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $orden = $_POST['orden'];
    $id_subtema = $_POST['id_subtema'];

    // Archivo de video
    $video_nombre = $_FILES['video']['name'];
    $video_tmp = $_FILES['video']['tmp_name'];
    $video_url = "Recursos/videos/" . basename($video_nombre);
    $video_tipo = $_FILES['video']['type'];

    // Archivo de miniatura
    $miniatura_nombre = $_FILES['miniatura']['name'];
    $miniatura_tmp = $_FILES['miniatura']['tmp_name'];
    $miniatura_url = "Recursos/videos/" . basename($miniatura_nombre);
    $miniatura_tipo = $_FILES['miniatura']['type'];

    // Validar el formato del video y la miniatura (opcional)
    $permitidos_video = array("video/mp4", "video/x-m4v", "video/*");
    $permitidos_miniatura = array("image/jpeg", "image/png");

    if (!in_array($video_tipo, $permitidos_video) || !in_array($miniatura_tipo, $permitidos_miniatura)) {
        echo "<script>alert('Formato de archivo no permitido. Sube un video en formato MP4 o imágenes JPEG/PNG.'); window.location.href = 'admin_video.php';</script>";
        exit();
    }

    // Mover archivos a la carpeta de destino
    if (move_uploaded_file($video_tmp, $video_url) && move_uploaded_file($miniatura_tmp, $miniatura_url)) {
        // Insertar datos en la tabla videos
        $query_insertar_video = "INSERT INTO videos (IDSubtema, Titulo, Descripcion, VideoURL, Orden, MiniaturaVideo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($cn, $query_insertar_video);
        mysqli_stmt_bind_param($stmt, "isssis", $id_subtema, $titulo, $descripcion, $video_url, $orden, $miniatura_url);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<script>alert('Video subido exitosamente.'); window.location.href = 'admin_video.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error al subir el video.'); window.location.href = 'admin_video.php';</script>";
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error al subir los archivos.'); window.location.href = 'admin_video.php';</script>";
        exit();
    }
}

mysqli_close($cn);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Videos - Panel Administrativo</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .form-container {
            background-color: #373737;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
        }

        .form-control {
            background-color: #484848;
            color: #ffffff;
        }

        .btn-submit {
            background-color: #6c757d;
            border: none;
            color: #ffffff;
        }

        .btn-submit:hover {
            background-color: #5a6268;
            color: #ffffff;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Panel Administrativo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../admin.php">Panel de Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../home.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_cursos.php">Cursos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_transportistas.php">Transportistas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="form-container">
            <h2 class="mb-4">Subir Nuevo Video</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título:</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="video" class="form-label">Archivo de Video (MP4, M4V, o cualquier formato de video):</label>
                    <input class="form-control" type="file" id="video" name="video" accept="video/mp4,video/x-m4v,video/*" required>
                </div>
                <div class="mb-3">
                    <label for="miniatura" class="form-label">Miniatura (JPEG o PNG):</label>
                    <input class="form-control" type="file" id="miniatura" name="miniatura" accept="image/jpeg,image/png" required>
                </div>
                <div class="mb-3">
                    <label for="orden" class="form-label">Orden:</label>
                    <input type="number" class="form-control" id="orden" name="orden" required>
                </div>
                <div class="mb-3">
                    <label for="id_subtema" class="form-label">ID del Subtema:</label>
                    <select class="form-select" id="id_subtema" name="id_subtema" required>
                        <option value="" selected disabled>Seleccione un Subtema</option>
                        <?php while ($row = mysqli_fetch_assoc($result_cursos_subtemas)) : ?>
                            <option value="<?php echo $row['IDSubtema']; ?>"><?php echo $row['NombreCurso'] . ' - ' . $row['NombreSubtema']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-submit">Subir Video</button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>