<?php
// Rodriguez Ugalde Jaime Emmanuel 5°J 0893
// aqui inicio la sesion para manejar los productos agregados
session_start();

// aqui llamo a mi archivo de conexion a la base de datos
include("conexion.php");

// aqui obtengo la ultima pagina visitada para que el usuario regrese a donde se habia quedado
$pagina_retorno = isset($_SESSION["ultima_pagina"]) ? $_SESSION["ultima_pagina"] : "index.php";

// aqui reviso si ya existe el arreglo del carrito en la sesion y si no lo creo
if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = array();
}

// aqui reviso que accion quiere realizar el usuario con los productos
if (isset($_GET["accion"])) {
    $accion = $_GET["accion"];
    $max_piezas_permitidas = 10;

    // aqui agrego un videojuego al carrito de compras
    if ($accion == "agregar" && isset($_GET["id"])) {
        $id = intval($_GET["id"]);
        if ($id > 0) {
            // aqui busco el producto en mi tabla para obtener sus datos reales
            $consulta = "SELECT * FROM productos WHERE id = $id";
            $resultado = mysqli_query($conexion, $consulta);
            if ($producto = mysqli_fetch_assoc($resultado)) {
                // si el producto ya esta en el carrito verifico el limite maximo
                if (isset($_SESSION["carrito"][$id])) {
                    if ($_SESSION["carrito"][$id]["cantidad"] < $max_piezas_permitidas) {
                        $_SESSION["carrito"][$id]["cantidad"] += 1;
                        $_SESSION["mensaje_agregado"] = "¡" . $producto["nombre"] . " agregado al carrito!";
                    } else {
                        $_SESSION["mensaje_agregado"] = "Límite alcanzado: máximo " . $max_piezas_permitidas . " piezas de " . $producto["nombre"] . " por pedido.";
                    }
                } else {
                    // si es nuevo lo guardo con sus datos completos
                    $_SESSION["carrito"][$id] = array(
                        "id" => $producto["id"],
                        "nombre" => $producto["nombre"],
                        "categoria" => $producto["categoria"],
                        "precio" => floatval($producto["precio"]),
                        "imagen" => $producto["imagen"],
                        "cantidad" => 1
                    );
                    $_SESSION["mensaje_agregado"] = "¡" . $producto["nombre"] . " agregado al carrito!";
                }
            }
        }

        // si nos mandaron una pagina de retorno regresamos a ella sin interrumpir al usuario
        if (isset($_GET["retorno"]) && !empty($_GET["retorno"])) {
            header("Location: " . $_GET["retorno"]);
            exit();
        }

        // si se agrego desde la misma pagina del carrito nos quedamos aqui
        header("Location: carrito.php");
        exit();
    }

    // aqui resto una unidad al videojuego seleccionado
    if ($accion == "restar" && isset($_GET["id"])) {
        $id = intval($_GET["id"]);
        if ($id > 0 && isset($_SESSION["carrito"][$id])) {
            $_SESSION["carrito"][$id]["cantidad"] -= 1;
            // si llega a cero lo borro del carrito
            if ($_SESSION["carrito"][$id]["cantidad"] <= 0) {
                unset($_SESSION["carrito"][$id]);
            }
        }
        header("Location: carrito.php");
        exit();
    }

    // aqui elimino completamente el producto del carrito
    if ($accion == "eliminar" && isset($_GET["id"])) {
        $id = intval($_GET["id"]);
        if ($id > 0 && isset($_SESSION["carrito"][$id])) {
            unset($_SESSION["carrito"][$id]);
        }
        header("Location: carrito.php");
        exit();
    }

    // aqui vacio todos los videojuegos guardados en el carrito
    if ($accion == "vaciar") {
        $_SESSION["carrito"] = array();
        header("Location: carrito.php");
        exit();
    }
}

// aqui calculo la cantidad total de piezas y el importe total a pagar
$total_piezas = 0;
$total_pagar = 0;
foreach ($_SESSION["carrito"] as $item) {
    $total_piezas += $item["cantidad"];
    $total_pagar += ($item["precio"] * $item["cantidad"]);
}
?>
<!DOCTYPE html>
<!--Rodriguez Ugalde Jaime Emmanuel 5°J 0893-->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras | Gamix</title>
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>">
</head>

