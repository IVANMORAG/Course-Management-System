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

// Consulta para obtener las revisiones de camiones con detalles adicionales
$query_revisiones = "SELECT rev.ID, tra.Nombre AS Transportista, cam.Placa AS PlacaCamion, sec.Nombre AS Seccion, rev.Estado, rev.Observaciones, est.Nombre AS EstadoCamion
                     FROM revisiones rev
                     LEFT JOIN camiones cam ON rev.IDCamion = cam.ID
                     LEFT JOIN secciones sec ON rev.IDSeccion = sec.ID
                     LEFT JOIN estados_camiones est ON rev.IDEstadoCamion = est.ID
                     LEFT JOIN transportistas tra ON cam.IDTransportista = tra.ID";

$result_revisiones = mysqli_query($cn, $query_revisiones);

if (!$result_revisiones) {
    echo "Error en la consulta: " . mysqli_error($cn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisiones de Camiones - Panel Administrativo</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid #ffffff;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #343a40;
            color: #ffffff;
        }

        table tr:nth-child(even) {
            background-color: #373737;
        }

        table tr:hover {
            background-color: #484848;
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
            <h2 class="mb-4">Registro de Revisiones de Camiones</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                    <label for="id_camion" class="form-label">ID del Camión:</label>
                    <input type="number" class="form-control" id="id_camion" name="id_camion" required>
                </div>
                <div class="mb-3">
                    <label for="id_seccion" class="form-label">ID de la Sección:</label>
                    <input type="number" class="form-control" id="id_seccion" name="id_seccion" required>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado:</label>
                    <select class="form-select" id="estado" name="estado" required>
                        <option value="Buen estado">Buen estado</option>
                        <option value="Necesita revisión">Necesita revisión</option>
                        <option value="Requiere reparación">Requiere reparación</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones:</label>
                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-submit"><i class="fas fa-save"></i> Registrar Revisión</button>
            </form>
        </div>

        <div class="mt-4">
            <h2>Revisiones de Camiones Registradas</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Transportista</th>
                        <th>Placa del Camión</th>
                        <th>Sección</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                        <th>Estado del Camión</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result_revisiones)) {
                        echo "<tr>";
                        echo "<td>" . $row['ID'] . "</td>";
                        echo "<td>" . $row['Transportista'] . "</td>";
                        echo "<td>" . $row['PlacaCamion'] . "</td>";
                        echo "<td>" . $row['Seccion'] . "</td>";
                        echo "<td>" . $row['Estado'] . "</td>";
                        echo "<td>" . $row['Observaciones'] . "</td>";
                        echo "<td>" . $row['EstadoCamion'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>