<?php
session_start();
include("../connection/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtener datos del formulario
    $usuario = trim($_POST['usuario']);
    $contrasena = trim($_POST['contrasena']);

    // Verificar que no estén vacíos
    if (empty($usuario) || empty($contrasena)) {
        echo "<script>alert('Debes completar ambos campos.'); window.location='../Login/login.php';</script>";
        exit();
    }

    // Consulta correcta
    $sql = "SELECT id, usuarios FROM login WHERE usuarios = ? AND contraseñas = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $contrasena);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $_SESSION['usuario'] = $row['usuarios'];
        header("Location: ../index.php");
        exit();
    } else {
        echo "<script>alert('Usuario o contraseña incorrectos.'); window.location='../Login/login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>



