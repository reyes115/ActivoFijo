<?php
// Establecer la función de manejo de errores personalizada
function errorHandler($errno, $errstr, $errfile, $errline) {
    // Redirigir a la página de error HTML
    header("Location: error_page");
    exit();
}

// Establecer la función de manejo de errores
set_error_handler("errorHandler");
?>
