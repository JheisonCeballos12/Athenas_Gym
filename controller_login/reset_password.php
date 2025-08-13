<?php
session_start();
include("../connection/connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario      = trim($_POST['usuario'] ?? "");
    $pin_seguridad = trim($_POST['pin_seguridad'] ?? "");
    $contrasena    = trim($_POST['contrasena'] ?? "");
    $contrasena2   = trim($_POST['contrasena2'] ?? "");

    if ($usuario === "" || $pin_seguridad === "" || $contrasena === "" || $contrasena2 === "") {
        die("Todos los campos son obligatorios.");
    }

    if ($contrasena !== $contrasena2) {
        die("Las contraseñas no coinciden.");
    }

    // Verificar usuario y PIN
    $sql = "SELECT id FROM login WHERE usuarios = ? AND pin_seguridad = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $pin_seguridad);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Actualizar contraseña
        $hash_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql_update = "UPDATE login SET contrasenas = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $hash_contrasena, $row['id']);

        if ($stmt_update->execute()) {
            header("Location: ../Login/login.php");
        } else {
            echo "Error al actualizar la contraseña: " . $stmt_update->error;
        }

        $stmt_update->close();
    } else {
        die("Usuario o PIN incorrectos.");
    }

    $stmt->close();
    $conn->close();
}
?>
