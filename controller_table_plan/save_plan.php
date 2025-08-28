<?php
session_start();
require_once("../connection/connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre']);
    $valor  = floatval($_POST['valor']);
    $meses  = intval($_POST['meses']);

    // 🔹 Si viene un ID, actualiza
    if (!empty($_POST['id'])) {
        $id = intval($_POST['id']);
        $sql = "UPDATE planes SET nombre=?, valor=?, meses=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdii", $nombre, $valor, $meses, $id);

        if ($stmt->execute()) {
            header("Location: ../views/table_plan.php?toast=" . urlencode("✅ Plan actualizado correctamente"));
            exit();
        } else {
            die("❌ Error al actualizar: " . $stmt->error);
        }

    // 🔹 Si no viene ID, inserta
    } else {
        $sql = "INSERT INTO planes (nombre, valor, meses) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdi", $nombre, $valor, $meses);

        if ($stmt->execute()) {
            header("Location: ../views/table_plan.php?toast=" . urlencode("✅ Plan creado correctamente"));
            exit();
        } else {
            die("❌ Error al insertar: " . $stmt->error);
        }
    }
}
?>
