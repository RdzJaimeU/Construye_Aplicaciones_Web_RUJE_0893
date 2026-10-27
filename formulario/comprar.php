<?php
// aqui inicio la sesion para saber si el usuario inicio sesion
session_start();

// aqui llamo a mi archivo de conexion
include("conexion.php");

$mensaje = "";

// aqui tomo los datos del juego que llegaron desde el catalogo
$juego = isset($_GET["juego"]) ? $_GET["juego"] : "";
$precio = isset($_GET["precio"]) ? $_GET["precio"] : "";
$nombre_usuario = isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : "";

// aqui reviso si ya enviaron los datos de la compra
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $direccion = $_POST["direccion"];
    $marca = $_POST["marca"];
    $juego = $_POST["juego"];
    $precio = $_POST["precio"];
    $metodo_pago = $_POST["metodo_pago"];

    // aqui preparo la orden sql para guardar la compra en mi tabla
    $sql = "INSERT INTO compras (usuario, direccion, marca, juego, precio, metodo_pago) VALUES ('$usuario', '$direccion', '$marca', '$juego', '$precio', '$metodo_pago')";

    // aqui ejecuto la orden en la base de datos
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Tu compra fue registrada con exito en la base de datos.";
    } else {
        $mensaje = "Ocurrio un error al registrar la compra.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comprar | Gamix</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>GAM<span>IX</span></h1>
    </header>

    <nav>
        <a href="index.php">Volver a la tienda</a>
        <?php if (isset($_SESSION["usuario"])): ?>
            <span class="bienvenida">Bienvenido <?php echo $_SESSION["usuario"]; ?></span>
            <a href="cerrar.php" class="btn-sesion">Cerrar Sesion</a>
        <?php else: ?>
            <a href="registro.php">Registrarse</a>
            <a href="iniciar.php">Iniciar Sesion</a>
        <?php endif; ?>
    </nav>

    <div class="contenedor-formulario">
        <h2>Datos de tu Compra</h2>
        <?php if (!empty($mensaje)): ?>
            <p class="alerta"><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form action="comprar.php" method="POST">
            <label>Nombre del Comprador:</label>
            <input type="text" name="usuario" value="<?php echo htmlspecialchars($nombre_usuario); ?>" required>

            <label>Direccion de Envio:</label>
            <input type="text" name="direccion" placeholder="Calle, numero y colonia" required>

            <label>Marca o Consola:</label>
            <select name="marca">
                <option value="Nintendo">Nintendo</option>
                <option value="PlayStation">PlayStation</option>
                <option value="Xbox">Xbox</option>
            </select>

            <label>Videojuego Seleccionado:</label>
            <input type="text" name="juego" value="<?php echo htmlspecialchars($juego); ?>" required>

            <label>Precio MXN:</label>
            <input type="text" name="precio" value="<?php echo htmlspecialchars($precio); ?>" required>

            <label>Metodo de Pago:</label>
            <select name="metodo_pago">
                <option value="Tarjeta">Tarjeta de Debito o Credito</option>
                <option value="Efectivo en OXXO">Efectivo en OXXO</option>
                <option value="Transferencia">Transferencia Bancaria</option>
            </select>

            <button type="submit">Confirmar Pedido</button>
        </form>
    </div>

    <footer>
        <p>&copy;Rodriguez Ugalde Jaime Emmanuel 5°J 0893</p>
    </footer>
</body>
</html>
