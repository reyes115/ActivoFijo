<?php
session_start();

// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header( "Location: denegado" );
    exit; // También puedes usar die en lugar de exit
}
//Verifica si el valor del campo 'codigoQR' enviado a través del método POST es nulo
if ( $_POST[ 'codigoQR' ] == null ) {
    // Si es nulo, redirecciona a la página 
    header( "Location: autos" );
    // Termina la ejecución del script
    exit;
}

// Incluye el archivo de conexión a la base de datos
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/autos/sql_autos.php' );
$codigoQR = $_POST[ 'codigoQR' ];
//Llama a la función 'view_equipo' con los parámetros de conexión y el valor de 'codigoQR'
$datos_equipo = view_auto( $conexion, $codigoQR );
//$id_movil = $datos_equipo[ 'id_celular' ];

/*
$stmt = mysqli_prepare( $conexion, "
 SELECT
    `nombre_fiscal`
FROM
    departamentos
LEFT JOIN empresa ON id_empresa = empresa_id_empresa
WHERE
    id_depa =?" );
$stmt->bind_param( "s", $datos_equipo[ 'id_depar' ] );
$stmt->execute();
$empresa = $stmt->get_result()->fetch_assoc();
$stmt->close();
*/
date_default_timezone_set( 'America/Mexico_City' );
setlocale( LC_TIME, "spanish" );
$DateAndTime = strftime( "%d de %B del %Y" );

require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/lib/TCPDF-main/tcpdf.php' );
// Crea una instancia de la clase TCPDF


$pdf = new TCPDF();
$pdf->AddPage('PORTRAIT', 'LETTER');
// Extender la clase TCPDF para personalizar el encabezado y pie de página
class CustomPDF extends TCPDF {
    // Método para el encabezado
    public function Header() {
        // Configura la fuente y el tamaño del texto
        $this->SetFont( 'dejavusans', 'B', 10 );

        // Agregar el logo
        $logoX = 12; // Posición X del logo
        $logoY = 1; // Posición Y del logo
        $logoAncho = 45; // Ancho del logo (ajustar según tus necesidades)
        $logoAlto = 45; // Alto del logo (ajustar según tus necesidades)
        $logoPath = $_SERVER[ 'DOCUMENT_ROOT' ] . '/img/AB_FORTI/PNG/AB_FORTI_Logotipo-04.png';
        $this->Image( $logoPath, $logoX, $logoY, $logoAncho, $logoAlto );

    }
    public function Footer() {

        $this->Image( $_SERVER[ 'DOCUMENT_ROOT' ] . '/img/innovet/LOGO-Innovet-2023-horizontal.png', 80, 240, 50 );
        $this->Image( $_SERVER[ 'DOCUMENT_ROOT' ] . '/img/upper/upperlogistics_02.png', 118, 240, 50 );
        $this->Image( $_SERVER[ 'DOCUMENT_ROOT' ] . '/img//beexen/Capa-2.png', 162, 259, 34 );
    }
}

$pdf = new CustomPDF( 'P', 'mm', 'Letter' );

$pdf->SetHeaderMargin( 10 );

$pdf->SetFooterMargin( 8 );
$pdf->SetRightMargin( 10 );
$pdf->SetLeftMargin( 10 );

$pdf->AddPage( 'PORTRAIT', 'LETTER' );
$pdf->SetAutoPageBreak( true, 15 );

$pdf->SetY( 15 );
$pdf->SetX(140);
$pdf->SetFillColor(129, 129, 129);
$pdf->SetFont( 'dejavusans', '', 12 );
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(60, 10, 'No. De Vehiculo', 1, 1, 'C',1 );

$pdf->SetX(140);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(60,10,$datos_equipo['codigo'],1,1,'C');
$pdf->Ln( 1 );
$qr_img = $_SERVER[ 'DOCUMENT_ROOT' ] . '/uploads/autos/'.$datos_equipo['codigo'].'/'.$datos_equipo['r_imagen'];
 $pdf->Image( $qr_img,155, 36, 30, 30 );

