<?php require_once("../connection/connection.php"); ?>
<?php include("../tables/report.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reportes - Athenas Gym</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../styles/report.css">

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
</head>
<body>
  
<div class="layout">
    <!-- Sidebar -->
    <?php include("../partials/sidebar.php"); ?>

    <main class="main-content">
      <div class="modal-content">
         <div class="report-container">

            <!-- Parte superior: Título, filtros y resumen -->
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

            <!-- Parte inferior: Gráficas -->
            <div class="chart-row" style="display: flex; justify-content: space-around; gap: 40px; margin-top: 40px; flex-wrap: wrap;">
                <div class="chart-box">
                  <canvas id="ventasPorMes" width="400" height="300"></canvas>
                </div>
              
                <div class="chart-box">
                  <canvas id="ventasPorPlan" width="400" height="300"></canvas>
                </div>
            </div>

            <!-- Tabla de ventas -->
            <div style="margin-top:50px;">
              <h2>📋 Detalle de Ventas</h2>
              <?php
              $sql = "
                  SELECT i.id, c.nombres, c.apellidos, p.nombre AS plan, p.meses, i.fecha_venta, i.valor
                  FROM inscripciones i
                  INNER JOIN clientes c ON i.cliente_id = c.id
                  INNER JOIN planes p ON i.plan_id = p.id
                  ORDER BY i.fecha_venta DESC
              ";
              $result = $conn->query($sql);
              ?>

              <table id="ventasTable" class="display" style="width:100%">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th>Cliente</th>
                          <th>Plan</th>
                          <th>Meses</th>
                          <th>Fecha venta</th>
                          <th>Valor</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php while($row = $result->fetch_assoc()): ?>
                      <tr>
                          <td><?= $row['id'] ?></td>
                          <td><?= $row['nombres'] . " " . $row['apellidos'] ?></td>
                          <td><?= $row['plan'] ?></td>
                          <td><?= $row['meses'] ?></td>
                          <td><?= $row['fecha_venta'] ?></td>
                          <td>$<?= number_format($row['valor']) ?></td>
                      </tr>
                      <?php endwhile; ?>
                  </tbody>
              </table>
            </div>

        </div>
      </div>
    </main>
</div>

<!-- Pasar datos PHP a JS -->
<script>
  window.ventasMesData = <?= json_encode($ventas_mes) ?>;
  window.ventasPlanesData = <?= json_encode($ventas_planes) ?>;
  window.duracionesLabels = <?= json_encode(array_map(fn($m) => "$m meses", $duraciones)) ?>;
</script>

<!-- Scripts DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<!-- Inicializar DataTable -->
<script>
$(document).ready(function() {
    $('#ventasTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf', 'print'
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });
});
</script>

<!-- Chart.js -->
<script src="../scripts/table_report.js"></script>

</body>
</html>
