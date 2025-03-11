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
    header( "Location: autos" );
    // Termina la ejecución del script
    exit;
}

// Incluye el archivo de conexión a la base de datos
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/autos/sql_autos.php' );
$codigoQR = $_POST[ 'codigoQR' ];
// Llama a la función 'view_equipo' con los parámetros de conexión y el valor de 'codigoQR'
$datos_equipo = view_equipo( $conexion, $codigoQR );
$id_auto = $datos_equipo[ 'id_autos' ];



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

date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'es_MX.UTF-8');

$dia = date('d');
$mes = date('m');
$year = date('Y');


require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/lib/TCPDF-main/tcpdf.php' );
// Crea una instancia de la clase TCPDF


$pdf = new TCPDF();
$pdf->AddPage('LANDSCAPE', 'LETTER');
// Extender la clase TCPDF para personalizar el encabezado y pie de página
class CustomPDF extends TCPDF {
    // Método para el encabezado
    public function Header() {
        // Configura la fuente y el tamaño del texto
        $this->SetFont( 'dejavusans', 'B', 15 );

        // Agregar el logo
        $logoX = 10; // Posición X del logo
        $logoY = 1; // Posición Y del logo
        $logoAncho = 30; // Ancho del logo (ajustar según tus necesidades)
        $logoAlto = 30; // Alto del logo (ajustar según tus necesidades)
        $logoPath = $_SERVER[ 'DOCUMENT_ROOT' ] . '/img/AB_FORTI/PNG/AB_FORTI_Logotipo-04.png';
        $this->Image( $logoPath, $logoX, $logoY, $logoAncho, $logoAlto );

        
		
        $this->Image( $_SERVER[ 'DOCUMENT_ROOT' ] . '/img/innovet/LOGO-Innovet-2023-horizontal.png', 202, $logoY-5, 25 );
        $this->Image( $_SERVER[ 'DOCUMENT_ROOT' ] . '/img/upper/upperlogistics_02.png', 220, $logoY-5, 25);
        $this->Image( $_SERVER[ 'DOCUMENT_ROOT' ] . '/img/beexen/Capa-2.png', 242, $logoY+4, 20 );
		// Escribir el contenido del encabezado
        $this->SetY( 16 );
        $this->Cell( 0, 10, 'Carta Responsiva - Asignación de Vehículo', 0, 1, 'C', 0, '', 0, false, 'M', 'M' );
    }
    public function Footer() {

    }
}

$pdf = new CustomPDF( 'L', 'mm', 'Letter' );

$pdf->SetHeaderMargin( 12 );

$pdf->SetFooterMargin( 12 );
$pdf->SetRightMargin( 15 );
$pdf->SetLeftMargin( 15 );

$pdf->AddPage( 'LANDSCAPE', 'LETTER' );
$pdf->SetAutoPageBreak( true, 10 );
$pdf->SetY( 20 );
$pdf->SetFont( 'dejavusans', 'B', 9 );
$pdf->Cell(208,10,'FECHA',0,0,'R');
$pdf->Cell(13,5,$dia,0,0,'C');
$pdf->Cell(13,5,$mes,0,0,'C');
$pdf->Cell(13,5,$year,0,1,'C');

$pdf->SetFont( 'dejavusans', '', 9 );
$pdf->Cell(208);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(13,5,'DÍA',0,0,'C',1);
$pdf->Cell(13,5,'MES',0,0,'C',1);
$pdf->Cell(13,5,'AÑO',0,0,'C',1);

$pdf->Ln( 1 );
$pdf->Line(249,20,249,30);
$pdf->Line(223,20,223,30);
$pdf->Line(236,20,236,30);

