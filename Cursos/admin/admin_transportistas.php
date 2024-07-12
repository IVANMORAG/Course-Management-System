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

// Consultar transportistas desde la base de datos
$stmt = mysqli_prepare($cn, "SELECT ID, NumeroTrabajador, Nombre, Apellido, CorreoElectronico, EstaActivo, EsAdmin FROM transportistas");
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $id, $numero_trabajador, $nombre, $apellido, $correo_electronico, $esta_activo, $es_admin);

// Almacenar resultados en un arreglo
$transportistas = [];
while (mysqli_stmt_fetch($stmt)) {
    $transportistas[] = [
        'ID' => $id,
        'NumeroTrabajador' => $numero_trabajador,
        'Nombre' => $nombre,
        'Apellido' => $apellido,
        'CorreoElectronico' => $correo_electronico,
        'EstaActivo' => $esta_activo,
        'EsAdmin' => $es_admin
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
    <title>Transportistas - Panel Administrativo</title>
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
            /* Color de fondo de las celdas */
            color: #ffffff;
            /* Color de texto dentro de las celdas */
        }

        .table td {
            background-color: #9E9E9E;
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
        <h2 class="table-title">Transportistas</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Número Trabajador</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido</th>
                    <th scope="col">Correo Electrónico</th>
                    <th scope="col">Activo</th>
                    <th scope="col">Admin</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($transportistas as $transportista) {
                    echo '<tr>';
                    echo '<th scope="row">' . $transportista['ID'] . '</th>';
                    echo '<td style="padding: 25px; background-color: #757575; color: #ffffff;">' . $transportista['NumeroTrabajador'] . '</td>';
                    echo '<td style="padding: 25px; background-color: #757575; color: #ffffff;">' . $transportista['Nombre'] . '</td>';
                    echo '<td style="padding: 25px; background-color: #757575; color: #ffffff;">' . $transportista['Apellido'] . '</td>';
                    echo '<td style="padding: 25px; background-color: #757575; color: #ffffff;">' . $transportista['CorreoElectronico'] . '</td>';
                    echo '<td style="padding: 25px; background-color: #757575; color: #ffffff;">';
                    echo '<select class="form-select" aria-label="Estado Activo" disabled>';
                    echo '<option selected>' . ($transportista['EstaActivo'] ? 'Sí' : 'No') . '</option>';
                    echo '</select>';
                    echo '</td>';
                    echo '<td style="padding: 25px; background-color: #757575; color: #ffffff;">';
                    echo '<select class="form-select" aria-label="Es Admin" disabled>';
                    echo '<option selected>' . ($transportista['EsAdmin'] ? 'Sí' : 'No') . '</option>';
                    echo '</select>';
                    echo '</td>';
                    echo '<td style="padding: 25px; background-color: #757575;">';
                    // Botón de Editar
                    echo '<a href="editar_transportista.php?id=' . $transportista['ID'] . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Editar</a>';
                    // Botón de Borrar con SweetAlert2
                    echo ' <a href="#" onclick="confirmDelete(' . $transportista['ID'] . ')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Borrar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <div class="text-end">
            <a href="agregar_transportista.php" class="btn btn-success"><i class="fas fa-plus"></i> Agregar Nuevo Usuario</a>
            <a href="../admin.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Regresar</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esta acción después.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'borrar_transportista.php?id=' + id;
                }
            });
        }
    </script>
</body>

</html>

<?php
// Cerrar la conexión
mysqli_close($cn);
?>