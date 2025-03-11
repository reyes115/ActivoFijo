<?php
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header( "Location: denegado" );
    exit; // También puedes usar die en lugar de exit
}

// funcion para ver la tabla de equipos
function view_compu( $conexion ) {
    $stmt = mysqli_prepare( $conexion, "
  SELECT *,
    CASE tipo 
        when 1 then 'Escritorio' 
        when 2 then 'Portátil'
        when 4 then 'Otros' 
        END AS tipo 
    FROM `computadora` 
    LEFT JOIN personal ON personal_id = id_personal 
    WHERE
        activo=1" );
    $stmt->execute();
    $computer = $stmt->get_result();

    return $computer;
}

function delete_computo($conexion, $id) {
    //variables para el historial
    $usu_usuario = $_SESSION['user_name'];
    $accion = "Elimino el registro del equipo de computo: " . $id;

    $stmt = mysqli_prepare($conexion, "UPDATE `computadora` SET `activo`='0' WHERE id_compu = ?");
    $stmt->bind_param("i", $id);  // Cambiado "s" a "i" para indicar un número entero

    if ($stmt->execute()) {
        include($_SERVER['DOCUMENT_ROOT'] . '/assets/historial.php');
        insert_history($conexion, $usu_usuario, $accion);
        echo "<script>location.href='computo'</script>";
        exit;
    } else {
        echo "<script>location.href='error_page'</script>";
    }
}


// funcion para ver datos del equipo
function view_equipo( $conexion, $codigoQR ) {
    $stmt = mysqli_prepare( $conexion, "
	 SELECT
		`computadora`.*,
		`personal`.`nombre`,
		`personal`.`a_paterno`,
		`personal`.`a_materno`,
		`personal`.`id_depar`,
		`propietarios`.`nombre` AS `propietario`
	FROM
		`computadora`
	LEFT JOIN `personal` ON `personal_id` = `id_personal`
	LEFT JOIN `propietarios` ON `computadora`.`id_propietario` = `propietarios`.`id_propietario`
	WHERE
		`QRKey` = ?" );
	$stmt->bind_param( "s", $codigoQR );
    $stmt->execute();
    $equipo = $stmt->get_result()->fetch_assoc() ;
	return $equipo;

}

?>