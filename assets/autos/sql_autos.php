<?php
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header( "Location: denegado" );
    exit; // También puedes usar die en lugar de exit
}

function verCards( $conexion ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
    (
        SELECT
            COUNT(id_autos)
        FROM
            autos
        WHERE
            (VencVerificacion BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) OR VencVerificacion < CURDATE() )
            AND activo = 1
            AND estatus = 1
    ) AS Autos_Vencimiento_Verificacion,
    (
        SELECT
            COUNT(id_autos)
        FROM
            autos
        WHERE
            (prox_servicio BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) OR prox_servicio < CURDATE() OR prox_servicio IS NULL)
            AND activo = 1
            AND estatus = 1
    ) AS Autos_Proximo_Servicio;
" );
    $stmt->execute();
    $Cards = $stmt->get_result()->fetch_assoc();

    return $Cards;
}

function view_autos( $conexion ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
   autos.*,
    case estatus when 1 then 'Alta' when 2 then 'Baja' END AS est,'activo',
`personal`.`nombre`,
		`personal`.`a_paterno`,
		`personal`.`a_materno`
FROM
    autos
LEFT JOIN personal ON id_personal = personal_id_personal 
WHERE
    activo = 1 " );
    $stmt->execute();
    $autos = $stmt->get_result();

    return $autos;

}

function tverificaciones($conexion) {
    $stmt = mysqli_prepare($conexion, "
    SELECT
        `codigo`,
		`id_autos`,
        `VencVerificacion`,
        `QRKey`,
        `note`,
        `personal`.`nombre`,
        `personal`.`a_paterno`,
        `personal`.`a_materno`,
        `personal`.`email`  -- Agregar el campo email
	
    FROM
        autos
    LEFT JOIN
        personal ON id_personal = personal_id_personal
    WHERE
        (VencVerificacion BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) 
        OR VencVerificacion < CURDATE())
        AND activo = 1
        AND estatus = 1;
    ");
    $stmt->execute();
    $autos = $stmt->get_result();

    return $autos;
}


function tservicios($conexion) {
    $stmt = mysqli_prepare($conexion, "
SELECT
    autos.codigo,
	autos.id_autos,
    MAX(servicio_autos.km) AS km,
    MAX(servicio_autos.ultimo_servicio) AS ultimo_servicio,
    autos.prox_servicio,
    autos.QRKey,
    servicio_autos.note AS Notificacion,
	servicio_autos.id_servicio,
    personal.nombre,
    personal.a_paterno,
    personal.a_materno,
    personal.email  
	
FROM 
    servicio_autos
LEFT JOIN autos ON servicio_autos.autos_id = autos.id_autos
LEFT JOIN personal ON autos.personal_id_personal = personal.id_personal
WHERE
    (autos.prox_servicio BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) 
    OR autos.prox_servicio < CURDATE() 
    OR autos.prox_servicio IS NULL)
    AND autos.activo = 1
    AND autos.estatus = 1
GROUP BY
    autos.codigo;
");
    $stmt->execute();
    $autos = $stmt->get_result();

    return $autos;
}


function view_auto( $conexion,$codigoQR ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
    autos.*,
    CASE estatus WHEN 1 THEN 'Alta' WHEN 2 THEN 'Baja'
END AS est,
`personal`.`nombre`,
`personal`.`a_paterno`,
`personal`.`a_materno`,
rutas.r_imagen,
propietarios.nombre AS propietario,
polizas.`no_poliza`
FROM
    autos
LEFT JOIN personal ON id_personal = personal_id_personal
LEFT JOIN rutas ON codigo_ruta = codigo
LEFT JOIN propietarios ON autos.id_propietario = propietarios.id_propietario
LEFT JOIN polizas ON autos.id_autos = polizas.asegurado_auto
WHERE
    `QRKey` =  ?" );
    $stmt->bind_param( "s", $codigoQR );
    $stmt->execute();
    $equipo = $stmt->get_result()->fetch_assoc() ;
	return $equipo;

}

