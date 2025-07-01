<?php
include("../connection/connection.php");

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM registros WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Cliente eliminado'); window.location='../index.php';</script>";
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
} else {
    echo "<script>alert('ID no recibido'); window.location='../index.php';</script>";
}
?>

