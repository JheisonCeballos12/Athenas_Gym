<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login/login.php");
    exit();
}

include("../connection/connection.php");

// FUNCIÓN EDITAR: cargar datos si viene edit_id por GET-------------------
$plan = null;
if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $id = intval($_GET['edit_id']);
    $sql_edit = "SELECT * FROM planes WHERE id = $id";
    $result_edit = $conn->query($sql_edit);
    $plan = $result_edit->fetch_assoc();
}

// FUNCIÓN SELECT: traer todos los planes-----------------------------
$sql = "SELECT * FROM planes";
$resultado = $conn->query($sql);
?>
