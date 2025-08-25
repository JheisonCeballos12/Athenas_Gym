
<?php  
//---------------- INICIO DE SESION Y CONTROL DE ACCESO---------------------------------------------
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login/login.php");  //Si no ha iniciado sesión,
                                            // lo redirige a la página de login y detiene la ejecución con exit().
    exit();
}
 //Conexión a la base de datos
include("../connection/connection.php");

// Capturar el mes filtrado (si viene por GET)
$mes = isset($_GET['mes']) && $_GET['mes'] !== '' ? (int)$_GET['mes'] : 0;


//-------------------FILTRO OPCIONAL POR MES--------------------------------------------
$where = "";
if (isset($_GET['mes']) && $_GET['mes'] != "") {
    $mes = intval($_GET['mes']);
    $where = "WHERE MONTH(fecha_registro) = $mes";

}
 //--------------------Contar clientes activos-------------------------------------------
$res_activos = $conn->query("SELECT COUNT(*) AS total FROM clientes WHERE estado = 1");
$total_activos = $res_activos->fetch_assoc()['total'];

//---------------------Calcular ventas totales del año actual---------------------------
$res_total = $conn->query("SELECT SUM(valor_pagado) AS total FROM clientes WHERE YEAR(fecha_registro) = YEAR(CURDATE())");
$total_anual = $res_total->fetch_assoc()['total'];

//---------------------Obtener el plan más vendido---------------------------------------
$res_top = $conn->query("SELECT meses_del_plan, COUNT(*) AS total FROM clientes GROUP BY meses_del_plan ORDER BY total DESC LIMIT 1");
$top_plan = $res_top->fetch_assoc();

//---------------------Ventas por mes (enero a diciembre)---------------------------------
$anio = date("Y");
$labels_meses = [];
$ventas_mes = [];

if ($mes > 0) {
    // Solo el mes seleccionado
    $labels_meses = [date("M", mktime(0,0,0,$mes,1))]; // "Jun", "Jul", etc.
    $res_mes = $conn->query("
        SELECT SUM(valor) AS total 
        FROM inscripciones 
        WHERE YEAR(fecha_venta) = $anio AND MONTH(fecha_venta) = $mes
    ");
    $total_mes = $res_mes->fetch_assoc()['total'] ?? 0;
    $ventas_mes = [$total_mes];
} else {
    // Todos los meses del año
    for ($i = 1; $i <= 12; $i++) {
        $labels_meses[] = date("M", mktime(0,0,0,$i,1)); // Ene, Feb, Mar...
        $res = $conn->query("
            SELECT SUM(valor) AS total 
            FROM inscripciones 
            WHERE YEAR(fecha_venta) = $anio AND MONTH(fecha_venta) = $i
        ");
        $row = $res->fetch_assoc();
        $ventas_mes[] = $row['total'] ?? 0;
    }
}



//-------------------------------Ventas por tipo de plan----------------------------------
$duraciones = [1, 2, 3, 6, 12];
$ventas_planes = [];
foreach ($duraciones as $dur) {
    $res = $conn->query("SELECT COUNT(*) AS total FROM clientes WHERE meses_del_plan = $dur");
    $row = $res->fetch_assoc();
    $ventas_planes[] = $row['total'] ?? 0;
}

//----------------------------------Tabla de ventas ----------------------------------------

$mes = isset($_GET['mes']) ? intval($_GET['mes']) : 0;
$anio = date("Y");

$sql = "
    SELECT 
        i.id,
        c.nombres,
        c.apellidos,
        p.nombre AS plan,
        p.meses,
        i.fecha_venta,
        i.valor,
        i.estado
    FROM inscripciones i
    JOIN clientes c ON c.id = i.cliente_id
    JOIN planes p ON p.id = i.plan_id
    WHERE YEAR(i.fecha_venta) = $anio
";

if ($mes > 0) {
    $sql .= " AND MONTH(i.fecha_venta) = $mes";
}

$sql .= " ORDER BY i.fecha_venta DESC";

$result = $conn->query($sql);

//---------------------Calcular ventas del mes filtrado---------------------------------
$total_mes = 0;
if ($mes > 0) {
    $res_mes = $conn->query("
        SELECT SUM(valor) AS total 
        FROM inscripciones 
        WHERE YEAR(fecha_venta) = $anio AND MONTH(fecha_venta) = $mes
    ");
    $total_mes = $res_mes->fetch_assoc()['total'] ?? 0;
}

