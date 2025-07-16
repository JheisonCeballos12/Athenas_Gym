<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="../styles/login.css">
</head>
<body>

  <!-- Fondo oscuro del modal -->
  <div class="modal">

    <!-- Contenedor del formulario -->
    <div class="modal-login">
      <h2>𝐋𝐎𝐆𝐈𝐍</h2>
      <form action="../controller_login/funtionsLogin.php" method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" id="usuario" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" id="contrasena" required>

        <button type="submit">Ingresar</button>
      </form>
    </div>
  </div>

</body>
</html>


