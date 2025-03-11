
<?php/*
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
		a.marca,
		a.tipo,
		a.modelo,
		a.vin,
		p.nombre,
		p.a_paterno,
		p.a_materno,
		p.id_depar,
		DAY(ultimo_servicio) as dia,
		MONTH(ultimo_servicio) AS mes,
		YEAR(ultimo_servicio) AS año,
		email,
		prop.nombre AS propietario
	FROM
		(
		SELECT
			id_servicio,
			note,
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
		s.nueva_fecha_servicio BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
		AND a.activo = 1
		AND a.estatus = 1
		AND s.note = '0';" );
$stmt->execute();
$result = $stmt->get_result();


while ( $row = mysqli_fetch_array( $result ) ) {
    $usuario = strtoupper( $row[ "nombre" ] . ' ' . $row[ "a_paterno" ] . ' ' . $row[ "a_materno" ] );


    $no_tiene = " ";
    if ( empty( $row[ 'email' ] ) ) {
        $email = "administracion@ab-forti.com";
        $no_tiene = "<b>El usuario no tiene email registrado favor de comunicarle acerca de la fecha de verificacion<b>";
    } else {
    $email = $row[ 'email' ];
     //$email = "camachoorlando0@gmail.com";
    }

    $mes_num = $row[ 'mes' ];
    switch ( $mes_num ) {
        case 1:
            $mes = "Enero";
            break;
        case 2:
            $mes = "Febrero";
            break;
        case 3:
            $mes = "Marzo";
            break;
        case 4:
            $mes = "Abril";
            break;
        case 5:
            $mes = "Mayo";
            break;
        case 6:
            $mes = "Junio";
            break;
        case 7:
            $mes = "Julio";
            break;
        case 8:
            $mes = "Agosto";
            break;
        case 9:
            $mes = "Septiembre";
            break;
        case 10:
            $mes = "Octubre";
            break;
        case 11:
            $mes = "Noviembre";
            break;
        case 12:
            $mes = "Diciembre";
            break;
    }


    ini_set( 'display_errors', 1 );
    error_reporting( E_ALL );
	
    $from = "informatica@innovet.com.mx";

    $to = $email;
    $subject = 'AVISO DE SERVICIO DE MANTENIMIENTO '.$row['marca'].' '.$row['tipo'].' '.$row['modelo'].' '.$row['vin'].' '.$row['propietario'];
    $message = '
<html>
<head>
</head>
<body>
  <p>Buen día, <b>'.$usuario.', </b></p>

 <p>El motivo de mi correo es informarle que el último servicio de mantenimiento del vehículo <b> '.$row['marca'].' '.$row['tipo'].' '.$row['modelo'].' '.$row['vin'].' </b>fue realizado el  <b>'.$row ['dia'].' de '.$mes.' del '.$row['año'].' </b>por lo que es necesario realizar nuevamente el servicio de mantenimiento.</p>
  <p>De su amable apoyo por favor de informar cualquier reparación realizada fuera del esquema de servicio de mantenimiento.</p>
  <p>

Favor de compartir el comprobante de pago y documento que acredite la realización del servicio una vez concluido el trámite al correo administracion@ab-forti.com</p>
 
  <p>Muchas gracias.</p>
  <p>' . $no_tiene . '</p>
  
  <br>
   <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExMTc4MWM2MGZmMTZjZmNlNTBmNTE1MmUxMzhhZjdlOWJhZjdhMDFhYSZjdD1n/Zk7n6SilbOJjXkZFtZ/giphy.gif" >
</body>
<footer>
 <span>Copyright &copy; 2024. AB FORTI CORPORATIVO.</span> 
    </footer>    
</html>';

        $headers = "From:" . $from . "\r\n";
	
    $headers .= "MIME-Version: Sistema de control AB FORTI\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
  $headers .= "Reply-To: administracion@ab-forti.com\r\n";
  $headers .= "Cc:admninistracion@ab-forti.com\r\n";
$stmt2 = mysqli_prepare($conexion, "UPDATE `servicio_autos` SET `note` = 1 WHERE `id_servicio` = ?");
    $stmt2->bind_param("i", $row['id_servicio']);

    if (mail($to, $subject, $message, $headers)&& $stmt2->execute() ) {
        echo "email enviado en " . $row['id_servicio'] . "\n";
    } else {
        echo "error de envío en " . $row['id_servicio'] . "\n";
    }
}

$stmt->close();
//$stmt2->close();
$conexion->close();

?>