$pdf->Ln(2);
$pdf->SetFont('dejavusans', 'B', 8);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(100, 6, 'RESPONSABLE DEL VEHÍCULO', 0, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('dejavusans', '', 8);
$pdf->SetFillColor(190, 204, 213);

$posicionY = $pdf->GetY();
$pdf->Cell(50, 6, 'NOMBRE', 0, 0, 'C', 1);
$pdf->Cell(50, 6, 'DEPARTAMENTO', 0, 1, 'C', 1);
$pdf->SetFont('dejavusans', '', 7);
$pdf->SetFillColor(255, 255, 255);

// Usar Cell con ajuste automático de altura
$pdf->Cell(50, 6, strtoupper($datos_equipo['nombre'].' '.$datos_equipo['a_paterno'].' '.$datos_equipo['a_materno']), 0, 0, 'C', 0);
$pdf->Cell(50, 6, $datos_equipo['departamentos'], 0, 1, 'C', 0);

$pdf->Line(68,33,68,45);

$pdf->Ln(5);
$pdf->SetFont('dejavusans','B',8);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(100,6,'DATOS DEL VEHÍCULO',0,1,'C');
$pdf->SetFont('dejavusans','',8);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(33,6,'MARCA',0,0,'C',1);
$pdf->Cell(33,6,'TIPO',0,0,'C',1);
$pdf->Cell(34,6,'MODELO',0,1,'C',1);
$pdf->SetFont('dejavusans','',7);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(33,6,$datos_equipo['marca'],0,0,'C');
$pdf->Cell(33,6,$datos_equipo['tipo'],0,0,'C');
$pdf->Cell(34,6,$datos_equipo['modelo'],0,1,'C');

$pdf->SetFont('dejavusans','',8);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(33,6,'COLOR',0,0,'C',1);
$pdf->Cell(32,6,'PLACAS',0,0,'C',1);
$pdf->Cell(35,6,'NO.SERIE',0,1,'C',1);
$pdf->SetFont('dejavusans','',7);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(33,6,$datos_equipo['color'],0,0,'C');
$pdf->Cell(32,6,$datos_equipo['placas'],0,0,'C');
$pdf->Cell(35,6,$datos_equipo['vin'],0,1,'C');


$pdf->Ln(1);
$pdf->SetFont('dejavusans','B',8);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(100,6,'DOCUMENTACIÓN DEL RESPONSABLE',0,0,'C');

$pdf->Ln(5);
$pdf->SetFont('dejavusans','',8);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(40,6,'INE',0,0,'R',1);
$pdf->Cell(30,6,'O SI',0,0,'C',1);
$pdf->Cell(30,6,'O NO',0,1,'C',1);
$pdf->SetFillColor(255, 255, 255);

$pdf->Cell(40,6,'LICENCIA DE CONDUCIR',0,0,'R',1);
$pdf->Cell(30,6,'O SI',0,0,'C',1);
$pdf->Cell(30,6,'O NO',0,1,'C',1);

$pdf->Ln(5);
$pdf->SetFont('dejavusans','B',8);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(100,6,'DOCUMENTACIÓN ENTREGADA AL RESPONSABLE',0,0,'C');

$pdf->Ln(5);
$pdf->SetFont('dejavusans','',8);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(40,6,'CARTA RESPONSIVA',0,0,'R',1);
$pdf->Cell(30,6,'O SI',0,0,'C',1);
$pdf->Cell(30,6,'O NO',0,1,'C',1);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(40,6,'POLITICAS DE USO',0,0,'R',1);
$pdf->Cell(30,6,'O SI',0,0,'C',1);
$pdf->Cell(30,6,'O NO',0,1,'C',1);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(40,6,'POLIZA DE SEGURO',0,0,'R',1);
$pdf->Cell(30,6,'O SI',0,0,'C',1);
$pdf->Cell(30,6,'O NO',0,1,'C',1);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(40,6,'TARJETA DE CIRCULACIÓN',0,0,'R',1);
$pdf->Cell(30,6,'O SI',0,0,'C',1);
$pdf->Cell(30,6,'O NO',0,1,'C',1);

$pdf->Line(85,108,85,132);

$pdf->Ln(5);
$pdf->SetFont('dejavusans','B',8);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(100,6,'ESTADO DE PINTURA DEL VEHÍCULO',0,0,'C');

$pdf->Ln(5);
$pdf->SetFont('dejavusans','',8);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(25,6,'O',0,0,'C',1);
$pdf->Cell(25,6,'O',0,0,'C',1);
$pdf->Cell(25,6,'O',0,0,'C',1);
$pdf->Cell(25,6,'O',0,1,'C',1);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(25,6,'NUEVO',0,0,'C',1);
$pdf->Cell(25,6,'BUENO',0,0,'C',1);
$pdf->Cell(25,6,'REGULAR',0,0,'C',1);
$pdf->Cell(25,6,'MALO',0,1,'C',1);

$pdf->SetFont('dejavusans','B',8);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(100,6,'ESTADO DE LA CARROCERIA DEL VEHÍCULO',0,0,'C');
$pdf->Ln(5);
$pdf->SetFont('dejavusans','',8);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(25,6,'O',0,0,'C',1);
$pdf->Cell(25,6,'O',0,0,'C',1);
$pdf->Cell(25,6,'O',0,0,'C',1);
$pdf->Cell(25,6,'O',0,1,'C',1);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(25,6,'NUEVO',0,0,'C',1);
$pdf->Cell(25,6,'BUENO',0,0,'C',1);
$pdf->Cell(25,6,'REGULAR',0,0,'C',1);
$pdf->Cell(25,6,'MALO',0,1,'C',1);

$pdf->SetFont('dejavusans','B',8);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(100,6,'ESTADO GENERAL DEL VEHÍCULO',0,0,'C');
$pdf->Ln(5);
$pdf->SetFont('dejavusans','',8);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(25,6,'O',0,0,'C',1);
$pdf->Cell(25,6,'O',0,0,'C',1);
$pdf->Cell(25,6,'O',0,0,'C',1);
$pdf->Cell(25,6,'O',0,1,'C',1);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(25,6,'NUEVO',0,0,'C',1);
$pdf->Cell(25,6,'BUENO',0,0,'C',1);
$pdf->Cell(25,6,'REGULAR',0,0,'C',1);
$pdf->Cell(25,6,'MALO',0,1,'C',1);


$pdf->SetXY(130, $posicionY);

$pdf->SetFont('dejavusans','B',8);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(30,6,'Kilometraje inicial',0,0,'C');
$pdf->Cell(30,6,'',1,0,'C');
$pdf->Cell(40,6,'Kilometraje final',0,0,'R');
$pdf->Cell(30,6,'',1,1,'C');

$pdf->Ln(1);
$pdf->SetX(130);
$pdf->Cell(0,10,'INVENTARIO DEL AUTOMOVIL',0,0,'C');


$pdf->Ln(8);
$pdf->SetX(125);
$pdf->SetFont('dejavusans','',5);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'',0,0,'C',1);
$posicionY = $pdf->GetY();
$pdf->Cell(14,3,'EXTERIORES',0,1,'C',1);

