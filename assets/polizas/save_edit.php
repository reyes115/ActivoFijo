<?php
session_start();
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
  session_destroy();
  // Redireccionar al usuario a la página de denegado
  header( "Location: denegado" );
  exit; // También puedes usar die en lugar de exit
}
if ( empty($_POST[ 'propietario' ])|| empty($_POST[ 'id_poliza' ])) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/historial.php' );

$id_poliza = $_POST[ 'id_poliza' ];
$asegurado = $_POST[ 'asegurado' ];
$t_asegurado = $_POST[ 't_asegurado' ];
$empresa =$_POST['empresa'];
$tipo=$_POST['tipo'];
$no_poliza=trim($_POST['no_poliza']);
$aseguradora=trim($_POST['aseguradora']);
$propietario =$_POST['propietario'];
$f_pago=$_POST['f_pago'];
$inicio_vigencia=$_POST['inicio_vigencia'];
$fin_vigencia=$_POST['fin_vigencia'];
$moneda=$_POST['moneda'];
$total=trim($_POST['total']);
$prima_neta=trim($_POST['prima_neta']);
$derecho_poliza=trim($_POST['derecho_poliza']);
$iva=trim($_POST['iva']);
$suma_asegurada=trim($_POST['suma_asegurada']);

// Consulta preparada para obtener el código del propietario
$stmt = $conexion->prepare( "SELECT codigo FROM propietarios WHERE id_propietario = ?" );
$stmt->bind_param( "i", $propietario );
$stmt->execute();
$stmt->bind_result( $pro );
$stmt->fetch();
$stmt->close();

    // Complementos para el código de registro
    $ceros = "0";
      switch ( $tipo ) {
        case 1:
           $t_code="AUT";
            break;
        case 2:
            $t_code="VID";
            break;
        case 3:
            $t_code="MED";
            break;
        case 4:
           $t_code="DAS";
            break;
    };

// Obtención del valor continuo
$stmt = $conexion->prepare( "select  codigo,cons,fin_vigencia from `polizas`  WHERE `id_poliza` = ?" );
$stmt->bind_param( "i", $id_poliza);
$stmt->execute();
$fila = $stmt->get_result()->fetch_assoc();
$doc = $fila['codigo'];
$num = $fila['cons'];
$fv = $fila['fin_vigencia'];
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

$codigo = $pro . $ceros . $empresa . $t_code . $cons;


$dire = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/polizas";

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
// Asignar valores a las variables $asegurado_auto, $asegurado_col y $asegurado_inm
    switch ($t_asegurado) {
        case 1:
            $asegurado_auto = $asegurado;
            $asegurado_col = NULL;
            $asegurado_inm = NULL;
            break;
        case 2:
            $asegurado_auto = NULL;
            $asegurado_col = $asegurado;
            $asegurado_inm = NULL;
            break;
        case 3:
            $asegurado_auto = NULL;
            $asegurado_col = NULL;
            $asegurado_inm = $asegurado;
            break;
    }


// Renombrar el archivo si el código ha cambiado
if ( $fin_vigencia != $fv ) {
    $note =0;
} else {
	$note = 1;
}



$stmt = $conexion->prepare( "
UPDATE `polizas`
SET
    `codigo` = ?,
    `t_asegurado` = ?,
    `asegurado_auto` = ?,
    `asegurado_col` = ?,
    `asegurado_inm` =?,
    `id_empresa` = ?,
    `tipo` = ?,
    `no_poliza` = ?,
    `aseguradora` = ?,
    `id_propietario` = ?,
    `f_pago` = ?,
    `inicio_vigencia` = ?,
    `fin_vigencia` = ?,
    `moneda` = ?,
    `total` = ?,
    `prima_neta` = ?,
    `derecho_poliza` = ?,
    `iva` = ?,
    `suma_asegurada` = ?,
    `note` = ?
WHERE
       `id_poliza` = ?
" );

$stmt->bind_param( "sssssssssssssssssssss", $codigo , $t_asegurado, $asegurado_auto, $asegurado_col, $asegurado_inm, $empresa, $tipo, $no_poliza, $aseguradora, $propietario, $f_pago, $inicio_vigencia, $fin_vigencia, $moneda, $total, $prima_neta, $derecho_poliza, $iva, $suma_asegurada, $note, $id_poliza);

//variables para guardar historial
$usu_usuario = $_SESSION[ 'user_name' ];
$accion = "Edito el registro de la poliza: " . $codigo;

// Ejecutar la consulta
if ( $stmt->execute() ) {
 
    insert_history( $conexion, $usu_usuario, $accion );
   
echo "<script>location.href='ver_polizas$id_poliza'</script>";
exit();

} else {
    // Manejo de errores en caso de falla en la inserción
    echo "<script>location.href='error_page'</script>";

}

// Cerrar la consulta preparada
$stmt->close();

?>