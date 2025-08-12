<?php require_once("../connection/connection.php");?>
<?php include("../tables/table_plan.php"); ?>


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

    <!------------ SIDEBAR ------------------------------- -->
    <?php include("../partials/sidebar.php"); ?>

      <main>
        <!-- MODAL FORM -->
        <div id="modal" class="modal <?= $plan ? 'show' : '' ?>">

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

        <!-- 🗂️ Contenedor de toda la sección principal -->
        <div class="main-container">

        <!-- 📌 Encabezado lateral con título y botón -->
          <div class="side-header">
            <h2 class="title_table">PLANES</h2>
            <button id="openModalBtn">Crear Plan</button>
          </div>

          <!-- 📊 Contenedor de tabla -->
          <div class="table-container">
            <table border="1" cellpadding="10">
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Valor</th>
                <th>Meses</th>
                <th>Acciones</th>
              </tr>

               <!-- 🔁 Ciclo que recorre los planes -->
              <?php while($row = $resultado->fetch_assoc()): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['valor']) ?></td>
                <td><?= htmlspecialchars($row['meses']) ?></td>
                <td>
                  <button class="button_edit" type="button" onclick="abrirModalEditar(<?= $row['id'] ?>)">Editar</button>

                  
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

  <script src="../scripts/table_plan.js"></script>

  <!--TOAST-->
  <?php include("../partials/toast.php"); ?>

</body>
</html>
