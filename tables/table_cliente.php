<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../Login/login.php");
    exit();
}

include ("../connection/connection.php");

$sql = "UPDATE clientes 
        SET mensualidad = 
            CASE 
                WHEN CURDATE() <= fecha_vencimiento THEN 'VIGENTE'
                ELSE 'VENCIDA'
            END";

if (mysqli_query($conn, $sql)) {
    // Éxito, puedes omitir este mensaje si lo quieres silencioso
    // echo "Mensualidades actualizadas correctamente.";
} else {
    echo "Error al actualizar mensualidades: " . mysqli_error($conn);
}

//------------------------------------------------------------------------------------------------


// FUNCIÓN EDITAR CLIENTE
$cliente = null;
if (isset($_POST['edit_id'])) {
    $id = intval($_POST['edit_id']);
    $sql_edit = "SELECT * FROM clientes WHERE id = $id";
    $result_edit = $conn->query($sql_edit);
    $cliente = $result_edit->fetch_assoc();
}
//------------------------------------------------------------------------------------------------

// FILTRO DE BÚSQUEDA
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
$estado_filtro = isset($_GET['estado_filtro']) ? $_GET['estado_filtro'] : '';
$vigencia_filtro = isset($_GET['vigencia_filtro']) ? $_GET['vigencia_filtro'] : '';


$sql = "SELECT * FROM clientes WHERE 1=1";

if ($busqueda !== '') {
    $busqueda = $conn->real_escape_string($busqueda);
    $sql .= " AND (
        nombres LIKE '%$busqueda%' OR
        apellidos LIKE '%$busqueda%' OR
        identidad LIKE '%$busqueda%' OR
        telefono LIKE '%$busqueda%'
    )";
}

if ($estado_filtro !== '') {
    $estado_filtro = intval($estado_filtro);
    $sql .= " AND estado = $estado_filtro";
}

$resultado = $conn->query($sql);

//------------------------------------------------------------------------------------------------

// ÚLTIMAS VENTAS POR CLIENTE
$sql_ventas = "
    SELECT u.cliente_id, u.plan_id, u.valor, p.meses
    FROM inscripciones u
    JOIN planes p ON u.plan_id = p.id
    WHERE u.id IN (
        SELECT MAX(id) FROM inscripciones GROUP BY cliente_id
    )
";
$resultado_ventas = $conn->query($sql_ventas);

//------------------------------------------------------------------------------------------------

// ACTUALIZAR FECHA DE VENCIMIENTO DE CADA CLIENTE
$sql_fecha = "
    SELECT c.id, MAX(i.fecha_venta) AS ultima_fecha, p.meses
    FROM clientes c
    JOIN inscripciones i ON c.id = i.cliente_id
    JOIN planes p ON i.plan_id = p.id
    GROUP BY c.id
";

$result_fecha = $conn->query($sql_fecha);

while ($fila = $result_fecha->fetch_assoc()) {
    $clienteId = $fila['id'];
    $fechaInscripcion = $fila['ultima_fecha'];
    $meses = (int)$fila['meses'];

    if ($fechaInscripcion && $meses > 0) {
        $fechaVencimiento = date('Y-m-d', strtotime("$fechaInscripcion +$meses months"));
        $conn->query("UPDATE clientes SET fecha_vencimiento = '$fechaVencimiento' WHERE id = $clienteId");
    }
}


//------------------------------------------------------------------------------------------------

// MAPEAR VENTAS
$ventas = [];
while ($v = $resultado_ventas->fetch_assoc()) {
    $ventas[$v['cliente_id']] = [
        'meses' => $v['meses'],
        'valor' => $v['valor']
    ];
}

//------------------------------------------------------------------------------------------------

// CLIENTES Y PLANES PARA MODAL VENTA
$result_clientes = $conn->query("SELECT id, nombres, apellidos FROM clientes");
$result_planes = $conn->query("SELECT id, nombre, valor FROM planes");

//------------------------------------------------------------------------------------------------

//CUMPLEAÑOS 
if (!isset($_SESSION['cumple_felicitado'])) {
   // include("../controllers/cumpleaneros.php"); // Este archivo debe buscar cumpleañeros de hoy
    $_SESSION['cumple_felicitado'] = true;
}


$cumpleaneros = [];
$sql_c = "SELECT nombres, apellidos, fecha_nacimiento FROM clientes";
$resultado_c = $conn->query($sql_c);

if ($resultado_c->num_rows > 0) {
    $hoy = date('m-d');
    while ($row = $resultado_c->fetch_assoc()) {
        $fechaNac = date('m-d', strtotime($row['fecha_nacimiento']));
        if ($hoy === $fechaNac) {
            $cumpleaneros[] = $row['nombres'] . ' ' . $row['apellidos'];
        }
    }
}

?>


