<?php
session_start();
include("../connection/connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM clientes WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<script>alert('Cliente eliminado'); window.location='../tables/table_cliente.php';</script>";
        } else {
            echo "Error al eliminar: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "<script>alert('ID inválido'); window.location='../tables/table_cliente.php';</script>";
    }
} else {
    echo "<script>alert('ID no recibido'); window.location='../tables/table_cliente.php';</script>";
}

$conn->close();
?>
