<?php
session_start();
include("../connection/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $valor = floatval($_POST['valor']);
    $meses = intval($_POST['meses']);

    $sql = "INSERT INTO planes (nombre, valor, meses) VALUES ('$nombre', $valor, $meses)";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/table_plan.php?toast=" . urlencode("✅ Plan creado exitosamente"));
        exit();
    } else {
        header("Location: ../views/table_plan.php?toast=" . urlencode("❌ Error al crear el plan"));
        exit();
    }
} else {
    header("Location: ../views/table_plan.php");
    exit();
}
?>



