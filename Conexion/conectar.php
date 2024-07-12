<?php
$servidor = "localhost";
$usuario = "root";
$contraseña = "12345";
$bd = "ranofweb";

// Conexión a la base de datos
$cn = new mysqli($servidor, $usuario, $contraseña, $bd);

// Verificar la conexión
if ($cn->connect_error) {
    die("Error de conexión: " . $cn->connect_error);
}

// Establecer el conjunto de caracteres a UTF-8
if (!$cn->set_charset("utf8")) {
    die("Error al establecer el conjunto de caracteres utf8: " . $cn->error);
}
