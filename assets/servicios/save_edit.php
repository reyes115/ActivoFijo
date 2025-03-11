<?php
session_start();
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
  session_destroy();
  // Redireccionar al usuario a la página de denegado
  header( "Location: denegado" );
  exit; // También puedes usar die en lugar de exit
}
if ( empty($_POST[ 'id_servicios' ])|| empty($_POST[ 'no_cuenta' ])) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/historial.php' );

 $id_servicios =$_POST[ 'id_servicios' ] ;
  $no_cuenta = trim( $_POST[ 'no_cuenta' ] );
        $proveedores = trim( $_POST[ 'proveedores' ] );
        $fecha_inicio = $_POST[ 'fecha_inicio' ] ;
        $fecha_renova= trim( $_POST[ 'fecha_renova' ] );
        $costo_renova = trim( $_POST[ 'costo_renova' ] );
        $ubicacion = trim( $_POST[ 'ubicacion' ] );
        $detalles = trim($_POST[ 'detalles' ]) ;

// Obtención del valor continuo
$stmt = $conexion->prepare( "SELECT * FROM `servicios` WHERE `id_servicios` =?" );
$stmt->bind_param( "i", $id_servicios );
$stmt->execute();
 $row = $stmt->get_result()->fetch_assoc();
$doc = $row[ "no_cuenta" ];
$stmt->close();



$rutaA = $no_cuenta;


$dire = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/servicios";

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
    `servicios`
SET
   `no_cuenta` = ?,
    `detalles` = ?,
    `fecha_inicio` = ?,
    `fecha_renova` = ?,
    `ubicacion` = ?,
    `costo_renova` = ?,
    `proveedores` = ?
WHERE
    `id_servicios`= ?
" );

$stmt->bind_param( "sssssssi", $no_cuenta, $detalles, $fecha_inicio, $fecha_renova, $ubicacion, $costo_renova, $proveedores, $id_servicios);

//variables para guardar historial
$usu_usuario = $_SESSION[ 'user_name' ];
$accion = "Edito el servicio de : " . $rutaA;

// Ejecutar la consulta
if ( $stmt->execute() ) {

    insert_history( $conexion, $usu_usuario, $accion );
    echo "<script>location.href='ver_servicios$id_servicios'</script>";

} else {
    // Manejo de errores en caso de falla en la inserción
    echo "<script>location.href='error_page'</script>";

}

// Cerrar la consulta preparada
$stmt->close();

?>
