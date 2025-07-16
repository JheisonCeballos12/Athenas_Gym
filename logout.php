<?php
// Iniciar sesión para poder destruirla
session_start();

// Destruir todos los datos de sesión
session_destroy();

// Redirigir al formulario de login
header("Location: Login/login.php");
exit();
?>
