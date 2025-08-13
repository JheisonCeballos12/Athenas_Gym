<?php
session_start();
include("../connection/connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombres      = trim($_POST['nombres'] ?? "");
    $usuario      = trim($_POST['usuario'] ?? "");
    $contrasena   = trim($_POST['contrasena'] ?? "");
    $pin_seguridad = trim($_POST['pin_seguridad'] ?? "");

    if ($nombres === "" || $usuario === "" || $contrasena === "" || $pin_seguridad === "") {
        die("Todos los campos son obligatorios.");
    }

    // Verificar que el usuario no exista
    $sql = "SELECT id FROM login WHERE usuarios = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("El usuario ya existe.");
    }

    // Hashear la contraseña
    $hash_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

    // Insertar en la base de datos
    $sql_insert = "INSERT INTO login (nombres, usuarios, contrasenas, pin_seguridad) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssss", $nombres, $usuario, $hash_contrasena, $pin_seguridad);

    if ($stmt_insert->execute()) {
        header("Location: ../views/dashboard.php");
    } else {
        echo "Error al registrar usuario: " . $stmt_insert->error;
    }

    $stmt_insert->close();
    $stmt->close();
    $conn->close();
}
?>
