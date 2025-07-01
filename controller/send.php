<?php
include("../connection/connection.php");

if (
    isset($_POST['nombres']) &&
    isset($_POST['apellidos']) &&               // aqui recibe todos los datos en metodo post 
    isset($_POST['identidad']) &&
    isset($_POST['telefono']) &&
    isset($_POST['direccion']) &&
    isset($_POST['fecha_nacimiento']) &&
    isset($_POST['valor'])
) {
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];    
    $identidad = $_POST['identidad'];
    $telefono = $_POST['telefono'];                    // una ves hayan llegado los datos los convierte en variable 
    $direccion = $_POST['direccion'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $estado = isset($_POST['estado']) ? 1 : 0;
    $valor = $_POST['valor'];

    $sql = "INSERT INTO registros(nombres, apellidos, identidad, telefono, direccion, fecha_nacimiento, estado, valor)
            VALUES('$nombres', '$apellidos', '$identidad', '$telefono', '$direccion', '$fecha_nacimiento', '$estado', '$valor')";   // aqui una ves los datos esten en variables los insertamos en cada columna  

    if ($conn->query($sql) === TRUE) { // si la conexion y la consulta sql son verdaderas por favor dejeme aqui en el formulario
        header("Location: ../index.php"); // Ajusta la ruta si es necesario
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<?php

