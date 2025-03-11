<?php
// Configuración de la base de datos
$config = array(
    'servername' => 'localhost',
    'username' => 'u470151145_ab_forti',
    'password' => 'A8#BfO2r0T4i!',
    'dbname' => 'u470151145_ceers_2_0'
);

// Conexión a la base de datos
$conexion = new mysqli( $config[ 'servername' ], $config[ 'username' ], $config[ 'password' ], $config[ 'dbname' ] );

// Verificar la conexión
if ( $conexion->connect_errno ) {
    die( "Error de conexión: " . $conexion->connect_error );
}

// Obtiene el mes actual y su número
$mes_actual = date('n');

$verificacion = [
    1 => [4, 5, 10, 11],
    2 => [4, 5, 10, 11],
    3 => [3, 4, 9, 10],
    4 => [3, 4, 9, 10],
    5 => [1, 2, 7, 8],
    6 => [1, 2, 7, 8],
    7 => [2, 3, 8, 9],
    8 => [2, 3, 8, 9],
    9 => [5, 6, 11, 12],
    0 => [5, 6, 11, 12]
];
// Consulta para obtener todos los autos que necesitan verificación en el mes actual
$stmt = mysqli_prepare( $conexion, "
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
    WHERE autos.activo = 1 AND autos.estatus = 1 AND autos.note = 0
    ORDER BY autos.id_autos DESC;" );
$stmt->execute();
$result = $stmt->get_result();

// Procesar cada auto
while ( $row = mysqli_fetch_array( $result ) ) {
	if ($row["note"] == 0){
    $usuario = strtoupper( $row[ "nombre" ] . ' ' . $row[ "a_paterno" ] . ' ' . $row[ "a_materno" ] );

    $no_tiene = " ";
    if ( empty( $row[ 'email' ] ) ) {
        $email = "administracion@ab-forti.com";
        $no_tiene = "<b>El usuario no tiene email registrado favor de comunicarle acerca de la fecha de verificacion<b>";
    } else {
        $email = $row[ 'email' ];
    }

$placa = $row["placas"];
preg_match('/(\d)(?!.*\d)/', $placa, $matches);

$numero = $matches[1] ?? null;

if ($mes_actual <= 6) {
	switch($numero){
		case 5:
		case 6:
			$verificar = "True";
			$periodo = "Enero - Febrero"; 
			break;
		case 7:
		case 8:
			$verificar = "True";
			$periodo = "Febrero - Marzo";
			break;
		case 3:
		case 4:
			$verificar = "True";
			$periodo = "Marzo - Abril";
			break;
		case 1:
		case 2:
			$verificar = "True";
			$periodo = "Abril - Mayo";
			break;
		case 9:
		case 0:
			$verificar = "True";
			$periodo = "Mayo - Junio";
			break;
		default:
			$verificar = "False";
			break;			
	}	
}else{
	switch($numero){
		case 5:
		case 6:
			$verificar = "True";
			$periodo = "Julio - Agosto";
			break;
		case 7:
		case 8:
			$verificar = "True";
			$periodo = "Agosto - Septiembre";
			break;
		case 3:
		case 4:
			$verificar = "True";
			$periodo = "Septiembre - Octubre";
			break;
		case 1:
		case 2:
			$verificar = "True";
			$periodo = "Octubre - Noviembre";
			break;
		case 9:
		case 0:
			$verificar = "True";
			$periodo = "Noviembre - Diciembre";
			break;
		default:
			$verificar = "False";
			break;
		
}
}


if (in_array($mes_actual, $verificacion[$numero])) {
   ini_set( 'display_errors', 1 );
   error_reporting( E_ALL );
    $from = "informatica@innovet.com.mx";
    $to = $email;
   // $to = "camachoorlando0@gmail.com";
    $subject = 'CORREO AVISO DE VERIFICACION ' . $row['marca'] . ' ' . $row['tipo'] . ' ' . $row['modelo'] . ' ' . $row['vin'] . ' ' . $row['propietario'];
            $message = '
	<html>
<head>
</head>
<body>
  <p>Buen día, <b>' . $usuario . ' </b> </p>

  <p>El motivo de mi correo es recordarle que la verificación del vehículo <b> ' . $row[ 'marca' ] . ' ' . $row[ 'tipo' ] . ' ' . $row[ 'modelo' ] . ' ' . $row[ 'vin' ] . ' </b>asignado a su persona está próxima a realizar, es necesario agendar cita y llevar el vehículo a verificar antes del termino del periodo de <b> ' . $periodo . ' del en curso. </b></p>
  <p>En caso de presentarlo fuera del plazo correspondiente, los gastos de verificación y multa serán responsabilidad del usuario.</p>
  <p>De su amable apoyo en compartir el comprobante de pago y la verificación actualizada una vez concluido el trámite al correo administracion@ab-forti.com</p>
  <p>Muchas gracias.</p>
  
  <p>' . $no_tiene . '</p>
  <br>
   <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExMTc4MWM2MGZmMTZjZmNlNTBmNTE1MmUxMzhhZjdlOWJhZjdhMDFhYSZjdD1n/Zk7n6SilbOJjXkZFtZ/giphy.gif" >
</body>
<footer>
 <span>Derechos Reservados &copy; 2024. AB FORTI CORPORATIVO.</span> 
    </footer>    
</html>';

    $headers = "From:" . $from . "\r\n";
     //$headers = "From: camachoorlando0@gmail.com\r\n";

    $headers .= "MIME-Version: Sistema de control AB FORTI\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "Reply-To: administracion@ab-forti.com\r\n";
    $headers .= "Cc:admninistracion@ab-forti.com, soporte@ab-forti.com\r\n";
	
    $stmt2 = mysqli_prepare( $conexion, "UPDATE autos SET note = 1 WHERE id_autos = ?" );
    $stmt2->bind_param( "s", $row[ 'id_autos' ] );
//
    if ( mail( $to, $subject, $message, $headers ) && $stmt2->execute() ) {
        echo "email a ".$row['codigo']."<br>  ";
	
		 //$stmt2->close();
                // Retraso de 5 segundos entre cada correo
                sleep(10);
    } else {
        echo "error de envío en " . $row[ 'id_autos' ] . "\n";
    }
      
}
		else{
		echo "no se puede enviar por el if" . $row[ 'codigo' ] . "<br>";
	}
}
}//
$stmt->close();

$conexion->close();

?>