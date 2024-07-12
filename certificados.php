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
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Certificados</title>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="Recursos/css/style.css">
</head>

<body>

   <?php
   include 'header.php';
   include 'side-bar.php';
   ?>

   <section class="courses">
      <h1 class="heading">Certificados de <?php echo htmlspecialchars($_SESSION['user']); ?></h1>
      <div class="box-container">
         <?php
         // Incluir el archivo de conexión a la base de datos
         require_once 'Conexion/conectar.php';

         // Obtener el ID del usuario desde la sesión
         $user_id = $_SESSION['user_id'];

         // Consulta SQL para obtener los certificados del usuario
         $sql = "SELECT c.Nombre AS NombreCurso, c.MiniaturaCurso, cer.FechaObtencion, cer.Certificado
                 FROM certificados cer
                 INNER JOIN cursos c ON cer.IDCurso = c.ID
                 WHERE cer.IDTransportista = ?";

         $stmt = $cn->prepare($sql);
         $stmt->bind_param("i", $user_id);
         $stmt->execute();
         $result = $stmt->get_result();

         if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
               echo '<div class="box">';
               echo '<div class="thumb">';
               echo '<img src="' . htmlspecialchars($row['MiniaturaCurso']) . '" alt="Miniatura del curso">';
               echo '</div>';
               echo '<div class="info">';
               echo '<h3 class="title">' . htmlspecialchars($row['NombreCurso']) . '</h3>';
               echo '<p>Fecha de obtención: ' . htmlspecialchars($row['FechaObtencion']) . '</p>';
               echo '<br><a href="' . htmlspecialchars($row['Certificado']) . '" class="inline-btn" target="_blank">Ver certificado</a>';
               echo '</div>';
               echo '</div>';
            }
         } else {
            echo '<p>No se encontraron certificados.</p>';
         }

         // Cerrar la conexión y el statement
         $stmt->close();
         $cn->close();
         ?>
      </div>

      <div class="more-btn">
         <a href="cursos.php" class="inline-option-btn">Ver todos los cursos</a>
      </div>
   </section>

   <?php
   include 'footer.php';
   ?>

   <script src="Recursos/js/script.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>