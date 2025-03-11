<?php
session_start();

// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header( "Location: denegado" );
    exit; // También puedes usar die en lugar de exit
}

// Incluye el archivo de conexión a la base de datos
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/computo/computo_sql.php' );
   include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/licencias/sql_licencias.php' );
if(isset($_POST[ 'codigoQR' ])){
	$codigoQR = $_POST[ 'codigoQR' ];
}


// Llama a la función 'view_equipo' con los parámetros de conexión y el valor de 'codigoQR'
$datos_equipo = view_equipo( $conexion, $codigoQR );
$id_compu = $datos_equipo[ 'id_compu' ];


$sistema = licencias_equipo( $conexion, 1, $id_compu );
$office = licencias_equipo( $conexion, 2, $id_compu );
$antivirus = licencias_equipo( $conexion, 3, $id_compu );

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
        $logoY = -14; // Posición Y del logo
        $logoAncho = 55; // Ancho del logo (ajustar según tus necesidades)
        $logoAlto = 55; // Alto del logo (ajustar según tus necesidades)
        $logoPath = $_SERVER[ 'DOCUMENT_ROOT' ] . '/img/AB_FORTI/PNG/AB_FORTI_Logotipo-03.png';
        $this->Image( $logoPath, $logoX, $logoY, $logoAncho, $logoAlto );

        // Escribir el contenido del encabezado
        $this->SetY( 17 );
        $this->Cell( 0, 10, 'RECEPCIÓN Y RESPONSIVA DE EQUIPO DE CÓMPUTO', 0, 1, 'R', 0, '', 0, false, 'M', 'M' );
    }
    public function Footer() {

        $this->Image( $_SERVER[ 'DOCUMENT_ROOT' ] . '/img/innovet/LOGO-Innovet-2023-horizontal.png', 80, 240, 50 );
        $this->Image( $_SERVER[ 'DOCUMENT_ROOT' ] . '/img/upper/upperlogistics_02.png', 118, 241, 50 );
        $this->Image( $_SERVER[ 'DOCUMENT_ROOT' ] . '/img//beexen/Capa-2.png', 162, 259, 34 );
    }
}

$pdf = new CustomPDF( 'P', 'mm', 'Letter' );

$pdf->SetHeaderMargin( 25 );

$pdf->SetFooterMargin( 8 );
$pdf->SetRightMargin( 28 );
$pdf->SetLeftMargin( 28 );

$pdf->AddPage( 'PORTRAIT', 'LETTER' );
$pdf->SetAutoPageBreak( true, 25 );
$pdf->SetY( 20 );
$pdf->SetFont( 'dejavusans', '', 9 );
$pdf->Cell( 0, 10, $DateAndTime . ', el Marqués, Querétaro.', 0, 1, 'R' );
$pdf->Ln( 1 );

// HTML con una palabra en negrita
$html = '<p>Por medio de la presente, el <b>Área de Informática y Comunicaciones</b> perteneciente a la persona moral <b>'.$empresa[ 'nombre_fiscal' ] . '</b>, hace entrega del equipo como concepto de <b>"Herramienta de Trabajo"</b> a efecto de que el(la) trabajador(a) que suscribe pueda desempeñar correctamente todas y cada una de las actividades laborales que le corresponden al puesto contratado, cuyas características se describen a continuación:</p>';

// Agregar el HTML usando writeHTMLCell
$pdf->writeHTMLCell( 0, 10, '', '', $html, 0, 1, false, true, 'J' );

$pdf->Ln(5);
$pdf->SetX( 30 );
$pdf->SetFont( 'dejavusans', '', 7 );
$pdf->SetFillColor(190, 204, 213);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(31,5,'TIPO DE EQUIPO',1,0,'C',1);
$pdf->Cell(31,5,'MARCA',1,0,'C',1);
$pdf->Cell(31,5,'MODELO',1,0,'C',1);
$pdf->Cell(31,5,'RAM',1,0,'C',1);
$pdf->Cell(31,5,'DISCO DURO',1,1,'C',1);

