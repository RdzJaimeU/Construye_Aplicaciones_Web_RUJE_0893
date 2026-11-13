<?php
// Rodriguez Ugalde Jaime Emmanuel 5°J 0893
// aqui inicio la sesion para verificar al usuario y su carrito
session_start();

// aqui llamo a mi archivo de conexion con la base de datos
include("conexion.php");

// aqui inicializo el carrito si todavia no existe
if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = array();
}

// aqui verifico si el usuario tiene sesion iniciada para poder comprar
if (!isset($_SESSION["usuario"])) {
    header("Location: iniciar.php?mensaje=debes_iniciar");
    exit();
}

// aqui si el usuario presiono comprar directamente desde el catalogo lo agrego al carrito
if (isset($_GET["id"])) {
    $id_directo = intval($_GET["id"]);
    $sql_prod = "SELECT * FROM productos WHERE id = $id_directo";
    $res_prod = mysqli_query($conexion, $sql_prod);
    if ($prod = mysqli_fetch_assoc($res_prod)) {
        if (!isset($_SESSION["carrito"][$id_directo])) {
            $_SESSION["carrito"][$id_directo] = array(
                "id" => $prod["id"],
                "nombre" => $prod["nombre"],
                "categoria" => $prod["categoria"],
                "precio" => floatval($prod["precio"]),
                "imagen" => $prod["imagen"],
                "cantidad" => 1
            );
        }
    }
}

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

// aqui busco los datos del usuario en la base de datos si ya tiene sesion activa
$nombre_defecto = "";
$correo_defecto = "";
$telefono_defecto = "";
$direccion_defecto = "";
$ciudad_defecto = "";
$codigo_postal_defecto = "";
$tarjeta_titular_defecto = "";
$tarjeta_numero_defecto = "";
$tarjeta_exp_defecto = "";
$tarjeta_cvv_defecto = "";

if (isset($_SESSION["usuario"])) {
    $user_id_sesion = isset($_SESSION["usuario_id"]) ? intval($_SESSION["usuario_id"]) : 0;
    $nombre_sesion = mysqli_real_escape_string($conexion, $_SESSION["usuario"]);
    $correo_sesion = isset($_SESSION["correo"]) ? mysqli_real_escape_string($conexion, $_SESSION["correo"]) : "";

    if ($user_id_sesion > 0) {
        $sql_usuario = "SELECT * FROM usuarios WHERE id = $user_id_sesion LIMIT 1";
    } else {
        $sql_usuario = "SELECT * FROM usuarios WHERE nombre = '$nombre_sesion' OR correo = '$nombre_sesion' OR correo = '$correo_sesion' LIMIT 1";
    }
    $res_usuario = mysqli_query($conexion, $sql_usuario);
    if ($usuario_datos = mysqli_fetch_assoc($res_usuario)) {
        $nombre_defecto = $usuario_datos["nombre"];
        $correo_defecto = $usuario_datos["correo"];
        $telefono_defecto = $usuario_datos["telefono"];
        $direccion_defecto = $usuario_datos["direccion"];
        $ciudad_defecto = $usuario_datos["ciudad"];
        $codigo_postal_defecto = $usuario_datos["codigo_postal"];
        $tarjeta_titular_defecto = $usuario_datos["tarjeta_titular"] ?? "";
        $tarjeta_numero_defecto = $usuario_datos["tarjeta_numero"] ?? "";
        $tarjeta_exp_defecto = $usuario_datos["tarjeta_expiracion"] ?? "";
        $tarjeta_cvv_defecto = $usuario_datos["tarjeta_cvv"] ?? "";
    } else {
        $nombre_defecto = $_SESSION["usuario"];
    }
}

// aqui obtengo la ultima pagina donde estaba navegando el usuario
$pagina_retorno = isset($_SESSION["ultima_pagina"]) ? $_SESSION["ultima_pagina"] : "index.php";

