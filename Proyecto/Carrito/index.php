<?php
// Rodriguez Ugalde Jaime Emmanuel 5°J 0893
// aqui inicio la sesion para verificar si el usuario ya inicio sesion
session_start();

// aqui guardo esta pagina como la ultima visitada para que al salir del carrito regrese aqui
$_SESSION["ultima_pagina"] = "index.php";

// aqui calculo cuantos articulos van en el carrito para mostrar en la barra
$total_articulos_nav = 0;
if (isset($_SESSION["carrito"]) && is_array($_SESSION["carrito"])) {
    foreach ($_SESSION["carrito"] as $item) {
        $total_articulos_nav += $item["cantidad"];
    }
}
?>
<!DOCTYPE html>
<!--Rodriguez Ugalde Jaime Emmanuel 5°J 0893-->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gamix | Tienda de Videojuegos</title>
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

    <!-- aqui pongo la presentacion de la tienda y como funcionan los envios -->
    <section class="parrafoin">
        <p><strong>Bienvenido a Gamix</strong>, tu tienda mexicana especializada en la venta y distribución de videojuegos en formato físico original para tus consolas favoritas: <strong>Nintendo Switch, PlayStation, Xbox y Steam</strong>.<br><br>
            <strong>¿Cómo funcionan nuestros envíos a domicilio?</strong><br>
            Todos nuestros títulos se envían en su caja original, nuevos, completamente sellados y con garantía. Realizamos entregas seguras a toda la República Mexicana mediante paqueterías líderes (como DHL, FedEx y Estafeta), llegando directamente hasta la puerta de tu casa en un tiempo estimado de <strong>24 a 48 horas hábiles</strong>. Además, cada pedido cuenta con un número de guía para que puedas rastrear tu paquete en tiempo real desde que sale de nuestro almacén hasta que llega a tus manos.<br><br>
            <strong>Métodos de pago y seguridad:</strong><br>
            Para que tu compra sea sencilla y confiable, contamos con múltiples formas de pago: tarjeta de débito o crédito, transferencias bancarias y pagos en efectivo en cualquier tienda OXXO. Al crear tu cuenta en nuestra plataforma, podrás guardar tu dirección de envío para agilizar futuras compras y consultar tus pedidos.<br><br>
            Navega por nuestro catálogo en el menú superior, selecciona tu consola preferida y prepárate para disfrutar de los mejores lanzamientos sin salir de casa.</p>
        <img style="margin: 20px auto 0px auto; display: block;" src="img/logo.png" alt="logo de la empresa">
    </section>

    <!-- aqui pongo el pie de pagina con mis datos de alumno -->
    <footer>
        <p>&copy;Rodriguez Ugalde Jaime Emmanuel 5°J 0893</p>
    </footer>
</body>

</html>
