<?php

// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header( "Location: denegado" );
    exit; // También puedes usar die en lugar de exit
}
// funcion para ver la tabla del personal
function view_personal( $conexion ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
    `personal`.*,
    `departamentos`.`nombre` AS `departamentos`,
empresa_id_empresa AS no_empresa,
empresa.nombre AS empresa
FROM
    `personal`
LEFT JOIN `departamentos` ON `id_depar` = `id_depa`
LEFT JOIN `empresa` ON `empresa_id_empresa` = `id_empresa`
WHERE
    `estado` = 1 and `id_personal` != 0;" );
    $stmt->execute();
    $personal = $stmt->get_result();

    return $personal;
}
function select_personal($conexion) {
    $query = "SELECT * FROM `personal`";
    $result = mysqli_query($conexion, $query);
    return $result;
}
//funcion para hacer un insert en la tabla clientes 
function insert_personal($conexion, $nombre, $apaterno, $amaterno, $telefono, $email, $numColaborador, $depart) {
    // guardar archivos
    $directorio = $_SERVER['DOCUMENT_ROOT'] . "/uploads/personal/" . $nombre . ' ' . $apaterno . ' ' . $amaterno;

    //Validamos si la ruta de destino existe, en caso de no existir la creamos
    if (!file_exists($directorio)) {
        mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");
    }

    foreach ($_FILES["archivos"]['tmp_name'] as $key => $tmp_name) {
        //Validamos que el archivo exista
        if ($_FILES["archivos"]["name"][$key]) {
            $filename = $_FILES["archivos"]["name"][$key]; // Obtenemos el nombre original del archivo
            $source = $_FILES["archivos"]["tmp_name"][$key]; // Obtenemos un nombre temporal del archivo

            $target_path = $directorio . '/' . $filename; // Indicamos la ruta de destino, así como el nombre del archivo

            // Movemos el archivo sin verificar si se ha cargado correctamente
            // El primer campo es el origen y el segundo el destino
            move_uploaded_file($source, $target_path);
        }
    }

    $stmt = mysqli_prepare($conexion, "
    INSERT INTO `personal`(
        `no_empleado`,
        `nombre`,
        `a_paterno`,
        `a_materno`,
        `email`,
        `phone`,
        `id_depar`,
        `estado`
    ) VALUES (?, ?, ?, ?, ?, ?, ?, 1)" );
    mysqli_stmt_bind_param( $stmt, "sssssss", $numColaborador, $nombre, $apaterno, $amaterno, $email, $telefono, $depart );


    if (mysqli_stmt_execute($stmt)) {
        echo "<script>location.href='personal'</script>";
        exit;
    } else {
        echo "<script>location.href='error'</script>";
    }
}
// funcion para ver datos del personal
function view_colaborador( $conexion, $idPersonal) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
    `personal`.*,
    `departamentos`.`nombre` AS `departamentos`,
empresa_id_empresa AS id_empresa,
empresa.nombre AS empresa
FROM
    `personal`
LEFT JOIN `departamentos` ON `id_depar` = `id_depa`
LEFT JOIN `empresa` ON `empresa_id_empresa` = `id_empresa`
WHERE
    `id_personal` = ?" );
	$stmt->bind_param( "s", $idPersonal );
    $stmt->execute();
    $equipo = $stmt->get_result()->fetch_assoc() ;
	return $equipo;

}
function delete_personal($conexion, $id) {
    //variables para el historial
    $usu_usuario = $_SESSION['user_name'];
    $accion = "Elimino al personal con id: " . $id;

    $stmt = mysqli_prepare($conexion, "UPDATE `personal` SET `estado`='0' WHERE `id_personal` = ?");
    $stmt->bind_param("i", $id);  // Cambiado "s" a "i" para indicar un número entero

    if ($stmt->execute()) {
        include($_SERVER['DOCUMENT_ROOT'] . '/assets/historial.php');
        insert_history($conexion, $usu_usuario, $accion);
        echo "<script>location.href='personal'</script>";
        exit;
    } else {
        echo "<script>location.href='error_page'</script>";
    }
}
?>