<?php
// Rodriguez Ugalde Jaime Emmanuel 5°J 0893
// aqui inicio la sesion para verificar si el usuario ya inicio sesion
session_start();

// aqui llamo a mi archivo de conexion
include("conexion.php");

// aqui preparo la consulta para traer solo los juegos de nintendo desde mi tabla
$sql = "SELECT * FROM productos WHERE categoria = 'Nintendo'";

// aqui ejecuto la consulta en la base de datos
$resultado = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<!--Rodriguez Ugalde Jaime Emmanuel 5°J 0893-->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nintendo | Gamix</title>
    <link rel="stylesheet" href="style.css">
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

        <!-- aqui reviso si hay sesion activa para mostrar bienvenida o el boton de cuenta -->
        <?php if (isset($_SESSION["usuario"])): ?>
            <span class="bienvenida">Bienvenido <?php echo htmlspecialchars($_SESSION["usuario"]); ?></span>
            <a href="cerrar.php" class="btn-sesion">Cerrar Sesion</a>
        <?php else: ?>
            <a href="iniciar.php">Cuenta</a>
        <?php endif; ?>
    </nav>

    <!-- aqui muestro el catalogo con los juegos consultados desde la base de datos -->
    <section id="nintendo">
        <h2>Juegos de Nintendo</h2>
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

                    <!-- aqui pongo el boton de compra -->
                    <a href="#">Comprar</a>
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
