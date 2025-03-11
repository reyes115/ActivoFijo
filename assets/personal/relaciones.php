<?php

// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header( "Location: denegado" );
    exit; // También puedes usar die en lugar de exit
}

// funcion para ver la tabla del personal
function computo( $conexion, $idPersonal ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
    *,case tipo when 1 then 'Equipo de escritorio' when 2 then 'Laptop' END AS tipos
FROM
    `computadora`
WHERE
    `personal_id`  = ? and activo = 1" );
	$stmt->bind_param( "s", $idPersonal );
    $stmt->execute();
    $equipo = $stmt->get_result();
	return $equipo;
}
// funcion para ver la tabla del personal
function movil( $conexion, $idPersonal ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
  *
FROM
    `celular`
WHERE
    `personal_id`  = ? and activo = 1" );
	$stmt->bind_param( "s", $idPersonal );
    $stmt->execute();
    $equipo = $stmt->get_result();
	return $equipo;
}

// funcion para ver la tabla del personal
function perifericos( $conexion, $idPersonal ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
    `codigo`,
    `QRKey`
FROM
    `perifericos`
WHERE
    `personal_id`  = ? and activo = 1" );
	$stmt->bind_param( "s", $idPersonal );
    $stmt->execute();
    $equipo = $stmt->get_result();
	return $equipo;
}
// funcion para ver la tabla del personal
function autos( $conexion, $idPersonal ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
    `codigo`,
    `QRKey`
FROM
    `autos`
WHERE
    `personal_id_personal`  = ? and activo = 1" );
	$stmt->bind_param( "s", $idPersonal );
    $stmt->execute();
    $equipo = $stmt->get_result();
	return $equipo;
}
// funcion para ver la tabla del personal
function mobiliario( $conexion, $idPersonal ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
    `codigo`,
    `QRKey`
FROM
    `stock`
WHERE
    `personal_id`  = ? and activo = 1" );
	$stmt->bind_param( "s", $idPersonal );
    $stmt->execute();
    $equipo = $stmt->get_result();
	return $equipo;
}
// funcion para ver la tabla del personal
function polizas( $conexion, $idPersonal ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
    `codigo`,
    `id_poliza` AS `QRKey`
FROM
    `polizas`
WHERE
    `asegurado_col`  = ? and activo = 1" );
	$stmt->bind_param( "s", $idPersonal );
    $stmt->execute();
    $equipo = $stmt->get_result();
	return $equipo;
}

?>