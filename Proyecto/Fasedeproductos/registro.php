<?php
// Rodriguez Ugalde Jaime Emmanuel 5°J 0893
// aqui llamo a mi archivo de conexion
include("conexion.php");

$mensaje = "";

// aqui reviso si ya enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];
    $direccion = $_POST["direccion"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $correo = $_POST["correo"];
    $password = $_POST["password"];

    // aqui preparo la orden sql para insertar el nuevo usuario en mi tabla
    $sql = "INSERT INTO usuarios (nombre, telefono, direccion, fecha_nacimiento, correo, password) VALUES ('$nombre', '$telefono', '$direccion', '$fecha_nacimiento', '$correo', '$password')";

    // aqui ejecuto la orden y reviso si se guardo bien
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Tu cuenta ha sido creada con éxito. Ya puedes iniciar sesión.";
    } else {
        $mensaje = "Error al crear la cuenta. Quizás ese correo ya está registrado.";
    }
}
?>
<!DOCTYPE html>
<!--Rodriguez Ugalde Jaime Emmanuel 5°J 0893-->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Cuenta | Gamix</title>
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

    <!-- aqui pongo el formulario para crear una nueva cuenta -->
    <div class="contenedor-formulario">
        <h2>Crear Cuenta</h2>
        <?php if (!empty($mensaje)): ?>
            <p class="alerta"><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form action="registro.php" method="POST">
            <label>Nombre Completo:</label>
            <input type="text" name="nombre" placeholder="Tu nombre y apellidos" required>

            <label>Teléfono o Celular:</label>
            <input type="tel" name="telefono" placeholder="Ej. 5512345678" required>

            <label>Dirección de Envío:</label>
            <input type="text" name="direccion" placeholder="Calle, número y colonia" required>

            <label>Fecha de Nacimiento:</label>
            <input type="date" name="fecha_nacimiento" required>

            <label>Correo Electrónico:</label>
            <input type="email" name="correo" placeholder="ejemplo@correo.com" required>

            <label>Contraseña:</label>
            <input type="password" name="password" placeholder="Crea una contraseña segura" required>

            <button type="submit">Crear Cuenta</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="iniciar.php">Inicia sesión aquí</a></p>
    </div>

    <!-- aqui pongo el pie de pagina con mis datos -->
    <footer>
        <p>&copy;Rodriguez Ugalde Jaime Emmanuel 5°J 0893</p>
    </footer>
</body>

</html>
