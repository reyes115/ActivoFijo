<?php
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header( "Location: denegado" );
    exit; // También puedes usar die en lugar de exit
}

// funcion para ver la tabla de equipos
function view_moviles( $conexion ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
   celular.*,
    CASE disponible WHEN 1 THEN 'Disponible' WHEN 2 THEN 'No disponible'
END AS estado,
`personal`.`nombre`,
		`personal`.`a_paterno`,
		`personal`.`a_materno`
FROM
    celular
LEFT JOIN personal ON id_personal = personal_id
WHERE
    activo = 1" );
    $stmt->execute();
    $moviles = $stmt->get_result();

    return $moviles;
}

// funcion para ver datos del equipo
function view_equipo( $conexion, $codigoQR ) {
    $stmt = mysqli_prepare( $conexion, "
	 SELECT
		`celular`.*,
		`personal`.`nombre`,
		`personal`.`a_paterno`,
		`personal`.`a_materno`,
		`personal`.`id_depar`,
		`propietarios`.`nombre` AS `propietario`
	FROM
		`celular`
	LEFT JOIN `personal` ON `personal_id` = `id_personal`
	LEFT JOIN `propietarios` ON `celular`.`id_propietario` = `propietarios`.`id_propietario`
	WHERE
		`QRKey` = ?" );
	$stmt->bind_param( "s", $codigoQR );
    $stmt->execute();
    $equipo = $stmt->get_result()->fetch_assoc() ;
	return $equipo;

}

function delete_moviles($conexion, $id) {
    //variables para el historial
    $usu_usuario = $_SESSION['user_name'];
    $accion = "Elimino el registro del equipo movil: " . $id;

    $stmt = mysqli_prepare($conexion, "UPDATE `celular` SET `activo`='0' WHERE `id_celular` = ?");
    $stmt->bind_param("i", $id);  // Cambiado "s" a "i" para indicar un número entero

    if ($stmt->execute()) {
        include($_SERVER['DOCUMENT_ROOT'] . '/assets/historial.php');
        insert_history($conexion, $usu_usuario, $accion);
        echo "<script>location.href='moviles'</script>";
        exit;
    } else {
        echo "<script>location.href='error_page'</script>";
    }
}


?>