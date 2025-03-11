<?php
// Verificar si la variable de sesión 'user_ceers' está definida y no es nula
if (!isset($_SESSION['user_ceers']) || $_SESSION['user_ceers'] === null || $_SESSION['activo'] == 0) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header("Location: denegado");
    exit; // También puedes usar die en lugar de exit
}

// Preparar la consulta SQL para obtener información del usuario
$sql_access = $conexion->prepare("SELECT * FROM access WHERE `id_usuario` = ?");
$sql_access->bind_param('i', $_SESSION['id_ceers']);
$sql_access->execute();

// Obtener el resultado de la consulta
$access = $sql_access->get_result()->fetch_assoc();

// Cerrar la conexión a la base de datos
$sql_access->close();

// Puedes continuar con el resto de tu código utilizando la variable $access para acceder a los datos del usuario
?>
