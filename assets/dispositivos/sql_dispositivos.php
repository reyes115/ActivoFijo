<?php
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header( "Location: denegado" );
    exit; // También puedes usar die en lugar de exit
}

// funcion para ver la tabla de equipos
function view_dispositivos( $conexion ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
   `perifericos`.*,
   `personal`.`nombre`,
		`personal`.`a_paterno`,
		`personal`.`a_materno`
FROM
    `perifericos`
LEFT JOIN personal ON id_personal = personal_id
WHERE
    `perifericos`.activo = 1" );
    $stmt->execute();
    $dispositivos = $stmt->get_result();

    return $dispositivos;
}

function insert_dispositivos( $conexion, $propietario, $no_serie, $estado, $costo, $fecha, $usuarioAsignado, $caracteristicas ) {

    include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/historial.php' );


	// Consulta preparada para obtener el código del propietario
$stmt = $conexion->prepare( "SELECT codigo FROM propietarios WHERE id_propietario = ?" );
$stmt->bind_param( "i", $propietario );
$stmt->execute();
$stmt->bind_result( $pro );
$stmt->fetch();
$stmt->close();

// Consulta preparada para obtener el número de la empresa
$stmt = $conexion->prepare( "SELECT empresa_id_empresa FROM personal LEFT JOIN departamentos ON id_depar = id_depa WHERE id_personal = ?" );
$stmt->bind_param( "i", $usuarioAsignado );
$stmt->execute();
$stmt->bind_result( $dep );
$stmt->fetch();
$stmt->close();

// Complementos para el código de registro
$ceros = "0";
$dispositivos = "DIA";

// Obtención del valor continuo
$stmt = $conexion->prepare( "SELECT MAX(cons) as cons FROM perifericos LIMIT 1" );
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

$codigo = $pro . $ceros . $dep . $dispositivos . $cons;

// guardar archivos
$directorio = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/dispositivos/$codigo"; //Declaramos un  variable con la ruta donde guardaremos los archivos

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

// colocar una fecha de mantenimiento 
// Obtener la fecha de compra de $_POST si está definida, de lo contrario, establecerla como vacía
$fecha_compra = isset($fecha) ? $fecha : '';

// Si la fecha de compra está vacía, utiliza la fecha actual
if (empty($fecha_compra) || !strtotime($fecha_compra)) {
    $fecha_compra = date('Y-m-d'); // Obtener la fecha actual
}
	
//generar un qr 	
$QRKey = uniqid();  // clave unica del para el codigo qr
$t_qr = "dispositivos";
$urlQr="https://ceers.innovet.com.mx/ver_dispositivos".$QRKey;
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/generartor_qr.php' );

    $stmt = mysqli_prepare( $conexion, "
    INSERT INTO `perifericos`(
    `codigo`,
    `no_serie`,
    `caracteristicas`,
    `Estado`,
    `fecha`,
    `costo`,
    `personal_id`,
    `id_propietario`,
    `QRKey`,
    `cons`,
    `activo`
    ) VALUES (?,?,?,?,?,?,?,?,?,?,1)" );
    mysqli_stmt_bind_param( $stmt, "ssssssssss", $codigo, $no_serie, $caracteristicas, $estado, $fecha_compra, $costo, $usuarioAsignado, $propietario, $QRKey, $cons );
	$usu_usuario = $_SESSION[ 'user_name' ];
$accion = "Registro nuevo equipo alterno: " . $codigo;
    if ( mysqli_stmt_execute( $stmt ) ) {
		
    insert_history( $conexion, $usu_usuario, $accion );
        echo "<script>location.href='dispositivos'</script>";
        exit;
    } else {
        echo "<script>location.href='error'</script>";
    }
}
// funcion para ver datos del equipo
function view_equipo( $conexion, $codigoQR ) {
    $stmt = mysqli_prepare( $conexion, "
	 SELECT
		`perifericos`.*,
		`personal`.`nombre`,
		`personal`.`a_paterno`,
		`personal`.`a_materno`,
		`personal`.`id_depar`,
		`propietarios`.`nombre` AS `propietario`
	FROM
		`perifericos`
	LEFT JOIN `personal` ON `personal_id` = `id_personal`
	LEFT JOIN `propietarios` ON `perifericos`.`id_propietario` = `propietarios`.`id_propietario`
	WHERE
		`QRKey` = ?" );
	$stmt->bind_param( "s", $codigoQR );
    $stmt->execute();
    $equipo = $stmt->get_result()->fetch_assoc() ;
	return $equipo;

}
function delete_dispositivos($conexion, $id) {
    //variables para el historial
    $usu_usuario = $_SESSION['user_name'];
    $accion = "Elimino el registro del equipo alterno: " . $id;

    $stmt = mysqli_prepare($conexion, "UPDATE `perifericos` SET `activo`='0' WHERE `id_perifericos` = ?");
    $stmt->bind_param("i", $id);  // Cambiado "s" a "i" para indicar un número entero

    if ($stmt->execute()) {
        include($_SERVER['DOCUMENT_ROOT'] . '/assets/historial.php');
        insert_history($conexion, $usu_usuario, $accion);
        echo "<script>location.href='dispositivos'</script>";
        exit;
    } else {
        echo "<script>location.href='error_page'</script>";
    }
}

?>