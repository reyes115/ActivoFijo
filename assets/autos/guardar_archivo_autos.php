<?php
session_start();

if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
    session_destroy();
    header( "Location: denegado" );
    exit;
}

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );

$accion = $_POST[ 'accion' ];

if ( $accion == 'edit' ) {
    $codigo_ruta = $_POST[ 'codigo' ];
    $documentos = array( "r_imagen", "r_tarjeta", "r_factura", "r_identificacion", "r_tenencia", "r_verificacion", "r_licencia", "r_servcicio", "r_politicas", "r_responsiva" );

    $stmt = $conexion->prepare( "SELECT * FROM `rutas` WHERE `codigo_ruta` = ?" );
    $stmt->bind_param( "s", $codigo_ruta );
    $stmt->execute();
    $ruta_actual = $stmt->get_result()->fetch_assoc();

    $directorio = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/autos/$codigo_ruta";
    if ( !file_exists( $directorio ) ) {
        mkdir( $directorio, 0777, true );
    }

    foreach ( $documentos as $documento ) {
        if ( isset( $_FILES[ $documento ] ) && $_FILES[ $documento ][ 'error' ] === UPLOAD_ERR_OK ) {
            $filename = basename( $_FILES[ $documento ][ 'name' ] );
            $destino = $directorio . "/" . $filename;

            move_uploaded_file( $_FILES[ $documento ][ 'tmp_name' ], $destino );

            if ( !empty( $ruta_actual[ $documento ] ) ) {
                $file = $ruta_actual[ $documento ];
                $fecha_actual = date( 'Y-m-d' );
                $hora_actual = date( 'H-i-s' );
                $nuevo_nombre = $fecha_actual . '_' . $hora_actual . '_' . $file;
                $carpeta_destino = $_SERVER[ 'DOCUMENT_ROOT' ] . '/trash/' . $fecha_actual;

                if ( !file_exists( $carpeta_destino ) ) {
                    mkdir( $carpeta_destino, 0777, true );
                }

                rename( $directorio . '/' . $file, $carpeta_destino . '/' . $nuevo_nombre );
            }

            $stmt_rutas = $conexion->prepare( "UPDATE `rutas` SET $documento = ? WHERE `codigo_ruta` = ?" );
            $stmt_rutas->bind_param( "ss", $filename, $codigo_ruta );
            $stmt_rutas->execute();
            $stmt_rutas->close();
        }
    }

    $stmt->close();
} elseif ( $accion == 'delete' ) {
    $borrar_archivo = $_POST[ 'borrarArchivo' ];
    $ruta_archivo = $_POST[ 'rutaArchivo' ];
    $codigo_ruta = $_POST[ 'codigo' ];

    $directorio = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/autos/$codigo_ruta";
    $fecha_actual = date( 'Y-m-d' );
    $hora_actual = date( 'H-i-s' );
    $nuevo_nombre = $fecha_actual . '_' . $hora_actual . '_' . $borrar_archivo;
    $carpeta_destino = $_SERVER[ 'DOCUMENT_ROOT' ] . '/trash/' . $fecha_actual;

    if ( !file_exists( $carpeta_destino ) ) {
        mkdir( $carpeta_destino, 0777, true );
    }

    if ( rename( $directorio . '/' . $borrar_archivo, $carpeta_destino . '/' . $nuevo_nombre ) ) {
        $stmt_rutas = $conexion->prepare( "UPDATE `rutas` SET $ruta_archivo = NULL WHERE `codigo_ruta` = ?" );
        $stmt_rutas->bind_param( "s", $codigo_ruta );
        $stmt_rutas->execute();
        $stmt_rutas->close();
    }
} elseif ( $accion == 'delete_servicio' ) {
    $id_servicio = $_POST[ 'id_servicio' ];
    $id_auto = $_POST[ 'id_auto' ];
    $fecha = $_POST[ 'fecha' ];
	
$codigo_ruta = $_POST['codigo'];
    $f_servicio = $_POST[ 'f_servicio' ];
    $evidencia_servicio = $_POST[ 'evidencia_servicio' ];
$directorio = $_SERVER['DOCUMENT_ROOT'] . "/uploads/autos/$codigo_ruta/servicio_evidencia";
 if ( !empty( $evidencia_servicio ) ) {
                $file = $evidencia_servicio;
                $fecha_actual = date( 'Y-m-d' );
                $hora_actual = date( 'H-i-s' );
                $nuevo_nombre = $fecha_actual . '_' . $hora_actual . '_' . $file;
                $carpeta_destino = $_SERVER[ 'DOCUMENT_ROOT' ] . '/trash/' . $fecha_actual;

                if ( !file_exists( $carpeta_destino ) ) {
                    mkdir( $carpeta_destino, 0777, true );
                }

                rename( $directorio . '/' . $file, $carpeta_destino . '/' . $nuevo_nombre );
            }
    $stmt = $conexion->prepare( "DELETE FROM `servicio_autos` WHERE `id_servicio` = ?" );
    $stmt->bind_param( "s", $id_servicio );
    $stmt->execute();

    $prox_servicio = date( "Y-m-d", strtotime( $fecha . "+ 6 months" ) );
    if ( $stmt->affected_rows > 0 ) {
        if ( $f_servicio == $prox_servicio ) {
            $stmt_auto = $conexion->prepare( "UPDATE `autos` SET `prox_servicio` = NULL WHERE `id_autos` = ?" );
            $stmt_auto->bind_param( "s", $id_auto );
            $stmt_auto->execute();
            $stmt_auto->close();
        }
    }
    $stmt->close();
} elseif ( $accion == 'new_servicio' ) {
$id_auto = $_POST['id_auto'];
$km = $_POST['km'];
$fecha = $_POST['fecha'];
$codigo_ruta = $_POST['codigo'];
$evidencia_servicio = 'evidencia_servicio'; // Definir el nombre del campo de archivo
$directorio = $_SERVER['DOCUMENT_ROOT'] . "/uploads/autos/$codigo_ruta/servicio_evidencia";

if (!file_exists($directorio)) {
    mkdir($directorio, 0777, true);
}

$filename = null; // Inicializar la variable $filename

if (isset($_FILES[$evidencia_servicio]) && $_FILES[$evidencia_servicio]['error'] === UPLOAD_ERR_OK) {
    $filename = basename($_FILES[$evidencia_servicio]['name']);
    $destino = $directorio . "/" . $filename;
    move_uploaded_file($_FILES[$evidencia_servicio]['tmp_name'], $destino);
}

$stmt = $conexion->prepare("INSERT INTO `servicio_autos`(`km`, `ultimo_servicio`, `autos_id`, `evidencia_servicio`) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $km, $fecha, $id_auto, $filename);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $prox_servicio = date("Y-m-d", strtotime($fecha . "+ 6 months"));
    $stmt_auto = $conexion->prepare("UPDATE `autos` SET `prox_servicio` = ? WHERE `id_autos` = ?");
    $stmt_auto->bind_param("ss", $prox_servicio, $id_auto);
    $stmt_auto->execute();
    $stmt_auto->close();
}

$stmt->close();
} elseif ( $accion == 'new_km' ) {
    $id_auto = $_POST[ 'id_auto' ];
    $year = $_POST[ 'year' ];
    $quincena = $_POST[ 'quincena' ];
    $km = $_POST[ 'km' ];

    $stmt = $conexion->prepare( "INSERT INTO `kilometraje`(`año`, `quincena`, `km`, `autos_id`) VALUES (?, ?, ?, ?)" );
    $stmt->bind_param( "ssss", $year, $quincena, $km, $id_auto );
    $stmt->execute();
    $stmt->close();
} elseif ( $accion == 'delete_km' ) {
    $id_km = $_POST[ 'id_km' ];
    $stmt = $conexion->prepare( "DELETE FROM `kilometraje` WHERE `id_km` = ?" );
    $stmt->bind_param( "s", $id_km );
    $stmt->execute();
    $stmt->close();
} else {
    // Manejo de errores en caso de falla en la inserción
    echo "<script>location.href='error_page'</script>";

}

echo "<script>window.history.back(); location.reload();</script>";
exit();
?>
