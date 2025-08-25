<?php
//--------------------------- INICIO DE SESIÓN ---------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login/login.php");
    exit();
}

//--------------------------- CONEXIÓN ---------------------------
include("../connection/connection.php");

//--------------------------- ACTUALIZAR MENSUALIDADES ---------------------------
$sql = "UPDATE clientes 
        SET mensualidad = 
            CASE 
                WHEN CURDATE() <= fecha_vencimiento THEN 'VIGENTE'
                ELSE 'VENCIDA'
            END";

if (!mysqli_query($conn, $sql)) {
    echo "Error al actualizar mensualidades: " . mysqli_error($conn);
}

//--------------------------- FUNCIÓN EDITAR CLIENTE ---------------------------
$cliente = null;
if (isset($_POST['edit_id'])) {
    $id = intval($_POST['edit_id']);
    $sql_edit = "SELECT * FROM clientes WHERE id = $id";
    $result_edit = $conn->query($sql_edit);
    $cliente = $result_edit->fetch_assoc();    
}

//--------------------------- FILTROS ---------------------------
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

//--------------------------- FILTRAR POR ESTADO Y VIGENCIA ---------------------------
function pasarFiltros($row, $estado_filtro, $vigencia_filtro) {
    $hoy = date('Y-m-d');
    $mensualidadEstado = '—';

    if (!empty($row['fecha_vencimiento'])) {
        $mensualidadEstado = ($row['fecha_vencimiento'] >= $hoy) ? 'VIGENTE' : 'VENCIDA';
    }

    // filtro estado
    if ($estado_filtro === '1' && !$row['estado']) return false;
    if ($estado_filtro === '0' && $row['estado']) return false;

    // filtro vigencia
    if ($vigencia_filtro === 'vigente' && $mensualidadEstado !== 'VIGENTE') return false;
    if ($vigencia_filtro === 'vencida' && $mensualidadEstado !== 'VENCIDA') return false;

    return ['estado' => $mensualidadEstado];
}

//--------------------------- ÚLTIMAS VENTAS POR CLIENTE ---------------------------
$sql_ventas = "
    SELECT u.cliente_id, u.plan_id, u.valor, p.meses
    FROM inscripciones u
    JOIN planes p ON u.plan_id = p.id
    WHERE u.id IN (
        SELECT MAX(id) FROM inscripciones GROUP BY cliente_id
    )
";
$resultado_ventas = $conn->query($sql_ventas);

//--------------------------- ACTUALIZAR FECHA DE VENCIMIENTO ---------------------------
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

//--------------------------- GRAFICA DE VENTAS ---------------------------
$ventas = [];
while ($v = $resultado_ventas->fetch_assoc()) {
    $ventas[$v['cliente_id']] = [
        'meses' => $v['meses'],
        'valor' => $v['valor']
    ];
}

//--------------------------- CLIENTES Y PLANES PARA MODAL VENTA ---------------------------
$result_clientes = $conn->query("SELECT id, nombres, apellidos FROM clientes");
$result_planes = $conn->query("SELECT id, nombre, valor FROM planes");

//--------------------------- ANULAR PLAN ---------------------------
if (isset($_POST['anular'])) {
    $id_inscripcion = $_POST['id_inscripcion'];

    $sql = "DELETE FROM inscripciones WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_inscripcion);

    if ($stmt->execute()) {
        header("Location: table_inscripciones.php?msg=anulado");
        exit();
    } else {
        echo "❌ Error al anular el plan";
    }
}

//--------------------------- NOTA ---------------------------
// La lógica de cumpleaños se ha movido a un archivo separado:
// controllers/cumpleaneros.php
// para que tu JS pueda hacer fetch() y mostrar los toasts sin romper la página.

?>
