<?php
session_start();
require_once '../Conexion/conectar.php';
require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verificar sesión y permisos de administrador
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Consultar si el usuario es administrador
$stmt_admin = mysqli_prepare($cn, "SELECT EsAdmin FROM transportistas WHERE ID = ?");
mysqli_stmt_bind_param($stmt_admin, "i", $user_id);
mysqli_stmt_execute($stmt_admin);
mysqli_stmt_bind_result($stmt_admin, $es_admin);
mysqli_stmt_fetch($stmt_admin);
mysqli_stmt_close($stmt_admin);

if (!$es_admin) {
    echo "<script>alert('Necesitas ser administrador para acceder a esta página.'); window.location.href = 'home.php';</script>";
    exit();
}

// Función para obtener el nombre del transportista por ID
function obtenerNombreTransportista($idTransportista, $conexion)
{
    $nombre = "";

    $stmt = mysqli_prepare($conexion, "SELECT Nombre FROM transportistas WHERE ID = ?");
    mysqli_stmt_bind_param($stmt, "i", $idTransportista);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nombre);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $nombre;
}

// Función para obtener el nombre de la prueba por ID
function obtenerNombrePrueba($idPrueba, $conexion)
{
    $nombrePrueba = "";

    $stmt = mysqli_prepare($conexion, "SELECT Nombre FROM pruebas WHERE ID = ?");
    mysqli_stmt_bind_param($stmt, "i", $idPrueba);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nombrePrueba);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $nombrePrueba;
}

// Consulta para obtener todos los resultados de las pruebas
$sql = "SELECT r.ID, r.IDTransportista, r.IDPrueba, r.FechaRealizacion, r.PuntajeTotal, t.CorreoElectronico
        FROM resultados r
        INNER JOIN transportistas t ON r.IDTransportista = t.ID
        ORDER BY r.FechaRealizacion DESC";

$resultado = mysqli_query($cn, $sql);

// Manejo de errores en la consulta
if (!$resultado) {
    echo "Error al ejecutar la consulta: " . mysqli_error($cn);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['IDTransportista']) && isset($_POST['IDPrueba'])) {
    $idTransportista = $_POST['IDTransportista'];
    $idPrueba = $_POST['IDPrueba'];

    // Obtener el correo del transportista
    $stmt_correo = mysqli_prepare($cn, "SELECT CorreoElectronico FROM transportistas WHERE ID = ?");
    mysqli_stmt_bind_param($stmt_correo, "i", $idTransportista);
    mysqli_stmt_execute($stmt_correo);
    mysqli_stmt_bind_result($stmt_correo, $correoElectronico);
    mysqli_stmt_fetch($stmt_correo);
    mysqli_stmt_close($stmt_correo);

    // Obtener el puntaje total específico para la combinación de IDTransportista e IDPrueba
    $stmt_puntaje = mysqli_prepare($cn, "SELECT PuntajeTotal FROM resultados WHERE IDTransportista = ? AND IDPrueba = ?");
    mysqli_stmt_bind_param($stmt_puntaje, "ii", $idTransportista, $idPrueba);
    mysqli_stmt_execute($stmt_puntaje);
    mysqli_stmt_bind_result($stmt_puntaje, $puntajeTotal);
    mysqli_stmt_fetch($stmt_puntaje);
    mysqli_stmt_close($stmt_puntaje);

    // Obtener el nombre de la prueba
    $nombrePrueba = obtenerNombrePrueba($idPrueba, $cn);

    // Configurar PHPMailer
    $mail = new PHPMailer(true); // true habilita excepciones

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'transportistasweb@gmail.com'; // Cambia esto por tu correo de Gmail
    $mail->Password = 'rahq dnlm khkq wcsg'; // Cambia esto por la contraseña de tu aplicación generada
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('transportistasweb@gmail.com', 'DeCasa');
    $mail->addAddress($correoElectronico);

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Resultado de la Prueba';

    if ($puntajeTotal < 20) {
        $mail->Body = "Hola,<br><br>Tu puntaje en la prueba '$nombrePrueba' ha sido $puntajeTotal. Te recomendamos tomar cursos adicionales.<br><br>Saludos, Equipo DeCasa.";
    } else {
        $mail->Body = "Hola,<br><br>Felicidades por tu puntaje de $puntajeTotal en la prueba '$nombrePrueba'.<br><br>Saludos, Equipo DeCasa.";
    }

    // Envío del correo
    try {
        $mail->send();
        $mensaje = "Se ha enviado un correo a $correoElectronico con el resultado de la prueba.";
    } catch (Exception $e) {
        $mensaje = "El correo no pudo ser enviado. Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Administrar Resultados</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../admin/styles/styles.css">
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
            color: #ffffff;
        }

        .btn {
            margin-right: 5px;
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
                        <a class="nav-link" href="admin_preguntasmental.php">Preguntas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_respuestas.php">Respuestas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="admin_resultados.php">Resultados</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <br>

    <div class="container">
        <h1>Administrar Resultados</h1>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Transportista ID</th>
                    <th>Nombre del Transportista</th>
                    <th>Nombre de la Prueba</th>
                    <th>Fecha de Realización</th>
                    <th>Puntaje Total</th>
                    <th>Correo Electrónico</th>
                    <th>Enviar Correo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($resultado)) {
                    $nombreTransportista = obtenerNombreTransportista($row['IDTransportista'], $cn);
                    $nombrePrueba = obtenerNombrePrueba($row['IDPrueba'], $cn);

                    echo "<tr>";
                    echo "<td>" . $row['ID'] . "</td>";
                    echo "<td>" . $row['IDTransportista'] . "</td>";
                    echo "<td>" . $nombreTransportista . "</td>";
                    echo "<td>" . $nombrePrueba . "</td>";
                    echo "<td>" . $row['FechaRealizacion'] . "</td>";
                    echo "<td>" . $row['PuntajeTotal'] . "</td>";
                    echo "<td>" . $row['CorreoElectronico'] . "</td>";
                    echo "<td>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='IDTransportista' value='" . $row['IDTransportista'] . "'>";
                    echo "<input type='hidden' name='IDPrueba' value='" . $row['IDPrueba'] . "'>";
                    echo "<button type='submit' name='correo' class='btn btn-info btn-sm' title='Enviar Correo'><i class='fas fa-envelope'></i></button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
        if (isset($mensaje)) {
            echo "<div class='alert alert-info mt-4'>$mensaje</div>";
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>