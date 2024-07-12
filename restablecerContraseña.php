<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="Recursos/css/login.css">
    <title>Restablecer Contraseña | Transportes Ranof</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <header>
            <img src="Recursos/imagenes/Imagen1.jpg" alt="usuario">
        </header>
        <main>
            <section class="form-container restablecer-contrasena">
                <form action="Controladores/RestablecerController.php" method="post">
                    <h1>Restablecer Contraseña</h1>
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>" required>
                    <input type="password" name="nueva_contrasena" placeholder="Nueva Contraseña" required>
                    <button type="submit" name="reset_password">Restablecer Contraseña</button>
                </form>
            </section>
        </main>
        <footer>
            <script src="Recursos/js/login.js"></script>
        </footer>
    </div>
</body>

</html>