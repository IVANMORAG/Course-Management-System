<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="Recursos/css/login.css">
    <title>Recuperar Contraseña | Transportes Ranof</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <header>
            <img src="Recursos/imagenes/Imagen1.jpg" alt="usuario">
        </header>
        <main>
            <section class="form-container recuperar-contrasena">
                <form action="Controladores/RecuperarController.php" method="post">
                    <h1>Recuperar Contraseña</h1>
                    <p>Ingresa tu correo electrónico y te enviaremos instrucciones para recuperar tu contraseña.</p>
                    <input type="email" name="correo" placeholder="Correo electrónico" required>
                    <button type="submit">Recuperar Contraseña</button>
                </form>
            </section>
        </main>
        <footer>
            <script src="Recursos/js/login.js"></script>
        </footer>
    </div>
</body>

</html>