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

// Procesamiento del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_trabajador = $_POST['numero_trabajador'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Encriptación MD5 de la contraseña
    $correo_electronico = $_POST['correo_electronico'];
    $esta_activo = isset($_POST['esta_activo']) ? 1 : 0;
    $es_admin = isset($_POST['es_admin']) ? 1 : 0;

    // Verificar si el correo electrónico ya existe
    $stmt_check_email = mysqli_prepare($cn, "SELECT ID FROM transportistas WHERE CorreoElectronico = ?");
    mysqli_stmt_bind_param($stmt_check_email, "s", $correo_electronico);
    mysqli_stmt_execute($stmt_check_email);
    mysqli_stmt_store_result($stmt_check_email);

    if (mysqli_stmt_num_rows($stmt_check_email) > 0) {
        echo "<script>alert('El correo electrónico ya está registrado.');</script>";
        mysqli_stmt_close($stmt_check_email);
    } else {
        mysqli_stmt_close($stmt_check_email);

        // Insertar datos en la base de datos
        $stmt_insert = mysqli_prepare($cn, "INSERT INTO transportistas (NumeroTrabajador, Nombre, Apellido, Contraseña, CorreoElectronico, EstaActivo, EsAdmin) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_insert, "ssssssi", $numero_trabajador, $nombre, $apellido, $contrasena, $correo_electronico, $esta_activo, $es_admin);
        mysqli_stmt_execute($stmt_insert);

        // Verificar inserción exitosa
        if (mysqli_stmt_affected_rows($stmt_insert) > 0) {
            mysqli_stmt_close($stmt_insert);
            mysqli_close($cn);
            header("Location: admin_transportistas.php");
            exit();
        } else {
            echo "<script>alert('Error al agregar transportista.');</script>";
        }

        mysqli_stmt_close($stmt_insert);
    }

    mysqli_close($cn);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Transportista - Panel Administrativo</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <h2 class="mb-4">Agregar Nuevo Transportista</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="numero_trabajador" class="form-label">Número de Trabajador</label>
                <input type="text" class="form-control" id="numero_trabajador" name="numero_trabajador" required>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
            </div>
            <div class="mb-3">
                <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="esta_activo" name="esta_activo" value="1" checked>
                <label class="form-check-label" for="esta_activo">Activo</label>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="es_admin" name="es_admin" value="1">
                <label class="form-check-label" for="es_admin">Administrador</label>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar Transportista</button>
            <a href="admin_transportistas.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Regresar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>