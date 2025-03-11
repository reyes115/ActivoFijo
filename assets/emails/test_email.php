<?php
// Configuración de la base de datos
$config = array(
    'servername' => 'localhost',
    'username' => 'u470151145_ab_forti',
    'password' => 'A8#BfO2r0T4i!',
    'dbname' => 'u470151145_ceers_2_0'
);

// Crear conexión
$conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

// Verificar conexión
if ($conn->connect_error) {
    die("La conexión falló: " . $conn->connect_error);
}

// ID personal para prueba
$id_personal = 144;

// Consulta SQL para obtener el correo electrónico
$sql = "SELECT email FROM personal WHERE id_personal = '$id_personal'";
$result = $conn->query($sql);

// Verificar si se encontraron resultados
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $email = $row['email'];

    if (!empty($email)) {
        // Preparar el correo
        $subject = "Asunto del Correo";
        $message = "Este es el cuerpo del correo.";
        $headers = "From: informatica@innovet.com.mx";

        // Enviar el correo
        if (mail($email, $subject, $message, $headers)) {
            echo "<h1>Correo enviado correctamente a: $email</h1>";
        } else {
            echo "<h1>Error al enviar el correo.</h1>";
        }
    } else {
        echo "<h1>Correo no asignado a este usuario.</h1>";
    }
} else {
    echo "<h1>Usuario no encontrado.</h1>";
}

$conn->close();
?>
