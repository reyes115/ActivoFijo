<?php
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header( "Location: denegado" );
    exit; // También puedes usar die en lugar de exit
}

// funcion para ver la tabla de equipos
function view_licencias( $conexion ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
    *
FROM
    `licencias`
WHERE
    activo = 1" );
    $stmt->execute();
    $licencias = $stmt->get_result();

    return $licencias;
}
function view_licencia( $conexion, $idlicencia ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
    *
FROM
    `licencias`
WHERE
   `id_licencias`= ?" );
	$stmt->bind_param( "s", $idlicencia );
    $stmt->execute();
    $licencias = $stmt->get_result()->fetch_assoc();

    return $licencias;
}
function view_count_licencia($conexion, $idlicencia) {
    $stmt = mysqli_prepare($conexion, "SELECT COUNT(licencias_id_licencias) as count FROM computadora_has_licencias WHERE licencias_id_licencias = ?");
    $stmt->bind_param("s", $idlicencia);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = $row['count'];
    return $count;
}

//funcion para hacer un insert en la tabla clientes 
function insert_licencias( $conexion,  $nombre_licencias, $fecha_inicio, $fecha_fin,$clave, $limite_usuarios,$costo, $tipo, $observaciones, $provedor) {
	
    include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/historial.php' );
    // guardar archivos
    $directorio = $_SERVER['DOCUMENT_ROOT'] . "/uploads/licencias/" .$nombre_licencias;

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
    INSERT INTO `licencias`(
    `nombre_licencias`,
    `fecha_inicio`,
    `fecha_fin`,
    `clave`,
    `limite_usuarios`,
    `costo`,
    `tipo`,
    `observaciones`,
    `provedor`,
    `activo`
    ) VALUES (?,?,?,?,?,?,?,?,?, 1)" );
    mysqli_stmt_bind_param( $stmt, "sssssssss", $nombre_licencias, $fecha_inicio, $fecha_fin,$clave, $limite_usuarios,$costo, $tipo, $observaciones, $provedor);
$usu_usuario = $_SESSION[ 'user_name' ];
$accion = "Registro la nueva lincencia de: " . $nombre_licencias;

    if (mysqli_stmt_execute($stmt)) {
		
    insert_history( $conexion, $usu_usuario, $accion );
        echo "<script>location.href='licencias'</script>";
        exit;
    } else {
        echo "<script>location.href='error'</script>";
    }
}

