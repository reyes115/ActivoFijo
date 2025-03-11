<?php

header("Content-Type: application/json");
include($_SERVER['DOCUMENT_ROOT'] . '/conexion.php');

if ( $_SERVER[ "REQUEST_METHOD" ] === "POST" ) {
$username = $_POST[ "username" ];
$password = $_POST[ "password" ]; 
//$password =  "21163708" ;
 //$username =  "scantask2024" ;
 
$sql = "SELECT * FROM `usuarios` WHERE `email` = ? AND `password` = ?";
$stmt = $conexion->prepare( $sql );
$stmt->bind_param( "ss", $username,$password );
$stmt->execute();
$resultados = $stmt->get_result();

// Verificar si se encontraron resultados
if ( $resultados->num_rows > 0 ) {

   
  // Devuelve el arreglo de datos en formato JSON
  echo json_encode(  [
      "respuesta" => "1"
  ] );
 
}else{
      
  // No se encontraron resultados
  echo json_encode( [ "respuesta" => "0" ] );
}
} else {
  // Método no permitido
  http_response_code( 405 );
  echo json_encode( [ "message" => "Método no permitido" ] );
}
// Cerrar la conexión
$conexion->close();
?>