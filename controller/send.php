<?php
include("../connection/connection.php");

if (
    isset($_POST['nombres']) &&
    isset($_POST['apellidos']) &&               
    isset($_POST['identidad']) &&
    isset($_POST['telefono']) &&
    isset($_POST['direccion']) &&
    isset($_POST['fecha_nacimiento'])
) {
    $nombres = $conn->real_escape_string($_POST['nombres']);
    $apellidos = $conn->real_escape_string($_POST['apellidos']);    
    $identidad = $conn->real_escape_string($_POST['identidad']);
    $telefono = $conn->real_escape_string($_POST['telefono']);                    
    $direccion = $conn->real_escape_string($_POST['direccion']);
    $fecha_nacimiento = $conn->real_escape_string($_POST['fecha_nacimiento']);
    $estado = isset($_POST['estado']) ? 1 : 0;

    $fecha_registro = date('Y-m-d');

    // Al registrar un cliente nuevo NO hay plan todavía ni vencimiento
    $sql = "INSERT INTO clientes 
        (nombres, apellidos, identidad, telefono, direccion, fecha_nacimiento, estado, fecha_registro)
        VALUES('$nombres', '$apellidos', '$identidad', '$telefono', '$direccion', '$fecha_nacimiento', $estado, '$fecha_registro')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../tables/table_cliente.php"); 
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Error: Faltan datos obligatorios.";
}

