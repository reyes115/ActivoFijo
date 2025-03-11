<?php
$config = array(
    'servername' => 'localhost',
    'username' => 'u470151145_ab_forti',
    'password' => 'A8#BfO2r0T4i!',
    'dbname' => 'u470151145_ceers_2_0'
);
$conexion = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

if ($conexion->connect_errno) {
    echo "Error de conexión: " . $conexion->connect_error;
    exit();
}

$email = $_POST['email'];
$id_servicio = $_POST['id_servicio'];

// Obtener los datos del servicio y usuario
$stmt = mysqli_prepare($conexion, "
    SELECT
        s.*, a.marca, a.tipo, a.modelo, a.vin, p.nombre, p.a_paterno, p.a_materno, prop.nombre AS propietario
    FROM
        servicio_autos s
    LEFT JOIN autos a ON s.autos_id = a.id_autos
    LEFT JOIN propietarios prop ON a.id_propietario = prop.id_propietario
    LEFT JOIN personal p ON a.personal_id_personal = p.id_personal
    WHERE s.id_servicio = ?
");
$stmt->bind_param("i", $id_servicio);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Función para verificar si una variable existe y no es nula
function checkField($field) {
    return isset($field) ? $field : "******";
}

if ($row) {
    $usuario = strtoupper(checkField($row["nombre"]) . ' ' . checkField($row["a_paterno"]) . ' ' . checkField($row["a_materno"]));
    $mes_num = date('m', strtotime(checkField($row['ultimo_servicio'])));
    $mes = '';
    switch ($mes_num) {
        case 1: $mes = "Enero"; break;
        case 2: $mes = "Febrero"; break;
        case 3: $mes = "Marzo"; break;
        case 4: $mes = "Abril"; break;
        case 5: $mes = "Mayo"; break;
        case 6: $mes = "Junio"; break;
        case 7: $mes = "Julio"; break;
        case 8: $mes = "Agosto"; break;
        case 9: $mes = "Septiembre"; break;
        case 10: $mes = "Octubre"; break;
        case 11: $mes = "Noviembre"; break;
        case 12: $mes = "Diciembre"; break;
    }

    $from = "informatica@innovet.com.mx";
    $subject = 'AVISO DE SERVICIO DE MANTENIMIENTO ' . checkField($row['marca']) . ' ' . checkField($row['tipo']) . ' ' . checkField($row['modelo']) . ' ' . checkField($row['vin']) . ' ' . checkField($row['propietario']);
    $message = '
    <html>
    <head>
    </head>
    <body>
      <p>Buen día, <b>' . $usuario . ', </b></p>
      <p>El motivo de mi correo es informarle que el último servicio de mantenimiento del vehículo <b> ' . checkField($row['marca']) . ' ' . checkField($row['tipo']) . ' ' . checkField($row['modelo']) . ' ' . checkField($row['vin']) . ' </b>fue realizado el <b>' . date('d', strtotime(checkField($row['ultimo_servicio']))) . ' de ' . $mes . ' del ' . date('Y', strtotime(checkField($row['ultimo_servicio']))) . '</b> por lo que es necesario realizar nuevamente el servicio de mantenimiento.</p>
      <p>De su amable apoyo por favor de informar cualquier reparación realizada fuera del esquema de servicio de mantenimiento.</p>
      <p>Favor de compartir el comprobante de pago y documento que acredite la realización del servicio una vez concluido el trámite al correo administracion@ab-forti.com</p>
      <p>Muchas gracias.</p>
      <br>
      <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExMTc4MWM2MGZmMTZjZmNlNTBmNTE1MmUxMzhhZjdlOWJhZjdhMDFhYSZjdD1n/Zk7n6SilbOJjXkZFtZ/giphy.gif">
    </body>
    <footer>
     <span>Copyright &copy; 2024. AB FORTI CORPORATIVO.</span> 
    </footer>    
    </html>';

    $headers = "From:" . $from . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "Reply-To: administracion@ab-forti.com\r\n";
    $headers .= "Cc: administracion@ab-forti.com\r\n";

    // Enviar el correo
    if (mail($email, $subject, $message, $headers)) {
        // Actualizar la base de datos
        $stmt2 = mysqli_prepare($conexion, "UPDATE servicio_autos SET note = 1 WHERE id_servicio = ?");
        $stmt2->bind_param("i", $id_servicio);
        $stmt2->execute();

        $response = array("status" => "success", "message" => "Correo enviado correctamente");
    } else {
        $response = array("status" => "error", "message" => "Error al enviar el correo");
    }
} else {
    $response = array("status" => "error", "message" => "Error al obtener los datos.");
}

// Devolver respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);

$stmt->close();
$conexion->close();
?>
