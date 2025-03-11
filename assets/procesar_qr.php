<?php
// Recibir el contenido del código QR
$qr_content = $_POST['qr_content'];

// Validar el contenido del código QR (puedes agregar más validaciones según tus necesidades)
if (filter_var($qr_content, FILTER_VALIDATE_URL)) {
    // Redirigir al usuario a la URL procesada
    $response = array('redirect_url' => $qr_content);
} else {
    // Enviar una respuesta de error si el contenido del código QR no es una URL válida
    $response = array('error' => 'El código QR no es una URL válida.');
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
