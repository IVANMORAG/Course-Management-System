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

// Consultar cursos desde la base de datos
$stmt = mysqli_prepare($cn, "SELECT ID, Nombre, Descripcion, Estado, FechaActualizacion, CertificadoRequerido FROM cursos");
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $id, $nombre, $descripcion, $estado, $fecha_actualizacion, $certificado_requerido);

// Almacenar resultados en un arreglo
$cursos = [];
while (mysqli_stmt_fetch($stmt)) {
    $cursos[] = [
        'ID' => $id,
        'Nombre' => $nombre,
        'Descripcion' => $descripcion,
        'Estado' => $estado,
        'FechaActualizacion' => $fecha_actualizacion,
        'CertificadoRequerido' => $certificado_requerido
    ];
}
mysqli_stmt_close($stmt);

// Función para obtener la URL actual
function getCurrentURL()
{
    $url = $_SERVER['REQUEST_URI'];
    return strtok($url, '?');
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos - Panel Administrativo</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            padding: 25px;
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

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
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
                    <li class="nav-item">
                        <a class="nav-link" href="admin_transportistas.php">Transportistas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="table-title">Cursos</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Certificado Requerido</th>
                    <th scope="col">Fecha Actualización</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cursos as $curso) : ?>
                    <tr>
                        <th scope="row"><?php echo $curso['ID']; ?></th>
                        <td><?php echo $curso['Nombre']; ?></td>
                        <td><?php echo $curso['Descripcion']; ?></td>
                        <td><?php echo $curso['Estado']; ?></td>
                        <td><?php echo $curso['CertificadoRequerido'] ? 'Sí' : 'No'; ?></td>
                        <td><?php echo $curso['FechaActualizacion']; ?></td>
                        <td>
                            <!-- Botón de Activar/Desactivar con formulario -->
                            <form method="POST" action="activar_curso.php">
                                <input type="hidden" name="curso_id" value="<?php echo $curso['ID']; ?>">
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-power-off"></i> <?php echo $curso['Estado'] == 'Activo' ? 'Desactivar' : 'Activar'; ?></button>
                                    <a href="borrar_curso.php?id=<?php echo $curso['ID']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este curso?');" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Eliminar</a>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-end">
            <a href="agregar_curso.php" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar Nuevo Curso</a>
            <a href="../admin.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Regresar</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Cerrar la conexión
mysqli_close($cn);
?>