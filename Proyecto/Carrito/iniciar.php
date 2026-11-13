<?php
// Rodriguez Ugalde Jaime Emmanuel 5°J 0893
// aqui inicio las sesiones para guardar el nombre del usuario
session_start();

// aqui llamo a mi archivo de conexion
include("conexion.php");

// si el usuario ya inicio sesion lo mando al inicio
if (isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit();
}

// aqui calculo cuantos articulos van en el carrito para mostrar en la barra
$total_articulos_nav = 0;
if (isset($_SESSION["carrito"]) && is_array($_SESSION["carrito"])) {
    foreach ($_SESSION["carrito"] as $item) {
        $total_articulos_nav += $item["cantidad"];
    }
}

// aqui muestro el aviso si el usuario debe iniciar sesion para comprar
$mensaje = "";
if (isset($_GET["mensaje"]) && $_GET["mensaje"] == "debes_iniciar") {
    $mensaje = "Debes iniciar sesión con tu cuenta para poder realizar una compra.";
}

// aqui reviso si ya enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = mysqli_real_escape_string($conexion, trim($_POST["correo"]));
    $password = mysqli_real_escape_string($conexion, trim($_POST["password"]));

    // aqui reviso que no dejen campos vacios y que el correo sea valido
    if (empty($correo) || empty($password)) {
        $mensaje = "Por favor completa ambos campos para ingresar.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "El correo electrónico ingresado no tiene un formato válido.";
    } else {
        // aqui busco al usuario en mi tabla de usuarios unicamente por su correo (cuenta)
        $sql = "SELECT * FROM usuarios WHERE correo = '$correo' AND password = '$password'";
        $resultado = mysqli_query($conexion, $sql);

        // aqui reviso si encontre alguna coincidencia
        if (mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_assoc($resultado);
            // aqui guardo el nombre, correo e id del usuario en la sesion
            $_SESSION["usuario"] = $fila["nombre"];
            $_SESSION["correo"] = $fila["correo"];
            $_SESSION["usuario_id"] = $fila["id"];
            // si tenia productos en el carrito va a comprar, si no va al inicio
            if (!empty($_SESSION["carrito"])) {
                header("Location: comprar.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            // si los datos no coinciden muestro el aviso de error
            $mensaje = "Correo o contraseña equivocados.";
        }
    }
}
?>
<!DOCTYPE html>
<!--Rodriguez Ugalde Jaime Emmanuel 5°J 0893-->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesion | Gamix</title>
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>">
</head>

<body>
    <!-- aqui pongo el encabezado con el logo y el titulo -->
    <header>
        <h1>GAM<span>IX</span></h1>
        <img src="img/logo1.png" alt="logo de la empresa">
    </header>

    <!-- aqui pongo la barra de navegacion -->
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
    </nav>

    <!-- aqui pongo el formulario para entrar a la cuenta -->
    <div class="contenedor-formulario">
        <h2>Iniciar Sesión</h2>
        <!-- aqui muestro el mensaje de error si los datos no fueron correctos -->
        <?php if (!empty($mensaje)): ?>
            <p class="alerta error"><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form action="iniciar.php" method="POST">
            <label>Correo Electrónico (Cuenta):</label>
            <input type="email" name="correo" value="<?php echo isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : ''; ?>" placeholder="correo@ejemplo.com" maxlength="100" required>

            <label>Contraseña:</label>
            <input type="password" name="password" placeholder="Tu contraseña" minlength="4" maxlength="50" required>

            <button type="submit">Ingresar</button>
        </form>
        <p>¿No tienes cuenta? <a href="registro.php">Crea tu cuenta aquí</a></p>
    </div>

    <!-- aqui pongo el pie de pagina con mis datos -->
    <footer>
        <p>&copy;Rodriguez Ugalde Jaime Emmanuel 5°J 0893</p>
    </footer>
</body>

</html>
