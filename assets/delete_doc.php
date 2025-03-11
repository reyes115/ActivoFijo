<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/conexion.php');
include($_SERVER['DOCUMENT_ROOT'] . '/assets/historial.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $ruta = $_POST['rutaArchivo'];
  $file = $_POST['borrarArchivo'];

  // Obtener la fecha y hora actual
  $fechaActual = date('Y-m-d');
  $horaActual = date('H-i-s');

  //variables para guardar historial
  $usu_usuario = $_SESSION['user_name'];
  $accion = "Elimino el archivo: " . $file;

  // Construir el nuevo nombre de archivo
  $nuevoNombre = $fechaActual . '_' . $horaActual . '_' . $file;

  // Obtener la ruta de la carpeta de destino
  $carpetaDestino = $_SERVER['DOCUMENT_ROOT'] . '/trash/' . $fechaActual;

  // Verificar si la carpeta de destino existe, de lo contrario, crearla
  if (!file_exists($carpetaDestino)) {
    mkdir($carpetaDestino, 0777, true);
  }

  // Mover el archivo a la carpeta de destino con el nuevo nombre
  if (rename($_SERVER['DOCUMENT_ROOT'] . $ruta . $file, $carpetaDestino . '/' . $nuevoNombre)) {
   insert_history( $conexion, $usu_usuario, $accion );
   echo "<script>
    window.history.go(-1); 
    setTimeout(function(){
        location.reload();
    }, 1000); // Tiempo de espera en milisegundos (1000ms = 1 segundo)
</script>";
  } else {
    echo "<script>location.href='error'</script>";
  }
}
?>