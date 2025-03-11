<?php
session_start();
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
  session_destroy();
  // Redireccionar al usuario a la página de denegado
  header( "Location: denegado" );
  exit; // También puedes usar die en lugar de exit
}
if ( empty($_POST[ 'id_personal' ])|| empty($_POST[ 'nombre' ])) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/historial.php' );

$id_personal=$_POST['id_personal'];
$nombre = trim( $_POST[ 'nombre' ] );
$apaterno = trim( $_POST[ 'aPaterno' ] );
$amaterno = trim( $_POST[ 'aMaterno' ] );
$telefono = trim( $_POST[ 'telefono' ] );
$email = trim( $_POST[ 'email' ] );
$numColaborador = trim( $_POST[ 'numColaborador' ] );
$depart = $_POST[ 'departamento' ] ;


// Obtención del valor continuo
$stmt = $conexion->prepare( "SELECT * FROM `personal` WHERE `id_personal` =?" );
$stmt->bind_param( "i", $id_personal );
$stmt->execute();
 $row = $stmt->get_result()->fetch_assoc();
$doc = $row[ "nombre" ] . ' ' . $row[ "a_paterno" ] . ' ' . $row[ "a_materno" ];
$stmt->close();



$rutaA = $nombre . ' ' . $apaterno . ' ' . $amaterno;


$dire = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/personal";

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
     `personal`
SET
    `no_empleado` = ?,
    `nombre` = ?,
    `a_paterno` = ?,
    `a_materno` = ?,
    `email` = ?,
    `phone` = ?,
    `id_depar` = ?
WHERE
    `id_personal`= ?
" );

$stmt->bind_param( "sssssssi",
   $numColaborador, $nombre, $apaterno, $amaterno, $email, $telefono, $depart, $id_personal
);

//variables para guardar historial
$usu_usuario = $_SESSION[ 'user_name' ];
$accion = "Edito el registro de : " . $rutaA;

// Ejecutar la consulta
if ( $stmt->execute() ) {

    insert_history( $conexion, $usu_usuario, $accion );
    echo "<script>location.href='ver_personal$id_personal'</script>";

} else {
    // Manejo de errores en caso de falla en la inserción
    echo "<script>location.href='error_page'</script>";

}

// Cerrar la consulta preparada
$stmt->close();

?>
