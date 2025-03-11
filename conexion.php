<?php
include_once 'scr/data_access.php';

// Resto de tu código
$conexion= new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

if ($conn->connect_errno) {
   echo "Error de conexión: " . $conexion->connect_error;
}

// Configuración de la zona horaria y obtención de la fecha actual
date_default_timezone_set('America/Mexico_City');
$fecha = date("Y-m-d H:i:s");
$dia = date("d", strtotime($fecha));
$mes = date("m", strtotime($fecha));
$anio = date("Y", strtotime($fecha));
?>
