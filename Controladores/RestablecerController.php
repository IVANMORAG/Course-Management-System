<?php
require_once '../Conexion/conectar.php';  // Ajusta la ruta según la ubicación real

$register_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $token = $_POST['token'];
    $nueva_contrasena = $_POST['nueva_contrasena']; // Asegúrate de validar y sanitizar esta entrada adecuadamente

    // Verificar si el token es válido y no ha expirado
    $stmt_select = mysqli_prepare($cn, "SELECT CorreoElectronico FROM password_resets WHERE Token = ? AND Expiration > NOW()");
    mysqli_stmt_bind_param($stmt_select, "s", $token);
    mysqli_stmt_execute($stmt_select);
    mysqli_stmt_store_result($stmt_select);

    if (mysqli_stmt_num_rows($stmt_select) > 0) {
        mysqli_stmt_bind_result($stmt_select, $correo);
        mysqli_stmt_fetch($stmt_select);

        // Actualizar la contraseña del usuario
        $hashed_password = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        $stmt_update = mysqli_prepare($cn, "UPDATE transportistas SET Contraseña = ? WHERE CorreoElectronico = ?");
        mysqli_stmt_bind_param($stmt_update, "ss", $hashed_password, $correo);
        mysqli_stmt_execute($stmt_update);

        // Eliminar el token de la base de datos
        $stmt_delete = mysqli_prepare($cn, "DELETE FROM password_resets WHERE Token = ?");
        mysqli_stmt_bind_param($stmt_delete, "s", $token);
        mysqli_stmt_execute($stmt_delete);

        // Cerrar todas las consultas preparadas
        mysqli_stmt_close($stmt_select);
        mysqli_stmt_close($stmt_update);
        mysqli_stmt_close($stmt_delete);

        // Redirigir al usuario después de restablecer la contraseña
        header("Location: ../index.php?register_success=1");
        exit();
    } else {
        $register_error = "El enlace de recuperación es inválido o ha expirado.";
        header("Location: ../index.php?register_error=" . urlencode($register_error));
        exit();
    }

    // Cerrar la conexión
    mysqli_close($cn);
}
