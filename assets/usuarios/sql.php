<?php
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header( "Location: denegado" );
    exit; // También puedes usar die en lugar de exit
}
// funcion para ver la tabla de equipos
function view_usuarios( $conexion ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT * FROM `usuarios`" );
    $stmt->execute();
    $usuarios = $stmt->get_result();

    return $usuarios;
}
function view_departamentos( $conexion) {
    $stmt = mysqli_prepare( $conexion, "SELECT * FROM departamentos" );
    $stmt->execute();
    $departamentos = $stmt->get_result();
	return $departamentos;

}
function view_usuario( $conexion, $id_usuario) {
    $stmt = mysqli_prepare( $conexion, "
SELECT usuarios.*, `computo`, `moviles`, `dispositivos`, `personal`, `licencias`, `servicios`, access.`password` AS 'm_password', `autos`, `stock`, `maquinaria`, `polizas`, `accesos`, `android` FROM `usuarios` LEFT JOIN access ON usuarios.id_usuarios = access.id_usuario WHERE `id_usuarios` = ?" );
	$stmt->bind_param("s", $id_usuario);
    $stmt->execute();
    $usuario =$stmt->get_result()->fetch_assoc() ;
	return $usuario;

}
function view_android( $conexion) {
    $stmt = mysqli_prepare( $conexion, "SELECT * FROM versiones_app ORDER BY `id_version` DESC" );
    $stmt->execute();
    $android = $stmt->get_result();
	return $android;

}

function delete_app( $conexion, $id ){
	   $stmt = mysqli_prepare( $conexion, "SELECT * FROM `versiones_app` WHERE `id_version`= ?" );
	$stmt->bind_param("s", $id);
    $stmt->execute();
    $version =$stmt->get_result()->fetch_assoc() ;
	$stmt->close();
	$ruta_actual= $_SERVER[ 'DOCUMENT_ROOT' ] . '/apk/app/'.$version['apk_url'];
	$ruta_nueva= $_SERVER[ 'DOCUMENT_ROOT' ] . '/apk/app/delete_version/'.$version['apk_url'];
	
	// Mover el archivo a la nueva ruta
if (rename($ruta_actual, $ruta_nueva)) {
       //variables para el historial
    $usu_usuario = $_SESSION['user_name'];
    $accion = "Elimino la apk: " . $version['apk_url'];

    $stmt = mysqli_prepare($conexion, "DELETE FROM `versiones_app` WHERE `id_version` = ?");
    $stmt->bind_param("i", $id);  // Cambiado "s" a "i" para indicar un número entero

    if ($stmt->execute()) {
        include($_SERVER['DOCUMENT_ROOT'] . '/assets/historial.php');
        insert_history($conexion, $usu_usuario, $accion);
        echo "<script>location.href='android'</script>";
        exit;
    } else {
        echo "<script>location.href='error_page'</script>";
    }
} else {
   echo "<script>location.href='error_page'</script>";

}
}




















