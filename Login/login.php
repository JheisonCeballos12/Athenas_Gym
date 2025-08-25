<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="../styles/login.css">
</head>
<body>

  <div class="modal">
    <div class="modal-login">
      <h2>LOGIN</h2>
      <form action="../controller_login/funtionsLogin.php" method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" id="usuario" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" id="contrasena" required>

        <button type="submit">Ingresar</button>
        <div style="text-align:center; margin-top: 15px;">
    </div>

      </form>

      <p class="update_password">
        <a href="reset_password_views.php">¿Olvidaste tu contraseña?</a>
      </p>
    </div>
  </div>

  <?php include("../partials/toast.php"); ?>
</body>
</html>