// funcion para ver sistemas operativos 
function view_SO( $conexion ) {
    $stmt = mysqli_prepare( $conexion, "
 	SELECT
		`id_licencias`,
		`nombre_licencias`
	FROM
		`licencias`
	WHERE
    	`tipo` = 3 AND `activo` = 1" );
    $stmt->execute();
    $SO = $stmt->get_result();

    return $SO;
}
// funcion para ver sistemas de office 
function view_office( $conexion ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
    `id_licencias`,
    `nombre_licencias`
FROM
    `licencias`
WHERE
    `tipo` = 2 AND `activo` = 1" );
    $stmt->execute();
    $office = $stmt->get_result();

    return $office;
}
// funcion para ver sistemas de antiviruz
function view_antivirus( $conexion ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT
    `id_licencias`,
    `nombre_licencias`
FROM
    `licencias`
WHERE
    `tipo` = 1 AND `activo` = 1" );
    $stmt->execute();
    $antivirus = $stmt->get_result();

    return $antivirus;
}

function insertLicencia( $conexion, $computadora_id, $licenciaId ) {
    // Verificar si el ID de la licencia no está vacío y $licenciaId no es nulo
    if ( $licenciaId != 999 ) {
        // Consulta preparada para insertar licencia
        $query = "INSERT INTO `computadora_has_licencias`(`computadora_id_compu`, `licencias_id_licencias`) VALUES (?, ?)";

        // Preparar la consulta
        $stmt = $conexion->prepare( $query );

        // Vincular parámetros
        $stmt->bind_param( "ss", $computadora_id, $licenciaId );
        $stmt->execute();
        // La inserción fue exitosa
        $stmt->close();
    } else {
        // Si $licenciaId está vacío o nulo, simplemente retornar sin hacer nada
        return;
    }
}


function generarOpciones( $conexion, $tipo, $id_compu ) {
    $query = "SELECT id_licencias, nombre_licencias FROM licencias 
              LEFT JOIN computadora_has_licencias ON id_licencias = licencias_id_licencias 
              LEFT JOIN computadora ON computadora_id_compu = id_compu 
              WHERE computadora_has_licencias.computadora_id_compu = $id_compu AND licencias.tipo = $tipo ";

    $result = mysqli_query( $conexion, $query );
    $row = mysqli_fetch_array( $result );

    if ( empty( $row ) ) {
        $row[ 'nombre_licencias' ] = "Seleccione";
    }

    echo '<option value="' . $row[ 'id_licencias' ] . '">' . $row[ 'nombre_licencias' ] . '</option>';
    echo '<option value="999">Quitar licencia</option>';

    $queryAll = "SELECT id_licencias, nombre_licencias FROM licencias WHERE tipo = $tipo AND `activo` = 1";
    $resultAll = mysqli_query( $conexion, $queryAll );

    while ( $mostrar = mysqli_fetch_array( $resultAll ) ) {
        echo '<option value="' . $mostrar[ 'id_licencias' ] . '">' . $mostrar[ 'nombre_licencias' ] . '</option>';
    }
}


function actualizarLicencia( $conexion, $id_compu, $tipo, $valor, $default ) {
    $stmt = mysqli_prepare( $conexion, "SELECT id_relacion FROM computadora_has_licencias
        LEFT JOIN licencias ON id_licencias = licencias_id_licencias
        LEFT JOIN computadora ON computadora_id_compu = id_compu
        WHERE computadora_has_licencias.computadora_id_compu = ? AND licencias.tipo = ?" );
    mysqli_stmt_bind_param( $stmt, "ii", $id_compu, $tipo );
    mysqli_stmt_execute( $stmt );
    mysqli_stmt_bind_result( $stmt, $id_relacion );
    mysqli_stmt_fetch( $stmt );
    mysqli_stmt_close( $stmt );

    if ( $valor == $default ) {
        if ( isset( $id_relacion ) ) {
            mysqli_query( $conexion, "DELETE FROM `computadora_has_licencias` WHERE `computadora_has_licencias`.`id_relacion` = $id_relacion" );
        }
    } else {
        if ( isset( $id_relacion ) ) {
            $stmt = mysqli_prepare( $conexion, "UPDATE `computadora_has_licencias` SET `licencias_id_licencias` = ? WHERE `computadora_has_licencias`.`id_relacion` = ?" );
            mysqli_stmt_bind_param( $stmt, "ii", $valor, $id_relacion );
            mysqli_stmt_execute( $stmt );
            mysqli_stmt_close( $stmt );
        } else {
            $stmt = mysqli_prepare( $conexion, "INSERT INTO `computadora_has_licencias`(`computadora_id_compu`, `licencias_id_licencias`) VALUES (?, ?)" );
            mysqli_stmt_bind_param( $stmt, "ii", $id_compu, $valor );
            mysqli_stmt_execute( $stmt );
            mysqli_stmt_close( $stmt );
        }
    }
}

function licencias_equipo( $conexion, $tipo, $id_compu ) {
    $stmt = mysqli_prepare( $conexion, "SELECT id_licencias, nombre_licencias FROM licencias 
              LEFT JOIN computadora_has_licencias ON id_licencias = licencias_id_licencias 
              LEFT JOIN computadora ON computadora_id_compu = id_compu 
              WHERE computadora_has_licencias.computadora_id_compu = ? AND licencias.tipo = ? " );
    $stmt->bind_param( "is", $id_compu, $tipo ); // Cambiado "ss" a "is" para indicar entero y cadena
    $stmt->execute();
    $licencia = $stmt->get_result()->fetch_assoc();
    return $licencia;
}
function delete_licencias($conexion, $id) {
    //variables para el historial
    $usu_usuario = $_SESSION['user_name'];
    $accion = "Elimino ala licencia con id: " . $id;

    $stmt = mysqli_prepare($conexion, "UPDATE `licencias` SET `activo`='0' WHERE `id_licencias` = ?");
    $stmt->bind_param("i", $id);  // Cambiado "s" a "i" para indicar un número entero

    if ($stmt->execute()) {
        include($_SERVER['DOCUMENT_ROOT'] . '/assets/historial.php');
        insert_history($conexion, $usu_usuario, $accion);
        echo "<script>location.href='licencias'</script>";
        exit;
    } else {
        echo "<script>location.href='error_page'</script>";
    }
}
?>