<body>
    <!-- aqui pongo el encabezado con el titulo y el logo -->
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

        <!-- aqui pongo el enlace al carrito mostrando la cantidad de piezas -->
        <a href="carrito.php" class="nav-carrito">
            <img src="img/carrito.png" alt="carrito" class="icono-carrito" onerror="this.style.display='none'">
            Carrito (<?php echo $total_piezas; ?>)
        </a>

        <!-- aqui muestro el estado de sesion del usuario -->
        <?php if (isset($_SESSION["usuario"])): ?>
            <a href="cuenta.php" class="btn-cuenta">Mi Cuenta (<?php echo htmlspecialchars($_SESSION["usuario"]); ?>)</a>
            <a href="cerrar.php" class="btn-sesion">Cerrar Sesion</a>
        <?php else: ?>
            <a href="iniciar.php">Cuenta</a>
        <?php endif; ?>
    </nav>

    <!-- aqui pongo el contenido principal del carrito -->
    <main class="contenedor-carrito">
        <h2>Tu Carrito de Videojuegos</h2>

        <?php if (empty($_SESSION["carrito"])): ?>
            <!-- aqui muestro el mensaje si todavia no hay productos en el carrito -->
            <div class="carrito-vacio">
                <p>Tu carrito esta vacio en este momento. Navega por nuestras categorias y agrega los titulos que quieras comprar.</p>
                <a href="<?php echo htmlspecialchars($pagina_retorno); ?>" class="btn-secundario">&larr; Volver al Catalogo</a>
            </div>
        <?php else: ?>
            <!-- aqui pongo la tabla con todos los videojuegos agregados -->
            <table class="tabla-carrito">
                <thead>
                    <tr>
                        <th>Videojuego</th>
                        <th>Plataforma</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // aqui recorro cada juego guardado en la sesion del carrito
                    foreach ($_SESSION["carrito"] as $id => $item):
                        $subtotal_fila = $item["precio"] * $item["cantidad"];
                    ?>
                        <tr>
                            <td>
                                <div class="producto-info">
                                    <img src="<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>" class="miniatura-carrito">
                                    <strong><?php echo htmlspecialchars($item['nombre']); ?></strong>
                                </div>
                            </td>
                            <td>
                                <span class="etiqueta-plataforma"><?php echo htmlspecialchars($item['categoria']); ?></span>
                            </td>
                            <td>$<?php echo number_format($item['precio'], 2); ?> MXN</td>
                            <td>
                                <div class="control-cantidad">
                                    <a href="carrito.php?accion=restar&id=<?php echo $id; ?>" class="btn-cantidad" title="Restar uno">-</a>
                                    <span><strong><?php echo $item['cantidad']; ?></strong></span>
                                    <a href="carrito.php?accion=agregar&id=<?php echo $id; ?>" class="btn-cantidad" title="Sumar uno">+</a>
                                </div>
                            </td>
                            <td><strong>$<?php echo number_format($subtotal_fila, 2); ?> MXN</strong></td>
                            <td>
                                <a href="carrito.php?accion=eliminar&id=<?php echo $id; ?>" class="btn-eliminar" onclick="return confirm('¿Deseas quitar este juego del carrito?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- aqui pongo el bloque con las acciones y el resumen final de la compra -->
            <div class="bloque-resumen">
                <div class="acciones-carrito">
                    <a href="<?php echo htmlspecialchars($pagina_retorno); ?>" class="btn-secundario">&larr; Seguir Comprando</a>
                    <a href="carrito.php?accion=vaciar" class="btn-secundario" onclick="return confirm('¿Seguro que deseas vaciar todo el carrito?')">Vaciar Carrito</a>
                </div>

                <div class="tarjeta-resumen">
                    <h3>Resumen del Pedido</h3>
                    <div class="fila-resumen">
                        <span>Total de Articulos:</span>
                        <span><?php echo $total_piezas; ?> piezas</span>
                    </div>
                    <div class="fila-resumen">
                        <span>Envio a Domicilio:</span>
                        <span style="color: #2e7d32; font-weight: bold;">GRATIS (Express)</span>
                    </div>
                    <div class="fila-resumen total">
                        <span>Total a Pagar:</span>
                        <span>$<?php echo number_format($total_pagar, 2); ?> MXN</span>
                    </div>
                    <?php if (isset($_SESSION["usuario"])): ?>
                        <a href="comprar.php" class="btn-comprar">Proceder a la Compra</a>
                    <?php else: ?>
                        <a href="iniciar.php?mensaje=debes_iniciar" class="btn-comprar">Iniciar Sesión para Comprar</a>
                        <p style="text-align: center; margin-top: 8px; font-size: 12px; color: #b81414; font-weight: bold;">
                            * Debes iniciar sesión para realizar tu compra
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <!-- aqui pongo el pie de pagina con mis datos de alumno -->
    <footer>
        <p>&copy;Rodriguez Ugalde Jaime Emmanuel 5°J 0893</p>
    </footer>
</body>

</html>
