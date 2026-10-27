<?php
// aqui llamo a mi archivo de conexion
include("conexion.php");

$mensaje = "";

// aqui reviso si ya enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $password = $_POST["password"];

    // aqui preparo la orden sql para insertar el usuario en mi tabla
    $sql = "INSERT INTO usuarios (nombre, correo, password) VALUES ('$nombre', '$correo', '$password')";

    // aqui ejecuto la orden y reviso si se guardo bien
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Te has registrado correctamente. Ya puedes iniciar sesion.";
    } else {
        $mensaje = "Error al registrarte. Quizas ese correo ya existe.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registro | Gamix</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>GAM<span>IX</span></h1>
    </header>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="iniciar.php">Iniciar Sesion</a>
    </nav>

    <div class="contenedor-formulario">
        <h2>Registro de Usuario</h2>
        <?php if (!empty($mensaje)): ?>
            <p class="alerta"><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form action="registro.php" method="POST">
            <label>Nombre Completo:</label>
            <input type="text" name="nombre" required>

            <label>Correo Electronico:</label>
            <input type="email" name="correo" required>

            <label>Contraseña:</label>
            <input type="password" name="password" required>

            <button type="submit">Crear Cuenta</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="iniciar.php">Inicia sesion aqui</a></p>
    </div>

    <footer>
        <p>&copy;Rodriguez Ugalde Jaime Emmanuel 5°J 0893</p>
    </footer>
</body>
</html>
