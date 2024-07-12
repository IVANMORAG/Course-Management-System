<?php
include 'Conexion/conectar.php'; // Incluye tu archivo de conexión a la base de datos

// Verifica que se recibieron datos JSON válidos
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['IDUsuario']) || !isset($data['IDCurso']) || !isset($data['IDSubtema']) || !isset($data['Puntaje'])) {
    $response['success'] = false;
    $response['error'] = 'Datos JSON no válidos';
    echo json_encode($response);
    exit;
}

// Asigna los datos recibidos a variables
$IDUsuario = $data['IDUsuario'];
$IDCurso = $data['IDCurso'];
$IDSubtema = $data['IDSubtema'];
$Puntaje = $data['Puntaje'];

// Iniciar la transacción
$cn->begin_transaction();

try {
    // Verificar si ya existe un registro con las mismas claves
    $sql_select = "SELECT ID FROM resultados_quiz WHERE IDUsuario = ? AND IDCurso = ? AND IDSubtema = ?";
    $stmt_select = $cn->prepare($sql_select);
    $stmt_select->bind_param("iii", $IDUsuario, $IDCurso, $IDSubtema);
    $stmt_select->execute();
    $stmt_select->store_result();

    if ($stmt_select->num_rows > 0) {
        // Ya existe un registro, entonces actualizamos el puntaje
        $sql_update = "UPDATE resultados_quiz SET Puntaje = ? WHERE IDUsuario = ? AND IDCurso = ? AND IDSubtema = ?";
        $stmt_update = $cn->prepare($sql_update);
        $stmt_update->bind_param("iiii", $Puntaje, $IDUsuario, $IDCurso, $IDSubtema);

        if ($stmt_update->execute()) {
            $response['success'] = true;
        } else {
            throw new Exception("Error al actualizar el registro: " . $stmt_update->error);
        }
    } else {
        // No existe un registro, insertamos uno nuevo
        $sql_insert = "INSERT INTO resultados_quiz (IDUsuario, IDCurso, IDSubtema, Puntaje) VALUES (?, ?, ?, ?)";
        $stmt_insert = $cn->prepare($sql_insert);
        $stmt_insert->bind_param("iiii", $IDUsuario, $IDCurso, $IDSubtema, $Puntaje);

        if ($stmt_insert->execute()) {
            $response['success'] = true;
        } else {
            throw new Exception("Error al insertar nuevo registro: " . $stmt_insert->error);
        }
    }

    // Confirmar la transacción
    $cn->commit();
} catch (Exception $e) {
    // Si hay algún error, hacer rollback de la transacción
    $cn->rollback();

    $response['success'] = false;
    $response['error'] = $e->getMessage();
}

$stmt_select->close();

// Cerrar la conexión
$cn->close();

// Retorna la respuesta como JSON
echo json_encode($response);
