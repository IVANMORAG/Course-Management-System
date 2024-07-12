<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="Recursos/css/login.css">
    <title>Iniciar Sesión | Transportes Ranof</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container" id="container">
        <main>
            <article class="form-container sign-up">
                <form action="Controladores/RegistroController.php" method="post" enctype="multipart/form-data">
                    <h1>Crear Cuenta</h1>
                    <input type="text" name="nombre" placeholder="Nombre" required>
                    <input type="text" name="apellido" placeholder="Apellido" required>
                    <input type="email" name="correo" placeholder="Correo electrónico" required>
                    <input type="password" name="contrasena" placeholder="Contraseña" required>
                    <input type="text" name="numero_trabajador" placeholder="Número de Trabajador" required>
                    <input type="file" name="imagen" accept="image/*" required>
                    <button type="submit" name="register">Registrarse</button>
                </form>
            </article>
            <article class="form-container sign-in">
                <form action="Controladores/LoginController.php" method="post">
                    <h1>Iniciar Sesión</h1>
                    <input type="email" name="correo" placeholder="Correo electrónico" required>
                    <input type="password" name="contrasena" placeholder="Contraseña" required>
                    <a href="recuperarContraseña.php">¿Olvidaste tu contraseña?</a>
                    <button type="submit" name="login">Iniciar Sesión</button>
                </form>
            </article>
            <aside class="toggle-container">
                <div class="toggle">
                    <section class="toggle-panel toggle-left">
                        <h1>¡BIENVENIDO A TRANSPORTES RANOF!</h1>
                        <p>¿Todavía no tienes una cuenta? Regístrate para usar todas las funciones del sitio</p>
                        <button class="hidden" id="login">Iniciar Sesión</button>
                    </section>
                    <section class="toggle-panel toggle-right">
                        <h1>¡BIENVENIDO A TRANSPORTES RANOF!</h1>
                        <p>¿Todavía no tienes una cuenta? Regístrate para usar todas las funciones del sitio</p>
                        <button class="hidden" id="register">Registrarse</button>
                    </section>
                </div>
            </aside>
        </main>
        <footer>
            <script src="Recursos/js/login.js"></script>

            <script>
                <?php if (isset($_GET['register_error'])) : ?>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '<?php echo urldecode($_GET['register_error']); ?>',
                    });
                <?php elseif (isset($_GET['register_success'])) : ?>
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Registro exitoso. Ahora puedes iniciar sesión.',
                    });

                <?php elseif (isset($_GET['register'])) : ?>
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Enviado correctamente !!!',
                    });

                <?php elseif (isset($_GET['mensaje'])) : ?>
                    Swal.fire({
                        icon: 'info',
                        title: 'Información',
                        text: '<?php echo urldecode($_GET['mensaje']); ?>',
                    });

                <?php elseif (isset($_GET['login_error'])) : ?>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '<?php echo urldecode($_GET['login_error']); ?>',
                    });

                <?php elseif (isset($_GET['login_success'])) : ?>
                    Swal.fire({
                        icon: 'success',
                        title: 'Inicio de sesión exitoso',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "home.php"; // Redirigir a la página principal
                    });
                <?php endif; ?>
            </script>

        </footer>
    </div>
</body>

</html>