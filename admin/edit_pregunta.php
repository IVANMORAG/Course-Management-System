<?php
session_start();
require_once '../Conexion/conectar.php';

// Verificar sesión y permisos de administrador
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

// Procesamiento para obtener y actualizar la pregunta
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $preguntaID = $_POST['id'];
        $textoPregunta = $_POST['textoPregunta'];

        // Actualizar la pregunta en la base de datos
        $stmt = mysqli_prepare($cn, "UPDATE preguntasmental SET Texto = ? WHERE ID = ?");
        mysqli_stmt_bind_param($stmt, "si", $textoPregunta, $preguntaID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Redirigir de vuelta a la página de administración de pruebas después de actualizar
        header("Location: admin_preguntasmental.php");
        exit();
    }
}

// Obtener el ID de la pregunta a editar desde la URL
$preguntaID = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $preguntaID = $_GET['id'];

    // Consultar la pregunta específica para obtener su texto actual
    $stmt = mysqli_prepare($cn, "SELECT Texto FROM preguntasmental WHERE ID = ?");
    mysqli_stmt_bind_param($stmt, "i", $preguntaID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $textoPregunta);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Editar Pregunta</title>
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
            padding: 50px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
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
                    <li class="nav-item">
                        <a class="nav-link" href="admin_pruebas.php">Pruebas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br>
    <div class="container">
        <h1>Editar Pregunta</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="id" value="<?php echo $preguntaID; ?>">
            <div class="form-group">
                <label for="textoPregunta">Texto de la Pregunta:</label>
                <textarea class="form-control" id="textoPregunta" name="textoPregunta" rows="3" required><?php echo htmlspecialchars($textoPregunta); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
            <a href="admin_preguntasmental.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
        </form>
    </div>

</body>

</html>