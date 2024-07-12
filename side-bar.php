<div class="side-bar">

    <div id="close-btn">
        <i class="fas fa-times"></i>
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
        <a href="profile.php" class="btn">view profile</a>
    </div>

    <nav class="navbar">
        <a href="home.php"><i class="fas fa-home"></i><span>home</span></a>
        <a href="admin.php"><i class="fas fa-question"></i><span>admin</span></a>
        <a href="cursos.php"><i class="fas fa-graduation-cap"></i><span>cursos</span></a>
        <a href="certificados.php"><i class="fa-solid fa-file"></i><span>certificados</span></a>
        <a href="pruebas.php"><i class="fa-solid fa-award"></i><span>pruebas</span></a>
    </nav>

</div>