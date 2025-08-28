<?php
include("../connection/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cliente_id']) && isset($_POST['plan_id'])) {

    $cliente_id = intval($_POST['cliente_id']);
    $plan_id    = intval($_POST['plan_id']);

    // 1️⃣ Obtener precio y duración del plan
    $sql_plan = "SELECT valor, meses FROM planes WHERE id = $plan_id";
    $result_plan = $conn->query($sql_plan);

    if ($result_plan && $result_plan->num_rows > 0) {
        $row_plan = $result_plan->fetch_assoc();
        $valor_plan = intval($row_plan['valor']);
        $duracion_meses = intval($row_plan['meses']);

        if ($valor_plan <= 0 || $duracion_meses <= 0) {
            header("Location: ../views/table_clients.php?toast=plan_invalido&type=error");
            exit();
        }

        // 2️⃣ Guardar datos antiguos del cliente
        $sql_cliente = "SELECT mensualidad, fecha_vencimiento, meses_del_plan, valor_pagado 
                        FROM clientes WHERE id = $cliente_id";
        $cliente_antiguo = $conn->query($sql_cliente)->fetch_assoc();

        $mensualidad_ant = $cliente_antiguo['mensualidad'] ?? null;
        $fecha_venc_ant  = $cliente_antiguo['fecha_vencimiento'] ?? null;
        $meses_ant       = $cliente_antiguo['meses_del_plan'] ?? 0;
        $valor_ant       = $cliente_antiguo['valor_pagado'] ?? 0;

        // 3️⃣ Insertar inscripción con estado VIGENTE + datos anteriores
       $stmt = $conn->prepare("INSERT INTO inscripciones 
            (cliente_id, plan_id, valor, estado, mensualidad_anterior, fecha_vencimiento_anterior, meses_plan_anterior, valor_pagado_anterior)
            VALUES (?, ?, ?, 'VIGENTE', ?, ?, ?, ?)");
        $stmt->bind_param(
            "iiissii",
            $cliente_id,
            $plan_id,
            $valor_plan,
            $mensualidad_ant,
            $fecha_venc_ant,
            $meses_ant,
            $valor_ant
        );

        $stmt->execute();

        // 4️⃣ Actualizar cliente con el nuevo plan
        $fecha_vencimiento_nueva = date('Y-m-d', strtotime("+$duracion_meses months"));
        $stmt2 = $conn->prepare("UPDATE clientes SET 
            mensualidad='VIGENTE', 
            fecha_vencimiento=?, 
            meses_del_plan=?, 
            valor_pagado=? 
            WHERE id=?");
        $stmt2->bind_param("siii", $fecha_vencimiento_nueva, $duracion_meses, $valor_plan, $cliente_id);
        $stmt2->execute();

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
?>

