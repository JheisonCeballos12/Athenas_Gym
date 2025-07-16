<?php
session_start(); // MUY IMPORTANTE

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
            header("Location: ../tables/table_plan.php?msg=Plan actualizado exitosamente");
            exit();
        } else {
            echo "Error al actualizar el plan: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Todos los campos son requeridos.";
    }
} else {
    header("Location: ../tables/table_plan.php");
    exit();
}

$conn->close();

