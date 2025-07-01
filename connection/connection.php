<?php
$server = "localhost";
$username = "root";
$password = "";
$bdname = "clientes";

$conn = new mysqli($server, $username, $password, $bdname); 

if($conn->connect_error){
    echo "error de conexion" . $conn->connect_error;
}
?>


