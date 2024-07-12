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
   <title>courses</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <!-- custom css file link  -->
   <link rel="stylesheet" href="Recursos/css/style.css">

</head>

<body>

   <?php
   include 'header.php';
   include 'side-bar.php';
   ?>

   <section class="courses">

      <h1 class="heading">Cursos disponibles</h1>

      <div class="box-container">

         <?php
         // Incluir el archivo de conexión
         require_once 'Conexion/conectar.php';

         $sql = "SELECT c.ID, c.Nombre AS NombreCurso, c.FechaActualizacion, c.MiniaturaCurso,
         t.ID AS IDAdmin, t.Nombre AS NombreAdmin
         FROM cursos c
         INNER JOIN transportistas t ON c.IDAdmin = t.ID
         WHERE c.Estado = 'Activo'";


         $result = $cn->query($sql);

         if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
               echo '<div class="box">';
               echo '<div class="tutor">';
               echo '<img src="Recursos/imagenes/admin.png" alt="Admin">';
               echo '<div class="info">';
               echo '<h3>' . $row['NombreAdmin'] . '</h3>';
               echo '<span>' . $row['FechaActualizacion'] . '</span>';
               echo '</div>';
               echo '</div>';
               echo '<div class="thumb">';
               echo '<img src="' . $row['MiniaturaCurso'] . '" alt="Miniatura del curso">';
               echo '</div>';
               echo '<h3 class="title">' . $row['NombreCurso'] . '</h3>';
               echo '<a href="detallesCurso.php?curso_id=' . $row['ID'] . '" class="inline-btn">Ver lista de reproducción</a>'; // Asegúrate de pasar el curso_id correcto
               echo '</div>';
            }
         } else {
            echo 'No se encontraron cursos';
         }

         // Cerrar la conexión al finalizar
         $cn->close();
         ?>

      </div>

   </section>



   <?php
   include 'footer.php';
   ?>

   <!-- custom js file link  -->
   <script src="Recursos/js/script.js"></script>



</body>

</html>