// aqui reviso si vengo de confirmar la compra para mostrar el recibo guardado
$compra_exitosa = false;
$pedido_confirmado = null;
$error_compra = "";

if (isset($_GET["ver_recibo"]) && isset($_SESSION["ultimo_recibo"])) {
    $compra_exitosa = true;
    $pedido_confirmado = $_SESSION["ultimo_recibo"];
}

// aqui reviso si ya enviaron los datos del formulario de compra
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_SESSION["carrito"])) {
    $usuario = mysqli_real_escape_string($conexion, trim($_POST["usuario"]));
    $correo = mysqli_real_escape_string($conexion, trim($_POST["correo"]));
    $telefono = mysqli_real_escape_string($conexion, trim($_POST["telefono"]));
    $direccion = mysqli_real_escape_string($conexion, trim($_POST["direccion"]));
    $ciudad = mysqli_real_escape_string($conexion, trim($_POST["ciudad"]));
    $codigo_postal = mysqli_real_escape_string($conexion, trim($_POST["codigo_postal"]));
    $metodo_pago = mysqli_real_escape_string($conexion, trim($_POST["metodo_pago"]));

    $tarjeta_titular = mysqli_real_escape_string($conexion, trim($_POST["tarjeta_titular"] ?? ""));
    // aqui quito los espacios del numero de tarjeta para guardarlo bien
    $tarjeta_numero = mysqli_real_escape_string($conexion, str_replace(' ', '', trim($_POST["tarjeta_numero"] ?? "")));
    $tarjeta_exp = mysqli_real_escape_string($conexion, trim($_POST["tarjeta_expiracion"] ?? ""));
    $tarjeta_cvv = mysqli_real_escape_string($conexion, trim($_POST["tarjeta_cvv"] ?? ""));

    // aqui reviso que todos los datos de la compra esten bien
    $errores_c = array();

    // aqui reviso el nombre del que recibe
    if (mb_strlen($usuario, 'UTF-8') < 3 || mb_strlen($usuario, 'UTF-8') > 80) {
        $errores_c[] = "El nombre del receptor debe tener entre 3 y 80 caracteres.";
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.]+$/u', $usuario)) {
        $errores_c[] = "El nombre del receptor solo puede contener letras, acentos y espacios.";
    }

    // aqui reviso que el correo electronico tenga un formato valido
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores_c[] = "El correo electrónico ingresado no tiene un formato válido.";
    }

    // aqui reviso que el telefono tenga 10 digitos
    if (!preg_match('/^[0-9]{10}$/', $telefono)) {
        $errores_c[] = "El teléfono debe contener exactamente 10 dígitos numéricos.";
    } elseif (preg_match('/^([0-9])\1{9}$/', $telefono)) {
        $errores_c[] = "El número de teléfono ingresado no es válido.";
    }

    // aqui reviso que la direccion no este vacia
    if (mb_strlen($direccion, 'UTF-8') < 6 || mb_strlen($direccion, 'UTF-8') > 150) {
        $errores_c[] = "La dirección de entrega debe tener entre 6 y 150 caracteres.";
    }

    // aqui reviso la ciudad seleccionada
    if (mb_strlen($ciudad, 'UTF-8') < 3 || mb_strlen($ciudad, 'UTF-8') > 60) {
        $errores_c[] = "La ciudad y estado deben tener entre 3 y 60 caracteres.";
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.,]+$/u', $ciudad)) {
        $errores_c[] = "La ciudad y estado solo pueden contener letras, acentos y espacios.";
    }

    // aqui reviso que el codigo postal tenga 5 digitos
    if (!preg_match('/^[0-9]{5}$/', $codigo_postal)) {
        $errores_c[] = "El código postal debe contener exactamente 5 dígitos numéricos.";
    } elseif (intval($codigo_postal) < 1000 || intval($codigo_postal) > 99999) {
        $errores_c[] = "El código postal ingresado no es válido para México.";
    }

    // aqui reviso el metodo de pago seleccionado
    if (!in_array($metodo_pago, array("Tarjeta", "Efectivo en OXXO", "Transferencia"))) {
        $errores_c[] = "Por favor selecciona un método de pago válido.";
    }

    // aqui reviso los datos de la tarjeta bancaria
    if ($metodo_pago == "Tarjeta") {
        if (mb_strlen($tarjeta_titular, 'UTF-8') < 3 || mb_strlen($tarjeta_titular, 'UTF-8') > 80) {
            $errores_c[] = "El nombre del titular de la tarjeta debe tener entre 3 y 80 caracteres.";
        } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.]+$/u', $tarjeta_titular)) {
            $errores_c[] = "El titular de la tarjeta solo puede contener letras y espacios.";
        }

        if (!preg_match('/^[0-9]{16}$/', $tarjeta_numero)) {
            $errores_c[] = "El número de tarjeta debe contener exactamente 16 dígitos numéricos.";
        } elseif (preg_match('/^([0-9])\1{15}$/', $tarjeta_numero)) {
            $errores_c[] = "El número de tarjeta ingresado no es válido.";
        }

        if (preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $tarjeta_exp, $m_exp_c)) {
            $exp_mes = intval($m_exp_c[1]);
            $exp_anio = intval("20" . $m_exp_c[2]);
            $mes_hoy = intval(date('n'));
            $anio_hoy = intval(date('Y'));
            if ($exp_anio < $anio_hoy || ($exp_anio === $anio_hoy && $exp_mes < $mes_hoy)) {
                $errores_c[] = "La tarjeta no puede estar vencida (fecha ingresada: $tarjeta_exp).";
            } elseif ($exp_anio > $anio_hoy + 15) {
                $errores_c[] = "La fecha de vencimiento ingresada es demasiado lejana ($tarjeta_exp).";
            }
        } else {
            $errores_c[] = "La fecha de vencimiento debe tener formato MM/AA (ejemplo 12/28).";
        }

        if (!preg_match('/^[0-9]{3,4}$/', $tarjeta_cvv) || $tarjeta_cvv === '000' || $tarjeta_cvv === '0000') {
            $errores_c[] = "El código CVV de seguridad debe contener 3 o 4 dígitos válidos.";
        }
    }

    if (!empty($errores_c)) {
        $error_compra = implode("<br>", $errores_c);
    } else {
        // aqui genero un folio unico para el pedido
        $folio = "GMX-" . rand(1000, 9999);

        // aqui guardo una copia de los videojuegos comprados antes de vaciar el carrito
        $articulos_comprados = $_SESSION["carrito"];
        $total_compra = 0;

        // aqui recorro cada juego del carrito para insertarlo en la tabla de compras
        foreach ($articulos_comprados as $item) {
            $juego = mysqli_real_escape_string($conexion, $item["nombre"]);
            $plataforma = mysqli_real_escape_string($conexion, $item["categoria"]);
            $marca = $plataforma;
            $cantidad = intval($item["cantidad"]);
            $precio = floatval($item["precio"]);
            $subtotal = $precio * $cantidad;
            $total_compra += $subtotal;

            // aqui ejecuto la orden sql para guardar cada compra en la base de datos
            $sql_insert = "INSERT INTO compras (folio, usuario, correo, telefono, direccion, ciudad, codigo_postal, marca, plataforma, juego, cantidad, precio, subtotal, metodo_pago) VALUES ('$folio', '$usuario', '$correo', '$telefono', '$direccion', '$ciudad', '$codigo_postal', '$marca', '$plataforma', '$juego', $cantidad, $precio, $subtotal, '$metodo_pago')";
            mysqli_query($conexion, $sql_insert);
        }

        // si el usuario inicio sesion actualizo sus datos de envio y tarjeta en la tabla de usuarios
        if (isset($_SESSION["usuario"])) {
            $set_extra_tarjeta = "";
            if ($metodo_pago == "Tarjeta" && isset($_POST["guardar_tarjeta_perfil"])) {
                $set_extra_tarjeta = ", tarjeta_titular = '$tarjeta_titular', tarjeta_numero = '$tarjeta_numero', tarjeta_expiracion = '$tarjeta_exp', tarjeta_cvv = '$tarjeta_cvv'";
            }
            if ($user_id_sesion > 0) {
                $sql_guardar_usuario = "UPDATE usuarios SET telefono = '$telefono', direccion = '$direccion', ciudad = '$ciudad', codigo_postal = '$codigo_postal' $set_extra_tarjeta WHERE id = $user_id_sesion";
            } else {
                $sql_guardar_usuario = "UPDATE usuarios SET telefono = '$telefono', direccion = '$direccion', ciudad = '$ciudad', codigo_postal = '$codigo_postal' $set_extra_tarjeta WHERE nombre = '$usuario' OR correo = '$correo'";
            }
            mysqli_query($conexion, $sql_guardar_usuario);
        }

        // aqui preparo el resumen del pedido para mostrarselo al comprador
        $pedido_confirmado = array(
            "folio" => $folio,
            "usuario" => $usuario,
            "correo" => $correo,
            "telefono" => $telefono,
            "direccion" => $direccion,
            "ciudad" => $ciudad,
            "codigo_postal" => $codigo_postal,
            "metodo_pago" => $metodo_pago,
            "tarjeta_numero" => $tarjeta_numero,
            "tarjeta_titular" => $tarjeta_titular,
            "articulos" => $articulos_comprados,
            "total" => $total_compra
        );

        // aqui vacio el carrito de compras una vez completado el pedido
        $_SESSION["carrito"] = array();
        $_SESSION["ultimo_recibo"] = $pedido_confirmado;
        $compra_exitosa = true;
    }
}

