<?php
include("../connection/connection.php");

if (isset($_POST['actualizar'])) {
    $id = intval($_POST['id']);
    $nombres = $conn->real_escape_string($_POST['nombres']);
    $apellidos = $conn->real_escape_string($_POST['apellidos']);
    $identidad = $conn->real_escape_string($_POST['identidad']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $direccion = $conn->real_escape_string($_POST['direccion']);
    $fecha_nacimiento = $conn->real_escape_string($_POST['fecha_nacimiento']);
    $estado = isset($_POST['estado']) ? 1 : 0;

    $sql = "UPDATE clientes SET 
        nombres='$nombres',
        apellidos='$apellidos',
        identidad='$identidad',
        telefono='$telefono',
        direccion='$direccion',
        fecha_nacimiento='$fecha_nacimiento',
        estado=$estado
        WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../tables/table_cliente.php?toast=" . urlencode("✅ Cliente actualizado correctamente") . "&type=success");

        exit();
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
} else {
    echo "Error: No se recibieron datos válidos.";
}
?>

