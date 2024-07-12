<?php
session_start();
require_once '../Conexion/conectar.php'; // Asegúrate de que la ruta sea correcta

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Preparar y ejecutar la consulta
    $stmt = mysqli_prepare($cn, "SELECT ID, Contraseña, Nombre, Apellido, Imagen, EsAdmin FROM transportistas WHERE CorreoElectronico = ? AND EstaActivo = 1");
    mysqli_stmt_bind_param($stmt, "s", $correo);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    // Verificar si se encontró el usuario
    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $userID, $hashed_password, $nombre, $apellido, $imagen, $esAdmin);
        mysqli_stmt_fetch($stmt);

        // Verificar la contraseña
        if (password_verify($contrasena, $hashed_password)) {
            // Iniciar sesión
            $_SESSION['user_id'] = $userID;
            $_SESSION['correo'] = $correo;
            $_SESSION['user'] = $nombre . ' ' . $apellido; // Nombre completo del usuario
            $_SESSION['foto'] = $imagen; // Ruta de la imagen del usuario
            $_SESSION['is_admin'] = $esAdmin; // Establecer si el usuario es administrador
            $_SESSION['loggedin'] = true; // Establecer sesión iniciada correctamente

            // Redirigir a la página principal (home.php)
            header("Location: ../home.php");
            exit();
        } else {
            $login_error = "Contraseña incorrecta.";
            header("Location: ../index.php?login_error=" . urlencode($login_error));
            exit();
        }
    } else {
        $login_error = "No se encontró un usuario activo con ese correo electrónico.";
        header("Location: ../index.php?login_error=" . urlencode($login_error));
        exit();
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($cn);
