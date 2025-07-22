<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login/login.php");
    exit();
}

include ("../connection/connection.php");


// FUNCIÓN EDITAR CLIENTE
$cliente = null;
if (isset($_POST['edit_id'])) {
    $id = intval($_POST['edit_id']);
    $sql_edit = "SELECT * FROM clientes WHERE id = $id";
    $result_edit = $conn->query($sql_edit);
    $cliente = $result_edit->fetch_assoc();
}

// FILTRO DE BÚSQUEDA
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
$estado_filtro = isset($_GET['estado_filtro']) ? $_GET['estado_filtro'] : '';
$vigencia_filtro = isset($_GET['vigencia_filtro']) ? $_GET['vigencia_filtro'] : '';


$sql = "SELECT * FROM clientes WHERE 1=1";

if ($busqueda !== '') {
    $busqueda = $conn->real_escape_string($busqueda);
    $sql .= " AND (
        nombres LIKE '%$busqueda%' OR
        apellidos LIKE '%$busqueda%' OR
        identidad LIKE '%$busqueda%' OR
        telefono LIKE '%$busqueda%'
    )";
}

if ($estado_filtro !== '') {
    $estado_filtro = intval($estado_filtro);
    $sql .= " AND estado = $estado_filtro";
}

$resultado = $conn->query($sql);

// ÚLTIMAS VENTAS POR CLIENTE
$sql_ventas = "
    SELECT u.cliente_id, u.plan_id, u.valor, p.meses
    FROM inscripciones u
    JOIN planes p ON u.plan_id = p.id
    WHERE u.id IN (
        SELECT MAX(id) FROM inscripciones GROUP BY cliente_id
    )
";
$resultado_ventas = $conn->query($sql_ventas);

// MAPEAR VENTAS
$ventas = [];
while ($v = $resultado_ventas->fetch_assoc()) {
    $ventas[$v['cliente_id']] = [
        'meses' => $v['meses'],
        'valor' => $v['valor']
    ];
}

// CLIENTES Y PLANES PARA MODAL VENTA
$result_clientes = $conn->query("SELECT id, nombres, apellidos FROM clientes");
$result_planes = $conn->query("SELECT id, nombre, valor FROM planes");


//CUMPLEAÑOS 

    
if (!isset($_SESSION['cumple_felicitado'])) {
    include("../controllers/cumpleaneros.php"); // Este archivo debe buscar cumpleañeros de hoy
    $_SESSION['cumple_felicitado'] = true;
}


$cumpleaneros = [];
$sql_c = "SELECT nombres, apellidos, fecha_nacimiento FROM clientes";
$resultado_c = $conn->query($sql_c);

