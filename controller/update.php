<?php
include("../connection/connection.php");

if (!isset($_POST['id'])) {
    echo "ID no recibido.";
    exit;
}

$id = $_POST['id'];
$sql = "SELECT * FROM registros WHERE id = $id";
$resultado = $conn->query($sql);
$fila = $resultado->fetch_assoc();
?>

<h2>Editar Cliente</h2>                                                                                     
<form method="POST" action="update_save.php">
    <input type="hidden" name="id" value="<?= $fila['id'] ?>">
    
    Nombre: <input type="text" name="nombres" value="<?= $fila['nombres'] ?>"><br>
    Apellido: <input type="text" name="apellidos" value="<?= $fila['apellidos'] ?>"><br>
    Identidad: <input type="number" name="identidad" value="<?= $fila['identidad'] ?>"><br>
    Teléfono: <input type="number" name="telefono" value="<?= $fila['telefono'] ?>"><br>
    Dirección: <input type="text" name="direccion" value="<?= $fila['direccion'] ?>"><br>
    Fecha Nacimiento: <input type="date" name="fecha_nacimiento" value="<?= $fila['fecha_nacimiento'] ?>"><br>
    Estado: <input type="checkbox" name="estado" value="1" <?= $fila['estado'] ? 'checked' : '' ?>><br>
    Valor: <input type="number" name="valor" value="<?= $fila['valor'] ?>"><br>

    <input type="submit" name="actualizar" value="Guardar Cambios">
</form>


