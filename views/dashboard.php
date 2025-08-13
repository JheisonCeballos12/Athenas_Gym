<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /Athenas_Gym/Login/login.php");
 
    exit();
}
include("../connection/connection.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reportes Athenas Gym</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../styles/style_tables.css" />
</head>
<body>

  <!-- SIDEBAR --------------------------------------------------------------------------------------------------------->
   <?php include("../partials/sidebar.php"); ?>

    <!-- Frases motivacionales -->
    <div class="motivational-container">
      <p id="motivational-text">¡Nunca te rindas!</p>
    </div>

 <!--TOAST-->
<?php include("../partials/toast.php"); ?>

<script src="../scripts/index.js"></script>

</body>
</html>