<?php
// Rodriguez Ugalde Jaime Emmanuel 5°J 0893
// aqui guardo los datos para entrar al servidor de xampp
$servidor = "localhost";
$usuario = "root";
$password = "";
$bd = "gamix";

// aqui creo la conexion con mi base de datos
$conexion = mysqli_connect($servidor, $usuario, $password, $bd);

// aqui verifico si fallo la conexion para avisar del error
if (!$conexion) {
    die("Fallo la conexion con la base de datos");
}

// aqui configuro el juego de caracteres en utf8mb4 para admitir acentos, eñes y caracteres especiales
mysqli_set_charset($conexion, "utf8mb4");
?>