<?php
// Rodriguez Ugalde Jaime Emmanuel 5°J 0893
// aqui inicio las sesiones para guardar el nombre del usuario
session_start();

// aqui llamo a mi archivo de conexion
include("conexion.php");

$mensaje = "";

// aqui reviso si ya enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $password = $_POST["password"];

    // aqui busco al usuario en mi tabla de usuarios
    $sql = "SELECT * FROM usuarios WHERE correo = '$correo' AND password = '$password'";
    $resultado = mysqli_query($conexion, $sql);

    // aqui reviso si encontre alguna coincidencia
    if (mysqli_num_rows($resultado) > 0) {
        $fila = mysqli_fetch_assoc($resultado);
        // aqui guardo el nombre del usuario en la sesion
        $_SESSION["usuario"] = $fila["nombre"];
        // aqui redirijo al usuario a la tienda principal
        header("Location: index.php");
        exit();
    } else {
        $mensaje = "Correo o contraseña equivocados.";
    }
}
?>
<!DOCTYPE html>
<!--Rodriguez Ugalde Jaime Emmanuel 5°J 0893-->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesion | Gamix</title>
    <link rel="stylesheet" href="style.css">
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
    </nav>

    <!-- aqui pongo el formulario para entrar a la cuenta -->
    <div class="contenedor-formulario">
        <h2>Iniciar Sesión</h2>
        <?php if (!empty($mensaje)): ?>
            <p class="alerta error"><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form action="iniciar.php" method="POST">
            <label>Correo Electrónico:</label>
            <input type="email" name="correo" required>

            <label>Contraseña:</label>
            <input type="password" name="password" required>

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
