<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login/login.php");
    exit();
}

include("../connection/connection.php");

// Filtros
$where = "";
if (isset($_GET['mes']) && $_GET['mes'] != "") {
    $mes = intval($_GET['mes']);
    $where = "WHERE MONTH(fecha_registro) = $mes";
}

// Total clientes activos
$res_activos = $conn->query("SELECT COUNT(*) AS total FROM clientes WHERE estado = 1");
$total_activos = $res_activos->fetch_assoc()['total'];

// Total vendido en el año
$res_total = $conn->query("SELECT SUM(valor_pagado) AS total FROM clientes WHERE YEAR(fecha_registro) = YEAR(CURDATE())");
$total_anual = $res_total->fetch_assoc()['total'];

// Plan más vendido (por meses del plan)
$res_top = $conn->query("SELECT meses_del_plan, COUNT(*) AS total FROM clientes GROUP BY meses_del_plan ORDER BY total DESC LIMIT 1");
$top_plan = $res_top->fetch_assoc();

// Gráfico: Ventas por mes
$ventas_mes = [];
for ($i = 1; $i <= 12; $i++) {
    $res = $conn->query("SELECT SUM(valor_pagado) AS total FROM clientes WHERE MONTH(fecha_registro) = $i");
    $row = $res->fetch_assoc();
    $ventas_mes[] = $row['total'] ?? 0;
}

// Gráfico: Ventas por duración de plan
$duraciones = [1, 2, 3, 6, 12];
$ventas_planes = [];
foreach ($duraciones as $dur) {
    $res = $conn->query("SELECT COUNT(*) AS total FROM clientes WHERE meses_del_plan = $dur");
    $row = $res->fetch_assoc();
    $ventas_planes[] = $row['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reportes - Athenas Gym</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="../styles/report.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="layout">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <img class="logo_athenas" src="../images/logo_athenas.png" alt="logo_athenas">
    <nav class="nav-panel">
      <a href="../index.php"><i class="fa-solid fa-house"></i> Inicio</a>
      <a href="table_cliente.php"><i class="fa-solid fa-users"></i> Clientes</a>
      <a href="table_plan.php"><i class="fa-solid fa-dumbbell"></i> Planes</a>
      <a href="report.php"><i class="fa-solid fa-chart-line"></i> Reportes</a>
      <a href="../logout.php"><i class="fa-solid fa-door-open"></i> Cerrar sesión</a>
    </nav>
  </aside>

  <!-- CONTENT -->
  <div class="content">
    <header class="top-header">
      <h1 class="title_header">𝐀𝐓𝐇𝐄𝐍𝐀𝐒 𝐆𝐘𝐌</h1>
    </header>

    <main class="main-content">
      <div class="top-bar">
        <h1 style="color: white;">Reportes de Ventas</h1>
      </div>

      <!-- Filtro por mes -->
      <form method="GET" class="filters">
        <label for="mes">Filtrar por mes:</label>
        <select name="mes" id="mes">
          <option value="">Todos</option>
          <?php
          for ($i = 1; $i <= 12; $i++) {
            $selected = (isset($_GET['mes']) && $_GET['mes'] == $i) ? 'selected' : '';
            echo "<option value='$i' $selected>" . date("F", mktime(0, 0, 0, $i, 10)) . "</option>";
          }
          ?>
        </select>
        <button type="submit">Filtrar</button>
      </form>

      <!-- Contenedor de reportes -->
      <div class="report-container">
        <h2>Resumen General</h2>
        <p><strong>Clientes activos:</strong> <?= $total_activos ?></p>
        <p><strong>Total vendido en el año:</strong> $<?= number_format($total_anual) ?></p>
        <p><strong>Plan más vendido:</strong> <?= $top_plan['meses_del_plan'] ?> meses (<?= $top_plan['total'] ?> inscripciones)</p>

        <div class="chart-row">
          <div class="chart-box">
            <canvas id="ventasPorMes" width="400" height="300"></canvas>
          </div>
          <div class="chart-box">
            <canvas id="ventasPorPlan" width="400" height="300"></canvas>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<script>
const ctxMes = document.getElementById('ventasPorMes').getContext('2d');
const ventasPorMes = new Chart(ctxMes, {
    type: 'bar',
    data: {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        datasets: [{
            label: 'Ventas por Mes',
            data: <?= json_encode($ventas_mes) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: { y: { beginAtZero: true } }
    }
});

const ctxPlan = document.getElementById('ventasPorPlan').getContext('2d');
const ventasPorPlan = new Chart(ctxPlan, {
    type: 'pie',
    data: {
        labels: <?= json_encode(array_map(fn($m) => "$m meses", $duraciones)) ?>,
        datasets: [{
            label: 'Inscripciones por duración de plan',
            data: <?= json_encode($ventas_planes) ?>,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
        }]
    }
});
</script>

</body>
</html>
