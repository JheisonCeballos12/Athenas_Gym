<?php
include("../connection/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cliente_id']) && isset($_POST['plan_id'])) {
        
        $cliente_id = intval($_POST['cliente_id']);
        $plan_id = intval($_POST['plan_id']);

        //------------------------------------------------------------------------------------------------

        // 1️⃣ Obtener precio y duración del plan
        $sql_plan = "SELECT valor, meses FROM planes WHERE id = $plan_id";
        $result_plan = $conn->query($sql_plan);

        if ($result_plan && $result_plan->num_rows > 0) {
            $row_plan = $result_plan->fetch_assoc();

            $valor_plan = isset($row_plan['valor']) && is_numeric($row_plan['valor']) ? intval($row_plan['valor']) : 0;
            $duracion_meses = isset($row_plan['meses']) && is_numeric($row_plan['meses']) ? intval($row_plan['meses']) : 0;

            if ($valor_plan <= 0 || $duracion_meses <= 0) {
                header("Location: ../views/table_clients.php?toast=plan_invalido&type=error");
                exit();
            }

            //------------------------------------------------------------------------------------------------

            // 2️⃣ Insertar inscripción
            $sql_insert_venta = sprintf(
                "INSERT INTO inscripciones (cliente_id, plan_id, valor) VALUES (%d, %d, %d)",
                $cliente_id, $plan_id, $valor_plan
            );

            if (!$conn->query($sql_insert_venta)) {
                header("Location: ../views/table_clients.php?toast=error_venta&type=error");
                exit();
            }

            //------------------------------------------------------------------------------------------------

            // 3️⃣ Calcular nueva fecha de vencimiento
            $fecha_hoy = date('Y-m-d');
            $nueva_fecha_vencimiento = date('Y-m-d', strtotime("+$duracion_meses months", strtotime($fecha_hoy)));
            
            //------------------------------------------------------------------------------------------------

            // 4️⃣ Actualizar cliente
            $sql_update_cliente = sprintf(
                "UPDATE clientes SET fecha_vencimiento='%s', mensualidad='VIGENTE', meses_del_plan=%d, valor_pagado=%d WHERE id=%d",
                $conn->real_escape_string($nueva_fecha_vencimiento),
                $duracion_meses,
                $valor_plan,
                $cliente_id
            );

            if (!$conn->query($sql_update_cliente)) {
                header("Location: ../views/table_clients.php?toast=error_actualizar_cliente&type=error");
                exit();
            }

            // ✅ Éxito
            header("Location: ../views/table_clients.php?toast=venta_exitosa&type=success");
            exit();

        } else {
            header("Location: ../views/table_clients.php?toast=plan_no_encontrado&type=error");
            exit();
        }

    } else {
        header("Location: ../views/table_clients.php?toast=faltan_datos&type=error");
        exit();
    }
} else {
    header("Location: ../views/table_clients.php");
    exit();
}
?>

