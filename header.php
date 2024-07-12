<!-- header.php -->
<header class="header">

    <section class="flex">

        <a href="home.php" class="logo">
            <img src="Recursos/imagenes/Logo.jpg" alt="Logo de RANOF">
        </a>

        <form action="search.php" method="post" class="search-form">
            <input type="text" name="search_box" required placeholder="search courses..." maxlength="100">
            <button type="submit" class="fas fa-search"></button>
        </form>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="search-btn" class="fas fa-search"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>

        <div class="profile">
            <?php
            // Verifica si hay una foto de perfil definida en la sesión
            if (isset($_SESSION['foto']) && !empty($_SESSION['foto'])) {
                // Ajusta la ruta relativa
                $foto_url = 'Recursos/imagenes/' . urlencode(basename($_SESSION['foto']));

                // Verifica si el archivo de la imagen existe
                if (file_exists($_SESSION['foto'])) {
                    echo '<img src="' . $foto_url . '" class="image" alt="Perfil">';
                } else {
                    echo '<img src="Recursos/imagenes/iconos.png" class="image" alt="Perfil">'; // Imagen por defecto si la ruta no es válida
                }
            } else {
                echo '<img src="Recursos/imagenes/def.jpg" class="image" alt="Perfil">'; // Imagen por defecto si no hay foto de perfil
            }
            ?>
            <h3 class="name">
                <?php
                // Verifica si hay un nombre de usuario definido en la sesión
                if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
                    echo $_SESSION['user'];
                } else {
                    echo 'Usuario'; // Nombre por defecto si no hay sesión iniciada
                }
                ?>
            </h3>
            <a href="profile.php" class="btn">Ver perfil</a>
            <div class="flex-btn">
                <a href="cerrarSesion.php" class="option-btn">Cerrar sesión</a>
                <a href="home.php" class="option-btn">Inicio</a>
            </div>
        </div>

    </section>

</header>