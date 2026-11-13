<?php
// Rodriguez Ugalde Jaime Emmanuel 5°J 0893
// aqui abro la sesion activa
session_start();

// aqui limpio y destruyo completamente los datos de la sesion
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// aqui mando al usuario de vuelta a la tienda
header("Location: index.php");
exit();
?>
