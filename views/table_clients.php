<?php require_once("../connection/connection.php");?>
<?php include("../tables/table_cliente.php"); ?>



<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Clientes Athenas Gym</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="../styles/style_tables.css" />
</head>
<body>

 <!-- SIDEBAR --------------------------------------------------------------------------------------------------------->
   <?php include("../partials/sidebar.php"); ?>


      <main>
        <!-- MODAL CREAR/EDITAR CLIENTE ------------------------------------------------------------------------------------------------->
        <div id="modal" class="modal" <?= $cliente ? 'style="display: flex;"' : '' ?>>
          <div class="modal-content">
            <span class="close">&times;</span>
            <form class="modal_form" action="<?= $cliente ? '../controller/update_save.php' : '../controller/send.php' ?>" method="POST">
              <?php if ($cliente): ?>
                <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
              <?php endif; ?>

              <h1 class="title_main">
                <?= $cliente ? 'Editar Cliente' : 'CREAR CLIENTE' ?>
              </h1>

              <div class="input_with_icon">
                <input type="text" name="nombres" placeholder="Nombres aquí" required value="<?= $cliente['nombres'] ?? '' ?>">
                <i class="fa-solid fa-user">+</i>
              </div>

              <div class="input_with_icon">
                <input type="text" name="apellidos" placeholder="Apellidos aquí" required value="<?= $cliente['apellidos'] ?? '' ?>">
                <i class="fa-solid fa-user"></i>
              </div>

              <div class="input_with_icon">
                <input type="number" name="identidad" placeholder="Número de identidad" required value="<?= $cliente['identidad'] ?? '' ?>">
                <i class="fa-solid fa-id-card"></i>
              </div>

              <div class="input_with_icon">
                <input type="number" name="telefono" placeholder="Teléfono aquí" required value="<?= $cliente['telefono'] ?? '' ?>">
                <i class="fa-solid fa-phone"></i>
              </div>

              <div class="input_with_icon">
                <input type="text" name="direccion" placeholder="Dirección aquí" required value="<?= $cliente['direccion'] ?? '' ?>">
                <i class="fa-solid fa-location-dot"></i>
              </div>

              <div class="input_with_icon">
                <label class="label_nacimiento"for="fecha_nacimiento">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" placeholder="fecha de nacimiento" required value="<?= $cliente['fecha_nacimiento'] ?? '' ?>" >
                <i class="fa-solid fa-calendar-days"></i>
              </div>

              <div class="checkbox_group">
                <input type="checkbox" id="estado" name="estado" value="1" <?= isset($cliente['estado']) && $cliente['estado'] ? 'checked' : '' ?>>
                <label for="estado"><i class="fa-solid fa-check-circle"></i> ¿Activo?</label>
              </div>

              <button class="button_register" type="submit" name="<?= $cliente ? 'actualizar' : 'send' ?>">
                <?= $cliente ? 'Actualizar' : 'Enviar' ?>
              </button>
            </form>
          </div>
        </div>
        

<!-- MODAL VENDER PLAN -->
<div id="modalVenta" class="modal">
  <div class="modal-content">
    <span class="close-venta">&times;</span>
    <form class="modal_form" action="../controller_usuarios/send_registro.php" method="POST">
      <h1 class="title_main">Registrar</h1>

      <!-- Cliente con buscador -->
      <div class="input_with_icon">
        <!-- Campo donde el usuario busca por nombre -->
        <input list="lista_clientes" id="cliente_nombre" placeholder="Escriba nombre..." autocomplete="off" required>

        <!-- Campo oculto donde se guarda el id -->
        <input type="hidden" name="cliente_id" id="cliente_id">

        <!-- Opciones -->
        <datalist id="lista_clientes">
          <?php 
          $result_clientes->data_seek(0);
          while($c = $result_clientes->fetch_assoc()): ?>
            <option data-id="<?= $c['id'] ?>" value="<?= htmlspecialchars($c['nombres'] . ' ' . $c['apellidos']) ?>"></option>
          <?php endwhile; ?>
        </datalist>
        <i class="fa-solid fa-users"></i>
      </div>

      <!-- Plan -->
      <div class="input_with_icon">
        <select name="plan_id" required>
          <option value="">Seleccione Plan</option>
          <?php 
          $result_planes->data_seek(0);
          while($p = $result_planes->fetch_assoc()): ?>
            <option value="<?= $p['id'] ?>">
                <?= htmlspecialchars($p['nombre']) ?> - $<?= number_format($p['valor'], 0, ',', '.') ?>
              </option>

          <?php endwhile; ?>
        </select>
        <i class="fa-solid fa-dumbbell"></i>
      </div>

      <button class="button_register" type="submit">Registrar</button>
    </form>
  </div>
