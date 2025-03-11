<?php
session_start();
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
  session_destroy();
  // Redireccionar al usuario a la página de denegado
  header( "Location: denegado" );
  exit; // También puedes usar die en lugar de exit
}
if ( empty($_POST[ 'propietario' ])|| empty($_POST[ 'id_dispositivos' ])) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/historial.php' );

$codeqr = $_POST[ 'qrcode' ];
$id_dispositivos= $_POST[ 'id_dispositivos' ];
    $propietario= $_POST[ 'propietario' ] ;
        $no_serie = trim( $_POST[ 'no_serie' ] );
        $estado =  $_POST[ 'estado' ] ;
        $costo = trim( $_POST[ 'costo' ] );
        $fecha = $_POST[ 'fecha'] ;
        $usuarioAsignado =  $_POST[ 'usuarioAsignado' ] ;
        $caracteristicas = trim($_POST[ 'caracteristicas' ] );

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
$dispositivos = "DIA";

// Obtención del valor continuo
$stmt = $conexion->prepare( "select  codigo, personal_id,cons  from perifericos  WHERE id_perifericos =?" );
$stmt->bind_param( "i", $id_dispositivos);
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

$codigo = $pro . $ceros . $dep . $dispositivos . $cons;


$dire = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/dispositivos";

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
    $stmt->bind_param("ssss", $tipo_dispositivo, $fila['personal_id'], $usuarioAsignado, $id_dispositivos);
    $tipo_dispositivo = "dispositivos"; // Asigna el tipo de dispositivo aquí
    $stmt->execute();
    $stmt->close();
}
$fecha_compra = isset($fecha) ? $fecha : '';

// Si la fecha de compra está vacía, utiliza la fecha actual
if (empty($fecha_compra) || !strtotime($fecha_compra)) {
    $fecha_compra = date('Y-m-d'); // Obtener la fecha actual
}
$stmt = $conexion->prepare( "
UPDATE
    `perifericos`
SET
    `codigo` = ?,
    `no_serie` = ?,
    `caracteristicas` = ?,
    `Estado` = ?,
    `fecha` = ?,
    `costo` = ?,
    `personal_id` = ?,
    `id_propietario` = ?
WHERE
    `id_perifericos` = ?
" );

$stmt->bind_param( "sssssssss",
    $codigo, $no_serie, $caracteristicas, $estado, $fecha_compra, $costo, $usuarioAsignado, $propietario, $id_dispositivos);

//variables para guardar historial
$usu_usuario = $_SESSION[ 'user_name' ];
$accion = "Edito el registro del equipo alterno: " . $codigo;

// Ejecutar la consulta
if ( $stmt->execute() ) {
   

    insert_history( $conexion, $usu_usuario, $accion );
    echo "<script>location.href='ver_dispositivos$codeqr'</script>";

} else {
    // Manejo de errores en caso de falla en la inserción
    echo "<script>location.href='error_page'</script>";

}

// Cerrar la consulta preparada
$stmt->close();

?>
