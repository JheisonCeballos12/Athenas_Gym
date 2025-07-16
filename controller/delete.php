<?php
session_start();
include("../connection/connection.php");

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql_get = "SELECT estado FROM clientes WHERE id = $id";
    $result = $conn->query($sql_get);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nuevo_estado = $row['estado'] ? 0 : 1;

        $sql_update = "UPDATE clientes SET estado = $nuevo_estado WHERE id = $id";
        if ($conn->query($sql_update) === TRUE) {
            header("Location: ../tables/table_cliente.php?msg=estado_actualizado");
            exit();
        } else {
            echo "Error al actualizar estado: " . $conn->error;
        }
    } else {
        echo "Cliente no encontrado.";
    }
} else {
    echo "ID no recibido.";
}
?>


