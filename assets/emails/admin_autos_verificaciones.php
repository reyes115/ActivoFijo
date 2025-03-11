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
	 	`autos`.`id_autos`,
	 	`autos`.`codigo`,
		`personal`.`nombre`,
		`personal`.`a_paterno`,
		`personal`.`a_materno`,
		`personal`.`id_depar`, 
		DAY(VencVerificacion) as dia,
		MONTH(VencVerificacion) AS mes,
		YEAR(VencVerificacion) AS año,
		email
	FROM 
		`autos` 
	LEFT JOIN `personal` ON `id_personal` = `personal_id_personal`
	WHERE 
		DATEDIFF(`VencVerificacion`, CURDATE()) >= 30 AND DATEDIFF(`VencVerificacion`, CURDATE()) <= 62  and activo=1 and estatus=1 ;" );
$stmt->execute();
$result = $stmt->get_result();
$email = "administracion@ab-forti.com";
//$email = "soporte@ab-forti.com";
$tbody = '';
while ( $row = mysqli_fetch_array( $result ) ) {
    $usuario = strtoupper( $row[ "nombre" ] . ' ' . $row[ "a_paterno" ] . ' ' . $row[ "a_materno" ] );
$tbody .= '
    <tr>
        <td style="text-align: center;">' . $row['codigo'] . '</td>
        <td style="text-align: center;">' . $row['dia'] . '/' . $row['mes'] . '/' . $row['año'] . '</td>
        <td style="text-align: center;">' . $usuario . '</td>
    </tr>
';


}
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

$from = "informatica@innovet.com.mx";

$to = $email;
$subject = 'Recordatorio de Verificación de Vehículos para el Próximo Mes';
$message = '
	<html>
<head>
</head>
<body>
  <p>Buen día</p>

  <p>El motivo de mi correo es para recordarte sobre las verificaciones programadas para los vehículos del próximo mes. La lista de los vehículos que serán sometidos a verificación es la siguiente:</p>
  
  <table style="width: 100%; text-align: center;" border="1">
  <thead>
	<tr>
	<th>Codigo</th>
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
//$headers .= "Reply-To: administracion@ab-forti.com\r\n";
$headers .= "Cc:e.villafuerte@ab-forti.com\r\n";


if ( mail( $to, $subject, $message, $headers )  ) {
    echo "email enviado de notificacion \n";
} else {
    echo "error de envío \n";
}

$stmt->close();
$conexion->close();

?>