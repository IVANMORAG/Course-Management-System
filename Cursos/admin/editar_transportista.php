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
    die("ID de transportista no válido.");
}

$transportista_id = $_GET['id'];

// Consultar los datos del transportista a editar
$stmt = mysqli_prepare($cn, "SELECT ID, NumeroTrabajador, Nombre, Apellido, CorreoElectronico, EstaActivo, EsAdmin FROM transportistas WHERE ID = ?");
mysqli_stmt_bind_param($stmt, "i", $transportista_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $id, $numero_trabajador, $nombre, $apellido, $correo_electronico, $esta_activo, $es_admin);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Verificar si se encontró el transportista
if (!$id) {
    die("Transportista no encontrado.");
}

// Procesar la actualización del transportista
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo_electronico = $_POST['correo_electronico'];
    $esta_activo = isset($_POST['esta_activo']) ? 1 : 0;
    $es_admin = isset($_POST['es_admin']) ? 1 : 0;

    // Actualizar en la base de datos
    $stmt = mysqli_prepare($cn, "UPDATE transportistas SET Nombre = ?, Apellido = ?, CorreoElectronico = ?, EstaActivo = ?, EsAdmin = ? WHERE ID = ?");
    mysqli_stmt_bind_param($stmt, "sssiii", $nombre, $apellido, $correo_electronico, $esta_activo, $es_admin, $transportista_id);
    mysqli_stmt_execute($stmt);

    // Verificar si la actualización fue exitosa
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "<script>alert('Transportista actualizado correctamente.'); window.location.href = 'admin_transportistas.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al actualizar transportista.'); window.location.href = 'admin_transportistas.php';</script>";
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
    <title>Editar Transportista - Panel Administrativo</title>
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
        <h2>Editar Transportista</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido:</label>
                <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($apellido); ?>">
            </div>
            <div class="mb-3">
                <label for="correo_electronico" class="form-label">Correo Electrónico:</label>
                <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="<?php echo htmlspecialchars($correo_electronico); ?>">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="esta_activo" name="esta_activo" <?php echo ($esta_activo == 1) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="esta_activo">¿Está activo?</label>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="es_admin" name="es_admin" <?php echo ($es_admin == 1) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="es_admin">¿Es administrador?</label>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
            <a href="admin_transportistas.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Cerrar la conexión
mysqli_close($cn);
?>