<?php require_once("../connection/connection.php");?>
<?php include("../tables/report.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reportes - Athenas Gym</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../styles/report.css">
</head>
<body>

<!------------ SIDEBAR ------------------------------- -->
    <?php include("../partials/sidebar.php"); ?>


  <div class="content">
    <header class="top-header">
      <h1 class="title_header">𝐀𝐓𝐇𝐄𝐍𝐀𝐒 𝐆𝐘𝐌</h1>
    </header>

    <main class="main-content">

      <!-- Reporte estático (sin modal oculto) -->
      <div class="modal-content">
        <div class="report-container">

          <!-- Parte superior: Título, Filtros y Resumen -->
          <div class="report-top" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 30px; flex-wrap: wrap;">
            <div class="filters-section">
              <h1>📊 Reportes de Ventas</h1>
              <form method="GET" class="filters-form">
                <label for="mes">Filtrar por mes:</label>
                <select name="mes" id="mes">
                  <option value="">Todos</option>
                  <?php for ($i = 1; $i <= 12; $i++) {
                    $selected = (isset($_GET['mes']) && $_GET['mes'] == $i) ? 'selected' : '';
                    echo "<option value='$i' $selected>" . date("F", mktime(0, 0, 0, $i, 10)) . "</option>";
                  } ?>
                </select>
                <button type="submit">Filtrar</button>
              </form>
            </div>
            <div class="summary-section">
              <h2>Resumen General</h2>
              <p><strong>Clientes activos:</strong> <?= $total_activos ?></p>
              <p><strong>Total vendido en el año:</strong> $<?= number_format($total_anual) ?></p>
              <p><strong>Plan más vendido:</strong> <?= $top_plan['meses_del_plan'] ?> meses (<?= $top_plan['total'] ?> inscripciones)</p>
            </div>
          </div>

          <!-- Parte inferior: Gráficas ----------------------------------------------------------------------------------------------------->
          <div class="chart-row" style="display: flex; justify-content: space-around; gap: 40px; margin-top: 40px; flex-wrap: wrap;">
            <div class="chart-box">
              <canvas id="ventasPorMes" width="400" height="300"></canvas>
            </div>
            <div class="chart-box">
              <canvas id="ventasPorPlan" width="400" height="300"></canvas>
            </div>
          </div>

        </div>
      </div>
    </main>
  </div>
</div>

<script>
  window.ventasMesData = <?= json_encode($ventas_mes) ?>;
  window.ventasPlanesData = <?= json_encode($ventas_planes) ?>;
  window.duracionesLabels = <?= json_encode(array_map(fn($m) => "$m meses", $duraciones)) ?>;
</script>
<script src="../scripts/table_report.js"></script>

</body>
</html>