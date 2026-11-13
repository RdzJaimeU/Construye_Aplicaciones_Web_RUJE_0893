<?php
// Rodriguez Ugalde Jaime Emmanuel 5°J 0893
// aqui inicio la sesion para verificar si el usuario ya inicio sesion
session_start();

// aqui llamo a mi archivo de conexion
include("conexion.php");

// aqui guardo esta pagina como la ultima visitada para que al salir del carrito regrese aqui
$_SESSION["ultima_pagina"] = "steam.php";

// aqui calculo cuantos articulos van en el carrito para mostrar en la barra
$total_articulos_nav = 0;
if (isset($_SESSION["carrito"]) && is_array($_SESSION["carrito"])) {
    foreach ($_SESSION["carrito"] as $item) {
        $total_articulos_nav += $item["cantidad"];
    }
}

// aqui preparo la consulta para traer solo los juegos de steam desde mi tabla
$sql = "SELECT * FROM productos WHERE categoria = 'Steam'";

// aqui ejecuto la consulta en la base de datos
$resultado = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<!--Rodriguez Ugalde Jaime Emmanuel 5°J 0893-->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Steam | Gamix</title>
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>">
</head>

<body>
    <!-- aqui pongo el encabezado con el titulo y el logo -->
    <header>
        <h1>GAM<span>IX</span></h1>
        <img src="img/logo1.png" alt="logo de la empresa">
    </header>

    <!-- aqui pongo la barra de navegacion para moverme entre las consolas -->
    <nav>
        <a href="index.php">Inicio</a>
        <a href="nintendo.php">Nintendo</a>
        <a href="play.php">PlayStation</a>
        <a href="xbox.php">Xbox</a>
        <a href="steam.php">Steam</a>

        <!-- aqui pongo el enlace al carrito mostrando la cantidad de productos -->
        <a href="carrito.php" class="nav-carrito">
            <img src="img/carrito.png" alt="carrito" class="icono-carrito" onerror="this.style.display='none'">
            Carrito (<?php echo $total_articulos_nav; ?>)
        </a>

        <!-- aqui reviso si hay sesion activa para mostrar mi cuenta o el boton de ingresar -->
        <?php if (isset($_SESSION["usuario"])): ?>
            <a href="cuenta.php" class="btn-cuenta">Mi Cuenta (<?php echo htmlspecialchars($_SESSION["usuario"]); ?>)</a>
            <a href="cerrar.php" class="btn-sesion">Cerrar Sesion</a>
        <?php else: ?>
            <a href="iniciar.php">Cuenta</a>
        <?php endif; ?>
    </nav>

    <!-- aqui muestro el aviso cuando se agrega un juego al carrito sin sacarte de la pagina -->
    <?php if (isset($_SESSION["mensaje_agregado"])): ?>
        <div class="alerta-agregado">
            <?php echo htmlspecialchars($_SESSION["mensaje_agregado"]); unset($_SESSION["mensaje_agregado"]); ?>
        </div>
    <?php endif; ?>

    <!-- aqui muestro el catalogo con los juegos consultados desde la base de datos -->
    <section id="steam">
        <h2>Juegos de Steam</h2>
        <div>
            <?php
            // aqui uso un ciclo while para recorrer cada registro que arrojo la base de datos
            while ($fila = mysqli_fetch_assoc($resultado)) {
            ?>
                <div>
                    <!-- aqui muestro la portada usando la ruta de la base de datos -->
                    <img src="<?php echo $fila['imagen']; ?>" alt="<?php echo $fila['nombre']; ?>">

                    <!-- aqui imprimo el nombre del juego sacado de la base de datos -->
                    <p><?php echo $fila['nombre']; ?></p>

                    <!-- aqui imprimo el precio formateado con dos decimales -->
                    <p>$<?php echo number_format($fila['precio'], 2); ?> MXN</p>

                    <!-- aqui pongo el boton para agregar al carrito sin salir de esta pagina -->
                    <a href="carrito.php?accion=agregar&id=<?php echo $fila['id']; ?>&retorno=steam.php">Agregar al Carrito</a>
                </div>
            <?php
            }
            ?>
        </div>
    </section>

    <!-- aqui pongo el pie de pagina con mis datos de alumno -->
    <footer>
        <p>&copy;Rodriguez Ugalde Jaime Emmanuel 5°J 0893</p>
    </footer>
</body>

</html>
