<?php
session_start();

// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header( "Location: denegado" );
    exit; // También puedes usar die en lugar de exit
}
// Verifica si el valor del campo 'codigoQR' enviado a través del método POST es nulo
if ( $_POST[ 'codigoQR' ] == null ) {
    // Si es nulo, redirecciona a la página 'computo'
    header( "Location: personal" );
    // Termina la ejecución del script
    exit;
}
$idPersonal = $_POST[ 'codigoQR' ];
// Incluye el archivo de conexión a la base de datos
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/personal/relaciones.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/personal/sql_personal.php' );

$computo=computo($conexion, $idPersonal);
$movil=movil($conexion, $idPersonal);
//Llama a la función 'view_equipo' con los parámetros de conexión y el valor de 'codigoQR'
//$datos_equipo = view_equipo( $conexion, $codigoQR );
//$id_compu = $datos_equipo[ 'id_compu' ];
$datos_equipo = view_colaborador( $conexion, $idPersonal );


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
        $this->Cell( 0, 10, 'BAJA DE RESPONSIVA  Y ENTREGA DE EQUIPOS', 0, 1, 'R', 0, '', 0, false, 'M', 'M' );
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
$html = '<p>Por medio de la presente, hago constar que realizo la entrega de los equipos asignados a mi persona por parte de la persona moral denominada <b>'.$empresa[ 'nombre_fiscal' ] . '</b>, con domicilio en <b>Av. del Marqués lote 7, Parque Industrial Bernardo Quintana, El Marqués, Querétaro.</b> Estos equipos fueron entregado a efecto de poder desempeñar correctamente todas y cada una de las actividades laborales que corresponden al puesto desempeñado.</p>
<p>
Adicionalmente, constato que entrego los equipos que me fueron asignados, además de la entrega de los accesorios correspondientes. Entiendo que en caso de que el área de Informática y comunicaciones encuentre alguna anomalía o incidencia con los equipos, pueda ser descontada de mi salario o finiquito de acuerdo con la tabla de valores descritas en las <b> "POLITICAS DE USO Y SERVICIO DE BIENES INFORMÁTICOS"</b>  y en las <b>"POLITICAS Y LINEAMIENTOS DEL USO DE EQUIPO TELEFÓNICO".</b>
</p>
<p>
<b>Descripción de los equipos:
</b>
</p>
';

// Agregar el HTML usando writeHTMLCell
$pdf->writeHTMLCell( 0, 10, '', '', $html, 0, 1, false, true, 'J' );
$pdf->Ln(5);
$pdf->SetX( 30 );
$pdf->SetFont( 'dejavusans', '', 8 );
$pdf->SetFillColor(190, 204, 213);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(35,8,'Equipo',1,0,'C',1);
$pdf->Cell(40,8,'Modelo',1,0,'C',1);
$pdf->Cell(40,8,'Número de serie',1,0,'C',1);
$pdf->Cell(40,8,'Características',1,1,'C',1);

$pdf->SetFont( 'dejavusans', '', 7 );
$pdf->SetFillColor(255, 255, 255);

while($ver_computo=mysqli_fetch_array($computo)){
	$pdf->SetX(30);
$pdf->Cell(35,8,$ver_computo['tipos'],1,0,'C',1);
$pdf->Cell(40,8,$ver_computo['modelo'],1,0,'C',1);
$pdf->Cell(40,8,$ver_computo['no_serie'],1,0,'C',1);
$pdf->Cell(40,8,"Cargador: ".$ver_computo['cargador'],1,1,'C',1);
}

while($ver_movil=mysqli_fetch_array($movil)){
	$pdf->SetX(30);
$pdf->Cell(35,8,"Móvil",1,0,'C',1);
$pdf->Cell(40,8,$ver_movil['modelo'],1,0,'C',1);
$pdf->Cell(40,8,$ver_movil['no_serie'],1,0,'C',1);
$pdf->Cell(40,8,"Cargador: ".$ver_movil['no_cargador'],1,1,'C',1);
}
$pdf->SetX(30);
$pdf->MultiCell(155,20,"Observaciones encontradas: ",1, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');


$pdf->Ln( 10 );
$pdf->SetFont('dejavusans','',10);
$pdf->SetY(175);
$posicionY = $pdf->GetY();
$pdf->Cell(75,5,'RECIBE',0,0,'C');
$pdf->Cell(75,5,'ENTREGA',0,1,'C');
$y3= $posicionY+25;
$pdf->SetY($y3);
$pdf->Line(35,$y3,95,$y3);
$pdf->Line(110,$y3,170,$y3);
$pdf->Ln(1);
$pdf->Cell(75,5,"NOMBRE Y FIRMA",0,0,'C');
$pdf->Cell(75,5,'NOMBRE Y FIRMA',0,0,'C');

$pdf->Ln( 15 );
$posicionY = $pdf->GetY();
$pdf->SetX(65);
$pdf->Cell(75,5,'RECIBE',0,0,'C');
$y3= $posicionY+25;
$pdf->SetY($y3);
$pdf->Line(80,$y3,125,$y3);
$pdf->Ln(1);
$pdf->SetX(65);
$pdf->Cell(75,5,"NOMBRE Y FIRMA",0,0,'C');
// Obtén el contenido del PDF en formato base64
$pdfContentBase64 = base64_encode( $pdf->Output( 'Responsiva.pdf', 'S' ) );

// Devuelve el contenido base64 al cliente
echo $pdfContentBase64;
?>
