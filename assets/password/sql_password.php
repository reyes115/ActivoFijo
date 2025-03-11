<?php
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header( "Location: denegado" );
    exit; // También puedes usar die en lugar de exit
}

// funcion para ver la tabla de equipos
function view_password( $conexion ) {
    $stmt = mysqli_prepare( $conexion, "
SELECT * FROM `pass` WHERE activo = 1 ORDER BY `tipo`;" );
    $stmt->execute();
    $password = $stmt->get_result();

    return $password;
}
// funcion para insert
function insert_password( $conexion, $tipo, $usuario , $password,$descripcion) {
	
    include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/historial.php' );
   
    $stmt = mysqli_prepare($conexion, "
   INSERT INTO `pass`(
    `tipo`,
    `descripcion`,
    `usuario-email`,
    `password`,
    `activo`
    ) VALUES (?,?,?,?, 1)" );
    mysqli_stmt_bind_param( $stmt, "ssss",$tipo,$descripcion, $usuario , $password);
$usu_usuario = $_SESSION[ 'user_name' ];
$accion = "Registro el la contraseña de: " . $usuario;

    if (mysqli_stmt_execute($stmt)) {
		
    insert_history( $conexion, $usu_usuario, $accion );
        echo "<script>location.href='password'</script>";
        exit;
    } else {
        echo "<script>location.href='error'</script>";
    }
}

// funcion para edit
function edit_password( $conexion, $tipo, $usuario , $password,$descripcion,$id_pass) {
	
    include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/historial.php' );
   
    $stmt = mysqli_prepare($conexion, "
   UPDATE
    `pass`
SET
    `tipo` = ?,
    `descripcion` = ?,
    `usuario-email` = ?,
    `password` = ?
WHERE 
	`id_pass` = ?" );
    mysqli_stmt_bind_param( $stmt, "sssss",$tipo,$descripcion, $usuario , $password,$id_pass);
$usu_usuario = $_SESSION[ 'user_name' ];
$accion = "actualizo el la contraseña de: " . $usuario;

    if (mysqli_stmt_execute($stmt)) {
		
    insert_history( $conexion, $usu_usuario, $accion );
        echo "<script>location.href='password'</script>";
        exit;
    } else {
        echo "<script>location.href='error'</script>";
    }
}
function delete_password($conexion, $id) {
    //variables para el historial
    $usu_usuario = $_SESSION['user_name'];
    $accion = "Elimino el la contrasña con id: " . $id;

    $stmt = mysqli_prepare($conexion, "UPDATE `pass` SET `activo`='0' WHERE `id_pass` = ?");
    $stmt->bind_param("i", $id);  // Cambiado "s" a "i" para indicar un número entero

    if ($stmt->execute()) {
        include($_SERVER['DOCUMENT_ROOT'] . '/assets/historial.php');
        insert_history($conexion, $usu_usuario, $accion);
        echo "<script>location.href='password'</script>";
        exit;
    } else {
        echo "<script>location.href='error_page'</script>";
    }
}
?>