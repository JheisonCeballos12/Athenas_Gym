<?php
include("../connection/connection.php");

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // id de la inscripción

    // 1️⃣ Marcar inscripción como ANULADA
    $stmt = $conn->prepare("UPDATE inscripciones SET estado='ANULADO' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // 2️⃣ Obtener datos anteriores de esa inscripción
    $stmt2 = $conn->prepare("SELECT cliente_id, mensualidad_anterior, fecha_vencimiento_anterior, meses_plan_anterior, valor_pagado_anterior 
                             FROM inscripciones WHERE id=?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $result = $stmt2->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        $cliente_id = $data['cliente_id'];

        // 3️⃣ Restaurar datos del cliente
        $stmt3 = $conn->prepare("UPDATE clientes SET 
            mensualidad=?, 
            fecha_vencimiento=?, 
            meses_del_plan=?, 
            valor_pagado=? 
            WHERE id=?");
        $stmt3->bind_param(
            "ssiii",
            $data['mensualidad_anterior'],
            $data['fecha_vencimiento_anterior'],
            $data['meses_plan_anterior'],
            $data['valor_pagado_anterior'],
            $cliente_id
        );
        $stmt3->execute();
    }

    header("Location: ../views/table_clients.php?toast=Inscripción+cancelada+y+cliente+restaurado&type=success");
    exit();
}
?>
