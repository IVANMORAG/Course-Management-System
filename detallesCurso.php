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
   <title>video playlist</title>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="Recursos/css/style.css">
   <link rel="stylesheet" href="Recursos/css/quiz.css">



</head>

<body>

   <?php
   include 'header.php';
   include 'side-bar.php';
   ?>

   <?php
   // Incluir el archivo de conexión
   require_once 'Conexion/conectar.php';

   // Verificar si 'curso_id' está presente en la URL
   if (isset($_GET['curso_id'])) {
      $IDCurso = $_GET['curso_id'];

      // Consulta SQL para obtener los detalles del curso y del tutor
      $sql = "SELECT c.Nombre AS NombreCurso, c.Descripcion, c.MiniaturaCurso, c.FechaActualizacion, 
               t.Nombre AS NombreAdmin, t.Imagen AS ImagenAdmin
           FROM cursos c
           INNER JOIN transportistas t ON c.IDAdmin = t.ID
           WHERE c.ID = $IDCurso";

      $result = $cn->query($sql);

      if ($result->num_rows > 0) {
         $curso = $result->fetch_assoc();
      } else {
         echo "No se encontró el curso.";
         exit;
      }

      // Consulta para contar los videos del curso actual
      $sql_videos = "SELECT COUNT(*) AS total_videos FROM videos WHERE IDSubtema IN (SELECT ID FROM subtemas WHERE IDCurso = $IDCurso)";
      $result_videos = $cn->query($sql_videos);

      if ($result_videos->num_rows > 0) {
         $total_videos = $result_videos->fetch_assoc()['total_videos'];
      } else {
         $total_videos = 0;
      }
   } else {
      echo "No se proporcionó el ID del curso.";
      exit;
   }
   ?>

   <section class="playlist-details">
      <h1 class="heading">Detalles del Playlist</h1>

      <div class="row">
         <div class="column">
            <form action="" method="post" class="save-playlist">
               <button type="submit"><i class="far fa-bookmark"></i> <span>Guardar Playlist</span></button>
            </form>

            <div class="thumb">
               <img src="<?php echo $curso['MiniaturaCurso']; ?>" alt="Miniatura del Curso">
               <span><?php echo $total_videos; ?> videos</span>
            </div>
         </div>
         <div class="column">
            <div class="tutor">
               <img src="<?php echo $curso['ImagenAdmin']; ?>" alt="Tutor">
               <div>
                  <h3><?php echo $curso['NombreAdmin']; ?></h3>
                  <span><?php echo date('d-F-Y', strtotime($curso['FechaActualizacion'])); ?></span>
               </div>
            </div>

            <div class="details">
               <h3><?php echo $curso['NombreCurso']; ?></h3>
               <p><?php echo $curso['Descripcion']; ?></p>
               <a href="" class="inline-btn">Ver tutor</a>
            </div>
         </div>
      </div>
   </section>

   <section class="playlist-details">
      <?php

      include 'Conexion/conectar.php';

      // Verificar si IDCurso está definido y no está vacío
      if (!isset($_GET['curso_id']) || empty($_GET['curso_id'])) {
         die('Error: IDCurso no está definido o es inválido.');
      }

      $IDCurso = $_GET['curso_id'];


      // ID del usuario logueado (esto debe ser obtenido de tu sistema de autenticación)
      if (!isset($_SESSION['user_id'])) {
         die('Error: Usuario no autenticado.');
      }

      $IDUsuario = $_SESSION['user_id'];

      // Consulta para obtener los subtemas del curso
      $sql_subtemas = "SELECT * FROM subtemas WHERE IDCurso = $IDCurso ORDER BY Orden";
      $result_subtemas = $cn->query($sql_subtemas);

      if ($result_subtemas->num_rows > 0) {
         $totalSubtemas = $result_subtemas->num_rows; // Número total de subtemas en el curso
         while ($subtema = $result_subtemas->fetch_assoc()) {
            $IDSubtema = $subtema['ID'];

            // Consulta para obtener los videos del subtema actual
            $sql_videos_subtema = "SELECT * FROM videos WHERE IDSubtema = $IDSubtema ORDER BY Orden";
            $result_videos_subtema = $cn->query($sql_videos_subtema);

            if ($result_videos_subtema->num_rows > 0) {
               while ($video = $result_videos_subtema->fetch_assoc()) {
      ?>
                  <div class="row">
                     <div class="column">
                        <div class="thumb" id="videoContainer_<?php echo $video['ID']; ?>">
                           <img src="<?php echo $video['MiniaturaVideo']; ?>" alt="Miniatura del Video">
                           <!-- Botón para mostrar el video -->
                           <button class="inline-btn show-video-btn" data-video="<?php echo $video['VideoURL']; ?>" data-miniatura="<?php echo $video['MiniaturaVideo']; ?>">Ver Video</button>
                        </div>
                     </div>
                     <div class="column">
                        <div class="tutor">
                           <!-- Aquí deberías obtener los datos del tutor si es necesario -->
                        </div>

                        <div class="details">
                           <div class="column">
                              <div class="tutor">
                                 <img src="<?php echo $curso['ImagenAdmin']; ?>" alt="Tutor">
                                 <div>
                                    <h3><?php echo $curso['NombreAdmin']; ?></h3>
                                    <span><?php echo date('d-F-Y', strtotime($curso['FechaActualizacion'])); ?></span>
                                 </div>
                              </div>


                              <h3><?php echo $video['Titulo']; ?></h3>
                              <p><?php echo $subtema['Descripcion']; ?></p>
                           </div>


                        </div>
                     </div>


                     <?php
                     // Consulta para obtener las preguntas del subtema actual
                     $sql_preguntas = "SELECT * FROM preguntas WHERE IDSubtema = $IDSubtema";
                     $result_preguntas = $cn->query($sql_preguntas);

                     if ($result_preguntas->num_rows > 0) {
                     ?>
                        <div class="quiz-section">
                           <h1 class="quiz-title">PRUEBA DE CONOCIMIENTOS</h1>
                           <form id="quizForm_<?php echo $IDSubtema; ?>" class="quiz-form">
                              <input type="hidden" name="IDUsuario" value="<?php echo $IDUsuario; ?>">
                              <input type="hidden" name="IDCurso" value="<?php echo $IDCurso; ?>">
                              <input type="hidden" name="IDSubtema" value="<?php echo $IDSubtema; ?>">
                              <?php
                              $delay = 0;
                              while ($pregunta = $result_preguntas->fetch_assoc()) { ?>
                                 <div class="quiz-question" style="animation-delay: <?php echo $delay; ?>s;">
                                    <label class="question-text"><?php echo $pregunta['Pregunta']; ?></label>
                                    <div class="quiz-option">
                                       <input class="option-input" type="radio" name="pregunta_<?php echo $pregunta['ID']; ?>" value="A" data-correct="<?php echo ($pregunta['RespuestaCorrecta'] == 'A') ? 'true' : 'false'; ?>" id="pregunta_<?php echo $pregunta['ID']; ?>_A">
                                       <label class="option-label" for="pregunta_<?php echo $pregunta['ID']; ?>_A"><?php echo $pregunta['OpcionA']; ?></label>
                                    </div>
                                    <div class="quiz-option">
                                       <input class="option-input" type="radio" name="pregunta_<?php echo $pregunta['ID']; ?>" value="B" data-correct="<?php echo ($pregunta['RespuestaCorrecta'] == 'B') ? 'true' : 'false'; ?>" id="pregunta_<?php echo $pregunta['ID']; ?>_B">
                                       <label class="option-label" for="pregunta_<?php echo $pregunta['ID']; ?>_B"><?php echo $pregunta['OpcionB']; ?></label>
                                    </div>
                                    <div class="quiz-option">
                                       <input class="option-input" type="radio" name="pregunta_<?php echo $pregunta['ID']; ?>" value="C" data-correct="<?php echo ($pregunta['RespuestaCorrecta'] == 'C') ? 'true' : 'false'; ?>" id="pregunta_<?php echo $pregunta['ID']; ?>_C">
                                       <label class="option-label" for="pregunta_<?php echo $pregunta['ID']; ?>_C"><?php echo $pregunta['OpcionC']; ?></label>
                                    </div>
                                 </div>
                              <?php
                                 $delay += 0.5; // Incrementa el retraso para la siguiente pregunta
                              } ?>
                              <button type="submit" class="quiz-submit">Enviar Quiz</button>
                           </form>
                           <div id="quizResult_<?php echo $IDSubtema; ?>" class="quiz-result">
                              <p class="result-text">Has respondido correctamente <span id="correctAnswers_<?php echo $IDSubtema; ?>"></span> de <span id="totalQuestions_<?php echo $IDSubtema; ?>"></span> preguntas.</p>
                           </div>
                        </div>




         <?php
                     } else {
                        echo "<p>No se encontraron preguntas para el subtema ID: $IDSubtema</p>";
                     }
                  }
               } else {
                  echo "No se encontraron videos para el subtema ID: $IDSubtema";
               }
            }
         } else {
            echo "No se encontraron subtemas para el curso ID: $IDCurso";
         }
         ?>

         <div class="certificate-section" style="display: <?php echo $certificado_disponible ? 'block' : 'none'; ?>;">
            <form id="certificateForm" action="obtener_certificado.php" method="post">
               <input type="hidden" name="IDUsuario" value="<?php echo $IDUsuario; ?>">
               <input type="hidden" name="IDCurso" value="<?php echo $IDCurso; ?>">
               <button type="submit" class="btn btn-success">Obtener Certificado</button>
            </form>
         </div>

         <div class="table-container">
            <table class="custom-table">
               <tbody>
                  <?php
                  // Incluir archivo de conexión y verificar sesión de usuario
                  include 'Conexion/conectar.php';

                  // Verificar si ID del transportista está definido y no está vacío
                  if (!isset($_GET['curso_id']) || empty($_GET['curso_id'])) {
                     die('Error: ID del transportista no está definido o es inválido.');
                  }

                  $IDCurso = $_GET['curso_id'];

                  // Consulta para obtener los resultados del quiz por transportista
                  $sql = "SELECT 
            t.Nombre AS NombreTransportista,
            c.Nombre AS NombreCurso,
            s.Nombre AS NombreSubtema,
            rq.Puntaje AS Resultado
            FROM 
                  transportistas t
                  INNER JOIN resultados_quiz rq ON t.ID = rq.IDUsuario
                  INNER JOIN cursos c ON rq.IDCurso = c.ID
                  INNER JOIN subtemas s ON rq.IDSubtema = s.ID
            WHERE 
                  t.ID = $IDUsuario";

                  $result = $cn->query($sql);

                  if ($result->num_rows > 0) {
                     $nombreTransportista = '';
                     $nombreCurso = '';
                     $output = '';

                     while ($row = $result->fetch_assoc()) {
                        if ($nombreTransportista == '' && $nombreCurso == '') {
                           // Guardar nombre del transportista y curso para la primera fila
                           $nombreTransportista = $row['NombreTransportista'];
                           $nombreCurso = $row['NombreCurso'];

                           // Crear las primeras filas de transportista y curso
                           $output .= '<tr class="transportista-row">
                                 <td class="transportista-label">Transportista</td>
                                 <td>' . $nombreTransportista . '</td>
                              </tr>
                              <tr class="curso-row">
                                 <td class="curso-label">Curso</td>
                                 <td>' . $nombreCurso . '</td>
                              </tr>
                              <tr class="header-row">
                                 <td>Subtema</td>
                                 <td>Resultado</td>
                              </tr>';
                        }

                        // Agregar las filas de subtema y resultado
                        $output .= '<tr>
                              <td>' . $row['NombreSubtema'] . '</td>
                              <td>' . $row['Resultado'] . '/4</td>
                        </tr>';
                     }

                     echo $output; // Devolver el contenido generado
                  } else {
                     echo '<tr><td colspan="2">No se encontraron resultados para este transportista.</td></tr>';
                  }

                  // Cerrar conexión o cualquier otro cleanup necesario
                  $cn->close();
                  ?>

               </tbody>
            </table>
         </div>

         <style>
            .certificate-section {
               width: 40%;
               /* Ancho del 90% del contenedor padre */
               max-width: 1000px;
               /* Ancho máximo para limitar el tamaño en pantallas grandes */
               margin: 0 auto;
               padding: 20px;
               font-family: Arial, sans-serif;
            }

            .table-container {
               width: 80%;
               /* Ancho del 90% del contenedor padre */
               max-width: 1000px;
               /* Ancho máximo para limitar el tamaño en pantallas grandes */
               margin: 0 auto;
               padding: 20px;
               font-family: Arial, sans-serif;
            }

            .custom-table {
               width: 100%;
               border-collapse: collapse;
               border: 1px solid #ddd;
               /* Color del borde */
            }

            .custom-table th,
            .custom-table td {
               padding: 12px;
               border: 1px solid #ddd;
               /* Color del borde */
               text-align: center;
               /* Centrar contenido */
               font-size: 16px;
               /* Tamaño de fuente */
            }

            .custom-table th {
               background-color: #f2f2f2;
               /* Color de fondo para las cabeceras */
               font-weight: bold;
            }

            .custom-table tbody tr:nth-child(even) {
               background-color: #f9f9f9;
               /* Color de fondo para filas pares */
            }

            .custom-table tbody tr:hover {
               background-color: #e5f7ff;
               /* Color de fondo al pasar el ratón */
            }
         </style>


         <div id="videoPlayer" class="video-container" style="display: none;">
            <video id="player" controls width="100%">
               <source id="videoSource" type="video/mp4">
               Tu navegador no soporta la reproducción de videos.
            </video>
         </div>
         <script>
            document.addEventListener('DOMContentLoaded', function() {
               const showVideoButtons = document.querySelectorAll('.show-video-btn');
               const videoPlayer = document.getElementById('player');
               const videoSource = document.getElementById('videoSource');
               const videoPlayerContainer = document.getElementById('videoPlayer');
               const quizForms = document.querySelectorAll('.quiz-form');
               const totalSubtemas = <?php echo $totalSubtemas; ?>; // Total de subtemas en el curso

               showVideoButtons.forEach(button => {
                  button.addEventListener('click', function(event) {
                     event.preventDefault();

                     const videoURL = this.getAttribute('data-video');
                     const miniaturaURL = this.getAttribute('data-miniatura');
                     const videoContainer = this.closest('.thumb');

                     // Cambiar la fuente del video y mostrar el contenedor del reproductor
                     videoSource.setAttribute('src', videoURL);
                     videoPlayer.load();
                     videoPlayer.play();
                     videoPlayerContainer.style.display = 'block';

                     // Ocultar la miniatura y el botón
                     videoContainer.innerHTML = '';
                     videoContainer.appendChild(videoPlayerContainer);

                     // Escuchar el evento 'ended' para volver a mostrar la miniatura y el botón
                     videoPlayer.addEventListener('ended', function() {
                        videoPlayerContainer.style.display = 'none';
                        videoContainer.innerHTML = '<img src="' + miniaturaURL + '" alt="Miniatura del Video"><button class="inline-btn show-video-btn" data-video="' + videoURL + '" data-miniatura="' + miniaturaURL + '">Ver Video</button>';
                     });
                  });
               });

               quizForms.forEach(form => {
                  form.addEventListener('submit', function(event) {
                     event.preventDefault();
                     const subtemaId = this.querySelector('[name="IDSubtema"]').value;
                     let correctAnswers = 0;
                     let totalQuestions = 0;

                     const formData = new FormData(this);
                     formData.forEach((value, key) => {
                        if (key.startsWith('pregunta_')) {
                           const input = this.querySelector(`[name="${key}"][value="${value}"]`);
                           if (input && input.dataset.correct === 'true') {
                              totalQuestions++;
                              correctAnswers++;
                           } else if (input) {
                              totalQuestions++;
                           }
                        }
                     });

                     document.getElementById(`correctAnswers_${subtemaId}`).textContent = correctAnswers;
                     document.getElementById(`totalQuestions_${subtemaId}`).textContent = totalQuestions;
                     document.getElementById(`quizResult_${subtemaId}`).style.display = 'block';

                     // Envío de resultados del quiz a la base de datos
                     const data = {
                        IDUsuario: <?php echo $IDUsuario; ?>,
                        IDCurso: <?php echo $IDCurso; ?>,
                        IDSubtema: subtemaId,
                        Puntaje: correctAnswers
                     };

                     fetch('guardar_resultado.php', {
                           method: 'POST',
                           headers: {
                              'Content-Type': 'application/json'
                           },
                           body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                           console.log('Resultado del quiz guardado:', data);
                           // Después de guardar el resultado, verificar si se puede mostrar el botón de certificado
                           verificarCertificadoDisponible();
                        })
                        .catch(error => {
                           console.error('Error al guardar el resultado del quiz:', error);
                        });
                  });
               });

               // Función para verificar si se puede mostrar el botón de certificado
               function verificarCertificadoDisponible() {
                  fetch('verificar_certificado_disponible.php?curso_id=<?php echo $IDCurso; ?>')
                     .then(response => response.json())
                     .then(data => {
                        const certificateSection = document.querySelector('.certificate-section');
                        if (data.error) {
                           console.error('Error:', data.error);
                        }
                        if (certificateSection) {
                           certificateSection.style.display = data.certificado_disponible ? 'block' : 'none';
                        }
                     })
                     .catch(error => {
                        console.error('Error al verificar el certificado disponible:', error);
                     });
               }

               // Llamar a la función para verificar inicialmente al cargar la página
               verificarCertificadoDisponible();
            });
         </script>


   </section>




   <?php
   include 'footer.php';
   ?>

   <!-- custom js file link  -->
   <script src="Recursos/js/script.js"></script>




</body>

</html>