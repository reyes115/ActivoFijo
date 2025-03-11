<?php
session_start();
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
  session_destroy();
  // Redireccionar al usuario a la página de denegado
  header( "Location: denegado" );
  exit; // También puedes usar die en lugar de exit
}
if ( empty($_POST[ 'propietario' ])|| empty($_POST[ 'id_auto' ])) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/historial.php' );


$codeqr = $_POST['qrcode'];
$id_auto = $_POST['id_auto'];
$propietario = $_POST['propietario'];
$claveVehicular = trim($_POST['claveVehicular']);
$factura = trim($_POST['factura']);
$vin = trim($_POST['vin']);
$marca = trim($_POST['marca']);
$modelo = trim($_POST['modelo']);
$tipo = trim($_POST['tipo']);
$transmision = $_POST['transmision'];
$color = trim($_POST['color']);
$combustible = trim($_POST['combustible']);
$numMotor = trim($_POST['numMotor']);
$placas = trim($_POST['placas']);
$color_engomado = $_POST['color_engomado'];
$tarjetaCirculacion = trim($_POST['tarjetaCirculacion']);
$vencimientoTarjeta = $_POST['vencimientoTarjeta'];
$estadoCirculacion = trim($_POST['estadoCirculacion']);
$estatus = $_POST['estatus'];
$estatusVerificacion = $_POST['estatusVerificacion'];
$vencimientoVerificacion = $_POST['vencimientoVerificacion'];
$usuarioAsignado = $_POST['usuarioAsignado'];
$observaciones = $_POST['observaciones'];



// Consulta preparada para obtener el código del propietario
$stmt = $conexion->prepare( "SELECT codigo FROM propietarios WHERE id_propietario = ?" );
$stmt->bind_param( "i", $propietario );
$stmt->execute();
$stmt->bind_result( $pro );
$stmt->fetch();
$stmt->close();

// Consulta preparada para obtener el número de la empresa
$stmt = $conexion->prepare( "SELECT empresa_id_empresa FROM personal LEFT JOIN departamentos ON id_depar = id_depa WHERE id_personal = ?" );
$stmt->bind_param( "i", $usuarioAsignado );
$stmt->execute();
$stmt->bind_result( $dep );
$stmt->fetch();
$stmt->close();

// Complementos para el código de registro
$ceros = "0";
$auto = "TRA";

// Obtención del valor continuo
$stmt = $conexion->prepare( "select `codigo`,`personal_id_personal`,cons FROM `autos` WHERE `id_autos` =?" );
$stmt->bind_param( "i", $id_auto);
$stmt->execute();
$fila = $stmt->get_result()->fetch_assoc();
$doc = $fila['codigo'];
$num = $fila['cons'];
$stmt->close();

if ( $num < 9 ) {
    $mas = $num;
    $cons = "000" . $mas;
} else if ( $num >= 9 AND $num < 99 ) {
	
    $mas = $num;
    $cons = "00" . $mas;
	
} else if ( $num >= 99 AND $num < 999 ) {	
    $mas = $num;
    $cons = "0" . $mas;
} else if ( $num >= 999 AND $num < 9999 ) {
    $mas = $num;
    $cons = $mas;
}

$codigo = $pro . $ceros . $dep . $auto . $cons;


$dire = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/autos";

// Renombrar el archivo si el código ha cambiado
if ( $codigo != $doc ) {
    $result = rename( "$dire/$doc", "$dire/$codigo" );
	$stmt= $conexion-> prepare ("UPDATE `rutas` SET `codigo_ruta` = ? WHERE `rutas`.`id_ruta` = ?;");
	$stmt->bind_param("ss",$codigo,$id_auto);
	$stmt->execute();
	$stmt->close();
}

if ($usuarioAsignado != $fila['personal_id_personal']){
    $stmt = $conexion->prepare("INSERT INTO `previous_devices`(`type`, `id_before`, `id_after`, `id_devices`) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $tipo_dispositivo, $fila['personal_id_personal'], $usuarioAsignado, $id_auto);
    $tipo_dispositivo = "auto"; // Asigna el tipo de dispositivo aquí
    $stmt->execute();
    $stmt->close();
}

$stmt =$conexion->prepare("
UPDATE
    `autos`
SET
    `id_propietario` = ?,
    `codigo` = ?,
    `claveVehicular` = ?,
    `vin` = ?,
    `factura` = ?,
    `marca` = ?,
    `modelo` = ?,
    `tipo` = ?,
    `transmision` = ?,
    `color` = ?,
    `combustible` = ?,
    `no_motor` = ?,
    `placas` = ?,
    `color_engomado` = ?,
    `tarjeta` = ?,
    `fin_tarjeta` = ?,
    `estado_placa` = ?,
    `estatus` = ?,
    `EstatusVerificacion` = ?,
    `VencVerificacion` = ?,
    `personal_id_personal` = ?,
    `obs` = ?
WHERE
    `id_autos` = ?
");

$stmt->bind_param("sssssssssssssssssssssss",$propietario, $codigo, $claveVehicular, $vin, $factura, $marca, $modelo, $tipo, $transmision, $color, $combustible, $numMotor, $placas, $color_engomado, $tarjetaCirculacion, $vencimientoTarjeta, $estadoCirculacion, $estatus, $estatusVerificacion, $vencimientoVerificacion, $usuarioAsignado, $observaciones, $id_auto);
//variables para guardar historial
$usu_usuario = $_SESSION[ 'user_name' ];
$accion = "Edito el registro del automovil: " . $codigo;

// Ejecutar la consulta
if ( $stmt->execute() ) {
	
	 insert_history( $conexion, $usu_usuario, $accion );
   
echo "<script>window.history.back(); location.reload();</script>";
exit();
}else {
    // Manejo de errores en caso de falla en la inserción
    echo "<script>location.href='error_page'</script>";

}

// Cerrar la consulta preparada
$stmt->close();
?>