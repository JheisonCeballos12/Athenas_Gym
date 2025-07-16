<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login/login.php");
    exit();
}

include("../connection/connection.php");

// FUNCIÓN EDITAR: cargar datos si viene edit_id por GET
$plan = null;
if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $id = intval($_GET['edit_id']);
    $sql_edit = "SELECT * FROM planes WHERE id = $id";
    $result_edit = $conn->query($sql_edit);
    $plan = $result_edit->fetch_assoc();
}

// FUNCIÓN SELECT: traer todos los planes
$sql = "SELECT * FROM planes";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Planes Athenas Gym</title>
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
        <!-- MODAL FORM -->
        <div id="modal" class="modal" style="<?= $plan ? 'display: block;' : 'display: none;' ?>">
          <div class="modal-content">
            <span class="close">&times;</span>
            <form class="modal_form" action="../controller_table_plan/insert.php" method="POST">
              <?php if ($plan): ?>
                <input type="hidden" name="id" value="<?= $plan['id'] ?>">
              <?php endif; ?>

              <h1 class="title_main">
                <?= $plan ? 'Editar Plan' : 'Crear Nuevo Plan' ?>
              </h1>

              <div class="input_with_icon">
                <input type="text" name="nombre" placeholder="Nombre del plan" required value="<?= htmlspecialchars($plan['nombre'] ?? '') ?>">
                <i class="fa-solid fa-dumbbell"></i>
              </div>

              <div class="input_with_icon">
                <input type="number" name="valor" placeholder="Valor" step="0.01" required value="<?= htmlspecialchars($plan['valor'] ?? '') ?>">
                <i class="fa-solid fa-dollar-sign"></i>
              </div>

              <div class="input_with_icon">
                <input type="number" name="meses" placeholder="Duración en meses" required value="<?= htmlspecialchars($plan['meses'] ?? '') ?>">
                <i class="fa-solid fa-calendar"></i>
              </div>

              <button class="button_register" type="submit" name="send">
                <?= $plan ? 'Actualizar' : 'Enviar' ?>
              </button>
            </form>
          </div>
        </div>

        <!-- SECTION WITH BUTTON AND TABLE -->
        <div class="main-container">
          <div class="side-header">
            <h2 class="title_table">PLANES</h2>
            <button id="openModalBtn">Crear Plan</button>
          </div>

          <div class="table-container">
            <table border="1" cellpadding="10">
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Valor</th>
                <th>Meses</th>
                <th>Acciones</th>
              </tr>
              <?php while($row = $resultado->fetch_assoc()): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['valor']) ?></td>
                <td><?= htmlspecialchars($row['meses']) ?></td>
                <td>
                  <a href="table_plan.php?edit_id=<?= $row['id'] ?>" id="button_edit">Editar</a>
                  
                  <form action="../controller/delete_plan.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro?');">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" id="button_delete">Eliminar</button>
                  </form>
                </td>
              </tr>
              <?php endwhile; ?>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script>
    const modal = document.getElementById("modal");
    const btn = document.getElementById("openModalBtn");
    const span = document.getElementsByClassName("close")[0];

    btn.onclick = () => modal.style.display = "block";
    span.onclick = () => {
      modal.style.display = "none";
      window.location.href = "table_plan.php";
    }
    window.onclick = (e) => {
      if (e.target == modal) {
        modal.style.display = "none";
        window.location.href = "table_plan.php";
      }
    }
  </script>
</body>
</html>
