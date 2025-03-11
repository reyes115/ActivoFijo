<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['archivo_temporal'])) {
    $archivoTemporal = $_POST['archivo_temporal'];
    $nombreOriginal = $_POST['nombre_original'];
    $extension = $_POST['extension'];
    
    // Directorio permanente donde deseas mover los archivos
    $directorioPermanente = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/factura/";

    // Crear el nombre del archivo permanente con el nombre original y la extensión
    $nombrePermanente = $directorioPermanente . $nombreOriginal;

    // Mover el archivo temporal al directorio permanente con el nombre permanente
    if (rename($archivoTemporal, $nombrePermanente)) {
        echo "El archivo se ha guardado permanentemente como $nombreOriginal.";
    } else {
        echo "Error al intentar guardar el archivo permanentemente.";
    }
}
?>
