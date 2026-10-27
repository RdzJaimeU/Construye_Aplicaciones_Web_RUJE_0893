<?php
// aqui abro la sesion activa
session_start();

// aqui borro todos los datos de la sesion
session_destroy();

// aqui mando al usuario de vuelta a la tienda
header("Location: index.php");
exit();
?>
