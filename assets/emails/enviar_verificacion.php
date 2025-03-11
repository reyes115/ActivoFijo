<?php
// Configuración de la base de datos
$config = array(
    'servername' => 'localhost',
    'username' => 'u470151145_ab_forti',
    'password' => 'A8#BfO2r0T4i!',
    'dbname' => 'u470151145_ceers_2_0'
);

// Conexión a la base de datos
$conexion = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

if ($conexion->connect_errno) {
    echo "Error de conexión: " . $conexion->connect_error;
    exit();
}

// Función para verificar y devolver el valor o "******" si está vacío o no existe
function checkField($field) {
    return isset($field) && !empty($field) ? $field : "******";
}

// Obtener los datos enviados por POST
$email = $_POST['email'];
$id_autos = $_POST['id_autos'];

// Obtener los datos del auto y el usuario correspondiente a ese id_autos
$stmt = mysqli_prepare($conexion, "
    SELECT 
        autos.*, 
        propietarios.nombre AS propietario, 
        personal.nombre, 
        personal.a_paterno, 
        personal.a_materno, 
        personal.id_depar, 
        personal.email 
    FROM autos 
    LEFT JOIN personal ON autos.personal_id_personal = personal.id_personal 
    LEFT JOIN propietarios ON autos.id_propietario = propietarios.id_propietario 
    WHERE autos.id_autos = ? AND autos.activo = 1 AND autos.estatus = 1");
$stmt->bind_param("i", $id_autos);
$stmt->execute();
$result = $stmt->get_result();

// Obtener los datos del auto
$row = $result->fetch_assoc();

if ($row) {
    // Crear el nombre del usuario en formato "NOMBRE APELLIDO_PATERNO APELLIDO_MATERNO"
    $usuario = strtoupper(checkField($row['nombre']) . ' ' . checkField($row['a_paterno']) . ' ' . checkField($row['a_materno']));

    // Obtener el último dígito de la placa del auto
    preg_match('/(\d)(?!.*\d)/', $row['placas'], $matches);
    $numero = $matches[1] ?? null;

    // Obtener el mes actual
    $mes_actual = date('n');

    // Determinar el periodo de verificación basado en el último dígito de la placa y el mes actual
    $periodo = '';
    if ($mes_actual <= 6) {
        switch ($numero) {
            case 5:
            case 6:
                $periodo = "Enero - Febrero";
                break;
            case 7:
            case 8:
                $periodo = "Febrero - Marzo";
                break;
            case 3:
            case 4:
                $periodo = "Marzo - Abril";
                break;
            case 1:
            case 2:
                $periodo = "Abril - Mayo";
                break;
            case 9:
            case 0:
                $periodo = "Mayo - Junio";
                break;
        }
    } else {
        switch ($numero) {
            case 5:
            case 6:
                $periodo = "Julio - Agosto";
                break;
            case 7:
            case 8:
                $periodo = "Agosto - Septiembre";
                break;
            case 3:
            case 4:
                $periodo = "Septiembre - Octubre";
                break;
            case 1:
            case 2:
                $periodo = "Octubre - Noviembre";
                break;
            case 9:
            case 0:
                $periodo = "Noviembre - Diciembre";
                break;
        }
    }

    // Mensaje por si el usuario no tiene un email registrado
    $no_tiene = '';
    if (empty($email)) {
        $email = "soporte@ab-forti.com";
        $no_tiene = "<b>El usuario no tiene email registrado, favor de comunicarle acerca de la fecha de verificación</b>";
    }

    // Crear el mensaje del correo
    $from = "soporte@ab-forti.com";
    $subject = 'CORREO AVISO DE VERIFICACION ' . checkField($row['marca']) . ' ' . checkField($row['tipo']) . ' ' . checkField($row['modelo']) . ' ' . checkField($row['vin']) . ' ' . checkField($row['propietario']);
    $message = '
    <html>
    <head>
    </head>
    <body>
      <p>Buen día, <b>' . $usuario . ', </b></p>
      <p>El motivo de mi correo es recordarle que la verificación del vehículo <b> ' . checkField($row['marca']) . ' ' . checkField($row['tipo']) . ' ' . checkField($row['modelo']) . ' ' . checkField($row['vin']) . ' </b>asignado a su persona está próxima a realizar, es necesario agendar cita y llevar el vehículo a verificar antes del término del periodo de <b>' . $periodo . '</b> en curso.</p>
      <p>En caso de presentarlo fuera del plazo correspondiente, los gastos de verificación y multa serán responsabilidad del usuario.</p>
      <p>De su amable apoyo en compartir el comprobante de pago y la verificación actualizada una vez concluido el trámite al correo administracion@ab-forti.com</p>
      <p>Muchas gracias.</p>
      <p>' . $no_tiene . '</p>
      <br>
      <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExMTc4MWM2MGZmMTZjZmNlNTBmNTE1MmUxMzhhZjdlOWJhZjdhMDFhYSZjdD1n/Zk7n6SilbOJjXkZFtZ/giphy.gif">
    </body>
    <footer>
     <span>Derechos Reservados &copy; 2024. AB FORTI CORPORATIVO.</span> 
    </footer>    
    </html>';

    // Encabezados del correo
    $headers = "From:" . $from . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "Reply-To: administracion@ab-forti.com\r\n";
    $headers .= "Cc: administracion@ab-forti.com\r\n";

    // Enviar el correo
    if (mail($email, $subject, $message, $headers)) {
        $response = array("status" => "success", "message" => "Correo enviado correctamente");
    } else {
        $response = array("status" => "error", "message" => "Error al enviar el correo");
    }
} else {
    $response = array("status" => "error", "message" => "Error al obtener los datos.");
}

header('Content-Type: application/json');
echo json_encode($response);

$stmt->close();
$conexion->close();
?>
