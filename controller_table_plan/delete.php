<?php
session_start();
include("../connection/connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM clientes WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: ../tables/table_cliente.php?toast=" . urlencode("✅ Cliente eliminado"));
            exit();
        } else {
            header("Location: ../tables/table_cliente.php?toast=" . urlencode("❌ Error al eliminar el cliente"));
            exit();
        }

        $stmt->close();
    } else {
        header("Location: ../tables/table_cliente.php?toast=" . urlencode("⚠️ ID inválido"));
        exit();
    }
} else {
    header("Location: ../tables/table_cliente.php?toast=" . urlencode("⚠️ ID no recibido"));
    exit();
}

$conn->close();

