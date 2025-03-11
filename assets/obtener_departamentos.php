<?php
session_start();
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
  session_destroy();
  // Redireccionar al usuario a la página de denegado
  header( "Location: denegado" );
  exit; // También puedes usar die en lugar de exit
}

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );
if(isset($_POST['empresa_id'])) {
    $empresa_id = $_POST['empresa_id'];
    $stmt = $conexion->prepare("SELECT * FROM `departamentos` WHERE empresa_id_empresa = ?");
    $stmt->bind_param("i", $empresa_id);
    $stmt->execute();
    $valores = $stmt->get_result();

    while ($ver = mysqli_fetch_array($valores)) {
        echo '<option value="' . $ver["id_depa"] . '">' . $ver["nombre"] . '</option>';
    }

    $stmt->close();
}
?>

