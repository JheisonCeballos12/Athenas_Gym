<?php
// controllers/cumpleaneros.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../connection/connection.php");

// Solo generar lista de cumpleaños una vez por sesión
if (!isset($_SESSION['cumple_felicitado'])) {
    $_SESSION['cumple_felicitado'] = true;

    $cumpleaneros = [];
    $hoy = date('m-d'); // Comparar solo mes y día

    $sql = "SELECT nombres, apellidos, fecha_nacimiento FROM clientes";
    $resultado = $conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $fechaNac = date('m-d', strtotime($row['fecha_nacimiento']));
            if ($fechaNac === $hoy) {
                $cumpleaneros[] = $row['nombres'] . ' ' . $row['apellidos'];
            }
        }
    }

    // Devolver JSON con los cumpleaños
    header('Content-Type: application/json');
    echo json_encode($cumpleaneros);

} else {
    // Si ya se mostró, devolver array vacío
    header('Content-Type: application/json');
    echo json_encode([]);
}
