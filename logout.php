<?php
session_start();

// Verifica si la sesión 'user_ceers' está establecida y no es nula
if(isset($_SESSION['user_ceers']) && $_SESSION['user_ceers']){
    // Destruye la sesión
    session_destroy();
    
    // Redirige a la página de inicio de ceers
    header("location: /");
    exit(); // Asegura que el script se detenga después de redirigir
} else {
    // Redirige a la página de error
    header("location: error_page");
    exit(); // Asegura que el script se detenga después de redirigir
}
?>
