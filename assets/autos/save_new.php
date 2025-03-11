<?php
session_start();

// Verificar si la variable de sesión 'usuario' está definida y no es nula
if (!isset($_SESSION['user_ceers']) || $_SESSION['user_ceers'] === null || $_SESSION['activo'] == 0) {
    session_destroy();
    header("Location: denegado");
    exit;
}

// Función para limpiar y validar datos de entrada
function clean_input($data) {
    return htmlspecialchars(trim($data));
}

// Validar que se haya enviado el propietario
if (empty($_POST['propietario'])) {
    header("Location: inicio");
    exit;
}

include($_SERVER['DOCUMENT_ROOT'] . '/conexion.php');
include($_SERVER['DOCUMENT_ROOT'] . '/assets/historial.php');

// Limpieza de datos de entrada
$propietario = clean_input($_POST['propietario']);
$claveVehicular = clean_input($_POST['claveVehicular']);
$factura = clean_input($_POST['factura']);
$vin = clean_input($_POST['vin']);
$marca = clean_input($_POST['marca']);
$modelo = clean_input($_POST['modelo']);
$tipo = clean_input($_POST['tipo']);
$transmision = clean_input($_POST['transmision']);
$color = clean_input($_POST['color']);
$combustible = clean_input($_POST['combustible']);
$numMotor = clean_input($_POST['numMotor']);
$placas = clean_input($_POST['placas']);
$color_engomado = clean_input($_POST['color_engomado']);
$tarjetaCirculacion = clean_input($_POST['tarjetaCirculacion']);
$vencimientoTarjeta = clean_input($_POST['vencimientoTarjeta']);
$estadoCirculacion = clean_input($_POST['estadoCirculacion']);
$estatus = clean_input($_POST['estatus']);
$estatusVerificacion = clean_input($_POST['estatusVerificacion']);
$vencimientoVerificacion = clean_input($_POST['vencimientoVerificacion']);
$usuarioAsignado = clean_input($_POST['usuarioAsignado']);
$observaciones = clean_input($_POST['observaciones']);

// Consultas preparadas para obtener información
$stmt_propietario = $conexion->prepare("SELECT codigo FROM propietarios WHERE id_propietario = ?");
$stmt_propietario->bind_param("i", $propietario);
$stmt_propietario->execute();
$stmt_propietario->bind_result($pro);
$stmt_propietario->fetch();
$stmt_propietario->close();

$stmt_usuario = $conexion->prepare("SELECT empresa_id_empresa FROM personal LEFT JOIN departamentos ON id_depar = id_depa WHERE id_personal = ?");
$stmt_usuario->bind_param("i", $usuarioAsignado);
$stmt_usuario->execute();
$stmt_usuario->bind_result($dep);
$stmt_usuario->fetch();
$stmt_usuario->close();

// Obtención del valor continuo
$stmt = $conexion->prepare( "SELECT MAX(cons) as cons FROM autos LIMIT 1" );
$stmt->execute();
$stmt->bind_result( $num );
$stmt->fetch();
$stmt->close();

// Generar código de auto
$cons = sprintf("%04d", $num + 1); // Genera un número de 4 dígitos con relleno de ceros a la izquierda
$codigo = $pro . "0" . $dep . "TRA" . $cons;

// Guardar archivos
$directorio = $_SERVER['DOCUMENT_ROOT'] . "/uploads/autos/$codigo";
if (!file_exists($directorio)) {
    mkdir($directorio, 0777, true);
}

// Guardar documentos
$documentos = array("imagen", "tarjeta", "facturas", "identificacion", "tenencia", "verificacion", "licencia", "servicio", "politicas");
foreach ($documentos as $documento) {
    if (isset($_FILES[$documento]) && $_FILES[$documento]['error'] === UPLOAD_ERR_OK) {
        $filename = basename($_FILES[$documento]['name']);
        $destino = $directorio . '/' . $filename;
        move_uploaded_file($_FILES[$documento]['tmp_name'], $destino);
        ${'r_' . $documento} = $filename;
    }
}

// Generar QR
$QRKey = uniqid();
$t_qr = "autos";
$urlQr = "https://ceers.innovet.com.mx/ver_auto" . $QRKey;
include($_SERVER['DOCUMENT_ROOT'] . '/assets/generartor_qr.php');

$sql = "INSERT INTO `autos`(
    `id_propietario`, `codigo`, `claveVehicular`, `vin`, `factura`, `marca`, `modelo`, `tipo`, `transmision`, `color`, `combustible`, `no_motor`, `placas`,`color_engomado`, `tarjeta`, `fin_tarjeta`, `estado_placa`, `estatus`, `EstatusVerificacion`, `VencVerificacion`,  `personal_id_personal`, `obs`, `QRKey`, `cons`, `activo`)
VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,1)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssssssssssssssssssssss", $propietario, $codigo, $claveVehicular, $vin, $factura, $marca, $modelo, $tipo, $transmision, $color, $combustible, $numMotor, $placas, $color_engomado, $tarjetaCirculacion, $vencimientoTarjeta, $estadoCirculacion, $estatus, $estatusVerificacion, $vencimientoVerificacion,   $usuarioAsignado, $observaciones, $QRKey, $cons);

$usu_usuario = $_SESSION['user_name'];
$accion = "Registro nuevo auto: " . $codigo;

if ($stmt->execute()) {
    insert_history($conexion, $usu_usuario, $accion);
 $stmt_rutas = $conexion->prepare("INSERT INTO `rutas`(`codigo_ruta`, `r_imagen`, `r_tarjeta`, `r_factura`, `r_identificacion`, `r_tenencia`, `r_verificacion`, `r_licencia`, `r_politicas`)
VALUES(?,?,?,?,?,?,?,?,?)");
	
	// Aquí cambiamos los nombres de las variables por los nombres de los archivos guardados
$r_imagen = isset($r_imagen) ? $r_imagen : null;
$r_tarjeta = isset($r_tarjeta) ? $r_tarjeta : null;
$r_facturas = isset($r_facturas) ? $r_facturas : null;
$r_identificacion = isset($r_identificacion) ? $r_identificacion : null;
$r_tenencia = isset($r_tenencia) ? $r_tenencia : null;
$r_verificacion = isset($r_verificacion) ? $r_verificacion : null;
$r_licencia = isset($r_licencia) ? $r_licencia : null;
$r_politicas = isset($r_politicas) ? $r_politicas : null;

$stmt_rutas->bind_param("sssssssss", $codigo, $r_imagen, $r_tarjeta, $r_facturas, $r_identificacion, $r_tenencia, $r_verificacion, $r_licencia,$r_politicas);



$stmt_rutas->execute();
$stmt_rutas->close();
    header("Location: autos");
} else {
    echo "Error al insertar el registro.";
}

$stmt->close();
$conexion->close();

echo $codigo;
?>
