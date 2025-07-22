<?php
session_start();
include("../connection/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtener datos del formulario
    $usuario = trim($_POST['usuario']);
    $contrasena = trim($_POST['contrasena']);

    // Verificar que no estén vacíos
    if (empty($usuario) || empty($contrasena)) {
        header("Location: ../Login/login.php?toast=Debes completar ambos campos.&type=error");
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
        header("Location: ../Login/login.php?toast=Usuario o contraseña incorrectos.&type=error");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>




