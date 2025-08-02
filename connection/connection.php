<?php
date_default_timezone_set('America/Bogota');

$server = "localhost";
$username = "root";
$password = "";
$bdname = "gym";

// Crear conexión
$conn = new mysqli($server, $username, $password, $bdname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Puedes eliminar este echo si no quieres mostrar nada al conectarte correctamente
// echo "Conexión exitosa";
?>



