<?php require_once("../connection/connection.php");?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Usuario</title>
  <link rel="stylesheet" href="../styles/login.css">
</head>
<body>
  <div class="modal">
    <div class="modal-login">
      <h2>Registrar Usuario</h2>
      <form action="../controller_login/create_user.php" method="POST">
        <label for="nombres">Nombre completo:</label>
        <input type="text" name="nombres" id="nombres" required>

        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" id="usuario" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" id="contrasena" required>

        <label for="pin_seguridad">PIN de seguridad:</label>
        <input type="text" name="pin_seguridad" id="pin_seguridad" maxlength="10" required>

        <button type="submit">Registrar</button>
      </form>
    </div>
  </div>
</body>
</html>
