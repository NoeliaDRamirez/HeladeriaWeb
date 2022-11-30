<?php
session_start();

// Me aseguro de tomar todas las variables de sesion a un array.
$_SESSION = array();

// Al eliminar la sesión, elimimos también las cookie de sesión.
// Ojo: ¡Esto destruirá la sesión y no solo los datos de la sesión!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Antes de salir elimino las variables de sesion para que no vuelva a ingresar
session_destroy();
header("Location: index.php");
exit;
?>