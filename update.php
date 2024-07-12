<?php
session_start();

// Verifica si el usuario no está autenticado redireccionándolo al login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
   header("location: index.php");
   exit;
}

// Incluir el archivo de conexión a la base de datos
require_once 'Conexion/conectar.php';

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['user_id'];

// Consulta SQL para obtener los datos actuales del usuario
$sql = "SELECT Nombre, Apellido, CorreoElectronico, NumeroTrabajador, Imagen FROM transportistas WHERE ID = ?";
$stmt = $cn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nombre, $apellido, $correo, $numero_trabajador, $imagen);
$stmt->fetch();
$stmt->close();

// Procesamiento del formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
   // Recibe los datos del formulario
   $new_name = $_POST['name'];
   $new_apellido = $_POST['apellido'];
   $new_email = $_POST['email'];
   $new_numero_trabajador = $_POST['no_trabajador'];

   // Validación básica (puedes expandirla según tus necesidades)
   $errors = array();

   // Procesar la imagen si se ha subido
   if (!empty($_FILES['imagen']['name'])) {
      $file_name = $_FILES['imagen']['name'];
      $file_tmp = $_FILES['imagen']['tmp_name'];
      $file_type = $_FILES['imagen']['type'];
      $file_size = $_FILES['imagen']['size'];

      // Guardar la imagen en la ruta especificada
      $target_path = "Recursos/imagenes/" . basename($file_name);
      if (!move_uploaded_file($file_tmp, $target_path)) {
         $errors[] = "Hubo un problema al subir la imagen.";
      }
   } else {
      // Si no se envió una nueva imagen, mantener la imagen actual
      $target_path = $imagen;
   }

   // Si no hay errores de validación ni de subida de imagen, procede a actualizar los datos
   if (empty($errors)) {
      // Preparar la consulta SQL para actualizar los datos del usuario
      $update_sql = "UPDATE transportistas SET Nombre = ?, Apellido = ?, CorreoElectronico = ?, NumeroTrabajador = ?, Imagen = ? WHERE ID = ?";
      $stmt_update = $cn->prepare($update_sql);
      $stmt_update->bind_param("sssssi", $new_name, $new_apellido, $new_email, $new_numero_trabajador, $target_path, $user_id);

      // Ejecutar la consulta
      if ($stmt_update->execute()) {
         // Actualizar la sesión con los nuevos datos si es necesario
         $_SESSION['correo'] = $new_email; // Actualiza el correo electrónico
         $_SESSION['user'] = $new_name . ' ' . $new_apellido; // Nombre completo del usuario actualizado
         $_SESSION['foto'] = $target_path; // Actualiza la ruta de la imagen

         // Redireccionar a profile.php
         header("location: profile.php");
         exit();
      } else {
         $errors[] = "Hubo un problema al actualizar los datos. Por favor, inténtalo de nuevo.";
      }

      // Cerrar la consulta
      $stmt_update->close();
   }

   // Mostrar errores si los hay
   if (!empty($errors)) {
      foreach ($errors as $error) {
         echo '<div style="color: red;">' . $error . '</div>';
      }
   }
}

// Cerrar la conexión a la base de datos
$cn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Actualizar perfil</title>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="Recursos/css/style.css">
</head>

<body>

   <?php
   include 'header.php';
   include 'side-bar.php';
   ?>

   <section class="form-container">
      <form action="" method="post" enctype="multipart/form-data">
         <h3>Actualiza tus datos</h3>
         <p>Nombre</p>
         <input type="text" name="name" value="<?php echo htmlspecialchars($nombre); ?>" maxlength="50" class="box">
         <p>Apellido</p>
         <input type="text" name="apellido" value="<?php echo htmlspecialchars($apellido); ?>" maxlength="50" class="box">
         <p>Email</p>
         <input type="email" name="email" value="<?php echo htmlspecialchars($correo); ?>" maxlength="50" class="box">
         <p>No trabajador</p>
         <input type="text" name="no_trabajador" value="<?php echo htmlspecialchars($numero_trabajador); ?>" maxlength="20" class="box">
         <p>Actualizar foto de perfil</p>
         <input type="file" accept="image/*" name="imagen" class="box">
         <input type="submit" value="Actualizar perfil" name="submit" class="btn">
      </form>
   </section>

   <?php
   include 'footer.php';
   ?>

</body>

</html>