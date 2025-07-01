<?php
include("connection/connection.php");

// FUNCION EDITAR 

$cliente = null;

if (isset($_POST['edit_id'])) { // comprueba si se ha enviado un valor con el nombre (edit_id) desde un formulario por POST
    $id = $_POST['edit_id']; // guarda el valor del campo edit_id en la variable ($id) este id es el de cambiar 
    $sql_edit = "SELECT * FROM registros WHERE id = $id"; // lo busca en la base de datos y lo guarda en ($sql_edit)
    $result_edit = $conn->query($sql_edit); // ejecuta o envia el dato 
    $cliente = $result_edit->fetch_assoc(); // convierte el resultado de la consulta en un array asociativo 
}

// FUNCION DE LLAMADO SELECT

$sql = "SELECT * FROM registros"; // hace un llamado a la tabla de registros  y lo guarda en la variable $sql
$resultado = $conn->query($sql); // $conn contiene la conexion a la base de datos 
                                 // y query ejecuta o envia la tabla que esta dentro de la variable $sql
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Athenas Gym</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="styles/form.css">
</head>
<body>

<header>
  <h1 class="title_header">𝐀𝐓𝐇𝐄𝐍𝐀𝐒 𝐆𝐘𝐌</h1>
  <img class="logo_athenas" src="images/logo_athenas.png" alt="logo_athenas"> 
</header>

<main>
     <div id="modal" class="modal" style="<?= $cliente ? 'display: block;' : 'display: none;' ?>">
  <div class="modal-content">
    <span class="close">&times;</span>

    <form class="modal_form" action="<?= $cliente ? 'controller/update_save.php' : 'controller/send.php' ?>" method="POST">

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

      <div class="input_with_icon">
        <input type="number" name="valor" placeholder="Valor aquí" required value="<?= $cliente['valor'] ?? '' ?>">
        <i class="fa-solid fa-dollar-sign"></i>
      </div>

      <button class="button_register" type="submit" name="<?= $cliente ? 'actualizar' : 'send' ?>">
        <?= $cliente ? 'Actualizar' : 'Enviar' ?>
      </button>

    </form>
  </div>
</div>



  <section>
    <div>
      <h2 class="title_table">𝐑𝐄𝐆𝐈𝐒𝐓𝐑𝐎𝐒</h2>
      <button id="openModalBtn">Crear Cliente</button>
    </div>

    <div class="table_register">
      <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Identidad</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Fecha Nac.</th>
            <th>Estado</th>
            <th>Valor</th>
            <th>Fecha Registro</th>
            <th>Acciones</th>
        </tr>
        <?php while($row = $resultado->fetch_assoc()): ?> <!-- hace un llamado a todos los registros y los mete en cada fila-->
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['nombres'] ?></td>
            <td><?= $row['apellidos'] ?></td>
            <td><?= $row['identidad'] ?></td>
            <td><?= $row['telefono'] ?></td>
            <td><?= $row['direccion'] ?></td>
            <td><?= $row['fecha_nacimiento'] ?></td>
            <td><?= $row['estado'] ? 'Activo' : 'Inactivo' ?></td>
            <td><?= $row['valor'] ?></td>
            <td><?= $row['fecha_registro'] ?></td>
             <td>
                <form action="" method="POST" style="display:inline;">
                 <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
                 <button type="submit" id="button_edit" >Editar</button>
                </form>


                <form action="controller/delete.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro?');">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <button type="submit" id="button_delete" >Eliminar</button>
                </form>
              </td>
        </tr>
         <?php endwhile; ?>
    </table>

    </div>
  </section>
</main>

</body>

      <script>
  const modal = document.getElementById("modal");
  const btn = document.getElementById("openModalBtn");
  const span = document.getElementsByClassName("close")[0];

  // Abrir modal para crear cliente
  btn.onclick = function() {
    modal.style.display = "block";
  }

  // Cerrar modal con la X
  span.onclick = function() {
    modal.style.display = "none";
    window.location.href = "index.php"; // resetea el formulario al cerrar
  }

  // Cerrar modal haciendo clic fuera
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
      window.location.href = "index.php"; // resetea el formulario al cerrar
    }
  }
</script>

</html>
