<?php
session_start();
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
  session_destroy();
  // Redireccionar al usuario a la página de denegado
  header( "Location: denegado" );
  exit; // También puedes usar die en lugar de exit
}
if ( empty($_POST[ 'propietario' ])|| empty($_POST[ 'id_maquinaria' ])) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/historial.php' );

$codeqr = $_POST[ 'qrcode' ];
$id_maquinaria = $_POST['id_maquinaria'];
$propietario =$_POST['propietario'];
$desc =trim($_POST['descripcion']);
$marca =trim($_POST['marca']);
$modelo =trim($_POST['modelo']);
$serie =trim($_POST['serie']);
$estado=$_POST['estado'];
$no_factura =trim($_POST['no_factura']);
$val_factura =trim($_POST['val_factura']);
$empresa =$_POST['empresa'];
$area =$_POST['area'];
$observaciones =trim($_POST['observaciones']);

// Consulta preparada para obtener el código del propietario
$stmt = $conexion->prepare( "SELECT codigo FROM propietarios WHERE id_propietario = ?" );
$stmt->bind_param( "i", $propietario );
$stmt->execute();
$stmt->bind_result( $pro );
$stmt->fetch();
$stmt->close();



// Complementos para el código de registro
$ceros = "0";
$maq = "MAQ";

// Obtención del valor continuo
$stmt = $conexion->prepare( "select  codigo,cons  from `maquinaria`  WHERE `id_cogs` = ?" );
$stmt->bind_param( "i", $id_maquinaria);
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

$codigo = $pro . $ceros . $empresa . $maq . $cons;


$dire = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/maquinaria";

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




$stmt = $conexion->prepare( "
UPDATE
    `maquinaria`
SET
   `codigo` = ?,
    `propietario_id` = ?,
    `modelo` = ?,
    `descripcion` = ?,
    `marca` = ?,
    `serie` = ?,
    `no_factura` = ?,
    `valor_factura` = ?,
    `empresa_id` = ?,
    `area_resp` = ?,
    `estado` = ?,
    `obs` = ?
WHERE

    `id_cogs` = ?
" );

$stmt->bind_param( "sssssssssssss", $codigo, $propietario, $modelo, $desc, $marca, $serie, $no_factura, $val_factura, $empresa, $area, $estado, $observaciones, $id_maquinaria);

//variables para guardar historial
$usu_usuario = $_SESSION[ 'user_name' ];
$accion = "Edito el registro de la maquinaria: " . $codigo;

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