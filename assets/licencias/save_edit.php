<?php
session_start();
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
  session_destroy();
  // Redireccionar al usuario a la página de denegado
  header( "Location: denegado" );
  exit; // También puedes usar die en lugar de exit
}
if ( empty($_POST[ 'id_licencias' ])|| empty($_POST[ 'nombre_licencias' ])) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/historial.php' );

 $id_licencias = trim( $_POST[ 'id_licencias' ] );
 $nombre_licencias = trim( $_POST[ 'nombre_licencias' ] );
        $costo = trim( $_POST[ 'costo' ] );
        $fecha_inicio = trim( $_POST[ 'fecha_inicio' ] );
        $fecha_fin = trim( $_POST[ 'fecha_fin' ] );
        $clave = trim( $_POST[ 'clave' ] );
        $tipo = $_POST[ 'tipo' ] ;
        $limite_usuarios = trim( $_POST[ 'limite_usuarios' ] );
        $provedor= trim( $_POST[ 'provedor' ] );
        $observaciones= trim( $_POST[ 'observaciones' ] );

// Obtención del valor continuo
$stmt = $conexion->prepare( "SELECT * FROM `licencias` WHERE `id_licencias` =?" );
$stmt->bind_param( "i", $id_licencias );
$stmt->execute();
 $row = $stmt->get_result()->fetch_assoc();
$doc = $row[ "nombre_licencias" ];
$stmt->close();



$rutaA = $nombre_licencias;


$dire = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/licencias";

// Renombrar el archivo si el código ha cambiado
if ( $rutaA != $doc ) {
    $result = rename( "$dire/$doc", "$dire/$rutaA" );
}

$directorio = "$dire/$rutaA"; // Ruta donde se guardarán los archivos

foreach ( $_FILES[ "archivos" ][ 'tmp_name' ] as $key => $tmp_name ) {
    if ( $_FILES[ "archivos" ][ "name" ][ $key ] ) {
        $filename = $_FILES[ "archivos" ][ "name" ][ $key ];
        $source = $_FILES[ "archivos" ][ "tmp_name" ][ $key ];
        $target_path = "$directorio/$filename";
        move_uploaded_file( $source, $target_path );

    }
}



$stmt = $conexion->prepare( "
UPDATE
    `licencias`
SET
    `nombre_licencias` = ?,
    `fecha_inicio` = ?,
    `fecha_fin` = ?,
    `clave` = ?,
    `limite_usuarios` = ?,
    `costo` = ?,
    `tipo` = ?,
    `observaciones` = ?,
    `provedor` = ?
WHERE
    `id_licencias`= ?
" );

$stmt->bind_param( "sssssssssi", $nombre_licencias, $fecha_inicio, $fecha_fin,$clave, $limite_usuarios,$costo, $tipo, $observaciones, $provedor, $id_licencias
);

//variables para guardar historial
$usu_usuario = $_SESSION[ 'user_name' ];
$accion = "Edito el registro de : " . $rutaA;

// Ejecutar la consulta
if ( $stmt->execute() ) {

    insert_history( $conexion, $usu_usuario, $accion );
    echo "<script>location.href='ver_licencias$id_licencias'</script>";

} else {
    // Manejo de errores en caso de falla en la inserción
    echo "<script>location.href='error_page'</script>";

}

// Cerrar la consulta preparada
$stmt->close();

?>
