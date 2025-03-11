<?php
// Verifica si se ha recibido una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene los datos del evento en formato JSON
    $post_data = file_get_contents("php://input");
    // Decodifica los datos JSON
    $event_data = json_decode($post_data, true);
    // Registra el evento en el archivo de registro
    register_event($event_data);
    // Envía una respuesta al lector de huellas
    echo "Evento recibido correctamente";
} else {
    // Si la solicitud no es POST, devuelve un mensaje de error
    http_response_code(405);
    echo "Método no permitido";
}

function register_event($event_data) {
    // Obtiene la fecha y hora actual
    $date_time = date("Y-m-d H:i:s");
    // Crea un string con la información del evento
    $event_info = "Fecha: $date_time, Usuario: {$event_data['usuario']}, Acción: {$event_data['accion']}\n";
    // Abre el archivo de registro en modo append
    $log_file = fopen("eventos.log", "a");
    // Escribe el evento en el archivo de registro
    fwrite($log_file, $event_info);
    // Cierra el archivo de registro
    fclose($log_file);
}
?>
