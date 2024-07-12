// script.js
$(document).ready(function() {
    // Manejo del envío del formulario
    $('#quizForm').submit(function(event) {
      event.preventDefault(); // Evitar que se recargue la página
  
      // Obtener respuesta seleccionada
      var answer = $('input[name="answer"]:checked').val();
  
      // Validar respuesta (aquí puedes comparar con la respuesta correcta desde la base de datos)
      var correctAnswer = 'option2'; // Respuesta correcta en este ejemplo
  
      var isCorrect = (answer === correctAnswer);
  
      // Aquí podrías enviar la respuesta a la base de datos con una solicitud AJAX
      // Ejemplo básico de solicitud AJAX usando jQuery
      $.ajax({
        type: 'POST',
        url: 'guardar_respuesta.php', // Archivo PHP para procesar y guardar la respuesta
        data: {
          answer: answer,
          isCorrect: isCorrect
        },
        success: function(response) {
          // Manejar la respuesta del servidor (opcional)
          console.log('Respuesta guardada correctamente');
        },
        error: function(err) {
          console.error('Error al guardar la respuesta:', err);
        }
      });
    });
  });
  