<?php
// Rodriguez Ugalde Jaime Emmanuel 5°J 0893
// aqui inicio la sesion para verificar que usuario esta conectado
session_start();

// aqui llamo a mi archivo de conexion
include("conexion.php");

// si no ha iniciado sesion lo mando al formulario de inicio de sesion
if (!isset($_SESSION["usuario"])) {
    header("Location: iniciar.php");
    exit();
}

$nombre_sesion = mysqli_real_escape_string($conexion, $_SESSION["usuario"]);
$correo_sesion = isset($_SESSION["correo"]) ? mysqli_real_escape_string($conexion, $_SESSION["correo"]) : "";
$user_id_sesion = isset($_SESSION["usuario_id"]) ? intval($_SESSION["usuario_id"]) : 0;
$mensaje_perfil = "";
$error_perfil = "";

// aqui busco los datos personales del usuario conectado por id, nombre o correo
if ($user_id_sesion > 0) {
    $sql_user = "SELECT * FROM usuarios WHERE id = $user_id_sesion LIMIT 1";
} else {
    $sql_user = "SELECT * FROM usuarios WHERE nombre = '$nombre_sesion' OR correo = '$nombre_sesion' OR correo = '$correo_sesion' LIMIT 1";
}
$res_user = mysqli_query($conexion, $sql_user);
$datos_usuario = mysqli_fetch_assoc($res_user);

// si por alguna razon ya no existe en la base de datos limpiamos sesion
if (!$datos_usuario) {
    header("Location: cerrar.php");
    exit();
}

$correo_usuario = mysqli_real_escape_string($conexion, $datos_usuario["correo"]);
$nombre_usuario = mysqli_real_escape_string($conexion, $datos_usuario["nombre"]);

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

