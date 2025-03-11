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
    exit();
}

$stmt = mysqli_prepare($conexion, "
SELECT
    DATE_FORMAT(p.`inicio_vigencia`, '%d/%m/%Y') AS fecha_inicio,
    DATE_FORMAT(p.`fin_vigencia`, '%d/%m/%Y') AS fecha_fin,
    p.id_poliza,
    p.codigo AS codigo_poliza,
    p.fin_vigencia,
    CASE p.tipo 
        WHEN 1 THEN 'Auto' 
        WHEN 2 THEN 'Vida' 
        WHEN 3 THEN 'Gatos medicos' 
        WHEN 4 THEN 'Daños'
    END AS tipo_poliza,
    a.codigo AS auto_codigo,
    per.nombre,
    per.a_paterno,
    per.a_materno,
    i.name AS nombre_inmobiliario
FROM
    polizas p
LEFT JOIN autos a ON p.asegurado_auto = a.id_autos
LEFT JOIN personal per ON p.asegurado_col = per.id_personal
LEFT JOIN inmobiliario i ON p.asegurado_inm = i.id_inmobiliario
WHERE
    DATEDIFF(p.fin_vigencia, CURDATE()) BETWEEN 30 AND 62
    AND p.activo = 1
");
$stmt->execute();
$result = $stmt->get_result();

// Dirección de correo siempre será la misma
$email = "administracion@ab-forti.com";

$tbody = '';
while ($row = mysqli_fetch_array($result)) {
    $usuario = strtoupper($row["nombre"] . ' ' . $row["a_paterno"] . ' ' . $row["a_materno"]);
    $tbody .= '
    <tr>
        <td style="text-align: center;">' . $row['codigo_poliza'] . '</td>
        <td style="text-align: center;">' . $row['tipo_poliza'] . '</td>
        <td style="text-align: center;">' . $row['fecha_inicio'] . '</td>
        <td style="text-align: center;">' . $row['fecha_fin'] . '</td>
        <td style="text-align: center;">' . $row['auto_codigo'] . $usuario . $row['nombre_inmobiliario'] . '</td>
    </tr>
    ';
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

$from = "informatica@innovet.com.mx";

$to = $email;
$subject = 'Recordatorio para Renovación de Pólizas para el Próximo Mes';
$message = '
<html>
<head>
</head>
<body>
  <p>Buen día</p>

  <p>El motivo de mi correo es para recordarle sobre las pólizas que se vencerán el próximo mes. Las pólizas próximas a vencer son las siguiente:</p>
  
  <table style="width: 100%; text-align: center;" border="1">
  <thead>
    <tr>
        <th>Codigo</th>
        <th>Tipo</th>
        <th>Fecha de inicio de vigencia</th>
        <th>Fecha de fin de vigencia</th>
        <th>Asegurado</th>
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
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "Cc:e.villafuerte@ab-forti.com\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "email enviado de notificación \n";
} else {
    echo "error de envío \n";
}

$stmt->close();
$conexion->close();

?>
