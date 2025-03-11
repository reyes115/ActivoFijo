<?php
$config = array(
    'servername' => 'localhost',
    'username' => 'u470151145_ab_forti',
    'password' => 'A8#BfO2r0T4i!',
    'dbname' => 'u470151145_ceers_2_0'
);
// Resto de tu código
$conexion = new mysqli( $config[ 'servername' ], $config[ 'username' ], $config[ 'password' ], $config[ 'dbname' ] );

if ( $conexio->connect_errno ) {
    echo "Error de conexión: " . $conexion->connect_error;
}

$stmt = mysqli_prepare( $conexion, "
	 SELECT 
	 	`computadora`.*,
		`personal`.`nombre`,
		`personal`.`a_paterno`,
		`personal`.`a_materno`,
		`personal`.`id_depar`, 
		DAY(fecha_sym) as dia,
		MONTH(fecha_sym) AS mes,
		YEAR(fecha_sym) AS año,
		email
	FROM 
		computadora 
	LEFT JOIN `personal` ON `personal_id` = `id_personal`
	WHERE 
		DATEDIFF(`fecha_sym`, CURDATE()) >= 0 AND DATEDIFF(`fecha_sym`, CURDATE()) <= 30 AND note = '0' AND activo = '1';" );
$stmt->execute();
$equipo = $stmt->get_result();

while ( $row = mysqli_fetch_array( $equipo ) ) {
    $usuario = strtoupper( $row[ "nombre" ] . ' ' . $row[ "a_paterno" ] . ' ' . $row[ "a_materno" ] );


    $no_tiene = " ";
    if ( empty( $row[ 'email' ] ) ) {
        $email = "soporte@ab-forti.com";
        $no_tiene = "El usuario no tiene email registrado favor de comunicarle el soporte";
    } else {
        $email = $row[ 'email' ];
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
    $subject = "AVISO DE SERVICIO DE MANTENIMIENTO ";
    $message = '<html>
<head>
</head>
<body>
  <p>Buen día, <b>' . $usuario . ' </b> </p>
  <p>El motivo de mi correo es informarte que en fecha <b>' . $row[ 'dia' ] . ' de ' . $mes . ' del ' . $row[ 'año' ] . ' </b> se tiene programado el mantenimiento a tu equipo de cómputo asignado, agradeceremos se informe con anticipación al equipo de Informática si existe algún inconveniente con esta fecha.</p>

  <p>Adicional, solicitamos tu apoyo para informar cualquier anomalía que hayas notado como usuario del equipo y agradecemos notificarla al correo soporte@ab-forti.com</p>
  <p>Muchas gracias.</p>
  <p>' . $no_tiene . '</p>
  <br>
   <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExZGY5MzYyMGNkNDA1ZjJiMGNiOWU2MDRkODA1NjVjNDdmNjAyOGJlMCZjdD1n/iZ5zZ9bkeLqb6ywwZQ/giphy.gif" >
<footer>
  <span>Copyright &copy; 2024. AB FORTI CORPORATIVO.</span> 
</footer>    
</body>
</html>
';
    $headers = "From:Mensaje de Ceers <" . $from . ">\r\n";

    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "MIME-Version: Sistema de control AB FORTI\r\n";
    $headers .= "Reply-To: soporte@ab-forti.com\r\n";
    $headers .= "Cc:soporte@ab-forti.com\r\n";
  $stmt2 = mysqli_prepare($conexion, "UPDATE `computadora` SET `note` = 1 WHERE `id_compu` = ?");
    $stmt2->bind_param("s", $row['id_compu']);

    if (mail($to, $subject, $message, $headers) && $stmt2->execute()) {
        echo "email enviado de " . $row['codigo'] . "\n";
    } else {
        echo "error de envío en " . $row['id_compu'] . "\n";
    }
}

$stmt->close();
$stmt2->close();
$conexion->close();
?>