// aqui permito al usuario actualizar sus datos de envio y tarjeta para guardarlos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actualizar_datos"])) {
    $nuevo_telefono = mysqli_real_escape_string($conexion, trim($_POST["telefono"]));
    $nueva_direccion = mysqli_real_escape_string($conexion, trim($_POST["direccion"]));
    $nueva_ciudad = mysqli_real_escape_string($conexion, trim($_POST["ciudad"]));
    $nuevo_cp = mysqli_real_escape_string($conexion, trim($_POST["codigo_postal"]));
    $nuevo_titular = mysqli_real_escape_string($conexion, trim($_POST["tarjeta_titular"]));
    // aqui quito los espacios del numero de tarjeta para guardarlo bien
    $nueva_tarjeta = mysqli_real_escape_string($conexion, str_replace(' ', '', trim($_POST["tarjeta_numero"])));
    $nueva_exp = mysqli_real_escape_string($conexion, trim($_POST["tarjeta_expiracion"]));
    $nuevo_cvv = mysqli_real_escape_string($conexion, trim($_POST["tarjeta_cvv"]));

    // aqui reviso que los datos enviados sean correctos
    $errores = array();

    // aqui reviso que el telefono tenga 10 digitos
    if (!preg_match('/^[0-9]{10}$/', $nuevo_telefono)) {
        $errores[] = "El teléfono debe contener exactamente 10 dígitos numéricos.";
    } elseif (preg_match('/^([0-9])\1{9}$/', $nuevo_telefono)) {
        $errores[] = "El número de teléfono ingresado no es válido.";
    }

    // aqui reviso que la direccion no este vacia
    if (mb_strlen($nueva_direccion, 'UTF-8') < 6 || mb_strlen($nueva_direccion, 'UTF-8') > 150) {
        $errores[] = "La dirección de envío debe tener entre 6 y 150 caracteres.";
    }

    // aqui reviso la ciudad seleccionada
    if (mb_strlen($nueva_ciudad, 'UTF-8') < 3 || mb_strlen($nueva_ciudad, 'UTF-8') > 60) {
        $errores[] = "La ciudad y estado deben tener entre 3 y 60 caracteres.";
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.,]+$/u', $nueva_ciudad)) {
        $errores[] = "La ciudad y estado solo pueden contener letras, acentos y espacios.";
    }

    // aqui reviso que el codigo postal tenga 5 digitos
    if (!preg_match('/^[0-9]{5}$/', $nuevo_cp)) {
        $errores[] = "El código postal debe contener exactamente 5 dígitos numéricos.";
    } elseif (intval($nuevo_cp) < 1000 || intval($nuevo_cp) > 99999) {
        $errores[] = "El código postal ingresado no es válido para México.";
    }

    // aqui reviso los datos de la tarjeta bancaria
    if (!empty($nueva_tarjeta) || !empty($nuevo_titular) || !empty($nueva_exp) || !empty($nuevo_cvv)) {
        // aqui reviso el nombre del titular de la tarjeta
        if (mb_strlen($nuevo_titular, 'UTF-8') < 3 || mb_strlen($nuevo_titular, 'UTF-8') > 80) {
            $errores[] = "El nombre del titular de la tarjeta debe tener entre 3 y 80 caracteres.";
        } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.]+$/u', $nuevo_titular)) {
            $errores[] = "El titular de la tarjeta solo puede contener letras y espacios.";
        }

        // aqui reviso que el numero de tarjeta tenga 16 digitos
        if (!preg_match('/^[0-9]{16}$/', $nueva_tarjeta)) {
            $errores[] = "El número de tarjeta debe contener exactamente 16 dígitos numéricos.";
        } elseif (preg_match('/^([0-9])\1{15}$/', $nueva_tarjeta)) {
            $errores[] = "El número de tarjeta ingresado no es válido.";
        }

        // aqui reviso que la tarjeta no este vencida
        if (preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $nueva_exp, $m_exp)) {
            $exp_mes = intval($m_exp[1]);
            $exp_anio = intval("20" . $m_exp[2]);
            $mes_hoy = intval(date('n'));
            $anio_hoy = intval(date('Y'));
            if ($exp_anio < $anio_hoy || ($exp_anio === $anio_hoy && $exp_mes < $mes_hoy)) {
                $errores[] = "La tarjeta ingresada no puede estar vencida (fecha: $nueva_exp).";
            } elseif ($exp_anio > $anio_hoy + 15) {
                $errores[] = "La fecha de vencimiento ingresada es demasiado lejana ($nueva_exp).";
            }
        } else {
            $errores[] = "La fecha de vencimiento debe tener formato MM/AA (ejemplo 12/28).";
        }

        // aqui reviso el codigo de seguridad cvv
        if (!preg_match('/^[0-9]{3,4}$/', $nuevo_cvv) || $nuevo_cvv === '000' || $nuevo_cvv === '0000') {
            $errores[] = "El código CVV debe contener 3 o 4 dígitos válidos.";
        }
    }

    if (empty($errores)) {
        $sql_actualizar = "UPDATE usuarios SET 
            telefono = '$nuevo_telefono', 
            direccion = '$nueva_direccion', 
            ciudad = '$nueva_ciudad', 
            codigo_postal = '$nuevo_cp',
            tarjeta_titular = '$nuevo_titular',
            tarjeta_numero = '$nueva_tarjeta',
            tarjeta_expiracion = '$nueva_exp',
            tarjeta_cvv = '$nuevo_cvv' 
            WHERE id = " . intval($datos_usuario["id"]);

        if (mysqli_query($conexion, $sql_actualizar)) {
            $mensaje_perfil = "Tus datos de envío y método de pago se guardaron correctamente.";
            // aqui vuelvo a pedir los datos a la base de datos para que se actualice la pantalla
            $res_user = mysqli_query($conexion, $sql_user);
            $datos_usuario = mysqli_fetch_assoc($res_user);
        } else {
            $error_perfil = "Error al guardar en la base de datos.";
        }
    } else {
        $error_perfil = implode("<br>", $errores);
    }
}

