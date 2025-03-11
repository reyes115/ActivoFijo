<?php
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header( "Location: denegado" );
    exit; // También puedes usar die en lugar de exit
}
// funcion para ver la tabla de equipos
function view_maquinaria( $conexion ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
   		`maquinaria`.*,
   		`empresa`.`nombre`
FROM
    `maquinaria`
left join empresa on `empresa_id` = id_empresa 
WHERE
    activo = 1" );
    $stmt->execute();
    $maquinaria = $stmt->get_result();

    return $maquinaria;
}
//ver historial de adignacion de mobiliario
function view_empresa( $conexion) {
    $stmt = mysqli_prepare( $conexion, "SELECT `id_empresa`, `nombre` FROM `empresa`;" );
    $stmt->execute();
    $empresa = $stmt->get_result();
	return $empresa;

}

function insert_maquinaria( $conexion, $propietario, $desc, $marca, $modelo, $serie, $estado, $no_factura, $val_factura, $empresa, $area, $observaciones ) {
    include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/historial.php' );


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
    $stmt = $conexion->prepare( "SELECT MAX(cons) as cons FROM maquinaria LIMIT 1" );
    $stmt->execute();
    $stmt->bind_result( $num );
    $stmt->fetch();
    $stmt->close();

    if ( $num < 9 ) {
        $mas = $num + 1;
        $cons = "000" . $mas;
    } elseif ( $num >= 9 && $num < 99 ) {
        $mas = $num + 1;
        $cons = "00" . $mas;
    } elseif ( $num >= 99 && $num < 999 ) {
        $mas = $num + 1;
        $cons = "0" . $mas;
    } elseif ( $num >= 999 && $num < 9999 ) {
        $mas = $num + 1;
        $cons = $mas;
    }

    $codigo = $pro . $ceros . $empresa . $maq . $cons;

    // guardar archivos
    $directorio = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/maquinaria/$codigo"; //Declaramos un  variable con la ruta donde guardaremos los archivos

    //Validamos si la ruta de destino existe, en caso de no existir la creamos
    if ( !file_exists( $directorio ) ) {
        mkdir( $directorio, 0777 )or die( "No se puede crear el directorio de extracci&oacute;n" );
    }

    foreach ( $_FILES[ "archivos" ][ 'tmp_name' ] as $key => $tmp_name ) {
        //Validamos que el archivo exista
        if ( $_FILES[ "archivos" ][ "name" ][ $key ] ) {
            $filename = $_FILES[ "archivos" ][ "name" ][ $key ]; // Obtenemos el nombre original del archivo
            $source = $_FILES[ "archivos" ][ "tmp_name" ][ $key ]; // Obtenemos un nombre temporal del archivo

            $dir = opendir( $directorio ); // Abrimos el directorio de destino
            $target_path = $directorio . '/' . $filename; // Indicamos la ruta de destino, así como el nombre del archivo

            // Movemos el archivo sin verificar si se ha cargado correctamente
            // El primer campo es el origen y el segundo el destino
            move_uploaded_file( $source, $target_path );

            closedir( $dir ); // Cerramos el directorio de destino

        }
    }


    //generar un qr 	
    $QRKey = uniqid(); // clave unica del para el codigo qr
    $t_qr = "maquinaria";
    $urlQr = "https://ceers.innovet.com.mx/ver_maquinaria" . $QRKey;
    include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/generartor_qr.php' );

    $stmt = mysqli_prepare( $conexion, "
	  INSERT INTO `maquinaria`(
		`codigo`,
		`propietario_id`,
		`modelo`,
		`descripcion`,
		`marca`,
		`serie`,
		`no_factura`,
		`valor_factura`,
		`empresa_id`,
		`area_resp`,
		`estado`,
		`obs`,
		`QRKey`,
		`cons`,
		`activo`
	)
	VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,1)" );
	
    mysqli_stmt_bind_param( $stmt, "ssssssssssssss", $codigo, $propietario, $modelo, $desc, $marca, $serie, $no_factura, $val_factura, $empresa, $area, $estado, $observaciones, $QRKey, $cons );

    $usu_usuario = $_SESSION[ 'user_name' ];
    $accion = "Registro nueva maquinaria: " . $codigo;
	
    if ( mysqli_stmt_execute( $stmt ) ) {

        insert_history( $conexion, $usu_usuario, $accion );
        echo "<script>location.href='maquinaria'</script>";
        exit;
    } else {
        echo "<script>location.href='error'</script>";
    }
} 

// funcion para ver datos del equipo
function view_equipo( $conexion, $codigoQR ) {
    $stmt = mysqli_prepare( $conexion, "
	SELECT
    `maquinaria`.*,
    empresa.id_empresa,
    `propietarios`.`nombre` AS `propietario`
FROM
    `maquinaria`
LEFT JOIN empresa ON `empresa_id` = id_empresa
LEFT JOIN `propietarios` ON `maquinaria`.`propietario_id` = `propietarios`.`id_propietario`
WHERE
    `QRKey` =  ?" );
	$stmt->bind_param( "s", $codigoQR );
    $stmt->execute();
    $equipo = $stmt->get_result()->fetch_assoc() ;
	return $equipo;

}
function delete_maquinaria($conexion, $id) {
    //variables para el historial
    $usu_usuario = $_SESSION['user_name'];
    $accion = "Elimino el registro de la maquinaria: " . $id;

    $stmt = mysqli_prepare($conexion, "UPDATE `maquinaria` SET `activo`='0' WHERE `id_cogs` = ?");
    $stmt->bind_param("i", $id);  

    if ($stmt->execute()) {
        include($_SERVER['DOCUMENT_ROOT'] . '/assets/historial.php');
        insert_history($conexion, $usu_usuario, $accion);
        echo "<script>location.href='maquinaria'</script>";
        exit;
    } else {
        echo "<script>location.href='error_page'</script>";
    }
}