$pdf->SetX(30);
$pdf->SetFont( 'dejavusans', '', 6 );
$pdf->SetFillColor(255, 255, 255);
switch($datos_equipo['tipo']) {
	case 1:
		$tipo = "Escritorio";
		break;
	case 2:
		$tipo = "Laptop";
		break;
	case 4:
		$tipo = "Otros";
		break;		
}
$pdf->Cell(31,5,$tipo,1,0,'C',1);
$pdf->Cell(31,5,$datos_equipo['marca'],1,0,'C',1);
$pdf->Cell(31,5,$datos_equipo['modelo'],1,0,'C',1);
$pdf->Cell(31,5,$datos_equipo['ram'],1,0,'C',1);
$pdf->Cell(31,5,$datos_equipo['almacenamiento'],1,1,'C',1);

$pdf->SetX( 30 );
$pdf->SetFont( 'dejavusans', '', 7 );
$pdf->SetFillColor(190, 204, 213);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(31,5,'PROCESADOR',1,0,'C',1);
$pdf->Cell(31,5,'COLOR',1,0,'C',1);
$pdf->Cell(31,5,'NUMERO DE SERIE',1,0,'C',1);
$pdf->Cell(31,5,'CARGADOR',1,0,'C',1);
$pdf->Cell(31,5,'ESTADO',1,1,'C',1);

$pdf->SetX(30);
$pdf->SetFont( 'dejavusans', '', 6 );
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(31,5,$datos_equipo['cpu'],1,0,'C',1);
$pdf->Cell(31,5,$datos_equipo['color'],1,0,'C',1);
$pdf->Cell(31,5,$datos_equipo['no_serie'],1,0,'C',1);
$pdf->Cell(31,5,$datos_equipo['cargador'],1,0,'C',1);
 switch ( $datos_equipo[ 'estado' ] ) {
                  case 1:
                    $estado = "Nuevo";
                    break;
                  case 2:
                    $estado = "Usado";
                    break;
                  case 3:
                    $estado = "Con fallas";
                    break;
                  case 4:
                    $estado = "Inservible";
                    break;
                }
$pdf->Cell(31,5,$estado,1,1,'C',1);


$pdf->SetX( 30 );
$pdf->SetFont( 'dejavusans', '', 7 );
$pdf->SetFillColor(190, 204, 213);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(31,5,'OFFICE',1,0,'C',1);
$pdf->Cell(31,5,'ANTIVIRUS',1,0,'C',1);
$pdf->Cell(31,5,'SISTEMA OPERATIVO',1,0,'C',1);
$pdf->Cell(62,5,'SOFTWARE ADICIONAL',1,1,'C',1);

$pdf->SetX(30);
$pdf->SetFont( 'dejavusans', '', 6 );
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(31,5,$office['nombre_licencias'],1,0,'C',1);
$pdf->Cell(31,5,$antivirus['nombre_licencias'],1,0,'C',1);
$pdf->Cell(31,5,$sistema['nombre_licencias'],1,0,'C',1);
$pdf->Cell(62,5,$datos_equipo['adicional'],1,1,'C',1);

$pdf->SetX( 30 );
$pdf->SetFont( 'dejavusans', '', 7 );
$pdf->SetFillColor(190, 204, 213);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(155,5,'OBSERVACIONES Y COMENTARIOS',1,1,'C',1);

$pdf->SetX(30);
$pdf->SetFont( 'dejavusans', '', 6 );
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(155,10,$datos_equipo['observaciones'],1,1,'C');

$pdf->SetX(30);
$pdf->SetFont('dejavusans','',7);
$pdf->SetFillColor(190, 204, 213);
$pdf->SetTextColor(0, 0, 0);
// Establece la posición X actual
$posicionX = $pdf->GetX();
$posicionY = $pdf->GetY();

