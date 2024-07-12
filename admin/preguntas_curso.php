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

// Verificar si se recibió un ID de curso válido por GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_preguntas.php");
    exit();
}

$id_curso = $_GET['id'];

// Consultar el nombre del curso
$stmt_curso = mysqli_prepare($cn, "SELECT Nombre FROM cursos WHERE ID = ?");
mysqli_stmt_bind_param($stmt_curso, "i", $id_curso);
mysqli_stmt_execute($stmt_curso);
mysqli_stmt_bind_result($stmt_curso, $nombre_curso);
mysqli_stmt_fetch($stmt_curso);
mysqli_stmt_close($stmt_curso);

// Consultar subtemas del curso
$stmt_subtemas = mysqli_prepare($cn, "SELECT ID, Nombre FROM subtemas WHERE IDCurso = ?");
mysqli_stmt_bind_param($stmt_subtemas, "i", $id_curso);
mysqli_stmt_execute($stmt_subtemas);
mysqli_stmt_bind_result($stmt_subtemas, $subtema_id, $subtema_nombre);

$subtemas = [];
while (mysqli_stmt_fetch($stmt_subtemas)) {
    $subtemas[] = [
        'ID' => $subtema_id,
        'Nombre' => $subtema_nombre
    ];
}
mysqli_stmt_close($stmt_subtemas);

// Procesar inserción de pregunta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['subtema_id']) && isset($_POST['pregunta']) && isset($_POST['opcionA']) && isset($_POST['opcionB']) && isset($_POST['opcionC']) && isset($_POST['respuesta_correcta'])) {
        $subtema_id = $_POST['subtema_id'];
        $pregunta = $_POST['pregunta'];
        $opcionA = $_POST['opcionA'];
        $opcionB = $_POST['opcionB'];
        $opcionC = $_POST['opcionC'];
        $respuesta_correcta = $_POST['respuesta_correcta'];

        // Insertar nueva pregunta en la base de datos
        $stmt_insert = mysqli_prepare($cn, "INSERT INTO preguntas (IDCurso, IDSubtema, Pregunta, OpcionA, OpcionB, OpcionC, RespuestaCorrecta) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_insert, "iisssss", $id_curso, $subtema_id, $pregunta, $opcionA, $opcionB, $opcionC, $respuesta_correcta);
        mysqli_stmt_execute($stmt_insert);
        mysqli_stmt_close($stmt_insert);

        // Redirigir para evitar reenvío de formulario
        header("Location: preguntas_curso.php?id=" . $id_curso);
        exit();
    }
}

// Procesar eliminación de pregunta
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
    $pregunta_id_eliminar = $_GET['eliminar'];

    // Eliminar pregunta de la base de datos
    $stmt_delete = mysqli_prepare($cn, "DELETE FROM preguntas WHERE ID = ?");
    mysqli_stmt_bind_param($stmt_delete, "i", $pregunta_id_eliminar);
    mysqli_stmt_execute($stmt_delete);

    // Verificar si se eliminó correctamente
    if (mysqli_stmt_affected_rows($stmt_delete) > 0) {
        echo "<script>alert('Pregunta eliminada correctamente.');</script>";
    } else {
        echo "<script>alert('No se pudo eliminar la pregunta.');</script>";
    }
    mysqli_stmt_close($stmt_delete);

    // Redirigir para evitar reenvío de formulario
    header("Location: preguntas_curso.php?id=" . $id_curso);
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas del Curso "<?php echo $nombre_curso; ?>" - Panel Administrativo</title>
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
            background-color: #2d2d2d;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        }

        .table {
            background-color: #373737;
            /* Color de fondo de la tabla */
            color: #ffffff;
            /* Color de texto dentro de la tabla */
        }

        .table th {
            background-color: #484848;
            /* Color de fondo de las celdas de encabezado */
            color: #ffffff;
            /* Color de texto dentro de las celdas de encabezado */
        }

        .table td {
            padding: 15px;
            background-color: #757575;
            /* Color de fondo de las celdas */
            color: #ffffff;
            /* Color de texto dentro de las celdas */
        }

        .table thead {
            background-color: #343a40;
            /* Color de fondo del encabezado de la tabla */
        }

        .table-title {
            margin-bottom: 30px;
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

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
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
        <h2 class="table-title">Preguntas por Subtema del Curso "<?php echo $nombre_curso; ?>"</h2>
        <?php foreach ($subtemas as $subtema) : ?>
            <h3><?php echo $subtema['Nombre']; ?></h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pregunta</th>
                        <th>Opción A</th>
                        <th>Opción B</th>
                        <th>Opción C</th>
                        <th>Respuesta Correcta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consultar preguntas del subtema actual
                    $stmt_preguntas = mysqli_prepare($cn, "SELECT ID, Pregunta, OpcionA, OpcionB, OpcionC, RespuestaCorrecta FROM preguntas WHERE IDCurso = ? AND IDSubtema = ?");
                    mysqli_stmt_bind_param($stmt_preguntas, "ii", $id_curso, $subtema['ID']);
                    mysqli_stmt_execute($stmt_preguntas);
                    mysqli_stmt_bind_result($stmt_preguntas, $pregunta_id, $pregunta, $opcionA, $opcionB, $opcionC, $respuesta_correcta);

                    while (mysqli_stmt_fetch($stmt_preguntas)) :
                    ?>
                        <tr>
                            <td><?php echo $pregunta_id; ?></td>
                            <td><?php echo $pregunta; ?></td>
                            <td><?php echo $opcionA; ?></td>
                            <td><?php echo $opcionB; ?></td>
                            <td><?php echo $opcionC; ?></td>
                            <td><?php echo $respuesta_correcta; ?></td>
                            <td>
                                <a href="preguntas_curso.php?id=<?php echo $id_curso; ?>&eliminar=<?php echo $pregunta_id; ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php mysqli_stmt_close($stmt_preguntas); ?>
                </tbody>
            </table>
        <?php endforeach; ?>
        <!-- Formulario para agregar nueva pregunta -->
        <div class="mt-4">
            <h3>Agregar Nueva Pregunta</h3>
            <form method="POST">
                <div class="mb-3">
                    <label for="subtema_id" class="form-label">Subtema</label>
                    <select class="form-select" id="subtema_id" name="subtema_id" required>
                        <option value="">Selecciona un subtema...</option>
                        <?php foreach ($subtemas as $subtema) : ?>
                            <option value="<?php echo $subtema['ID']; ?>"><?php echo $subtema['Nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="pregunta" class="form-label">Pregunta</label>
                    <input type="text" class="form-control" id="pregunta" name="pregunta" required>
                </div>
                <div class="mb-3">
                    <label for="opcionA" class="form-label">Opción A</label>
                    <input type="text" class="form-control" id="opcionA" name="opcionA" required>
                </div>
                <div class="mb-3">
                    <label for="opcionB" class="form-label">Opción B</label>
                    <input type="text" class="form-control" id="opcionB" name="opcionB" required>
                </div>
                <div class="mb-3">
                    <label for="opcionC" class="form-label">Opción C</label>
                    <input type="text" class="form-control" id="opcionC" name="opcionC" required>
                </div>
                <div class="mb-3">
                    <label for="respuesta_correcta" class="form-label">Respuesta Correcta (A, B o C)</label>
                    <input type="text" class="form-control" id="respuesta_correcta" name="respuesta_correcta" maxlength="1" required>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar Pregunta</button>
            </form>
        </div>
        <div class="text-end mt-4">
            <a href="admin_preguntas.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Regresar</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>