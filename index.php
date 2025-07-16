<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: Login/login.php"); 
    exit();
}
include("connection/connection.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reportes Athenas Gym</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="styles/style_tables.css" />
</head>
<body>
  <div class="layout">
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <img class="logo_athenas" src="images/logo_athenas.png" alt="logo_athenas">
      <nav class="nav-panel">
        <a href="index.php"><i class="fa-solid fa-house"></i> Inicio</a>
        <a href="tables/table_cliente.php"><i class="fa-solid fa-users"></i> Clientes</a>
        <a href="tables/table_plan.php"><i class="fa-solid fa-dumbbell"></i> Planes</a>              <!-- crear un menu para todos los archivos -->
        <a href="tables/report.php"><i class="fa-solid fa-chart-line"></i> Reportes</a>
        <a href="logout.php"><i class="fa-solid fa-door-open"></i> Cerrar sesión</a>
      </nav>
    </aside>
    
    <!-- CONTENT -->
    <div class="content">
      <header class="top-header">
        <h1 class="title_header">𝐀𝐓𝐇𝐄𝐍𝐀𝐒 𝐆𝐘𝐌</h1>
      </header>
</body>
</html>