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

// Procesar el formulario de agregar curso
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];
    $certificado_requerido = isset($_POST['certificado_requerido']) ? 1 : 0;
    $id_admin = $_SESSION['user_id'];

    // Crear la carpeta para el curso
    $carpeta_curso = "Cursos/$nombre";
    if (!file_exists($carpeta_curso)) {
        mkdir($carpeta_curso, 0777, true);
    }

    // Subir miniatura del curso si se seleccionó
    if ($_FILES['miniatura']['size'] > 0) {
        $ruta_miniatura = $carpeta_curso . '/' . basename($_FILES['miniatura']['name']);
        move_uploaded_file($_FILES['miniatura']['tmp_name'], $ruta_miniatura);
    } else {
        $ruta_miniatura = null;
    }

    // Insertar datos en la base de datos
    $stmt = mysqli_prepare($cn, "INSERT INTO cursos (Nombre, Descripcion, Estado, CertificadoRequerido, IDAdmin, MiniaturaCurso) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssiis", $nombre, $descripcion, $estado, $certificado_requerido, $id_admin, $ruta_miniatura);
    mysqli_stmt_execute($stmt);

    // Verificar si se agregó correctamente
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "<script>alert('Curso agregado correctamente.'); window.location.href = 'admin_cursos.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al agregar curso.'); window.location.href = 'agregar_curso.php';</script>";
        exit();
    }
    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Curso - Panel Administrativo</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin/styles/styles.css">
    <style>
        body {
            background-color: #202124;
            /* Color de fondo oscuro */
            color: #ffffff;
            /* Color de texto blanco */
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .navbar {
            background-color: #343a40 !important;
            /* Color de fondo navbar */
        }

        .container {
            max-width: 600px;
            /* Ancho máximo del contenedor */
            background-color: #2d2d2d;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        }

        .form-control {
            background-color: #373737;
            border: 1px solid #4e4e4e;
            color: #ffffff;
        }

        .form-control:focus {
            background-color: #373737;
            border-color: #6b6b6b;
            color: #ffffff;
            box-shadow: none;
        }

        .form-label {
            color: #ffffff;
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
                    <a class="nav-link" href="home.php"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php"><i class="fas fa-user-shield"></i> Admin</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <h2>Agregar Nuevo Curso</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="certificado_requerido" name="certificado_requerido">
                <label class="form-check-label" for="certificado_requerido">¿Certificado Requerido?</label>
            </div>
            <div class="mb-3">
                <label for="miniatura" class="form-label">Miniatura del Curso:</label>
                <input type="file" class="form-control" id="miniatura" name="miniatura">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar Curso</button>
            <a href="admin_cursos.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Regresar</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Cerrar la conexión
mysqli_close($cn);
?>