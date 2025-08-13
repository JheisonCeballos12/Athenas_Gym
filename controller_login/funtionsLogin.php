<?php
session_start();
include("../connection/connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario    = trim($_POST['usuario'] ?? "");
    $contrasena = trim($_POST['contrasena'] ?? "");

    if ($usuario === "" || $contrasena === "") {
        header("Location: ../Login/login.php?toast=Debes completar ambos campos.&type=error");
        exit();
    }

    // Traer hash de la base de datos
    $sql = "SELECT id, usuarios, contrasenas FROM login WHERE usuarios = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($contrasena, $row['contrasenas'])) {
            session_regenerate_id(true);
            $_SESSION['usuario'] = $row['usuarios'];
            header("Location: ../views/dashboard.php");
            exit();
        }
    }

    header("Location: ../Login/login.php?toast=Usuario o contraseña incorrectos.&type=error");
    exit();
}





