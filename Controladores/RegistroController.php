<?php
session_start();
require_once '../Conexion/conectar.php'; // Asegúrate de que la ruta sea correcta

$register_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    // Verificación adicional para numero_trabajador
    $numeroTrabajador = isset($_POST['numero_trabajador']) ? $_POST['numero_trabajador'] : '';

    // Manejar la carga de la imagen
    $target_dir = "C:/xampp/htdocs/RANOF-WEB/Recursos/imagenes/";
    // Ajusta la ruta si es necesario
    $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar si el archivo es una imagen real
    $check = getimagesize($_FILES["imagen"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $register_error = "El archivo no es una imagen.";
        $uploadOk = 0;
    }

    // Verificar si el archivo ya existe
    if (file_exists($target_file)) {
        $register_error = "Lo siento, el archivo ya existe.";
        $uploadOk = 0;
    }

    // Verificar el tamaño del archivo
    if ($_FILES["imagen"]["size"] > 500000) {
        $register_error = "Lo siento, tu archivo es demasiado grande.";
        $uploadOk = 0;
    }

    // Permitir solo ciertos formatos de archivo
    $allowed_formats = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowed_formats)) {
        $register_error = "Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.";
        $uploadOk = 0;
    }

    // Verificar si hubo algún error con la subida del archivo
    if ($_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
        $register_error = "Error al subir el archivo: " . $_FILES["imagen"]["error"];
        $uploadOk = 0;
    }

    // Verificar si $uploadOk es 0 por un error
    if ($uploadOk == 0) {
        $register_error .= " Tu archivo no fue subido.";
        header("Location: ../index.php?register_error=" . urlencode($register_error));
        exit();
    } else {
        // Verificar si el correo o número de trabajador ya está registrado
        $stmt = mysqli_prepare($cn, "SELECT ID FROM transportistas WHERE CorreoElectronico = ? OR NumeroTrabajador = ?");
        mysqli_stmt_bind_param($stmt, "ss", $correo, $numeroTrabajador);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $register_error = "Ya existe una cuenta con este correo electrónico o número de trabajador.";
            header("Location: ../index.php?register_error=" . urlencode($register_error));
            exit();
        } else {
            // Si el correo y el número de trabajador son únicos, entonces subir la imagen y registrar al usuario
            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                // Insertar nuevo usuario con el nuevo campo NumeroTrabajador
                $stmt = mysqli_prepare($cn, "INSERT INTO transportistas (Nombre, Apellido, CorreoElectronico, Contraseña, Imagen, NumeroTrabajador, EstaActivo) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $estaActivo = 1; // valor por defecto
                mysqli_stmt_bind_param($stmt, "ssssssi", $nombre, $apellido, $correo, $contrasena, $target_file, $numeroTrabajador, $estaActivo);
                if (mysqli_stmt_execute($stmt)) {
                    // Registro exitoso, redirigir al formulario de inicio de sesión
                    header("Location: ../index.php?register_success=1");
                    exit();
                } else {
                    $register_error = "Error al registrar el usuario: " . mysqli_error($cn);
                    header("Location: ../index.php?register_error=" . urlencode($register_error));
                    exit();
                }
            } else {
                // Depurar la razón del fallo en move_uploaded_file
                if (!is_writable($target_dir)) {
                    $register_error = "La carpeta de destino no tiene permisos de escritura.";
                } else {
                    $register_error = "Lo siento, hubo un error al subir tu archivo.";
                }
                header("Location: ../index.php?register_error=" . urlencode($register_error));
                exit();
            }
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($cn);