$pdf->SetX(125);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(26,3,'',0,0,'C',1);
$pdf->Cell(7,3,'SI',0,0,'C',1);
$pdf->Cell(7,3,'NO',0,1,'C',1);

$pdf->SetX(125);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'UNIDAD DE LUCES',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(125);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(26,3,'¼ LUCES',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(125);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'ANTENA',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(125);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(26,3,'ESPEJO LATERAL',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(125);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'CRISTALES',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(125);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(26,3,'EMBLEMA',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(125);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'LLANTAS (4)',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(125);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(26,3,'TAPON DE RUEDAS (4)',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(125);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'MOLDURAS COMPLETAS',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(125);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(26,3,'TAPON DE GASOLINA',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(125);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'CARROCERIA SIN GOLPES',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(125);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(26,3,'BOCINAS DE CLAXON',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(125);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'LIMPIADORES',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->Line(158,$posicionY+3,158,$posicionY+45);

$pdf->Ln(5);
$pdf->SetXY(165,$posicionY);

$pdf->SetFont('dejavusans','',5);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'',0,0,'C',1);
$pdf->Cell(14,3,'COMPONENTES MECANICOS',0,1,'C',1);

$pdf->SetX(171);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(26,3,'',0,0,'C',1);
$pdf->Cell(7,3,'SI',0,0,'C',1);
$pdf->Cell(7,3,'NO',0,1,'C',1);

$pdf->SetX(171);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'CLAXON',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(171);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(26,3,'TAPON DE ACEITE',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(171);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'TAPON DE RADIADOR',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(171);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(26,3,'VARILLA DE ACEITE',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(171);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'FILTRO DE AIRE',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);
$pdf->SetX(171);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(26,3,'BATERIA (MCA)',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);


$pdf->SetXY(171,$posicionY+30);

$pdf->SetFont('dejavusans','',5);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'',0,0,'C',1);
$pdf->Cell(14,3,'ACCESORIOS',0,1,'C',1);

$pdf->SetX(171);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(26,3,'',0,0,'C',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(171);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'GATO',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(171);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(26,3,'MANERAL DE GATO',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(171);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'LLAVE DE RUEDAS/DE CRUZ',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(171);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(26,3,'ESTUCHE DE HERRAMIENTAS',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(171);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'TRIANGULO DE SEGURIDAD',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(171);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(26,3,'LLANTA DE REFACCIÓN',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->Line(204,$posicionY+3,204,$posicionY+24);
$pdf->Line(204,$posicionY+33,204,$posicionY+54);

$pdf->Ln(5);
$pdf->SetXY(215,$posicionY);

$pdf->SetFont('dejavusans','',5);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(30,3,'',0,0,'C',1);
$pdf->Cell(14,3,'INTERIORES',0,1,'C',1);

$pdf->SetX(215);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(30,3,'',0,0,'C',1);
$pdf->Cell(7,3,'SI',0,0,'C',1);
$pdf->Cell(7,3,'NO',0,1,'C',1);

$pdf->SetX(215);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(30,3,'INSTRUMENTOS DE TABLERO',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);


$pdf->SetX(215);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(30,3,'CALEFACCIÓN',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(215);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(30,3,'RADIO/TIPO',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);


$pdf->SetX(215);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(30,3,'BOCINAS',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(215);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(30,3,'ENCENDEDOR',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(215);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(30,3,'RETROVISOR',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(215);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(30,3,'CENICEROS',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(215);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(30,3,'CINTURONES',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(215);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(26,3,'BOTONES DE INTERIORES',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(215);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(30,3,'MANIJAS DE INTERIORES',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(215);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(30,3,'TAPETES',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(215);
$pdf->SetFillColor(190,204,213);
$pdf->Cell(30,3,'VESTIDURAS',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->SetX(215);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(30,3,'GUANTERA',0,0,'L',1);
$pdf->Cell(14,3,'',0,1,'C',1);

$pdf->Line(252,$posicionY+3,252,$posicionY+45);

$pdf->Ln( 2 );

$pdf->SetXY(120, 105);
$pdf->SetFont( 'dejavusans', '', 7 );
$html = '<p>Por medio de la presente se hace constar que el usuario recibe de <b>' . $empresa['nombre_fiscal'] . '</b> como parte del equipo de trabajo el vehículo descrito en la presente carta responsiva por medio de la cual se obliga a devolver dicho vehículo cuando lo solicite <b>' . $empresa['nombre_fiscal'] . '</b> o cuando termine la relación laboral. El vehículo, así como el equipo, accesorios, documentos e instructivos, fueron entregados de acuerdo con el presente inventario del vehículo.</p><p>El usuario se responsabiliza de su buen uso, y está enterado y de acuerdo que el vehículo es primordialmente para las funciones propias del empleo, cuya regulación queda establecida en el documento "Políticas y Condiciones para Uso de Vehículos Utilitarios", el cual también ha firmado de conformidad y se anexa a éste.</p><p>La dirección que se indica en este documento es el lugar donde se guardará el vehículo, y el usuario confirma que dicho domicilio cumple con la seguridad necesaria para el resguardo de la unidad, así mismo se compromete a informar de manera inmediata en caso de existir algún cambio de domicilio del lugar de resguardo. </p>';


// Agregar el HTML usando writeHTMLCell
$pdf->writeHTMLCell( 0, 10, '', '', $html, 0, 1, false, true, 'J' );
$pdf->Ln(2);
$pdf->SetX(120);

$pdf->SetFont('dejavusans','B',7);
$pdf->SetFillColor(255, 255, 255);
$pdf->Ln(1);
$pdf->SetX(120);
$pdf->SetFillColor(190,204,213);
$posicionY = $pdf->GetY();
$posicionX = $pdf->GetX();
$pdf->MultiCell(80,10,'El usuario acepta de manera voluntaria la responsabilidad del resguardo del vehículo',0, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');

$pdf->SetXY($posicionX+80,$posicionY);
$pdf->Cell(30,10,'SI',0,0,'C',1);
$pdf->Cell(30,10,'NO',0,1,'C',1);

$pdf->Ln(1);
$pdf->SetFillColor(255, 255, 255);

$pdf->SetFont('dejavusans','B',6);
$pdf->SetX(120);
$pdf->Cell(0,5,"El responsable tendrá que devolver el vehículo con la misma cantidad de gasolina que fue entregado",0,1,'L');

$pdf->SetXY(120, 175);
$pdf->SetFont('dejavusans','',5);
$pdf->Cell(47,3,'USUARIO NOMBRE Y FIRMA',0,0,'C',1);
$pdf->Cell(47,3,'JOSE ANGEL JIMENEZ PINEDA',0,0,'C',1);
$pdf->Cell(47,3,'DEPARTAMENTO DE CONTROL',0,1,'C',1);

$pdf->SetX(120);
$pdf->Cell(47,3,'SALIDA DE VEHÍCULO',0,0,'C',1);
$pdf->Cell(47,3,'DIRECTOR GENERAL',0,0,'C',1);
$pdf->Cell(47,3,'RESPONSABLE DE ASIGNACIÓN DE VEHÍCULOS',0,1,'C',1);

$pdf->SetXY(123, 195);
$pdf->Cell(40,3,'USUARIO NOMBRE Y FIRMA',0,1,'C',1);

$pdf->SetX(123);
$pdf->Cell(40,3,'ENTREGA DE VEHÍCULO',0,1,'C',1);

$pdf->Line(125,175,162,175);
$pdf->Line(172,175,207,175);
$pdf->Line(215,175,260,175);
$pdf->Line(125,195,162,195);

$pdf->SetXY(167,182);
// Vertical alignment
$pdf->MultiCell(92, 18, 'Observaciones:', 1, 'J', 1, 0, '', '', true, 0, false, true, 40, 'T');

// Obtén el contenido del PDF en formato base64
$pdfContentBase64 = base64_encode( $pdf->Output( 'Responsiva.pdf', 'S' ) );

// Devuelve el contenido base64 al cliente
echo $pdfContentBase64;
?>