if ($resultado_c->num_rows > 0) {
    $hoy = date('m-d');
    while ($row = $resultado_c->fetch_assoc()) {
        $fechaNac = date('m-d', strtotime($row['fecha_nacimiento']));
        if ($hoy === $fechaNac) {
            $cumpleaneros[] = $row['nombres'] . ' ' . $row['apellidos'];
        }
    }
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Clientes Athenas Gym</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../styles/style_tables.css" />
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

      <main>
        <!-- MODAL CREAR/EDITAR CLIENTE -->
        <div id="modal" class="modal" <?= $cliente ? 'style="display: flex;"' : '' ?>>
          <div class="modal-content">
            <span class="close">&times;</span>
            <form class="modal_form" action="<?= $cliente ? '../controller/update_save.php' : '../controller/send.php' ?>" method="POST">
              <?php if ($cliente): ?>
                <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
              <?php endif; ?>

              <h1 class="title_main">
                <?= $cliente ? 'Editar Cliente' : '𝐒𝐔𝐏𝐄𝐑𝐀 𝐓𝐔𝐒 𝐋𝐈𝐌𝐈𝐓𝐄𝐒 𝐀𝐐𝐔𝐈 𝐘 𝐀𝐇𝐎𝐑𝐀' ?>
              </h1>

              <div class="input_with_icon">
                <input type="text" name="nombres" placeholder="Nombres aquí" required value="<?= $cliente['nombres'] ?? '' ?>">
                <i class="fa-solid fa-user"></i>
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
                <input type="date" name="fecha_nacimiento" required value="<?= $cliente['fecha_nacimiento'] ?? '' ?>">
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

              <div class="input_with_icon">
                <select name="cliente_id" id="cliente_id_select" required>
                  <option value="">Seleccione Cliente</option>
                  <?php
                  $result_clientes->data_seek(0);
                  while($c = $result_clientes->fetch_assoc()): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombres'] . ' ' . $c['apellidos']) ?></option>
                  <?php endwhile; ?>
                </select>
                <i class="fa-solid fa-users"></i>
              </div>

              <div class="input_with_icon">
                <select name="plan_id" required>
                  <option value="">Seleccione Plan</option>
                  <?php
                  $result_planes->data_seek(0);
                  while($p = $result_planes->fetch_assoc()): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?> - $<?= $p['valor'] ?></option>
                  <?php endwhile; ?>
                </select>
                <i class="fa-solid fa-dumbbell"></i>
              </div>

              <button class="button_register" type="submit">Registrar</button>
            </form>
          </div>
        </div>

        <!-- SECCIÓN CON ENCABEZADO -->
        <div class="side-header">
          <h2 class="title_table">CLIENTES</h2>

          <div class="header-controls">
            <div class="search-group">

            <!-- SECCION DE FILTROS -->

              <form method="GET" action="">
                  <form method="GET" action="">
                    <input type="text" name="busqueda" placeholder="Buscar por nombre, apellido, cédula o celular" value="<?= htmlspecialchars($busqueda) ?>">
                   
                    <!-- FILTRO ACTIVO E INACTIVO -->                     
                    <select name="estado_filtro">
                      <option value="">Todos (Estado)</option>
                      <option class="button_activate" value="1" <?= $estado_filtro === '1' ? 'selected' : '' ?>>Activos</option>
                      <option class="button_deactivate" value="0" <?= $estado_filtro === '0' ? 'selected' : '' ?>>Inactivos</option>
                    </select>
                    <!-- FILTRO VIGENCIA -->
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

        <!-- VISTA TABLA CLIENTES -->
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
            <?php while($row = $resultado->fetch_assoc()): ?>
              
              <?php
                $hoy = date('Y-m-d');
                $mensualidadEstado = '—';
                if (!empty($row['fecha_vencimiento'])) {
                    $mensualidadEstado = ($row['fecha_vencimiento'] >= $hoy) ? 'VIGENTE' : 'VENCIDA';
                }

                // FILTRAR POR ESTADO
                if ($estado_filtro === '1' && !$row['estado']) {
                    continue;
                }
                if ($estado_filtro === '0' && $row['estado']) {
                    continue;
                }

                // FILTRAR POR VIGENCIA
                  if ($vigencia_filtro === 'vigente' && $mensualidadEstado !== 'VIGENTE') {
                          continue;
                }
                  if ($vigencia_filtro === 'vencida' && $mensualidadEstado !== 'VENCIDA') {
                          continue;
                }
                $meses = $ventas[$row['id']]['meses'] ?? '—';
                $valor = $ventas[$row['id']]['valor'] ?? '—';
              ?>
              <!-- AQUI LLEGA TODA LA INFORMACION JUNTO A LOS BOTONES -->
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nombres']) ?></td>
                <td><?= htmlspecialchars($row['apellidos']) ?></td>
                <td><?= htmlspecialchars($row['identidad']) ?></td>
                <td><?= htmlspecialchars($row['telefono']) ?></td>
                <td><?= htmlspecialchars($row['direccion']) ?></td>
                <td><?= htmlspecialchars($row['fecha_nacimiento']) ?></td>
                <td class="<?= $row['estado'] ? 'estado-activo' : 'estado-inactivo' ?>">
                  <?= $row['estado'] ? 'Activo' : 'Inactivo' ?>
                </td>
                <td><?= $mensualidadEstado ?></td>
                <td><?= htmlspecialchars($row['fecha_registro']) ?></td>
                <td><?= htmlspecialchars($row['fecha_vencimiento'] ?? '—') ?></td>
                <td><?= htmlspecialchars($meses) ?></td>
                <td>$<?= htmlspecialchars($valor) ?></td>
                <td>
                  <button class="button_edit" type="button" onclick="abrirModalEditar(<?= $row['id'] ?>)">Editar</button>
                  <button type="button" onclick="abrirModalVenta(<?= $row['id'] ?>)">Actualizar Plan</button>

                  <form action="../controller/delete.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro?');">
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
  </div>

<script>
  const modal = document.getElementById("modal");
  const modalVenta = document.getElementById("modalVenta");
  const clienteSelectVenta = document.getElementById("cliente_id_select");

  function abrirModalEditar(clienteId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'edit_id';
    input.value = clienteId;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
  }

  function abrirModalVenta(clienteId) {
    if (modalVenta) {
      modalVenta.style.display = "flex";
      if (clienteSelectVenta) {
        clienteSelectVenta.value = clienteId;
      }
    }
  }

  document.addEventListener("DOMContentLoaded", () => {
    const openModalBtn = document.getElementById("openModalBtn");
    if (openModalBtn && modal) {
      openModalBtn.addEventListener("click", () => {
        modal.style.display = "flex";
      });
    }

    const openVentaBtn = document.getElementById("openVentaBtn");
    if (openVentaBtn && modalVenta) {
      openVentaBtn.addEventListener("click", () => {
        modalVenta.style.display = "flex";
      });
    }

    const closeBtn = document.querySelector(".close");
    if (closeBtn && modal) {
      closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
        window.location.href = "table_cliente.php";
      });
    }

    const closeVentaBtn = document.querySelector(".close-venta");
    if (closeVentaBtn && modalVenta) {
      closeVentaBtn.addEventListener("click", () => {
        modalVenta.style.display = "none";
      });
    }

    window.addEventListener("click", (e) => {
      if (e.target === modal) {
        modal.style.display = "none";
        window.location.href = "table_cliente.php";
      }
      if (e.target === modalVenta) {
        modalVenta.style.display = "none";
      }
    });

    // TOAST DE CUMPLEAÑOS
    const cumpleaneros = <?= json_encode($cumpleaneros) ?>;
    if (cumpleaneros.length > 0) {
      cumpleaneros.forEach(nombre => {
        mostrarToast(`🎉 ¡Hoy está de cumpleaños ${nombre}! 🎂`);
      });
    }

    function mostrarToast(mensaje) {
      const toast = document.createElement('div');
      toast.className = 'toast-cumple';
      toast.textContent = mensaje;
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 6000);
    }
  });
</script>

<!-- Incluir el archivo PHP fuera del script -->
<?php include("../partials/toast.php"); ?>


</body>
</html>
