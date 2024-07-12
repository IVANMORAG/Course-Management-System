<?php
require_once '../Conexion/conectar.php';
require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['correo'])) {
    $correo = $_POST['correo'];
    echo "Correo electrónico recibido: " . $correo;

    // Verificar si el correo existe en la base de datos
    $stmt = mysqli_prepare($cn, "SELECT CorreoElectronico FROM transportistas WHERE CorreoElectronico = ?");
    mysqli_stmt_bind_param($stmt, "s", $correo);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $correoElectronico);
        mysqli_stmt_fetch($stmt);

        // Generar un token único para la recuperación de contraseña
        $token = bin2hex(random_bytes(50));

        // Guardar el token en la base de datos con una fecha de expiración
        $stmt_insert = mysqli_prepare($cn, "INSERT INTO password_resets (CorreoElectronico, Token, Expiration) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))");
        mysqli_stmt_bind_param($stmt_insert, "ss", $correoElectronico, $token);
        mysqli_stmt_execute($stmt_insert);

        // Configurar PHPMailer
        $mail = new PHPMailer(true); // true habilita excepciones

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'transportistasweb@gmail.com'; // Coloca aquí tu correo de la organización
        $mail->Password = 'rahq dnlm khkq wcsg '; // Coloca aquí tu contraseña de aplicación generada
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('transportistasweb@gmail.com', 'DeCasa');
        $mail->addAddress($correoElectronico);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Password Recovery';
        $mail->Body    = "Haz clic en el siguiente enlace para restablecer tu contraseña: <a href='http://localhost/ranof-web/restablecerContraseña.php?token=$token'>Recuperar Contraseña</a>";

        // Envío del correo
        try {
            $mail->send();
            $mensaje = "Se ha enviado un correo de recuperación a $correoElectronico.";
        } catch (Exception $e) {
            // Manejar error de envío de correo
            $mensaje = "El correo no pudo ser enviado. Error: {$mail->ErrorInfo}";
        }

        // Cerrar la consulta preparada de inserción
        mysqli_stmt_close($stmt_insert);
    } else {
        // Manejar el caso de que no se haya encontrado el correo en la base de datos
        $mensaje = "No se encontró un usuario con ese correo electrónico.";
    }

    // Cerrar la consulta preparada de selección
    mysqli_stmt_close($stmt);
}

// Cerrar la conexión a la base de datos
mysqli_close($cn);

// Redireccionar con el mensaje
header("Location: ../index.php?mensaje=" . urlencode($mensaje));
exit();
