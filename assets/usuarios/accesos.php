<?php
session_start();

if (!isset($_SESSION['user_ceers']) || $_SESSION['user_ceers'] === null || $_SESSION['activo'] == 0) {
    session_destroy();
    header("Location: denegado");
    exit;
}

include($_SERVER['DOCUMENT_ROOT'] . '/conexion.php');

$accion = $_POST['accion'];

if ($accion == 'bloquear'){
	
	$id= $_POST['id_usuarioB'];
    $stmt = $conexion->prepare("UPDATE `usuarios` SET `activo`= 0 WHERE `id_usuarios`=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
}elseif ($accion == 'activar'){
	
	$id= $_POST['id_usuarioA'];
    $stmt = $conexion->prepare("UPDATE `usuarios` SET `activo`= 1 WHERE `id_usuarios`=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
}else {
    // Manejo de errores en caso de falla en la inserción
    echo "<script>location.href='error_page'</script>";

}

echo "<script>window.history.back(); location.reload();</script>";
exit();
?>