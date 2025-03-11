<?php
//funcion para hacer un insert en la tabla clientes 
function insert_history($conexion, $usu_usuario, $accion) {
	// Configuración de la zona horaria y obtención de la fecha actual
date_default_timezone_set('America/Mexico_City');
$fecha = date("Y-m-d H:i:s");
    $sql = "INSERT INTO movimientos (usuario, accion, fecha) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $usu_usuario, $accion, $fecha);
    $stmt->execute();
	
$stmt->close();

}

?>