// aqui calculo la cantidad total de articulos en el carrito para la barra
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
    <title>Finalizar Compra | Gamix</title>
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>">
    <!-- aqui pongo los estilos para el selector de ciudades de la compra -->
    <style>
        .columna-formulario select {
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
        .columna-formulario select:focus {
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

        <!-- aqui pongo el enlace al carrito -->
        <a href="carrito.php" class="nav-carrito">
            <img src="img/carrito.png" alt="carrito" class="icono-carrito" onerror="this.style.display='none'">
            Carrito (<?php echo $total_articulos_nav; ?>)
        </a>

        <!-- aqui pongo la bienvenida o el enlace de cuenta -->
        <?php if (isset($_SESSION["usuario"])): ?>
            <a href="cuenta.php" class="btn-cuenta">Mi Cuenta (<?php echo htmlspecialchars($_SESSION["usuario"]); ?>)</a>
            <a href="cerrar.php" class="btn-sesion">Cerrar Sesion</a>
        <?php else: ?>
            <a href="iniciar.php">Cuenta</a>
        <?php endif; ?>
    </nav>

    <!-- aqui muestro el comprobante de compra o el formulario de checkout -->
    <?php if ($compra_exitosa && $pedido_confirmado): ?>
        <!-- aqui muestro el recibo cuando la compra fue registrada exitosamente en la bd -->
        <main class="recibo-exito">
            <div class="encabezado-exito">
                <h2>¡Muchas Gracias por tu Compra!</h2>
                <p>Tu pedido fue registrado correctamente en nuestra base de datos.</p>
                <span class="folio-badge">Folio de Pedido: #<?php echo htmlspecialchars($pedido_confirmado["folio"]); ?></span>
            </div>

            <!-- aqui pongo los detalles de entrega a domicilio -->
            <div class="caja-detalles-envio">
                <h3>Informacion del Envio a Domicilio</h3>
                <p><strong>Comprador:</strong> <?php echo htmlspecialchars($pedido_confirmado["usuario"]); ?></p>
                <p><strong>Correo:</strong> <?php echo htmlspecialchars($pedido_confirmado["correo"]); ?> | <strong>Telefono:</strong> <?php echo htmlspecialchars($pedido_confirmado["telefono"]); ?></p>
                <p><strong>Direccion de Entrega:</strong> <?php echo htmlspecialchars($pedido_confirmado["direccion"]); ?>, <?php echo htmlspecialchars($pedido_confirmado["ciudad"]); ?>, C.P. <?php echo htmlspecialchars($pedido_confirmado["codigo_postal"]); ?></p>
                <p><strong>Paqueteria:</strong> DHL / FedEx Express (Entrega garantizada de 24 a 48 horas habiles).</p>
            </div>

            <!-- aqui pongo las instrucciones segun el metodo de pago seleccionado -->
            <div class="caja-pago-instrucciones">
                <strong>Método de Pago: <?php echo htmlspecialchars($pedido_confirmado["metodo_pago"]); ?></strong><br>
                <?php if ($pedido_confirmado["metodo_pago"] == "Tarjeta"): ?>
                    Tu pago con tarjeta de crédito/débito a nombre de <strong><?php echo htmlspecialchars(!empty($pedido_confirmado["tarjeta_titular"]) ? $pedido_confirmado["tarjeta_titular"] : $pedido_confirmado["usuario"]); ?></strong> (terminación: <strong>**** <?php echo htmlspecialchars(substr($pedido_confirmado["tarjeta_numero"], -4)); ?></strong>) fue aprobado y procesado con éxito. En breve recibirás la factura y número de guía en tu correo.
                <?php elseif ($pedido_confirmado["metodo_pago"] == "Efectivo en OXXO"): ?>
                    Presenta esta referencia en la caja de cualquier OXXO de la República: <strong>4912-3840-9128-<?php echo rand(1000, 9999); ?></strong> para liquidar en efectivo.
                <?php else: ?>
                    Realiza tu transferencia SPEI a la cuenta CLABE: <strong>012180001234567890</strong> (Banco BBVA - Gamix S.A. de C.V.) con concepto: <strong><?php echo htmlspecialchars($pedido_confirmado["folio"]); ?></strong>.
                <?php endif; ?>
            </div>

            <!-- aqui muestro la tabla con los videojuegos que se compraron -->
            <table class="tabla-carrito">
                <thead>
                    <tr>
                        <th>Videojuego</th>
                        <th>Consola / Plataforma</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedido_confirmado["articulos"] as $articulo): ?>
                        <tr>
                            <td>
                                <div class="producto-info">
                                    <img src="<?php echo htmlspecialchars($articulo['imagen']); ?>" alt="<?php echo htmlspecialchars($articulo['nombre']); ?>" class="miniatura-carrito">
                                    <strong><?php echo htmlspecialchars($articulo['nombre']); ?></strong>
                                </div>
                            </td>
                            <td><span class="etiqueta-plataforma"><?php echo htmlspecialchars($articulo['categoria']); ?></span></td>
                            <td><?php echo $articulo['cantidad']; ?></td>
                            <td>$<?php echo number_format($articulo['precio'], 2); ?> MXN</td>
                            <td><strong>$<?php echo number_format($articulo['precio'] * $articulo['cantidad'], 2); ?> MXN</strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="text-align: right; margin-bottom: 25px;">
                <p style="font-size: 16px; color: #555555;">Envio Express: <strong style="color: #2e7d32;">GRATIS</strong></p>
                <h3 style="font-size: 22px; color: #b81414; margin-top: 5px;">Total Pagado: $<?php echo number_format($pedido_confirmado["total"], 2); ?> MXN</h3>
            </div>

            <div style="text-align: center;">
                <a href="<?php echo htmlspecialchars($pagina_retorno); ?>" class="btn-secundario" style="margin-right: 10px;">&larr; Volver al Catalogo</a>
                <a href="<?php echo htmlspecialchars($pagina_retorno); ?>" class="btn-comprar" style="display: inline-block; width: auto; padding: 10px 24px;">Seguir Comprando Videojuegos</a>
            </div>
        </main>

    <?php elseif (empty($_SESSION["carrito"])): ?>
        <!-- aqui aviso si intentan entrar a comprar sin tener articulos en el carrito -->
        <main class="carrito-vacio">
            <p>No tienes productos en tu carrito para realizar la compra.</p>
            <a href="<?php echo htmlspecialchars($pagina_retorno); ?>" class="btn-secundario">&larr; Ir a Seleccionar Videojuegos</a>
        </main>

    <?php else: ?>
        <!-- aqui muestro el formulario de compra junto con el resumen del pedido -->
        <main class="contenedor-checkout">
            <!-- aqui pongo la columna con los campos para registrar los datos de entrega y pago -->
            <div class="columna-formulario">
                <h2>Datos de Envío y Método de Pago</h2>
                <?php if (!empty($error_compra)): ?>
                    <p class="alerta error" style="margin-bottom: 14px;"><?php echo $error_compra; ?></p>
                <?php endif; ?>
                <form action="comprar.php" method="POST">
                    <label>Nombre Completo del Receptor:</label>
                    <input type="text" name="usuario" value="<?php echo htmlspecialchars($nombre_defecto); ?>" placeholder="Nombre y Apellidos" minlength="3" maxlength="80" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.]{3,80}" title="Solo letras, acentos y espacios" required>

                    <label>Correo Electrónico:</label>
                    <input type="email" name="correo" value="<?php echo htmlspecialchars($correo_defecto); ?>" placeholder="correo@ejemplo.com" maxlength="100" required>

                    <label>Teléfono de Contacto (10 dígitos):</label>
                    <input type="tel" name="telefono" value="<?php echo htmlspecialchars($telefono_defecto); ?>" placeholder="Ej. 6561234567" minlength="10" maxlength="10" pattern="[0-9]{10}" title="Debe contener exactamente 10 dígitos numéricos" required>

                    <label>Dirección Completa (Calle, Número y Colonia):</label>
                    <input type="text" name="direccion" value="<?php echo htmlspecialchars($direccion_defecto); ?>" placeholder="Calle, número exterior e interior, colonia" minlength="6" maxlength="150" required>

                    <label>Ciudad y Estado:</label>
                    <select name="ciudad" required>
                        <option value="" disabled>-- Selecciona tu Ciudad y Estado --</option>
                        <?php 
                        // aqui recorro la lista de ciudades para las opciones del selector
                        $ciudad_val = isset($_POST['ciudad']) ? $_POST['ciudad'] : $ciudad_defecto;
                        if (!empty($ciudad_val) && !in_array($ciudad_val, $ciudades_mexico)) {
                            echo '<option value="' . htmlspecialchars($ciudad_val) . '" selected>' . htmlspecialchars($ciudad_val) . '</option>';
                        }
                        foreach ($ciudades_mexico as $cd): 
                        ?>
                            <option value="<?php echo htmlspecialchars($cd); ?>" <?php echo ($ciudad_val == $cd) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cd); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Código Postal (5 dígitos):</label>
                    <input type="text" name="codigo_postal" value="<?php echo htmlspecialchars($codigo_postal_defecto); ?>" placeholder="Ej. 32000" minlength="5" maxlength="5" pattern="[0-9]{5}" title="Debe contener exactamente 5 dígitos numéricos" required>

                    <label>Método de Pago:</label>
                    <select name="metodo_pago" id="select_metodo_pago" onchange="alternarCamposTarjeta()" required>
                        <option value="Tarjeta">Tarjeta de Débito o Crédito (Visa / Mastercard)</option>
                        <option value="Efectivo en OXXO">Efectivo en Tiendas OXXO</option>
                        <option value="Transferencia">Transferencia Bancaria SPEI (BBVA)</option>
                    </select>

                    <!-- aqui pongo los campos para capturar o usar la tarjeta guardada -->
                    <div id="seccion_tarjeta" style="background: #fafafa; border: 1px solid #e0e0e0; border-left: 4px solid #b81414; padding: 14px; border-radius: 6px; margin: 14px 0px;">
                        <h4 style="margin-bottom: 8px; font-size: 14px; color: #111111;">Detalles de la Tarjeta</h4>

                        <label>Nombre del Titular:</label>
                        <input type="text" name="tarjeta_titular" id="tarjeta_titular" value="<?php echo htmlspecialchars($tarjeta_titular_defecto); ?>" placeholder="Como aparece en la tarjeta" minlength="3" maxlength="80" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.]{3,80}" title="Solo letras y espacios" data-tarjeta="1">

                        <label>Número de Tarjeta (16 dígitos):</label>
                        <input type="text" name="tarjeta_numero" id="tarjeta_numero" value="<?php echo htmlspecialchars(trim(chunk_split(str_replace(' ', '', $tarjeta_numero_defecto), 4, ' '))); ?>" placeholder="4152 3134 5678 9012" minlength="16" maxlength="19" pattern="[0-9\s]{16,19}" title="Debe contener exactamente 16 dígitos numéricos" data-tarjeta="1">

                        <div style="display: flex; gap: 10px;">
                            <div style="flex: 1;">
                                <label>Vencimiento (MM/AA):</label>
                                <input type="text" name="tarjeta_expiracion" id="tarjeta_expiracion" value="<?php echo htmlspecialchars($tarjeta_exp_defecto); ?>" placeholder="MM/AA" minlength="5" maxlength="5" pattern="(0[1-9]|1[0-2])\/[0-9]{2}" title="Formato MM/AA vigente (ejemplo 12/28)" data-tarjeta="1">
                            </div>
                            <div style="flex: 1;">
                                <label>CVV (3-4 dígitos):</label>
                                <input type="password" name="tarjeta_cvv" id="tarjeta_cvv" value="<?php echo htmlspecialchars($tarjeta_cvv_defecto); ?>" placeholder="123" minlength="3" maxlength="4" pattern="[0-9]{3,4}" title="3 o 4 dígitos de seguridad" data-tarjeta="1">
                            </div>
                        </div>

                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; margin-top: 10px; cursor: pointer; color: #333333;">
                            <input type="checkbox" name="guardar_tarjeta_perfil" value="1" checked style="width: auto; margin: 0;">
                            Guardar/actualizar esta tarjeta en Mi Perfil para futuras compras
                        </label>
                    </div>

                    <button type="submit" class="btn-comprar">Confirmar Pedido y Finalizar Compra</button>
                </form>
            </div>

            <!-- aqui pongo la columna con el resumen de los videojuegos a pagar -->
            <div class="columna-resumen">
                <div class="tarjeta-resumen">
                    <h3>Resumen de tu Pedido</h3>
                    <?php
                    // aqui calculo el subtotal de cada producto para la vista de compra
                    $total_checkout = 0;
                    foreach ($_SESSION["carrito"] as $item):
                        $sub = $item["precio"] * $item["cantidad"];
                        $total_checkout += $sub;
                    ?>
                        <div class="fila-resumen" style="align-items: center; border-bottom: 1px solid #eeeeee; padding-bottom: 8px;">
                            <img src="<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>" style="width: 40px; height: 48px; object-fit: contain; border-radius: 3px;">
                            <div style="flex: 1; margin-left: 10px;">
                                <div style="font-weight: bold; color: #111111; font-size: 13px;"><?php echo htmlspecialchars($item['nombre']); ?></div>
                                <div style="font-size: 11px; color: #777777;"><?php echo htmlspecialchars($item['categoria']); ?> &times; <?php echo $item['cantidad']; ?></div>
                            </div>
                            <div style="font-weight: bold; font-size: 13px;">$<?php echo number_format($sub, 2); ?></div>
                        </div>
                    <?php endforeach; ?>

                    <div class="fila-resumen" style="margin-top: 15px;">
                        <span>Envio a Domicilio:</span>
                        <span style="color: #2e7d32; font-weight: bold;">GRATIS</span>
                    </div>

                    <div class="fila-resumen total">
                        <span>Total a Pagar:</span>
                        <span>$<?php echo number_format($total_checkout, 2); ?> MXN</span>
                    </div>

                    <p style="text-align: center; margin-top: 14px; font-size: 12px; color: #777777;">
                        <a href="carrito.php" style="color: #b81414; font-weight: bold; text-decoration: none;">&larr; Modificar Carrito</a>
                    </p>
                </div>
            </div>
        </main>
    <?php endif; ?>

    <!-- aqui pongo el pie de pagina con mis datos de alumno -->
    <footer>
        <p>&copy;Rodriguez Ugalde Jaime Emmanuel 5°J 0893</p>
    </footer>
    <!-- aqui pongo el script para mostrar la tarjeta segun el metodo de pago y validar los campos -->
    <script>
    // aqui muestro u oculto los campos de tarjeta segun el metodo seleccionado
    function alternarCamposTarjeta() {
        var select = document.getElementById("select_metodo_pago");
        var seccion = document.getElementById("seccion_tarjeta");
        if (!select || !seccion) return;
        var campos = seccion.querySelectorAll("input[data-tarjeta='1']");
        if (select.value === "Tarjeta") {
            seccion.style.display = "block";
            campos.forEach(function(c) { c.required = true; });
        } else {
            seccion.style.display = "none";
            campos.forEach(function(c) { c.required = false; });
        }
    }

    // aqui agrego los eventos cuando carga la pagina
    document.addEventListener("DOMContentLoaded", function() {
        alternarCamposTarjeta();

        // aqui agrego la diagonal automaticamente a la fecha de vencimiento
        var expInput = document.getElementById('tarjeta_expiracion');
        if (expInput) {
            expInput.addEventListener('input', function(e) {
                var val = this.value.replace(/[^0-9]/g, '');
                if (val.length === 1 && parseInt(val, 10) > 1) {
                    this.value = '0' + val + '/';
                    return;
                }
                if (val.length >= 2) {
                    var mm = val.slice(0, 2);
                    var aa = val.slice(2, 4);
                    if (parseInt(mm, 10) > 12) mm = '12';
                    if (parseInt(mm, 10) === 0) mm = '01';
                    this.value = mm + '/' + aa;
                } else {
                    this.value = val;
                }
            });

            expInput.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value.endsWith('/')) {
                    e.preventDefault();
                    this.value = this.value.slice(0, -2);
                }
            });
        }

        // aqui separo el numero de tarjeta en bloques de 4 digitos
        var cardInput = document.getElementById('tarjeta_numero');
        if (cardInput) {
            cardInput.addEventListener('input', function(e) {
                var raw = this.value.replace(/[^0-9]/g, '').slice(0, 16);
                var parts = raw.match(/.{1,4}/g);
                this.value = parts ? parts.join(' ') : raw;
            });
        }

        // aqui permito unicamente numeros en el codigo de seguridad
        var cvvInput = document.getElementById('tarjeta_cvv');
        if (cvvInput) {
            cvvInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);
            });
        }

        // aqui permito unicamente 10 numeros en el telefono
        var telInput = document.querySelector('input[name="telefono"]');
        if (telInput) {
            telInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
            });
        }

        // aqui permito unicamente 5 numeros en el codigo postal
        var cpInput = document.querySelector('input[name="codigo_postal"]');
        if (cpInput) {
            cpInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
            });
        }
    });
    </script>
</body>

</html>
