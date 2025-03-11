<?php
session_start();
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
  session_destroy();
  // Redireccionar al usuario a la página de denegado
  header( "Location: denegado" );
  exit; // También puedes usar die en lugar de exit
}
if ( empty($_POST[ 'propietario' ])|| empty($_POST[ 'id_compu' ])) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/licencias/sql_licencias.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/historial.php' );

$codeqr = $_POST[ 'qrcode' ];
$id_compu = $_POST[ 'id_compu' ];
$propietario = $_POST[ 'propietario' ];
$tipo = $_POST[ 'tipo' ];
$costo = trim( $_POST[ 'costo' ] );
$fecha_compra = $_POST[ 'fecha_compra' ];
$fecha_sym = $_POST[ 'fecha_sym' ];
$cpu = trim( $_POST[ 'cpu' ] );
$ram = trim( $_POST[ 'ram' ] );
$almacenamiento = trim( $_POST[ 'almacenamiento' ] );
$marca = trim( $_POST[ 'marca' ] );
$modelo = trim( $_POST[ 'modelo' ] );
$color = trim( $_POST[ 'color' ] );
$no_serie = trim( $_POST[ 'no_serie' ] );
$cargador = trim( $_POST[ 'cargador' ] );
$usuarioAsignado = $_POST[ 'usuarioAsignado' ];
$accesorios = $_POST[ 'accesorios' ];
$observaciones = $_POST[ 'observaciones' ];
$estado = $_POST[ 'estado' ];
$so = $_POST[ 'so' ];
$office = $_POST[ 'office' ];
$antivirus = $_POST[ 'antivirus' ];
$adicional = trim( $_POST[ 'adicional' ] );

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
$computo = "COM";

// Obtención del valor continuo
$stmt = $conexion->prepare( "select  codigo, personal_id,cons, fecha_sym from computadora  WHERE id_compu =?" );
$stmt->bind_param( "i", $id_compu );
$stmt->execute();
$fila = $stmt->get_result()->fetch_assoc();
$doc = $fila['codigo'];
$num = $fila['cons'];
$fecha_sym_anterior = $fila['fecha_sym'];
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

$codigo = $pro . $ceros . $dep . $computo . $cons;


$dire = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/computo";

// Renombrar el archivo si el código ha cambiado
if ( $codigo != $doc ) {
    $result = rename( "$dire/$doc", "$dire/$codigo" );
}

$directorio = "$dire/$codigo"; // Ruta donde se guardarán los archivos

foreach ( $_FILES[ "archivos" ][ 'tmp_name' ] as $key => $tmp_name ) {
    if ( $_FILES[ "archivos" ][ "name" ][ $key ] ) {
        $filename = $_FILES[ "archivos" ][ "name" ][ $key ];
        $source = $_FILES[ "archivos" ][ "tmp_name" ][ $key ];
        $target_path = "$directorio/$filename";
        move_uploaded_file( $source, $target_path );

    }
}


if ($usuarioAsignado != $fila['personal_id']){
    $stmt = $conexion->prepare("INSERT INTO `previous_devices`(`type`, `id_before`, `id_after`, `id_devices`) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $tipo_dispositivo, $fila['personal_id'], $usuarioAsignado, $id_compu);
    $tipo_dispositivo = "computo"; // Asigna el tipo de dispositivo aquí
    $stmt->execute();
    $stmt->close();
}

if ($fecha_sym != $fecha_sym_anterior){
	$note = ", note = 0";
	
}else{
	$note="";
}

$stmt = $conexion->prepare( "
UPDATE
    `computadora`
SET
    `codigo` = ?,
    `id_propietario` = ?,
    `tipo` = ?,
    `cpu` = ?,
    `ram` = ?,
    `almacenamiento` = ?,
    `marca` = ?,
    `modelo` = ?,
    `color` = ?,
    `no_serie` = ?,
    `cargador` = ?,
    `costo` = ?,
    `fecha_compra` = ?,
    `estado` = ?,
    `accesorios` = ?,
    `adicional` = ?,
    `observaciones` = ?,
    `fecha_sym` = ?,
    `personal_id` = ?
	$note
WHERE
    id_compu = ?
" );

$stmt->bind_param( "ssssssssssssssssssii",
    $codigo, $propietario, $tipo, $cpu, $ram, $almacenamiento,
    $marca, $modelo, $color, $no_serie, $cargador, $costo,
    $fecha_compra, $estado, $accesorios, $adicional, $observaciones,
    $fecha_sym, $usuarioAsignado, $id_compu
);

//variables para guardar historial
$usu_usuario = $_SESSION[ 'user_name' ];
$accion = "Edito el registro del equipo de computo: " . $codigo;

// Ejecutar la consulta
if ( $stmt->execute() ) {
    // Llamar a la función insertLicencia para insertar las licencias
    if ( !empty( $so ) ) {
        actualizarLicencia( $conexion, $id_compu, 3, $so, 999 );
    }
    if ( !empty( $office ) ) {
        actualizarLicencia( $conexion, $id_compu, 2, $office, 999 );
    }
    if ( !empty( $antivirus ) ) {
        actualizarLicencia( $conexion, $id_compu, 1, $antivirus, 999 );
    }

    insert_history( $conexion, $usu_usuario, $accion );
    echo "<script>location.href='ver_equipo$codeqr'</script>";

} else {
    // Manejo de errores en caso de falla en la inserción
    echo "<script>location.href='error_page'</script>";

}

// Cerrar la consulta preparada
$stmt->close();

?>
