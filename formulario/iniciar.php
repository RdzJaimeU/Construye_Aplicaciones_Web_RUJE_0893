<?php
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
<html>
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesion | Gamix</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>GAM<span>IX</span></h1>
    </header>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="registro.php">Registrarse</a>
    </nav>

    <div class="contenedor-formulario">
        <h2>Iniciar Sesion</h2>
        <?php if (!empty($mensaje)): ?>
            <p class="alerta error"><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form action="iniciar.php" method="POST">
            <label>Correo Electronico:</label>
            <input type="email" name="correo" required>

            <label>Contraseña:</label>
            <input type="password" name="password" required>

            <button type="submit">Ingresar</button>
        </form>
        <p>¿Aun no tienes cuenta? <a href="registro.php">Registrate aqui</a></p>
    </div>

    <footer>
        <p>&copy;Rodriguez Ugalde Jaime Emmanuel 5°J 0893</p>
    </footer>
</body>
</html>
