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

// Consulta para obtener usuarios que han obtenido certificados y de qué curso
$query = "SELECT t.ID AS IDTransportista, t.Nombre AS NombreTransportista, t.Apellido AS ApellidoTransportista, c.ID AS IDCurso, c.Nombre AS NombreCurso
          FROM transportistas t
          INNER JOIN certificados cert ON t.ID = cert.IDTransportista
          INNER JOIN cursos c ON cert.IDCurso = c.ID
          ORDER BY t.ID, c.ID";

$result = mysqli_query($cn, $query);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios con Certificados - Panel Administrativo</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="admin_transportistas.php">Transportistas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="table-title">Usuarios con Certificados</h2>
        <?php if (mysqli_num_rows($result) > 0) : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Transportista</th>
                        <th>Nombre Transportista</th>
                        <th>Apellido Transportista</th>
                        <th>ID Curso</th>
                        <th>Nombre Curso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo $row['IDTransportista']; ?></td>
                            <td><?php echo $row['NombreTransportista']; ?></td>
                            <td><?php echo $row['ApellidoTransportista']; ?></td>
                            <td><?php echo $row['IDCurso']; ?></td>
                            <td><?php echo $row['NombreCurso']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No se encontraron usuarios con certificados.</p>
        <?php endif; ?>
        <a href="../admin.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Regresar</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php mysqli_close($cn); ?>