</div>

<script>
// Cuando el usuario elige un nombre, buscamos su id en el datalist
document.getElementById("cliente_nombre").addEventListener("input", function() {
  let valor = this.value;
  let opciones = document.querySelectorAll("#lista_clientes option");
  let hiddenId = document.getElementById("cliente_id");

  hiddenId.value = ""; // reset
  opciones.forEach(op => {
    if (op.value === valor) {
      hiddenId.value = op.dataset.id; // Guardamos el ID real
    }
  });
});
</script>






        <!-- SECCIÓN CON ENCABEZADO -------------------------------------------------------------->
        <div class="side-header">
          <h2 class="title_table">CLIENTES</h2>

          <div class="header-controls">
            <div class="search-group">

            <!-- SECCION DE FILTROS ------------------------------------------------------------------------>
             <!-- FILTRO PARA BUSCAR NOMBRE APELLIDO EN LA TABLA? ------------------------------------------------------------------------>

                  <form method="GET" action="">
                    <input type="text" name="busqueda" placeholder="Buscar por nombre, apellido, cédula o celular" value="<?= htmlspecialchars($busqueda) ?>">
                   
                    <!-- FILTRO ACTIVO E INACTIVO -------------------------------------------------------------------------->                     
                    <select name="estado_filtro">
                      <option value="">Todos (Estado)</option>
                      <option class="button_activate" value="1" <?= $estado_filtro === '1' ? 'selected' : '' ?>>Activos</option>
                      <option class="button_deactivate" value="0" <?= $estado_filtro === '0' ? 'selected' : '' ?>>Inactivos</option>
                    </select>
                    <!-- FILTRO VIGENCIA ----------------------------------------------------------------------------------->
                   <select name="vigencia_filtro">
                      <option value="">Todos (Vigencia)</option>
                      <option value="vigente" <?= $vigencia_filtro === 'vigente' ? 'selected' : '' ?>>Vigentes</option>
                      <option value="vencida" <?= $vigencia_filtro === 'vencida' ? 'selected' : '' ?>>Vencidas</option>
                    </select>

                    <button type="submit" class="search-button">Buscar</button>
                  </form>

            </div>

            <div class="button-group" style="margin-bottom: 10px;">
              <button id="openModalBtn" type="button">Crear Cliente</button>
              <button id="openVentaBtn" type="button">Registrar Cliente a Plan</button>
            </div>
          </div>
        </div>



        <!-- VISTA TABLA CLIENTES -------------------------------------------------------------------------------->
        <div class="table-container">
          <table border="1" cellpadding="10">
            <tr>
              <th>ID</th>
              <th>Nombres</th>
              <th>Apellidos</th>
              <th>N. doc</th>
              <th>Teléfono</th>
              <th>Dirección</th>
              <th>F. nacimiento</th>
              <th>Estado</th>
              <th>Mensualidad</th>
              <th>F. registro</th>
              <th>F. vencimiento</th>
              <th>Meses del plan</th>
              <th>Valor pagado</th>
              <th>Acciones</th>
            </tr>

              <?php
                $contador = 1; // empieza en 1
                
              ?>

            <?php while($row = $resultado->fetch_assoc()): ?>
              <?php
        // Calcular vigencia aquí para que siempre exista
        if (!empty($row['fecha_vencimiento']) && $row['fecha_vencimiento'] >= date('Y-m-d')) {
            $mensualidadEstado = 'VIGENTE';
        } else {
            $mensualidadEstado = 'VENCIDA';
        }

        // Obtener meses y valor si existen
        $meses = isset($ventas[$row['id']]['meses']) ? $ventas[$row['id']]['meses'] : '—';
        $valor = isset($ventas[$row['id']]['valor']) ? $ventas[$row['id']]['valor'] : '—';
    ?>
              
              <!-- AQUI LLEGA TODA LA INFORMACION JUNTO A LOS BOTONES -------------------------------------------------------------------------------------->
              <tr>
                <td data-label="id"><?= $contador++ ?></td>
                <td data-label="nombres"><?= htmlspecialchars($row['nombres']) ?></td>
                <td data-label="apellidos"><?= htmlspecialchars($row['apellidos']) ?></td>
                <td data-label="identidad"><?= htmlspecialchars($row['identidad']) ?></td>
                <td data-label="teléfono">
              <?php if (!empty($row['telefono'])): ?>
                  <?php
                    $telefono = $row['telefono'];
                    $nombre = $row['nombres'];
                    $mensaje = "Hola $nombre, tu plan en Athenas Gym ha vencido. ¿Deseas renovarlo?";
                    $urlWhatsapp = 'https://wa.me/57' . $telefono;


                    if ($mensualidadEstado === 'VENCIDA') {
                      $urlWhatsapp .= '?text=' . urlencode($mensaje);
                    }
                  ?>
          
                  <a
                    href="<?= $urlWhatsapp ?>"
                    target="_blank"
                    title="Enviar WhatsApp"
                    style="margin-left: 6px;"
                  >
                    <i class="fab fa-whatsapp" style="color: green; font-size: 20px;"></i>
                    <?= $telefono ?>
                  </a>
              <?php endif; ?>
                <td data-label="direccion"><?= htmlspecialchars($row['direccion']) ?></td>
                <td data-label="fecha_nacimiento"><?= htmlspecialchars($row['fecha_nacimiento']) ?></td>
                <td data-label="estado" class="<?= $row['estado'] ? 'estado-activo' : 'estado-inactivo' ?>">
                  <?= $row['estado'] ? 'Activo' : 'Inactivo' ?>
                </td>
                
                <?php $clase_mensualidad = ($mensualidadEstado === 'VIGENTE') ? 'estado-vigente' : 'estado-vencida';?>
                <td data-label="mensualidad" class="<?= $clase_mensualidad ?>"><?= $mensualidadEstado ?></td>

                <td data-label="fecha_registro"><?= htmlspecialchars($row['fecha_registro']) ?></td>
                <td data-label="fecha_vencimiento"><?= htmlspecialchars($row['fecha_vencimiento'] ?? '—') ?></td>
                <td data-label="meses"><?= htmlspecialchars($meses) ?></td>
                <td data-label="valor">$<?= htmlspecialchars($valor) ?></td>
                <td>
                  <button class="button_edit" type="button" onclick="abrirModalEditar(<?= $row['id'] ?>)">Editar</button>
                  <button type="button" onclick="abrirModalVenta(<?= $row['id'] ?>)">Renovar Plan</button>

                  <form action="../controller/delete.php" method="POST" class="form-delete" style="display:inline;">
                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                      <input type="hidden" name="nuevo_estado" value="<?= $row['estado'] ? 0 : 1 ?>">
                      <button type="submit"
                        class="<?= $row['estado'] ? 'button_deactivate' : 'button_activate' ?>">
                        <?= $row['estado'] ? 'desactivar' : 'Activar' ?>
                      </button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>

          </table>
        </div>
      </main>
    </div>
  

<script>
  const cumpleaneros = <?= json_encode($cumpleaneros) ?>;
</script>

<script src="../scripts/table_clients.js"></script>


<!-- Incluir el archivo PHP fuera del script -->
<?php include("../partials/toast.php"); ?>


</body>
</html>