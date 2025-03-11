<?php
$config = array(
    'servername' => 'localhost',
    'username' => 'u470151145_ab_forti',
    'password' => 'A8#BfO2r0T4i!',
    'dbname' => 'u470151145_ceers_2_0'
);
// Resto de tu código
$conexion = new mysqli( $config[ 'servername' ], $config[ 'username' ], $config[ 'password' ], $config[ 'dbname' ] );

if ( $conexion->connect_errno ) {
    echo "Error de conexión: " . $conexion->connect_error;
}
$stmt = mysqli_prepare( $conexion, "
		SELECT
		s.*,
		a.codigo,
		p.nombre,
		p.a_paterno,
		p.a_materno,
		p.id_depar,
		prop.nombre AS propietario
	FROM
		(
		SELECT
			id_servicio,
			MAX(km) AS km,
			MAX(ultimo_servicio) AS ultimo_servicio,
			DATE_ADD(MAX(ultimo_servicio), INTERVAL 6 MONTH) AS nueva_fecha_servicio,
			autos_id
		FROM
			servicio_autos
		GROUP BY
			autos_id
		) AS s
	LEFT JOIN autos AS a ON s.autos_id = a.id_autos
	LEFT JOIN propietarios AS prop ON a.id_propietario = prop.id_propietario
	LEFT JOIN personal AS p ON a.personal_id_personal = p.id_personal
	WHERE
		 s.nueva_fecha_servicio BETWEEN DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND DATE_ADD(CURDATE(), INTERVAL 62 DAY)
		AND a.activo = 1
		AND a.estatus = 1 ;" );
$stmt->execute();
$result = $stmt->get_result();
$email = "administracion@ab-forti.com";
//$email = "soporte@ab-forti.com";
$tbody = '';
while ( $row = mysqli_fetch_array( $result ) ) {
    $usuario = strtoupper( $row[ "nombre" ] . ' ' . $row[ "a_paterno" ] . ' ' . $row[ "a_materno" ] );
    $tbody .= '
    <tr>
        <td style="text-align: center;">' . $row[ 'codigo' ] . '</td>
        <td style="text-align: center;">' . $row[ 'ultimo_servicio' ] . '</td>
        <td style="text-align: center;">' . $row[ 'nueva_fecha_servicio' ] . '</td>
        <td style="text-align: center;">' . $usuario . '</td>
    </tr>
';


}
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

$from = "informatica@innovet.com.mx";

$to = $email;
$subject = 'Recordatorio de Próximo Servicio de Mantenimiento para Vehículos';
$message = '
	<html>
<head>
</head>
<body>
  <p>Buen día</p>

  <p>El motivo de mi correo es para  informarle que hemos llevado a cabo el cálculo correspondiente, y ha transcurrido un período de seis meses desde el último servicio de mantenimiento de algunos de los vehículos de nuestra flota.
</p>

<p>A continuación, le proporciono la lista de los vehículos que están programados para someterse al servicio de mantenimiento el próximo mes:</p>
  
  <table style="width: 100%; text-align: center;" border="1">
  <thead>
	<tr>
	<th>Codigo</th>
	<th>Fecha del último servicio</th>
	<th>Fecha de vencimiento</th>
	<th>Usuario Asignado</th>
	</tr>
	</thead>
	<tbody>
	                ' . $tbody . '

	</tbody>
  </table>
  
  <p>Muchas gracias.</p>
   <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExMTc4MWM2MGZmMTZjZmNlNTBmNTE1MmUxMzhhZjdlOWJhZjdhMDFhYSZjdD1n/Zk7n6SilbOJjXkZFtZ/giphy.gif" >
</body>
<footer>
 <span>Copyright &copy; 2024. AB FORTI CORPORATIVO.</span> 
    </footer>    
</html>';

$headers = "From:" . $from . "\r\n";

$headers .= "MIME-Version: Sistema de control AB FORTI\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";;
$headers .= "Reply-To: administracion@ab-forti.com\r\n";
$headers .= "Cc:e.villafuerte@ab-forti.com\r\n";


if ( mail( $to, $subject, $message, $headers ) ) {
    echo "email enviado de notificacion \n";
} else {
    echo "error de envío \n";
}

$stmt->close();
$conexion->close();

?>