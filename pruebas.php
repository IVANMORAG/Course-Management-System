<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pruebas Psicométricas</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="Recursos/css/style.css">
    <!-- Otros enlaces de estilos o scripts que necesites -->
    <style>
        /* Estilos adicionales personalizados */
        .container {
            max-width: 1200px;
            /* Aumentado para permitir más tarjetas en una fila */
            margin: 0 auto;
            padding: 60px;
            display: flex;
            flex-wrap: wrap;
            /* Permite que las tarjetas se alineen en filas múltiples */
            justify-content: space-between;
            /* Espacio entre tarjetas */
        }

        .card {
            background-color: #f0f0f0;
            border: 1px solid #dddddd;
            padding: 50px;
            transition: transform 0.3s ease;
            flex: 0 0 48%;
            /* Tamaño de las tarjetas, puedes ajustarlo según sea necesario */
            margin-bottom: 60px;
            /* Espacio entre filas */
            box-sizing: border-box;
            /* Incluye padding y border en el ancho total */
        }

        .card:hover {
            transform: translateY(-10px);
            /* Efecto de elevación al hacer hover */
        }

        .card-title {
            font-size: 3rem;
            /* Tamaño de letra más grande */
            font-weight: bold;
            margin-bottom: 25px;
            text-align: center;
        }

        .card-subtitle {
            font-size: 1.5rem;
            /* Tamaño de letra más grande */
            margin-bottom: 10px;
            text-align: center;
            /* Alineado al centro */
        }

        .form-check-input {
            margin-right: 20px;
            /* Espacio aumentado */
        }

        .form-check-label {
            color: #333333;
            font-size: 1.5rem;
            /* Tamaño de letra más grande */
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: #ffffff;
            padding: 12px 25px;
            /* Padding ajustado */
            transition: background-color 0.3s ease;
            font-size: 1.2rem;
            /* Tamaño de letra más grande */
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .result-container {
            max-width: 800px;
            margin-top: 10px;
            padding: 60px;
            /* Padding aumentado */
            background-color: #BDBDBD;
            border: 1px solid #dddddd;
            text-align: center;
            justify-content: center;
            /* Alineado al centro */
            border-radius: 8px;
            /* Bordes redondeados */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Sombra inicial */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            /* Transición suave para la elevación */
        }

        .result-container {
            max-width: 800px;
            margin-top: 10px;
            padding: 25px;
            /* Padding aumentado */
            background-color: #f0f0f0;
            border: 1px solid #dddddd;
            text-align: center;
            justify-content: center;
            /* Alineado al centro */
            border-radius: 8px;
            /* Bordes redondeados */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Sombra inicial */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            /* Transición suave para la elevación */
        }

        .result-container:hover {
            transform: translateY(-10px);
            /* Efecto de elevación al hacer hover */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            /* Sombra aumentada al hacer hover */
        }

        .result-title {
            padding: 10px;
            font-size: 3rem;
            /* Tamaño de letra más grande */
            font-weight: bold;
            margin-bottom: 15px;
        }

        .result-text {
            font-size: 2rem;
            /* Tamaño de letra más grande */
            margin-bottom: 10px;
        }
    </style>

</head>


<body>
    <?php include 'header.php'; ?>
    <?php include 'side-bar.php'; ?>


    <?php
    // Incluir el archivo de conexión a la base de datos
    require_once 'Conexion/conectar.php';

    // Función para calcular el puntaje total (ejemplo)
    function calcularPuntajeTotal($respuestas)
    {
        // Ejemplo: Sumar puntajes de las respuestas seleccionadas
        $puntaje_total = 0;
        foreach ($respuestas as $respuesta) {
            $puntaje_total += $respuesta;
        }
        return $puntaje_total;
    }

    // Procesamiento del formulario de pruebas
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['id_prueba']) && isset($_POST['respuestas'])) {
            // Obtener el ID del usuario desde la sesión
            $user_id = $_SESSION['user_id'];
            // ID de la prueba desde el formulario
            $id_prueba = $_POST['id_prueba'];

            // Calcular el puntaje total
            $puntaje_total = calcularPuntajeTotal($_POST['respuestas']);

            // Verificar si ya existe un registro reciente para esta prueba y usuario
            $sql_check = "SELECT FechaRealizacion FROM resultados
                      WHERE IDTransportista = ? AND IDPrueba = ?
                      ORDER BY FechaRealizacion DESC
                      LIMIT 1";
            $stmt_check = $cn->prepare($sql_check);
            $stmt_check->bind_param("ii", $user_id, $id_prueba);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                $row_check = $result_check->fetch_assoc();
                $fecha_realizacion = new DateTime($row_check['FechaRealizacion']);
                $fecha_actual = new DateTime();
                $intervalo = $fecha_actual->diff($fecha_realizacion);

                // Si han pasado menos de 15 días desde la última realización
                if ($intervalo->days < 15) {
                    echo "<script>
                        Swal.fire({
                            icon: 'info',
                            title: 'Oops...',
                            text: 'Ya has realizado esta prueba recientemente. Puedes volver a intentarlo después de " . (15 - $intervalo->days) . " días.'
                        });
                      </script>";
                } else {
                    // Actualizar el registro existente
                    $sql_update = "UPDATE resultados
                               SET PuntajeTotal = ?, FechaRealizacion = CURRENT_TIMESTAMP
                               WHERE IDTransportista = ? AND IDPrueba = ?";
                    $stmt_update = $cn->prepare($sql_update);
                    $stmt_update->bind_param("iii", $puntaje_total, $user_id, $id_prueba);

                    if ($stmt_update->execute()) {
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'Resultado actualizado exitosamente.'
                            });
                          </script>";
                    } else {
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error al actualizar el resultado: " . $stmt_update->error . "'
                            });
                          </script>";
                    }

                    $stmt_update->close();
                }
            } else {
                // Insertar un nuevo registro si no existe ninguno previo
                $sql_insert = "INSERT INTO resultados (IDTransportista, IDPrueba, PuntajeTotal)
                           VALUES (?, ?, ?)";
                $stmt_insert = $cn->prepare($sql_insert);
                $stmt_insert->bind_param("iii", $user_id, $id_prueba, $puntaje_total);

                if ($stmt_insert->execute()) {
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Resultado guardado exitosamente.'
                        });
                      </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error al guardar el resultado: " . $stmt_insert->error . "'
                        });
                      </script>";
                }

                $stmt_insert->close();
            }

            $stmt_check->close();
        }
    }

    ?>

    <div class="container py-4">

        <?php
        // Consulta SQL para obtener las pruebas
        $sql = "SELECT * FROM pruebas";
        $result = $cn->query($sql);

        while ($row = $result->fetch_assoc()) :
        ?>
            <div class="row mb-4">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title"><?= htmlspecialchars($row['Nombre']) ?></h2>
                            <form method="POST" action="pruebas.php">
                                <input type="hidden" name="id_prueba" value="<?= $row['ID'] ?>">

                                <?php
                                // Obtener las preguntas de la prueba
                                $sql_preguntas = "SELECT * FROM preguntasmental WHERE IDPrueba = ?";
                                $stmt_preguntas = $cn->prepare($sql_preguntas);
                                $stmt_preguntas->bind_param("i", $row['ID']);
                                $stmt_preguntas->execute();
                                $result_preguntas = $stmt_preguntas->get_result();

                                while ($row_pregunta = $result_preguntas->fetch_assoc()) :
                                ?>
                                    <h3 class="card-subtitle mb-3"><?= htmlspecialchars($row_pregunta['Texto']) ?></h3>

                                    <?php
                                    // Obtener las respuestas de la pregunta
                                    $sql_respuestas = "SELECT * FROM respuestas WHERE IDPregunta = ?";
                                    $stmt_respuestas = $cn->prepare($sql_respuestas);
                                    $stmt_respuestas->bind_param("i", $row_pregunta['ID']);
                                    $stmt_respuestas->execute();
                                    $result_respuestas = $stmt_respuestas->get_result();

                                    while ($row_respuesta = $result_respuestas->fetch_assoc()) :
                                    ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="respuestas[<?= $row_pregunta['ID'] ?>]" id="respuesta_<?= $row_respuesta['ID'] ?>" value="<?= $row_respuesta['ID'] ?>" required>
                                            <label class="form-check-label" for="respuesta_<?= $row_respuesta['ID'] ?>"><?= htmlspecialchars($row_respuesta['Texto']) ?></label>
                                        </div>
                                <?php endwhile;
                                    $stmt_respuestas->close();
                                endwhile;
                                $stmt_preguntas->close();
                                ?>

                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </div>
                            </form>
                        </div><!-- .card-body -->
                    </div><!-- .card -->
                </div><!-- .col-md-8 offset-md-2 -->
            </div><!-- .row -->
        <?php endwhile; ?>

        <!-- Resultados de las pruebas -->
        <div class="result-container">
            <h1 class="result-title">Resultados de Pruebas Psicométricas</h1>
            <?php
            // Consulta SQL para obtener los resultados de las pruebas del usuario
            $sql_resultados = "SELECT p.Nombre AS NombrePrueba, r.PuntajeTotal, r.FechaRealizacion
                       FROM resultados r
                       INNER JOIN pruebas p ON r.IDPrueba = p.ID
                       WHERE r.IDTransportista = ?";
            $stmt_resultados = $cn->prepare($sql_resultados);
            $stmt_resultados->bind_param("i", $_SESSION['user_id']);
            $stmt_resultados->execute();
            $result_resultados = $stmt_resultados->get_result();

            if ($result_resultados->num_rows > 0) :
                while ($row_resultado = $result_resultados->fetch_assoc()) :
            ?>
                    <div class="mb-4">
                        <h2 class="result-text"><?= htmlspecialchars($row_resultado['NombrePrueba']) ?></h2>
                        <p class="result-text">Puntaje Total: <?= htmlspecialchars($row_resultado['PuntajeTotal']) ?></p>
                        <p class="result-text">Fecha de Realización: <?= htmlspecialchars($row_resultado['FechaRealizacion']) ?></p>
                    </div>
            <?php
                endwhile;
            else :
                echo "<p class='result-text'>No se encontraron resultados de pruebas.</p>";
            endif;
            $stmt_resultados->close();
            $cn->close();
            ?>
        </div><!-- .result-container -->
    </div><!-- .container -->



    <?php
    include 'footer.php';
    ?>

    <!-- Enlace al archivo JavaScript personalizado -->
    <script src="Recursos/js/script.js"></script>
    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>