$pdf->Ln(10);
$pdf->SetFont('dejavusans','',22);
$pdf->SetFillColor(255, 255, 255);
$pdf->MultiCell(90,10,$datos_equipo['marca'].' '.$datos_equipo['tipo'],0,1,'C');

$pdf->Ln(1);
$pdf->SetFont('dejavusans','B',11);
$pdf->SetFillColor(129, 129, 129);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(80,10,'PROPIETARIO',0,0,'R',1);
$pdf->Cell(116,10,$datos_equipo['propietario'],0,1,'C',1);

$pdf->SetFillColor(190,204,213);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,10,'CLAVE VEHICULAR',0,0,'R',1);
$pdf->Cell(116,10,$datos_equipo['claveVehicular'],0,1,'C',1);

$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,10,'VIN',0,0,'R',1);
$pdf->Cell(116,10,$datos_equipo['vin'],0,1,'C',1);

$pdf->SetFillColor(190,204,213);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,10,'MODELO',0,0,'R',1);
$pdf->Cell(116,10,$datos_equipo['modelo'],0,1,'C',1);

$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,10,'NO.MOTOR',0,0,'R',1);
$pdf->Cell(116,10,$datos_equipo['no_motor'],0,1,'C',1);

$pdf->SetFillColor(190,204,213);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,10,'COMBUSTIBLE',0,0,'R',1);
$pdf->Cell(116,10,$datos_equipo['combustible'],0,1,'C',1);

$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,10,'COLOR',0,0,'R',1);
$pdf->Cell(116,10,$datos_equipo['color'],0,1,'C',1);

$pdf->SetFillColor(190,204,213);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,10,'TRANSMISIÓN',0,0,'R',1);
switch ($datos_equipo['transmision']){
	case 1:
		$transmision="Automatica";
		break;
	case 2 :
		$transmision="Manual";
			break;
		
}
$pdf->Cell(116,10,$transmision,0,1,'C',1);


$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,10,'PÓLIZA DE SEGURO',0,0,'R',1);
$pdf->Cell(116,10,$datos_equipo['no_poliza'],0,1,'C',1);


$pdf->SetFillColor(190,204,213);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,10,'PLACAS',0,0,'R',1);
$pdf->Cell(116,10,$datos_equipo['placas'],0,1,'C',1);


$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,10,'USUARIO',0,0,'R',1);
$pdf->Cell(116,10,$datos_equipo[ "nombre" ] . ' ' . $datos_equipo[ "a_paterno" ] . ' ' . $datos_equipo[ "a_materno" ],0,1,'C',1);


$pdf->SetFillColor(190,204,213);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,10,'TARJETA DE CIRCULACIÓN',0,0,'R',1);
$pdf->Cell(116,10,$datos_equipo['tarjeta'],0,1,'C',1);


$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,10,'ESTADO DE CIRCULACIÓN',0,0,'R',1);
$pdf->Cell(116,10,$datos_equipo['estado_placa'],0,1,'C',1);


$pdf->SetFillColor(190,204,213);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,10,'FACTURA',0,0,'R',1);
$pdf->Cell(116,10,$datos_equipo['factura'],0,1,'C',1);


$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,10,'TIPO',0,0,'R',1);
$pdf->Cell(116,10,$datos_equipo['tipo'],0,1,'C',1);


$pdf->SetFillColor(190,204,213);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,10,'MARCA',0,0,'R',1);
$pdf->Cell(116,10,$datos_equipo['marca'],0,1,'C',1);


$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(80,10,'ESTADO',0,0,'R',1);
$posicionX = $pdf->GetX();
$posicionY = $pdf->GetY();
$pdf->Cell(116,10,$datos_equipo['est'],0,1,'C',1);

$qr_img = $_SERVER[ 'DOCUMENT_ROOT' ] . '/uploads/autos/'.$datos_equipo['codigo'].'/'.$codigoQR.'_qr.png';
 $pdf->Image( $qr_img, 25, $posicionY+7, 27, 27 );

// Obtén el contenido del PDF en formato base64
$pdfContentBase64 = base64_encode( $pdf->Output( 'Responsiva.pdf', 'S' ) );

// Devuelve el contenido base64 al cliente
echo $pdfContentBase64;
?>
