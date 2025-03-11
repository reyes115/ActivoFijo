<?php
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header( "Location: denegado" );
    exit; // También puedes usar die en lugar de exit
}

// funcion para ver la tabla de equipos
function view_servicios( $conexion ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT * FROM `servicios` WHERE activo = 1" );
    $stmt->execute();
    $servicios = $stmt->get_result();

    return $servicios;
}

// funcion para insert
function insert_servicios( $conexion, $no_cuenta, $proveedores, $fecha_inicio, $fecha_renova, $costo_renova, $ubicacion, $detalles) {
	
    include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/historial.php' );
    // guardar archivos
    $directorio = $_SERVER['DOCUMENT_ROOT'] . "/uploads/servicios/" .$no_cuenta;

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
    INSERT INTO `servicios`(
     `no_cuenta`,
    `detalles`,
    `fecha_inicio`,
    `fecha_renova`,
    `ubicacion`,
    `costo_renova`,
    `proveedores`,
    `activo`
    ) VALUES (?,?,?,?,?,?,?, 1)" );
    mysqli_stmt_bind_param( $stmt, "sssssss",$no_cuenta, $detalles,  $fecha_inicio, $fecha_renova, $ubicacion, $costo_renova,  $proveedores);
$usu_usuario = $_SESSION[ 'user_name' ];
$accion = "Registro el servicio de: " . $no_cuenta;

    if (mysqli_stmt_execute($stmt)) {
		
    insert_history( $conexion, $usu_usuario, $accion );
        echo "<script>location.href='servicios'</script>";
        exit;
    } else {
        echo "<script>location.href='error'</script>";
    }
}
function delete_servicios($conexion, $id) {
    //variables para el historial
    $usu_usuario = $_SESSION['user_name'];
    $accion = "Elimino el servicio con id: " . $id;

    $stmt = mysqli_prepare($conexion, "UPDATE `servicios` SET `activo`='0' WHERE `id_servicios` = ?");
    $stmt->bind_param("i", $id);  // Cambiado "s" a "i" para indicar un número entero

    if ($stmt->execute()) {
        include($_SERVER['DOCUMENT_ROOT'] . '/assets/historial.php');
        insert_history($conexion, $usu_usuario, $accion);
        echo "<script>location.href='servicios'</script>";
        exit;
    } else {
        echo "<script>location.href='error_page'</script>";
    }
}
// funcion para ver la tabla de equipos
function view_servicio( $conexion, $id_servicios ) {
 $stmt = mysqli_prepare( $conexion, "
SELECT
    *
FROM
    `servicios`
WHERE
    `id_servicios`= ?" );
	$stmt->bind_param( "s", $id_servicios );
    $stmt->execute();
    $servicio = $stmt->get_result()->fetch_assoc();

    return $servicio;
}

?>