function delete_autos($conexion, $id) {
    //variables para el historial
    $usu_usuario = $_SESSION['user_name'];
    $accion = "Elimino el registro del auto: " . $id;

    $stmt = mysqli_prepare($conexion, "UPDATE `autos` SET `activo`='0' WHERE `id_autos` = ?");
    $stmt->bind_param("i", $id);  

    if ($stmt->execute()) {
        include($_SERVER['DOCUMENT_ROOT'] . '/assets/historial.php');
        insert_history($conexion, $usu_usuario, $accion);
        echo "<script>location.href='autos'</script>";
        exit;
    } else {
        echo "<script>location.href='error_page'</script>";
    }
}

// funcion para ver datos del equipo
function view_equipo( $conexion, $codigoQR ) {
    $stmt = mysqli_prepare( $conexion, "
	  SELECT
		`autos`.*,
		`personal`.`nombre`,
		`personal`.`a_paterno`,
		`personal`.`a_materno`,
		`personal`.`id_depar`,
		departamentos.nombre  AS departamentos,
		`propietarios`.`nombre` AS `propietario`,rutas.*
	FROM
		`autos`
	LEFT JOIN `rutas` ON `codigo` = `codigo_ruta`
	LEFT JOIN `personal` ON `personal_id_personal` = `id_personal`
	LEFT JOIN `propietarios` ON `autos`.`id_propietario` = `propietarios`.`id_propietario`
	left join departamentos	on id_depar = id_depa
	WHERE
		`QRKey` = ?" );
	$stmt->bind_param( "s", $codigoQR );
    $stmt->execute();
    $equipo = $stmt->get_result()->fetch_assoc() ;
	return $equipo;

}
// funcion para ver datos del polizas
function view_autos_poliza( $conexion, $id ) {
    $stmt = mysqli_prepare( $conexion, "
	SELECT
		polizas.*
	FROM
		`polizas`
	LEFT JOIN autos ON asegurado_auto = id_autos
	WHERE
		asegurado_auto = ?" );
	$stmt->bind_param( "s", $id );
    $stmt->execute();
    $equipo = $stmt->get_result();
	return $equipo;

}
//ver historial de servicio de auto
function view_autos_servicio( $conexion, $id ) {
    $stmt = mysqli_prepare( $conexion, "
	SELECT
		id_servicio,
		km,
		evidencia_servicio,
		ultimo_servicio,
		LPAD(DAY(ultimo_servicio),
		2,
		'0') AS dia,
		LPAD(MONTH(ultimo_servicio),
		2,
		'0') AS mes,
		YEAR(ultimo_servicio) AS año
	FROM
		servicio_autos
	WHERE
		autos_id = ?
	ORDER BY
		km ASC;
" );
	$stmt->bind_param( "s", $id );
    $stmt->execute();
    $equipo = $stmt->get_result();
	return $equipo;

}
//ver historial de kilometraje de auto
function view_autos_km( $conexion, $id ) {
    $stmt = mysqli_prepare( $conexion, "
	SELECT * FROM `kilometraje` WHERE autos_id= ?" );
	$stmt->bind_param( "s", $id );
    $stmt->execute();
    $equipo = $stmt->get_result();
	return $equipo;

}
//ver historial de adignacion de auto
function view_autos_asig( $conexion, $id ) {
    $stmt = mysqli_prepare( $conexion, "SELECT *,DATE_FORMAT(fecha_relevo, '%d-%m-%Y') AS fecha_formateada FROM `previous_devices` WHERE `id_devices`= ? AND `type`= 'auto';" );
	$stmt->bind_param( "s", $id );
    $stmt->execute();
    $equipo = $stmt->get_result();
	return $equipo;

}
//ver historial de adignacion de auto
function view_autos_rutas( $conexion, $codigo ) {
    $stmt = mysqli_prepare( $conexion, "SELECT  `r_imagen`, `r_tarjeta`, `r_factura`, `r_identificacion`, `r_tenencia`, `r_verificacion`, `r_licencia`,  `r_politicas` FROM `rutas` WHERE `codigo_ruta`= ?;" );
	$stmt->bind_param( "s", $codigo );
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc() ;
	return $r;

}

?>