<?php
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header( "Location: denegado" );
    exit; // También puedes usar die en lugar de exit
}
// funcion para ver la tabla de equipos
function view_polizas( $conexion ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
    `polizas`.*,
    `autos`.`codigo` AS `auto_codigo`,
    CONCAT(`nombre`, ' ', `a_paterno`) AS `nombre`,
    `inmobiliario`.`name`
FROM
    `polizas`
LEFT JOIN `autos` ON `polizas`.`asegurado_auto` = `autos`.`id_autos`
LEFT JOIN `personal` ON `polizas`.`asegurado_col` = `personal`.`id_personal`
LEFT JOIN `inmobiliario` ON `polizas`.`asegurado_inm` = `inmobiliario`.`id_inmobiliario`
WHERE
    `polizas`.`activo` = 1;" );
    $stmt->execute();
    $polizas = $stmt->get_result();

    return $polizas;
}
//ver historial de adignacion de mobiliario
function view_empresa( $conexion) {
    $stmt = mysqli_prepare( $conexion, "SELECT `id_empresa`, `nombre` FROM `empresa`;" );
    $stmt->execute();
    $empresa = $stmt->get_result();
	return $empresa;

}
function verCards( $conexion ) {
    $stmt = mysqli_prepare( $conexion, "SELECT
            DATE_FORMAT(polizas.fin_vigencia, '%d/%m/%Y') AS fecha_fin,
            polizas.*,
            autos.codigo AS auto_codigo,
            CONCAT(personal.nombre, ' ', personal.a_paterno) AS nombre,
            inmobiliario.name AS nombre_inmobiliario
        FROM
            polizas
        LEFT JOIN autos ON polizas.asegurado_auto = autos.id_autos
        LEFT JOIN personal ON polizas.asegurado_col = personal.id_personal
        LEFT JOIN inmobiliario ON polizas.asegurado_inm = inmobiliario.id_inmobiliario
        WHERE
            polizas.fin_vigencia BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
            AND polizas.activo = 1" );
    $stmt->execute();
    $Cards= $stmt->get_result();  
    
   return $Cards;
}


function insert_polizas($conexion, $asegurado, $t_asegurado, $empresa, $tipo, $no_poliza, $aseguradora, $propietario, $f_pago, $inicio_vigencia, $fin_vigencia, $moneda, $total, $prima_neta, $derecho_poliza, $iva, $suma_asegurada){
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
    }

    // Obtención del valor continuo
    $stmt = $conexion->prepare( "SELECT MAX(cons) as cons FROM polizas LIMIT 1" );
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

    $codigo = $pro . $ceros . $empresa . $t_code . $cons;

    // guardar archivos
    $directorio = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/polizas/$codigo"; //Declaramos un  variable con la ruta donde guardaremos los archivos

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


    $stmt = mysqli_prepare( $conexion, "INSERT INTO `polizas`(
                `codigo`,
                `t_asegurado`,
                `asegurado_auto`,
                `asegurado_col`,
                `asegurado_inm`,
                `id_empresa`,
                `tipo`,
                `no_poliza`,
                `aseguradora`,
                `id_propietario`,
                `f_pago`,
                `inicio_vigencia`,
                `fin_vigencia`,
                `moneda`,
                `total`,
                `prima_neta`,
                `derecho_poliza`,
                `iva`,
                `suma_asegurada`,
                `cons`
                )
            VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)" );

    mysqli_stmt_bind_param( $stmt, "ssssssssssssssssssss", $codigo , $t_asegurado, $asegurado_auto, $asegurado_col, $asegurado_inm, $empresa, $tipo, $no_poliza, $aseguradora, $propietario, $f_pago, $inicio_vigencia, $fin_vigencia, $moneda, $total, $prima_neta, $derecho_poliza, $iva, $suma_asegurada, $cons);

    $usu_usuario = $_SESSION[ 'user_name' ];
    $accion = "Registro nueva poliza: " . $codigo;
    
    if ( mysqli_stmt_execute( $stmt ) ) {
       insert_history( $conexion, $usu_usuario, $accion );
	 echo "<script>location.href='polizas'</script>";
     exit;
    } else {
      echo "<script>location.href='error'</script>";
		echo "Error al ejecutar la consulta: " . mysqli_error($stmt);
    }
}

// funcion para ver datos del equipo
function view_poliza( $conexion, $idpoliza ) {
    $stmt = mysqli_prepare( $conexion, "
	SELECT
		`polizas`.*,
		`autos`.`codigo` AS `auto_codigo`,
		CONCAT(`nombre`, ' ', `a_paterno`) AS `nombre`,
		`inmobiliario`.`name`
	FROM
		`polizas`
	LEFT JOIN `autos` ON `polizas`.`asegurado_auto` = `autos`.`id_autos`
	LEFT JOIN `personal` ON `polizas`.`asegurado_col` = `personal`.`id_personal`
	LEFT JOIN `inmobiliario` ON `polizas`.`asegurado_inm` = `inmobiliario`.`id_inmobiliario`
	WHERE
		`id_poliza` = ?" );
	$stmt->bind_param( "s", $idpoliza  );
    $stmt->execute();
    $equipo = $stmt->get_result()->fetch_assoc() ;
	return $equipo;

}

function delete_polizas($conexion, $id) {
    //variables para el historial
    $usu_usuario = $_SESSION['user_name'];
    $accion = "Elimino el registro de la poliza: " . $id;

    $stmt = mysqli_prepare($conexion, "UPDATE `polizas` SET `activo`='0' WHERE `id_poliza` = ?");
    $stmt->bind_param("i", $id);  

    if ($stmt->execute()) {
        include($_SERVER['DOCUMENT_ROOT'] . '/assets/historial.php');
        insert_history($conexion, $usu_usuario, $accion);
        echo "<script>location.href='polizas'</script>";
        exit;
    } else {
        echo "<script>location.href='error_page'</script>";
    }
}
