<?php 
// funcion para ver la tabla de operadores
function view_propietarios( $conexion ) {
  $stmt = mysqli_prepare( $conexion, "SELECT * FROM `propietarios`" );
  $stmt->execute();
  $propietarios = $stmt->get_result();
 
  return $propietarios;
}


?>