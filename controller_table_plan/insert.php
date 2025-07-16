<?php
session_start();

include("../connection/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $valor = floatval($_POST['valor']);
    $meses = intval($_POST['meses']);

    $sql = "INSERT INTO planes (nombre, valor, meses) VALUES ('$nombre', $valor, $meses)";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../tables/table_plan.php?msg=Plan creado exitosamente");
        exit();
    } else {
        echo "Error al crear el plan: " . $conn->error;
    }
} else {
    header("Location: ../tables/table_plan.php");
    exit();
}
?>


