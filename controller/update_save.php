<?php
include("../connection/connection.php");

if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $identidad = $_POST['identidad'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $estado = isset($_POST['estado']) ? 1 : 0;
    $valor = $_POST['valor'];

    $sql = "UPDATE registros SET 
        nombres='$nombres',
        apellidos='$apellidos',
        identidad='$identidad',
        telefono='$telefono',
        direccion='$direccion',
        fecha_nacimiento='$fecha_nacimiento',
        estado=$estado,
        valor=$valor
        WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Datos actualizados correctamente'); window.location='../index.php';</script>"; // cambiar este alert por un modal de notificaciones
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}
?>

