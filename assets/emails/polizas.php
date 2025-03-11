<?php
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
}

// Consulta para obtener las pólizas próximas a vencer
$stmt = mysqli_prepare($conexion, "
SELECT
    polizas.*,
    DATE_FORMAT(fin_vigencia, '%d/%m/%Y') AS fecha_fin,
    CASE `tipo` WHEN '1' THEN 'Auto' WHEN 2 THEN 'Vida' WHEN 3 THEN 'Gastos médicos' WHEN 4 THEN 'Daños'
END AS tipos
FROM
    polizas
WHERE
    fin_vigencia BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND polizas.activo = 1 AND note = '0';");

$stmt->execute();
$result = $stmt->get_result();

while ($row = mysqli_fetch_array($result)) {
    // Lógica para obtener los datos del bien asegurado y el usuario
    switch ($row['tipo']) {
        case 1:
            $id_autos = $row['asegurado_auto'];
            $datos = ver_autos($conexion, $id_autos);
            $bien = $datos['marca'] . ' ' . $datos['tipo'] . ' ' . $datos['modelo'];
            $usuario = strtoupper($datos["nombre"] . ' ' . $datos["a_paterno"] . ' ' . $datos["a_materno"]);
            break;
        case 2:
        case 3:
            $id_personal = $row['asegurado_col'];
            $datos = ver_usuario($conexion, $id_personal);
            $bien = strtoupper($datos["nombre"] . ' ' . $datos["a_paterno"] . ' ' . $datos["a_materno"]);
            $usuario = $bien;
            break;
        case 4:
            $id_inm = $row['asegurado_inm'];
            $datos = ver_inmobiliario($conexion, $id_inm);
            $bien = strtoupper($datos["name"]);
            $usuario = "";
            break;
    }

    // Fijar el correo de administración como destinatario único
    $email = "administracion@ab-forti.com";

    $no_tiene = " ";
    if (empty($email)) {
        $no_tiene = "<b>El usuario no tiene email registrado, favor de comunicarle acerca de la fecha de verificación</b>";
    }

    // Configuración para el envío de correo
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $from = "informatica@innovet.com.mx";
    $to = $email;
    $subject = 'Recordatorio para renovación de póliza';
    $message = '
	<html>
<head></head>
<body>
  <p>Apreciable: Gabriela Huerta Cruz </p>

  <p>Le recordamos que su Póliza, la cual se describe a continuación está próxima a vencer, por lo que lo invitamos a realizar su renovación y evitar quedarse sin cobertura.</p>
  <table style="width: 100%; text-align: center;" border="1">
  <thead>
	<tr>
	<th>Folio</th>
	<th>Tipo de póliza</th>
	<th>Bien asegurado</th>
	<th>Fecha de vencimiento</th>
	</tr>
	</thead>
	<tbody>
	<tr>
        <td style="text-align: center;">' . $row['no_poliza'] . '</td>
        <td style="text-align: center;">' . $row['tipos'] . '</td>
        <td style="text-align: center;">' . $bien . '</td>
        <td style="text-align: center;">' . $row['fecha_fin'] . '</td>
    </tr>
	</tbody>
  </table>

  <p>Cualquier duda o comentario estamos a sus órdenes.</p>
  <p>' . $no_tiene . '</p>
  <br>
  <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExMTc4MWM2MGZmMTZjZmNlNTBmNTE1MmUxMzhhZjdlOWJhZjdhMDFhYSZjdD1n/Zk7n6SilbOJjXkZFtZ/giphy.gif">
</body>
<footer>
    <span>Copyright &copy; 2024. AB FORTI CORPORATIVO.</span>
</footer>
</html>';

    $headers = "From:" . $from . "\r\n";
    $headers .= "MIME-Version: Sistema de control AB FORTI\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "Reply-To: administracion@ab-forti.com\r\n";
    $headers .= "Cc:administracion@ab-forti.com\r\n";

    // Actualización del estado de la póliza
    $stmt2 = mysqli_prepare($conexion, "UPDATE `polizas` SET `note` = 1 WHERE `id_poliza` = ?");
    $stmt2->bind_param("s", $row['id_poliza']);

    // Envío del correo
    if (mail($to, $subject, $message, $headers) && $stmt2->execute()) {
        echo "Correo enviado";
    } else {
        echo "Error en el envío";
    }
}

$stmt->close();
$conexion->close();

// Funciones adicionales para obtener datos de usuarios, autos e inmobiliario
function ver_usuario($conexion, $id_personal) {
    $stmtu = mysqli_prepare($conexion, "
    SELECT `personal`.`nombre`, `personal`.`a_paterno`, `personal`.`a_materno`, `personal`.`id_depar`, email
    FROM personal WHERE `id_personal` = ?");
    $stmtu->bind_param("s", $id_personal);
    $stmtu->execute();
    return $stmtu->get_result()->fetch_assoc();
}

function ver_autos($conexion, $id_autos) {
    $stmt = mysqli_prepare($conexion, "
    SELECT `autos`.`id_autos`, `autos`.`codigo`, `autos`.`marca`, `autos`.`tipo`, `autos`.`modelo`, `autos`.`vin`, 
    `propietarios`.`nombre` AS `propietario`, `personal`.`nombre`, `personal`.`a_paterno`, `personal`.`a_materno`, `personal`.`id_depar`, email
    FROM autos
    LEFT JOIN personal ON `id_personal` = `personal_id_personal`
    LEFT JOIN propietarios ON `autos`.`id_propietario` = `propietarios`.`id_propietario`
    WHERE `id_autos` = ?");
    $stmt->bind_param("s", $id_autos);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function ver_inmobiliario($conexion, $id_inm) {
    $stmt = mysqli_prepare($conexion, "SELECT `id_inmobiliario`, `name`, `email` FROM inmobiliario WHERE `id_inmobiliario` = ?");
    $stmt->bind_param("s", $id_inm);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
?>
