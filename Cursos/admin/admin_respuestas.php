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

// Procesamiento para eliminar una respuesta si se envía un ID válido por GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $respuestaID = $_GET['id'];
    $stmt = mysqli_prepare($cn, "DELETE FROM respuestas WHERE ID = ?");
    mysqli_stmt_bind_param($stmt, "i", $respuestaID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirigir de vuelta a esta página después de eliminar
    header("Location: admin_respuestas.php");
    exit();
}

// Obtener todas las respuestas y mostrarlas según el ID de pregunta
$sql = "SELECT r.ID, r.Texto, r.Puntaje, p.IDPrueba
        FROM respuestas r
        JOIN preguntasmental p ON r.IDPregunta = p.ID
        ORDER BY p.IDPrueba, r.IDPregunta, r.ID";

$result = $cn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Administrar Respuestas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin/styles/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .table {
            background-color: #373737;
            color: #ffffff;
        }

        .table th {
            background-color: #484848;
            color: #ffffff;
        }

        .table td {
            padding: 15px;
            background-color: #757575;
            color: #ffffff;
        }

        .table thead {
            background-color: #343a40;
        }

        .table-title {
            margin-bottom: 30px;
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
                    <li class="nav-item">
                        <a class="nav-link" href="admin_respuestas.php">Respuestas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br>
    <div class="container">
        <h1>Administrar Respuestas</h1>

        <?php if ($result->num_rows > 0) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Texto</th>
                        <th>Puntaje</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['ID']; ?></td>
                            <td><?php echo $row['Texto']; ?></td>
                            <td><?php echo $row['Puntaje']; ?></td>
                            <td>
                                <a href="edit_respuesta.php?id=<?php echo $row['ID']; ?>" class="btn btn-secondary"><i class="fas fa-edit"></i> Editar</a>
                                <button class="btn btn-danger" onclick="confirmarEliminacion(<?php echo $row['ID']; ?>)"><i class="fas fa-trash-alt"></i> Eliminar</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No hay respuestas para mostrar.</p>
        <?php endif; ?>

        <a href="create_respuesta.php" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar Respuesta</a>
    </div>

    <script>
        // Función para confirmar la eliminación de una respuesta
        function confirmarEliminacion(respuestaID) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminarlo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, redirigir a esta misma página con el ID de la respuesta para eliminar
                    window.location.href = `admin_respuestas.php?id=${respuestaID}`;
                }
            });
        }
    </script>

</body>

</html>