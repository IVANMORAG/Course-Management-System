<?php
// Inicia la sesión PHP
session_start();

// Verifica si el usuario no está autenticado redireccionándolo al login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
   header("location: index.php");
   exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>profile</title>
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
   <?php

   // Incluir el archivo de conexión
   require_once 'Conexion/conectar.php';

   // Obtener el ID del usuario desde la sesión
   $user_id = $_SESSION['user_id'];

   // Consulta SQL para obtener los datos del perfil del usuario
   $sql = "SELECT Nombre, Apellido, Imagen FROM transportistas WHERE ID = ?";
   $stmt = $cn->prepare($sql);
   $stmt->bind_param("i", $user_id);
   $stmt->execute();
   $stmt->bind_result($nombre, $apellido, $imagen);
   $stmt->fetch();
   $stmt->close();

   // Determinar la URL de la imagen
   if (!empty($imagen)) {
      $foto_url = 'Recursos/imagenes/' . urlencode(basename($imagen));
      if (!file_exists($foto_url)) {
         $foto_url = 'Recursos/imagenes/iconos.png'; // Imagen por defecto si la ruta no es válida
      }
   } else {
      $foto_url = 'Recursos/imagenes/def.jpg'; // Imagen por defecto si no hay foto de perfil
   }

   // Consulta SQL para obtener otros datos del usuario
   $sql = "SELECT CorreoElectronico, NumeroTrabajador FROM transportistas WHERE ID = ?";
   $stmt = $cn->prepare($sql);
   $stmt->bind_param("i", $user_id);
   $stmt->execute();
   $stmt->bind_result($correo, $numero_trabajador);
   $stmt->fetch();
   $stmt->close();

   // Cerrar la conexión a la base de datos
   $cn->close();
   ?>

   <section class="user-profile">
      <h1 class="heading">Mi perfil</h1>
      <div class="info">
         <div class="user">
            <img src="<?php echo htmlspecialchars($foto_url); ?>" alt="Perfil">
            <h3><?php echo htmlspecialchars($nombre . ' ' . $apellido); ?></h3>
            <a href="update.php" class="inline-btn">Actualiza tus datos</a>
         </div>
         <div class="box-container">
            <div class="box">
               <div class="flex">
                  <i class="fas fa-bookmark"></i>
                  <div>
                     <span>4</span>
                     <p>saved playlist</p>
                  </div>
               </div>
               <a href="#" class="inline-btn">view playlists</a>
            </div>
            <div class="box">
               <div class="flex">
                  <i class="fas fa-heart"></i>
                  <div>
                     <span>3</span>
                     <p>certificados</p>
                  </div>
               </div>
               <a href="#" class="inline-btn">ver certificados</a>
            </div>
         </div>
      </div>
   </section>

   <section class="form-container">
      <form action="" method="post" enctype="multipart/form-data">
         <h3>TUS DATOS</h3>
         <p>Nombre</p>
         <input type="text" name="name" value="<?php echo htmlspecialchars($nombre . ' ' . $apellido); ?>" maxlength="50" class="box" readonly>
         <p>E-mail</p>
         <input type="email" name="email" value="<?php echo htmlspecialchars($correo); ?>" maxlength="50" class="box" readonly>
         <p>No trabajador</p>
         <input type="text" name="no_trabajador" value="<?php echo htmlspecialchars($numero_trabajador); ?>" maxlength="20" class="box" readonly>
      </form>
   </section>



   <?php
   include 'footer.php';
   ?>


   <!-- custom js file link  -->
   <script src="Recursos/js/script.js"></script>


</body>

</html>