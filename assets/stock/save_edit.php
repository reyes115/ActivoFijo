<?php
session_start();
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
  session_destroy();
  // Redireccionar al usuario a la página de denegado
  header( "Location: denegado" );
  exit; // También puedes usar die en lugar de exit
}
if ( empty($_POST[ 'propietario' ])|| empty($_POST[ 'id_stock' ])) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/historial.php' );

$codeqr = $_POST[ 'qrcode' ];
$id_stock = $_POST['id_stock'];
$propietario =$_POST['propietario'];
$tipo =trim($_POST['tipo']);
$caracteristicas=trim($_POST['caracteristicas']);
$estado=$_POST['estado'];
$cantidad=$_POST['cantidad'];
$almacenamiento =$_POST['opt'];
$usuarioAsignado =$_POST['usuarioAsignado'];

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
$mob = "MOB";

// Obtención del valor continuo
$stmt = $conexion->prepare( "select  codigo, personal_id,cons  from `stock`  WHERE `id_stock` = ?" );
$stmt->bind_param( "i", $id_stock);
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

$codigo = $pro . $ceros . $dep . $mob . $cons;


$dire = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/stock";

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
    $stmt->bind_param("ssss", $tipo_dispositivo, $fila['personal_id'], $usuarioAsignado, $id_stock);
    $tipo_dispositivo = "mobiliario"; // Asigna el tipo de dispositivo aquí
    $stmt->execute();
    $stmt->close();
}


$stmt = $conexion->prepare( "
UPDATE
    `stock`
SET
`codigo` = ?,
    `tipo` = ?,
    `caracteristicas` = ?,
    `estado` = ?,
    `cantidad` = ?,
    `almacenado` = ?,
    `id_propietario` = ?,
    `personal_id` = ?
WHERE

    `id_stock` = ?
" );

$stmt->bind_param( "sssssssss", $codigo, $tipo, $caracteristicas, $estado, $cantidad, $almacenamiento, $propietario, $usuarioAsignado,$id_stock);

//variables para guardar historial
$usu_usuario = $_SESSION[ 'user_name' ];
$accion = "Edito el registro del mobiliario: " . $codigo;

// Ejecutar la consulta
if ( $stmt->execute() ) {
 
    insert_history( $conexion, $usu_usuario, $accion );
   
echo "<script>window.history.back(-1); location.reload();</script>";
exit();

} else {
    // Manejo de errores en caso de falla en la inserción
    echo "<script>location.href='error_page'</script>";

}

// Cerrar la consulta preparada
$stmt->close();

?>