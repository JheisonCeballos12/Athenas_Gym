<?php
session_start();

include("../connection/connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['actualizar'])) {
    $id = intval($_POST['id']);
    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    $valor = floatval($_POST['valor']);
    $meses = intval($_POST['meses']);

    if ($id && $nombre && $valor && $meses) {
        $sql = "UPDATE planes SET nombre = ?, valor = ?, meses = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdii", $nombre, $valor, $meses, $id);

        if ($stmt->execute()) {
            header("Location: ../tables/table_plan.php?toast=" . urlencode("✅ Plan actualizado exitosamente"));
            exit();
        } else {
            header("Location: ../tables/table_plan.php?toast=" . urlencode("❌ Error al actualizar el plan"));
            exit();
        }

        $stmt->close();
    } else {
        header("Location: ../tables/table_plan.php?toast=" . urlencode("⚠️ Todos los campos son requeridos"));
        exit();
    }
} else {
    header("Location: ../tables/table_plan.php");
    exit();
}

$conn->close();


