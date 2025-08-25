<?php require_once("../connection/connection.php"); ?>
<?php include("../tables/report.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reportes - Athenas Gym</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!--Bibliotecas de gráficas-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!--Bibliotecas de notificaciones-->
  <link rel="stylesheet" href="../styles/report.css">

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
</head>
<body>

 <!-- SIDEBAR --------------------------------------------------------------------------------------------------------->
   <?php include("../partials/sidebar.php"); ?>


    <main class="main-content">
      <div class="modal-content">
         <div class="report-container">

            <!-- TITULO Y FILTRO POR MES ----------->
            <div class="report-top">

              <div class="filters-section">
                <h1>📊 Reportes de Ventas</h1>
                <br><br>
                    <form method="GET" class="filters-form">
                        <label for="mes">Filtrar por mes:</label>
                        <select name="mes" id="mes">
                            <option value="">Todos</option>
                            <?php 
                                $meses = [
                                  1 => "Enero", 
                                  2 => "Febrero", 
                                  3 => "Marzo", 
                                  4 => "Abril", 
                                  5 => "Mayo", 
                                  6 => "Junio", 
                                  7 => "Julio", 
                                  8 => "Agosto", 
                                  9 => "Septiembre", 
                                  10 => "Octubre", 
                                  11 => "Noviembre", 
                                  12 => "Diciembre"
                                ];
                                ?>

                                <?php for ($i = 1; $i <= 12; $i++) {
                                    $selected = (isset($_GET['mes']) && $_GET['mes'] == $i) ? 'selected' : '';
                                    echo "<option value='$i' $selected>{$meses[$i]}</option>";
                                } ?>

                        </select>
                        <button type="submit">Filtrar</button>

                    </form>
              </div>
            <!------------------ RESUMEN GENERAL------------------->
              <div class="summary-section">
                  <h2>Resumen General</h2>
                  <p><strong>Clientes activos:</strong> <?= $total_activos ?></p>
                  <p><strong>Total vendido en el año:</strong> $<?= number_format($total_anual) ?></p>
                  <p><strong>Plan más vendido:</strong> <?= $top_plan['meses_del_plan'] ?> meses (<?= $top_plan['total'] ?> inscripciones)</p>
              </div>

            </div>

            <!--------------------------- GRAFICA DE TABLAS --------------------------->
            <div class="chart-row">
                <div class="chart-box">
                  <h1 class="graph_Tables">GRAFICA DE BARRAS</h1>
                  <canvas id="ventasPorMes" width="400" height="300"></canvas>
                </div>

                 <div id="resumenVentasMes" style="margin-top:20px; font-size:16px; font-weight:bold;"></div>
              
                <div class="chart-box">
                  <h1 class="graph_Tables">GRAFICA DE PASTEL</h1>
                  <canvas id="ventasPorPlan" width="400" height="300"></canvas>
                </div>
            </div>

            <!--------------------------- TABLA DE VENTAS------------------------------->
            <div style="margin-top:50px;">
              <h2>📋 Detalle de Ventas</h2>
                <div class="table-container">
                  <table id="ventasTable" class="display">
                      <thead>
                          <tr>
                              <th>ID</th>
                              <th>Cliente</th>
                              <th>Plan</th>
                              <th>Meses</th>
                              <th>Fecha venta</th>
                              <th>Valor</th>  
                              <th>Acciones</th>                                                    
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
                              
                             <td>
                                <?php if ($row['estado'] == 'VIGENTE') { ?>
                                  <form method="POST" action="../controller/cancelar_inscripcion.php" class="cancelar-form" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="button" class="btn-cancelar cancelar-btn">❌ Cancelar</button>
                                  </form>

                                <?php } else { ?>
                                  <span style="color:red;">Anulado</span>
                                <?php } ?>
                              </td>

                         
                          </tr>
                          <?php endwhile; ?>
                      </tbody>
                  </table>
                </div>
            </div>

        </div>
      </div>
    </main>

<!-- Pasar datos PHP a JS -->
<script>
  window.labelsMeses = <?= json_encode($labels_meses) ?>;   // 🔥 ahora mandamos los labels
  window.ventasMesData = <?= json_encode($ventas_mes) ?>;
  window.ventasPlanesData = <?= json_encode($ventas_planes) ?>;
  window.duracionesLabels = <?= json_encode(array_map(fn($m) => "$m meses", $duraciones)) ?>;
</script>

<!-- Esto es DataTables + extensiones (para exportar en Excel, PDF, imprimir, etc.). -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<!-- Chart.js -->
<script src="../scripts/table_report.js"></script>

</body>
</html>