// aqui consulto todas las compras que ha realizado este usuario especifico
$sql_compras = "SELECT * FROM compras WHERE usuario = '$nombre_usuario' OR correo = '$correo_usuario' ORDER BY fecha DESC";
$res_compras = mysqli_query($conexion, $sql_compras);

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
    <title>Mi Cuenta y Mis Compras | Gamix</title>
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>">
    <!-- aqui pongo los estilos para el selector de ciudades de mi perfil -->
    <style>
        .columna-perfil select {
            display: block !important;
            width: 100% !important;
            height: 36px !important;
            padding: 0px 30px 0px 10px !important;
            margin-top: 5px !important;
            background-color: #fafafa !important;
            border: 1px solid #cccccc !important;
            color: #111111 !important;
            border-radius: 4px !important;
            font-size: 13px !important;
            font-family: Arial, sans-serif !important;
            box-sizing: border-box !important;
            cursor: pointer !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2212%22%20height%3D%2212%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%23555555%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            background-size: 12px !important;
        }
        .columna-perfil select:focus {
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

        <!-- aqui pongo el enlace a mi cuenta y el boton de cerrar sesion -->
        <a href="cuenta.php" class="btn-cuenta">Mi Cuenta</a>
        <a href="cerrar.php" class="btn-sesion">Cerrar Sesion</a>
    </nav>

    <!-- aqui pongo el contenedor con la informacion de la cuenta y sus compras -->
    <main class="contenedor-cuenta">
        <!-- aqui pongo la columna izquierda con el perfil y direccion guardada -->
        <div class="columna-perfil">
            <div class="tarjeta-perfil">
                <h2>Mi Perfil</h2>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($datos_usuario["nombre"]); ?></p>
                <p><strong>Correo:</strong> <?php echo htmlspecialchars($datos_usuario["correo"]); ?></p>
                <p><strong>Miembro desde:</strong> <?php echo date("d/m/Y", strtotime($datos_usuario["fecha_registro"])); ?></p>

                <h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 16px; color: #111111; border-top: 1px solid #eeeeee; padding-top: 15px;">Dirección de Envío Guardada</h3>
                <!-- aqui muestro el aviso si los datos de la cuenta se actualizaron o hubo un error -->
                <?php if (!empty($mensaje_perfil)): ?>
                    <p class="alerta" style="margin-bottom: 12px; font-size: 13px;"><?php echo $mensaje_perfil; ?></p>
                <?php endif; ?>
                <?php if (!empty($error_perfil)): ?>
                    <p class="alerta error" style="margin-bottom: 12px; font-size: 13px;"><?php echo $error_perfil; ?></p>
                <?php endif; ?>
                <form action="cuenta.php" method="POST">
                    <input type="hidden" name="actualizar_datos" value="1">

                    <label>Teléfono de Contacto (10 dígitos):</label>
                    <input type="tel" name="telefono" value="<?php echo htmlspecialchars($datos_usuario['telefono'] ?? ''); ?>" minlength="10" maxlength="10" pattern="[0-9]{10}" placeholder="Ej. 6561234567" title="Debe contener exactamente 10 dígitos numéricos" required>

                    <label>Calle y Número (Mínimo 6 caracteres):</label>
                    <input type="text" name="direccion" value="<?php echo htmlspecialchars($datos_usuario['direccion'] ?? ''); ?>" placeholder="Calle, número y colonia" minlength="6" maxlength="150" required>

                    <label>Ciudad y Estado:</label>
                    <select name="ciudad" required>
                        <option value="" disabled>-- Selecciona tu Ciudad y Estado --</option>
                        <?php 
                        // aqui recorro la lista de ciudades para llenar las opciones del selector
                        $ciudad_actual = $datos_usuario["ciudad"] ?? "";
                        if (!empty($ciudad_actual) && !in_array($ciudad_actual, $ciudades_mexico)) {
                            echo '<option value="' . htmlspecialchars($ciudad_actual) . '" selected>' . htmlspecialchars($ciudad_actual) . '</option>';
                        }
                        foreach ($ciudades_mexico as $cd): 
                        ?>
                            <option value="<?php echo htmlspecialchars($cd); ?>" <?php echo ($ciudad_actual == $cd) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cd); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Código Postal (5 dígitos):</label>
                    <input type="text" name="codigo_postal" value="<?php echo htmlspecialchars($datos_usuario['codigo_postal'] ?? ''); ?>" minlength="5" maxlength="5" pattern="[0-9]{5}" placeholder="Ej. 32000" title="Debe contener exactamente 5 dígitos numéricos" required>

                    <h3 style="margin-top: 20px; margin-bottom: 8px; font-size: 16px; color: #111111; border-top: 1px solid #eeeeee; padding-top: 15px;">Método de Pago Guardado (Tarjeta)</h3>
                    <p style="font-size: 12px; color: #777777; margin-bottom: 10px;">Registra tu tarjeta de débito o crédito para agilizar tus compras:</p>

                    <label>Nombre del Titular de la Tarjeta:</label>
                    <input type="text" name="tarjeta_titular" value="<?php echo htmlspecialchars($datos_usuario['tarjeta_titular'] ?? ''); ?>" placeholder="Como aparece en la tarjeta" minlength="3" maxlength="80" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.]{3,80}" title="Solo letras y espacios">

                    <label>Número de Tarjeta (16 dígitos):</label>
                    <input type="text" name="tarjeta_numero" value="<?php echo htmlspecialchars(trim(chunk_split(str_replace(' ', '', $datos_usuario['tarjeta_numero'] ?? ''), 4, ' '))); ?>" minlength="16" maxlength="19" pattern="[0-9\s]{16,19}" placeholder="4152 3134 5678 9012" title="Debe contener exactamente 16 dígitos numéricos">

                    <div style="display: flex; gap: 10px;">
                        <div style="flex: 1;">
                            <label>Vencimiento (MM/AA):</label>
                            <input type="text" name="tarjeta_expiracion" value="<?php echo htmlspecialchars($datos_usuario['tarjeta_expiracion'] ?? ''); ?>" minlength="5" maxlength="5" pattern="(0[1-9]|1[0-2])\/[0-9]{2}" placeholder="MM/AA" title="Formato MM/AA vigente (ejemplo 12/28)">
                        </div>
                        <div style="flex: 1;">
                            <label>CVV (3-4 dígitos):</label>
                            <input type="password" name="tarjeta_cvv" value="<?php echo htmlspecialchars($datos_usuario['tarjeta_cvv'] ?? ''); ?>" minlength="3" maxlength="4" pattern="[0-9]{3,4}" placeholder="123" title="3 o 4 dígitos de seguridad">
                        </div>
                    </div>

                    <button type="submit" class="btn-comprar" style="margin-top: 15px; padding: 10px 0px;">Guardar Datos de Envío y Tarjeta</button>
                </form>
            </div>
        </div>

        <!-- aqui pongo la columna derecha con el historial de compras de esa cuenta -->
        <div class="columna-historial">
            <h2>Historial de Mis Compras</h2>

            <!-- aqui reviso si el usuario tiene compras registradas en la base de datos -->
            <?php if (mysqli_num_rows($res_compras) == 0): ?>
                <div class="carrito-vacio">
                    <p>Todavia no has realizado compras con esta cuenta.</p>
                    <a href="index.php" class="btn-secundario">Explorar Videojuegos</a>
                </div>
            <?php else: ?>
                <p style="margin-bottom: 15px; color: #666666; font-size: 14px;">Aqui puedes consultar todos los pedidos que has realizado en Gamix:</p>
                <table class="tabla-carrito">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Videojuego</th>
                            <th>Plataforma</th>
                            <th>Cantidad</th>
                            <th>Total Pagado</th>
                            <th>Metodo de Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- aqui recorro cada compra registrada para mostrarla en la tabla -->
                        <?php while ($compra = mysqli_fetch_assoc($res_compras)): ?>
                            <tr>
                                <td><span class="folio-badge">#<?php echo htmlspecialchars($compra["folio"]); ?></span></td>
                                <td><?php echo date("d/m/Y H:i", strtotime($compra["fecha"])); ?></td>
                                <td><strong><?php echo htmlspecialchars($compra["juego"]); ?></strong></td>
                                <td><span class="etiqueta-plataforma"><?php echo htmlspecialchars($compra["plataforma"]); ?></span></td>
                                <td style="text-align: center;"><?php echo $compra["cantidad"]; ?></td>
                                <td><strong style="color: #b81414;">$<?php echo number_format($compra["subtotal"] > 0 ? $compra["subtotal"] : $compra["precio"], 2); ?> MXN</strong></td>
                                <td><?php echo htmlspecialchars($compra["metodo_pago"]); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <!-- aqui pongo el pie de pagina con mis datos de alumno -->
    <footer>
        <p>&copy;Rodriguez Ugalde Jaime Emmanuel 5°J 0893</p>
    </footer>

    <!-- aqui pongo el script para validar los campos y dar formato a la tarjeta -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.querySelector('form');
            if (!form) return;

            // aqui coloco la diagonal en la fecha de expiracion
            var expInput = form.querySelector('input[name="tarjeta_expiracion"]');
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
            var cardInput = form.querySelector('input[name="tarjeta_numero"]');
            if (cardInput) {
                cardInput.addEventListener('input', function(e) {
                    var raw = this.value.replace(/[^0-9]/g, '').slice(0, 16);
                    var parts = raw.match(/.{1,4}/g);
                    this.value = parts ? parts.join(' ') : raw;
                });
            }

            // aqui permito unicamente numeros en el codigo de seguridad
            var cvvInput = form.querySelector('input[name="tarjeta_cvv"]');
            if (cvvInput) {
                cvvInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);
                });
            }

            // aqui permito unicamente 10 numeros en el telefono
            var telInput = form.querySelector('input[name="telefono"]');
            if (telInput) {
                telInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
                });
            }

            // aqui permito unicamente 5 numeros en el codigo postal
            var cpInput = form.querySelector('input[name="codigo_postal"]');
            if (cpInput) {
                cpInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
                });
            }
        });
    </script>
</body>

</html>