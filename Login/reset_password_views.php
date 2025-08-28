<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar Contraseña</title>
  <link rel="stylesheet" href="../styles/login.css">
</head>
<body>
  <div class="modal">
    <div class="modal-login">
      <h2>Cambiar Contraseña</h2>
      <form action="../controller_login/reset_password.php" method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" id="usuario" required>

        <label for="pin_seguridad">Pin de seguridad:</label>
        <input type="password" name="pin_seguridad" id="pin_seguridad" maxlength="10" required>

        <label for="contrasena">Nueva contraseña:</label>
        <input type="password" name="contrasena" id="contrasena" required>

        <label for="contrasena2">Confirmar contraseña:</label>
        <input type="password" name="contrasena2" id="contrasena2" required>
        <br><br>

        <button type="submit">Actualizar</button>
        <a class="return_login" href="../Login/login.php">volver</a>
        
      </form>
    </div>
  </div>
</body>
</html>
