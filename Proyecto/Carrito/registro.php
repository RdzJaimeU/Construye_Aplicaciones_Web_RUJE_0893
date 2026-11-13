<?php
// Rodriguez Ugalde Jaime Emmanuel 5°J 0893
// aqui inicio la sesion para verificar si el usuario tiene productos en el carrito
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

// aqui calculo las fechas limites para la mayoria de edad (minimo 18 años)
$fecha_max_18 = date('Y-m-d', strtotime('-18 years'));
$fecha_min_120 = date('Y-m-d', strtotime('-120 years'));

// aqui pongo la lista de ciudades de mexico para el selector
$ciudades_mexico = array(
    "Cd. Juárez, Chih.",
    "Chihuahua, Chih.",
    "Ciudad de México, CDMX",
    "Guadalajara, Jal.",
    "Monterrey, N.L.",
    "Puebla, Pue.",
    "Tijuana, B.C.",
    "León, Gto.",
    "Querétaro, Qro.",
    "Mérida, Yuc.",
    "Cancún, Q. Roo",
    "Hermosillo, Son.",
    "Toluca, Edo. Méx.",
    "Aguascalientes, Ags.",
    "San Luis Potosí, S.L.P.",
    "Culiacán, Sin.",
    "Saltillo, Coah.",
    "Mexicali, B.C.",
    "Morelia, Mich.",
    "Veracruz, Ver.",
    "Torreón, Coah.",
    "Cuernavaca, Mor.",
    "Villahermosa, Tab.",
    "Durango, Dgo.",
    "Mazatlán, Sin.",
    "Tampico, Tamps.",
    "Oaxaca, Oax.",
    "Tuxtla Gutiérrez, Chis.",
    "Pachuca, Hgo.",
    "Zacatecas, Zac.",
    "Colima, Col.",
    "La Paz, B.C.S.",
    "Campeche, Camp.",
    "Tepic, Nay.",
    "Tlaxcala, Tlax.",
    "Chilpancingo, Gro."
);

$mensaje = "";

