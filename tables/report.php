
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
$ventas_mes = [];
for ($i = 1; $i <= 12; $i++) {
    $res = $conn->query("SELECT SUM(valor_pagado) AS total FROM clientes WHERE MONTH(fecha_registro) = $i");
    $row = $res->fetch_assoc();
    $ventas_mes[] = $row['total'] ?? 0;
}

//-------------------------------Ventas por tipo de plan----------------------------------
$duraciones = [1, 2, 3, 6, 12];
$ventas_planes = [];
foreach ($duraciones as $dur) {
    $res = $conn->query("SELECT COUNT(*) AS total FROM clientes WHERE meses_del_plan = $dur");
    $row = $res->fetch_assoc();
    $ventas_planes[] = $row['total'] ?? 0;
}
?>