$pdf->MultiCell(80, 10, '¿SE AUTORIZA LA SALIDA DEL EQUIPO DE CÓMPUTO FUERA DE LAS INSTALACIÓNES?',1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');

$pdf->SetXY($posicionX+80,$posicionY);

$pdf->MultiCell(12, 10, '', 1, 'L');
$pdf->SetXY($posicionX+92,$posicionY);

$pdf->SetFont('dejavusans','I',4);

$pdf->SetFillColor(190, 204, 213);
$pdf->MultiCell(63, 10, '*EN CASO DE INDICAR “SI”, EL(LA) COLABORADOR(A) PAGARÁ EL 50% DEL COSTO DE REPOSICIÓN EN CASO DE ROBO O EXTRAVÍO',1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');

$pdf->Ln( 15 );

$pdf->SetFont( 'dejavusans', '', 9 );
$html = '<p>Herramienta que, desde el momento de la entrega, el(la) trabajador(a) ha inspeccionado y lo recibe en buen estado de funcionamiento y buen estado general, por lo que desde este momento se hace responsable del correcto uso que se haga del mismo, privilegiando el cumplimiento de sus responsabilidades en la empresa, así como de cualquier avería que sufra este dispositivo, a consecuencia de falta de notificación de su parte. De igual manera, será su responsabilidad el buen o mal uso que se haga con el mismo, autorizando que la empresa <b>' . $empresa['nombre_fiscal'] . '</b> realice el descuento correspondiente al daño por avería, extravío, por la comisión de delitos de abuso de confianza y/o fraude genérico, así como los derivados de cualquier mal uso que se efectúe.</p>';


// Agregar el HTML usando writeHTMLCell
$pdf->writeHTMLCell( 0, 10, '', '', $html, 0, 1, false, true, 'J' );

$pdf->Ln( 5 );

$html = '<p>Es del conocimiento y conformidad del(la) trabajador(a) que <b>'.$empresa[ 'nombre_fiscal' ] . '</b> podrá disponer del equipo de cómputo, así como de sus accesorios a que se refiere la presente en el momento que así convenga a sus intereses, ya sea para una nueva asignación, reasignación, baja del(la) trabajador(a) en la empresa o por el cambio de las funciones que el suscrito tiene que desarrollar y/o desempeñar.</p>';

$posicionX = $pdf->GetX();
$posicionY = $pdf->GetY();
$pdf->writeHTMLCell( 130, 10, '', '', $html, 0, 1, false, true, 'J' );

 $qr_img = $_SERVER[ 'DOCUMENT_ROOT' ] . '/uploads/computo/'.$datos_equipo['codigo'].'/'.$codigoQR.'_qr.png';
 $pdf->Image( $qr_img, $posicionX+131, $posicionY, 27, 27 );

$pdf->Ln( 5 );
$html = '<p>Habiendo leído y estando de acuerdo con el contenido y consecuencias legales de la presente carta responsiva de asignación del equipo de cómputo y sus accesorios, firma el(la) trabajador(a) la presente.</p>';

$pdf->writeHTMLCell( 0, 10, '', '', $html, 0, 1, false, true, 'J' );

$pdf->Ln( 10 );
$pdf->SetFont('dejavusans','',10);

$posicionY = $pdf->GetY();
$pdf->Cell(75,5,'RECIBE DE CONFORMIDAD',0,0,'C');
$pdf->Cell(75,5,'ENTREGA',0,1,'C');
$y3= $posicionY+38;
$pdf->SetY($y3);
$pdf->Line(35,$y3,95,$y3);
$pdf->Line(110,$y3,170,$y3);
$pdf->Ln(1);
$pdf->Cell(75,5,strtoupper($datos_equipo['nombre'].' '.$datos_equipo['a_paterno'].' '.$datos_equipo['a_materno']),0,0,'C');
$pdf->Cell(75,5,'Equipo de informatica',0,0,'C');


    // Obtén el contenido del PDF en formato base64
    $pdfContentBase64 = base64_encode( $pdf->Output( 'Responsiva.pdf', 'S' ) );

    // Devuelve el contenido base64 al cliente
    echo $pdfContentBase64;

?>