// aqui reviso si ya enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conexion, trim($_POST["nombre"]));
    $telefono = mysqli_real_escape_string($conexion, trim($_POST["telefono"]));
    $direccion = mysqli_real_escape_string($conexion, trim($_POST["direccion"]));
    $ciudad = mysqli_real_escape_string($conexion, trim($_POST["ciudad"]));
    $codigo_postal = mysqli_real_escape_string($conexion, trim($_POST["codigo_postal"]));
    $fecha_nacimiento = mysqli_real_escape_string($conexion, trim($_POST["fecha_nacimiento"]));
    $correo = mysqli_real_escape_string($conexion, trim($_POST["correo"]));
    $password = mysqli_real_escape_string($conexion, trim($_POST["password"]));

    $errores_reg = array();

    // aqui reviso que el nombre completo tenga al menos nombre y apellido
    if (mb_strlen($nombre, 'UTF-8') < 3 || mb_strlen($nombre, 'UTF-8') > 80) {
        $errores_reg[] = "El nombre completo debe tener entre 3 y 80 caracteres.";
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.]+$/u', $nombre)) {
        $errores_reg[] = "El nombre solo puede contener letras, acentos y espacios (sin números ni caracteres extraños).";
    } elseif (count(preg_split('/\s+/', trim($nombre))) < 2) {
        $errores_reg[] = "Por favor ingresa tu nombre y al menos un apellido.";
    }

    // aqui reviso que el telefono tenga 10 digitos
    if (!preg_match('/^[0-9]{10}$/', $telefono)) {
        $errores_reg[] = "El teléfono debe tener exactamente 10 dígitos numéricos.";
    } elseif (preg_match('/^([0-9])\1{9}$/', $telefono)) {
        $errores_reg[] = "El número de teléfono ingresado no es válido.";
    }

    // aqui reviso que la direccion tenga una longitud adecuada
    if (mb_strlen($direccion, 'UTF-8') < 6 || mb_strlen($direccion, 'UTF-8') > 150) {
        $errores_reg[] = "La dirección de envío debe tener entre 6 y 150 caracteres.";
    }

    // aqui reviso la ciudad seleccionada
    if (mb_strlen($ciudad, 'UTF-8') < 3 || mb_strlen($ciudad, 'UTF-8') > 60) {
        $errores_reg[] = "La ciudad y estado deben tener entre 3 y 60 caracteres.";
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.,]+$/u', $ciudad)) {
        $errores_reg[] = "La ciudad y estado solo pueden contener letras, acentos y espacios.";
    }

    // aqui reviso que el codigo postal tenga 5 digitos
    if (!preg_match('/^[0-9]{5}$/', $codigo_postal)) {
        $errores_reg[] = "El código postal debe contener exactamente 5 dígitos numéricos.";
    } elseif (intval($codigo_postal) < 1000 || intval($codigo_postal) > 99999) {
        $errores_reg[] = "El código postal ingresado no es válido para la República Mexicana.";
    }

    // aqui reviso que sea mayor de 18 anos y que no sea fecha futura
    $fecha_dt = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
    $hoy_dt = new DateTime('today');
    if (!$fecha_dt || $fecha_dt->format('Y-m-d') !== $fecha_nacimiento) {
        $errores_reg[] = "Por favor selecciona una fecha de nacimiento válida en formato AAAA-MM-DD.";
    } elseif ($fecha_dt >= $hoy_dt) {
        $errores_reg[] = "La fecha de nacimiento no puede ser una fecha futura ni la de hoy.";
    } else {
        $edad = $hoy_dt->diff($fecha_dt)->y;
        if ($edad < 18) {
            $errores_reg[] = "Debes tener al menos 18 años para poder registrarte (tu edad actual calculada es $edad años).";
        } elseif ($edad > 120) {
            $errores_reg[] = "La fecha de nacimiento ingresada no es válida (edad superior a 120 años).";
        }
    }

    // aqui reviso que el correo electronico sea valido
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores_reg[] = "El correo electrónico ingresado no tiene un formato válido.";
    } elseif (strlen($correo) > 100) {
        $errores_reg[] = "El correo electrónico no puede exceder los 100 caracteres.";
    }

    // aqui reviso que la contrasena tenga al menos 6 caracteres
    if (strlen($password) < 6 || strlen($password) > 50) {
        $errores_reg[] = "La contraseña debe tener entre 6 y 50 caracteres para mayor seguridad.";
    }

    if (empty($errores_reg)) {
        // aqui verifico si el correo ya esta registrado en la base de datos
        $sql_verificar = "SELECT id FROM usuarios WHERE correo = '$correo' LIMIT 1";
        $res_verificar = mysqli_query($conexion, $sql_verificar);

        if (mysqli_num_rows($res_verificar) > 0) {
            $mensaje = "Ese correo ya está registrado en Gamix. <a href='iniciar.php' style='color:#b81414;font-weight:bold;'>Inicia sesión aquí</a>.";
        } else {
            // aqui preparo la orden sql para insertar el nuevo usuario en mi tabla
            $sql = "INSERT INTO usuarios (nombre, telefono, direccion, ciudad, codigo_postal, fecha_nacimiento, correo, password) VALUES ('$nombre', '$telefono', '$direccion', '$ciudad', '$codigo_postal', '$fecha_nacimiento', '$correo', '$password')";

            // aqui ejecuto la orden y reviso si se guardo bien
            if (mysqli_query($conexion, $sql)) {
                $mensaje = "Tu cuenta ha sido creada con éxito. Ya puedes <a href='iniciar.php' style='color:#b81414;font-weight:bold;'>iniciar sesión aquí</a>.";
            } else {
                $mensaje = "Error al crear la cuenta. Inténtalo de nuevo.";
            }
        }
    } else {
        $mensaje = implode("<br>", $errores_reg);
    }
}
?>
<!DOCTYPE html>
<!--Rodriguez Ugalde Jaime Emmanuel 5°J 0893-->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Cuenta | Gamix</title>
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>">
    <!-- aqui le doy estilo al selector de ciudades para que combine con el formulario -->
    <style>
        .contenedor-formulario select {
            display: block !important;
            width: 100% !important;
            height: 42px !important;
            padding: 0px 36px 0px 12px !important;
            margin-top: 5px !important;
            margin-bottom: 6px !important;
            background-color: #fafafa !important;
            border: 1px solid #cccccc !important;
            color: #111111 !important;
            border-radius: 4px !important;
            font-size: 14px !important;
            font-family: Arial, sans-serif !important;
            box-sizing: border-box !important;
            cursor: pointer !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2212%22%20height%3D%2212%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%23555555%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 14px center !important;
            background-size: 14px !important;
        }
        .contenedor-formulario select:focus {
            outline: none !important;
            border-color: #b81414 !important;
            background-color: #ffffff !important;
        }
    </style>
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

    <!-- aqui pongo el formulario para crear una nueva cuenta -->
    <div class="contenedor-formulario">
        <h2>Crear Cuenta</h2>
        <!-- aqui muestro las alertas si hay errores o si la cuenta se creo con exito -->
        <?php if (!empty($mensaje)): ?>
            <p class="alerta"><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form action="registro.php" method="POST">
            <label>Nombre Completo (Nombre y Apellido):</label>
            <input type="text" name="nombre" value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" placeholder="Tu nombre y apellidos" minlength="3" maxlength="80" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.]{3,80}" title="Solo letras, acentos y espacios (mínimo nombre y apellido)" required>

            <label>Teléfono de Contacto (10 dígitos):</label>
            <input type="tel" name="telefono" value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>" placeholder="Ej. 6561234567" minlength="10" maxlength="10" pattern="[0-9]{10}" title="Debe contener exactamente 10 dígitos numéricos" required>

            <label>Dirección de Envío (Calle, Número y Colonia):</label>
            <input type="text" name="direccion" value="<?php echo isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : ''; ?>" placeholder="Calle, número y colonia" minlength="6" maxlength="150" required>

            <label>Ciudad y Estado:</label>
            <select name="ciudad" required>
                <option value="" disabled <?php echo empty($_POST['ciudad']) ? 'selected' : ''; ?>>-- Selecciona tu Ciudad y Estado --</option>
                <!-- aqui recorro la lista de ciudades para el selector -->
                <?php foreach ($ciudades_mexico as $cd): ?>
                    <option value="<?php echo htmlspecialchars($cd); ?>" <?php echo (isset($_POST['ciudad']) && $_POST['ciudad'] == $cd) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cd); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Código Postal (5 dígitos):</label>
            <input type="text" name="codigo_postal" value="<?php echo isset($_POST['codigo_postal']) ? htmlspecialchars($_POST['codigo_postal']) : ''; ?>" placeholder="Ej. 32000" minlength="5" maxlength="5" pattern="[0-9]{5}" title="Debe contener exactamente 5 dígitos numéricos (ej. 32000)" required>

            <label>Fecha de Nacimiento (Mayor de 18 años):</label>
            <input type="date" name="fecha_nacimiento" value="<?php echo isset($_POST['fecha_nacimiento']) ? htmlspecialchars($_POST['fecha_nacimiento']) : ''; ?>" min="<?php echo $fecha_min_120; ?>" max="<?php echo $fecha_max_18; ?>" title="Debes ser mayor de 18 años para poder registrarte" required>

            <label>Correo Electrónico (Tu cuenta para ingresar):</label>
            <input type="email" name="correo" value="<?php echo isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : ''; ?>" placeholder="ejemplo@correo.com" maxlength="100" required>

            <label>Contraseña (Mínimo 6 caracteres):</label>
            <input type="password" name="password" placeholder="Mínimo 6 caracteres" minlength="6" maxlength="50" required>

            <button type="submit">Crear Cuenta</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="iniciar.php">Inicia sesión aquí</a></p>
    </div>

    <!-- aqui pongo el pie de pagina con mis datos -->
    <footer>
        <p>&copy;Rodriguez Ugalde Jaime Emmanuel 5°J 0893</p>
    </footer>

    <!-- aqui pongo el script para que el telefono y codigo postal solo acepten numeros -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // aqui hago que el telefono solo acepte 10 numeros
            var inputTel = document.querySelector('input[name="telefono"]');
            if (inputTel) {
                inputTel.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
                });
            }

            // aqui hago que el codigo postal solo acepte 5 numeros
            var inputCP = document.querySelector('input[name="codigo_postal"]');
            if (inputCP) {
                inputCP.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
                });
            }
        });
    </script>
</body>

</html>
