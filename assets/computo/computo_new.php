<?php

  $pdf = new TCPDF();
  $pdf->AddPage();

  // Extender la clase TCPDF para personalizar el encabezado y pie de página
  class CustomPDF extends TCPDF {
    // Método para el encabezado
    public function Header() {
      // Configura la fuente y el tamaño del texto
      $this->SetFont( 'helvetica', 'B', 12 );
      // Definir las medidas personalizadas del cuadro en el encabezado
      $cuadroX = 13; // Posición X del cuadro
      $cuadroY = 19; // Posición Y del cuadro
      $cuadroAncho = 184; // Ancho del cuadro
      $cuadroAlto = 20; // Alto del cuadro

      // Dibujar un cuadro en el encabezado
      $this->SetDrawColor( 0, 0, 0 ); // Establecer el color del contorno (negro)
      $this->SetFillColor( 255, 255, 255 ); // Establecer el color de relleno (blanco)
      $this->Rect( $cuadroX, $cuadroY, $cuadroAncho, $cuadroAlto, 'DF' );


      // Agregar el logo
      $logoX = $cuadroX + 1; // Posición X del logo
      $logoY = $cuadroY + 1; // Posición Y del logo
      $logoAncho = 18; // Ancho del logo (ajustar según tus necesidades)
      $logoAlto = 18; // Alto del logo (ajustar según tus necesidades)
      $logoPath = $_SERVER[ 'DOCUMENT_ROOT' ] . '/saturno/img/INNOVET_Logotipo-02.jpg'; // Ruta al archivo de imagen del logo

      $this->Image( $logoPath, $logoX, $logoY, $logoAncho, $logoAlto );

      // Escribir el contenido del encabezado
      $this->Cell( 0, 10, 'REQUISICIÓN DE COTIZACIÓN', 0, false, 'C', 0, '', 0, false, 'M', 'M' );
    }

    // Método para el pie de página
    public function Footer() {
      // Posiciona el pie de página a 1.5 cm del borde inferior
      $this->SetY( -27 );
      // Configura la fuente y el tamaño del texto
      $this->SetFont( 'helvetica', '', 6 );

      // Dibuja una línea negra encima del texto
      $this->SetDrawColor( 0, 0, 0 ); // Establece el color de la línea a negro
      $this->SetLineWidth( 0.4 ); // Establece el grosor de la línea
      $this->Line( $this->GetX(), $this->GetY() + 3, $this->GetX() + $this->getPageWidth() - $this->lMargin - $this->rMargin, $this->GetY() + 3 );

      // Escribir el contenido del pie de página
      $this->Cell( 0, 10, ' ACF05 Fecha de efectividad: 01-noviembre-2017 Revisión: 02', 0, false, 'L', 0, '', 0, false, 'T', 'M' );
    }
    // Método para agregar el cuadro
    public function AddMarginBox() {
      // Guardar las configuraciones actuales
      $this->StartTransform();
      $this->SetLineStyle( array( 'width' => 0.2, 'color' => array( 0, 0, 0 ) ) ); // Grosor y color del contorno
      $this->Rect( $this->GetX(), $this->GetY(), $this->getPageWidth() - $this->lMargin - $this->rMargin, $this->getPageHeight() - $this->tMargin - $this->bMargin, 'D' ); // Dibuja el rectángulo
      $this->StopTransform();
    }
  }

  // Crea una nueva instancia de CustomPDF
  $pdf = new CustomPDF( 'P', 'mm', 'Letter', true, 'UTF-8' );
  $pdf->SetMargins( 13, 19, 13 );
  $pdf->SetHeaderMargin( 30 );
  $pdf->SetFooterMargin( 8 );


  // add a page
  $pdf->AddPage();
  $pdf->AddMarginBox();

  $cuadroX2 = 13; // Posición X del cuadro
  $cuadroY2 = 39; // Posición Y del cuadro
  $cuadroAncho2 = 184; // Ancho del cuadro
  $cuadroAlto2 = 5; // Alto del cuadro

  // Dibujar un cuadro con el fondo gris del sub-titulo
  $pdf->SetDrawColor( 0, 0, 0 ); // Establecer el color del contorno (negro)
  $pdf->SetFillColor( 202, 202, 202 ); // Establecer el color de relleno (blanco)
  $pdf->Rect( $cuadroX2, $cuadroY2, $cuadroAncho2, $cuadroAlto2, 'DF' );
  $pdf->SetY( $cuadroY2 - 2 );
  $pdf->SetFillColor( 0, 0, 0 );
  $pdf->SetFont( 'helvetica', 'B', 9 );
  $pdf->Cell( 0, 10, 'DATOS GENERALES', 0, false, 'C', 0, '', 0 );

  $pdf->Ln( 10 );
  $pdf->SetFont( 'dejavusans', '', 7 );

  // Definir las columnas
  $cellWidth = 40; // Ancho de cada celda
  $cellHeight = 0; // Alto de cada fila

  // Escribir el texto en cada columna
   
$nuevaFecha = date("d/m/Y", strtotime($fecha));
  $pdf->Cell( $cellWidth, $cellHeight, 'FECHA', 0, 0, 'C' );
  $pdf->Cell( $cellWidth, $cellHeight, $nuevaFecha , 'B', 0, 'C' );
  $pdf->Cell( $cellWidth, $cellHeight, 'NO. PROYECTO', 0, 0, 'C' );
  $pdf->Cell( $cellWidth, $cellHeight, $folio, 'B', 1, 'C' );

  $pdf->Ln( 2 );
  $pdf->Cell( $cellWidth, $cellHeight, 'CLIENTE', 0, 0, 'C' );
  $pdf->Cell( $cellWidth, $cellHeight, $resultado[ 'cliente' ], 'B', 1, 'C' );

  $pdf->Ln( 4 );
  $pdf->Cell( $cellWidth, $cellHeight, 'CONTACTO', 0, 0, 'C' );

  // Obtener el ancho del texto en la fuente actual
  $texto = $resultado4[ 'nombre_contacto' ];
  $anchoTexto = $pdf->GetStringWidth( $texto );

  // Verificar si el ancho del texto es mayor que el ancho de la celda
  if ( $anchoTexto > $cellWidth ) {
    $fontSize = 8; // Tamaño de fuente inicial
    $minFontSize = 6; // Tamaño de fuente mínimo permitido
    $anchoDisponible = $cellWidth - $pdf->cMargin * 2;

    // Reducir gradualmente el tamaño de la fuente hasta que el texto quepa en la celda
    while ( $pdf->GetStringWidth( $texto ) > $anchoDisponible && $fontSize > $minFontSize ) {
      $fontSize--;
      $pdf->SetFontSize( $fontSize );
    }
  }

  $pdf->Cell( $cellWidth, $cellHeight, $texto, 'B', 0, 'C' );
  $pdf->Cell( $cellWidth, $cellHeight, " NOMBRE DEL PROYECTO", '0', 0, 'C' );
  // Obtener el ancho del texto en la fuente actual
  $txt = $resultado2[ 'nombre_proyecto' ];
  $anTexto = $pdf->GetStringWidth( $txt );

  // Verificar si el ancho del texto es mayor que el ancho de la celda
  if ( $anTexto > $cellWidth ) {
    $fontS = 8; // Tamaño de fuente inicial
    $minFontS = 6; // Tamaño de fuente mínimo permitido
    $anchoD = $cellWidth - $pdf->cMargin * 2;

    // Reducir gradualmente el tamaño de la fuente hasta que el texto quepa en la celda
    while ( $pdf->GetStringWidth( $txt ) > $anchoD && $fontS > $minFontS ) {
      $fontS--;
      $pdf->SetFontSize( $fontS );
    }
  }
  $pdf->Cell( $cellWidth, $cellHeight, $txt, 'B', 1, 'C' );

  $pdf->Ln( 2 );
  $pdf->Cell( $cellWidth, $cellHeight, "PUESTO", 0, 0, 'C' );
  $pdf->Cell( $cellWidth, $cellHeight, $resultado4[ 'puesto' ], 'B', 1, 'C' );

  $pdf->Ln( 3 );
  $pdf->Cell( $cellWidth, $cellHeight + 6, "DOMICILIO", 0, 0, 'C' );

 $txtd = $domicilio;
$anchoDisponible2 = $cellWidth - $pdf->getMargins()['left'] * 2;
$maxCaracteres = 40;

// Verificar si el texto tiene más de 35 caracteres y truncarlo con puntos suspensivos si es necesario
if (strlen($txtd) > $maxCaracteres) {
    $txtd = substr($txtd, 0, $maxCaracteres - 3) . '...';
}

$fontSize2 = 8; // Tamaño de fuente inicial
$minFontSize2 = 6; // Tamaño de fuente mínimo permitido

// Reducir gradualmente el tamaño de la fuente hasta que el texto quepa en la celda
while ($pdf->GetStringWidth($txtd) > $anchoDisponible2 && $fontSize2 > $minFontSize2) {
    $fontSize2--;
    $pdf->SetFontSize($fontSize2);
}

$pdf->MultiCell($cellWidth, $cellHeight, $txtd,  'B', 'C', 0, 1, '', '', true, 0, false, true, 5, 'M');


  $pdf->Ln( 3 );
  $pdf->SetFont( 'dejavusans', '', 7 );
  $pdf->Cell( $cellWidth, $cellHeight + 6, "LUGAR DE ENTREGA", 0, 0, 'C' );
 $txtLE = $ver_lugar_entrega;
$anchoLE = $cellWidth - $pdf->getMargins()['left'] * 2;
$maxCaracteres = 40;

// Verificar si el texto tiene más de 35 caracteres y truncarlo con puntos suspensivos si es necesario
if (strlen($txtLE) > $maxCaracteres) {
    $txtLE = substr($txtLE, 0, $maxCaracteres - 3) . '...';
}

$fontSizeLE = 8; // Tamaño de fuente inicial
$minFontSizeLE = 6; // Tamaño de fuente mínimo permitido

// Reducir gradualmente el tamaño de la fuente hasta que el texto quepa en la celda
while ($pdf->GetStringWidth($txtLE) > $anchoLE && $fontSizeLE > $minFontSizeLE) {
    $fontSizeLE--;
    $pdf->SetFontSize($fontSizeLE);
}

$pdf->MultiCell($cellWidth, $cellHeight, $txtLE,  'B', 'C', 0, 1, '', '', true, 0, false, true, 5 , 'M');



  $pdf->Ln( 3 );

  $pdf->SetFont( 'dejavusans', '', 7 );
  $pdf->Cell( $cellWidth, $cellHeight, "TELEFONO", 0, 0, 'C' );
  $pdf->Cell( $cellWidth, $cellHeight, $resultado4[ "tel" ], 'B', 1, 'C' );
  $pdf->Ln( 2 );
  $pdf->Cell( $cellWidth, $cellHeight, "EMAIL", 0, 0, 'C' );
  $pdf->Cell( $cellWidth, $cellHeight, $resultado4[ "correo" ], 0, 1, 'C' );

  $pdf->Ln();


  $pdf->SetFont( 'dejavusans', '', 9 );
  $pdf->SetXY( 115, 75 );
  $pdf->MultiCell( 20, $cellHeight, "TIPO DE EMPAQUE", 0, 'R' );
  switch ( $tipo_empaque ) {
    case 1:
      $tipo_empaqueC = "Blíster";
      break;
    case 2:
      $tipo_empaqueC = "Charola";
      break;
    case 3:
      $tipo_empaqueC = "Clamshells";
      break;
    case 4:
      $tipo_empaqueC = "Bancos termoformados";
      break;
    case 5:
      $tipo_empaqueC = "Piezas de movilidad";
      break;
  }
  $pdf->SetFont( 'dejavusans', '', 10 );
  $pdf->SetXY( 137, 75 );
  $pdf->MultiCell( 30, $cellHeight, $tipo_empaqueC, 0, 'C' );


  $pdf->Ln();
  // Dibuja una línea negra encima del texto
  $pdf->SetDrawColor( 0, 0, 0 ); // Establece el color de la línea a negro
  $pdf->SetLineWidth( 0.2 ); // Establece el grosor de la línea

  $pdf->SetY( 91 );
  $cuadroX2 = $pdf->GetX(); // Posición X del cuadro
  $cuadroY2 = $pdf->GetY() + 3; // Posición Y del cuadro
  $cuadroAncho2 = 184; // Ancho del cuadro
  $cuadroAlto2 = 5; // Alto del cuadro

  // Dibujar un cuadro con el fondo gris del sub-titulo
  $pdf->SetDrawColor( 0, 0, 0 ); // Establecer el color del contorno (negro)
  $pdf->SetFillColor( 202, 202, 202 ); // Establecer el color de relleno (blanco)
  $pdf->Rect( $cuadroX2, $cuadroY2, $cuadroAncho2, $cuadroAlto2, 'DF' );
  $pdf->SetY( $cuadroY2 - 2 );
  $pdf->SetFillColor( 0, 0, 0 );
  $pdf->SetFont( 'helvetica', 'B', 9 );
  $pdf->Cell( 0, 10, 'ESPECIFICACIONES DEL PROYECTO', 0, false, 'C', 0, '', 0 );

  $pdf->Ln( 10 );
  $pdf->SetFont( 'dejavusans', '', 7 );
  $pdf->MultiCell( $cellWidth - 20, $cellHeight, "FRECUENCIA DE COMPRA", 0, 'R' );
  $pdf->SetXY( $pdf->GetX() + 21, $pdf->GetY() - 4 );
  $pdf->MultiCell( $cellWidth - 5, $cellHeight, $frecuencia, 'B', 'C' );

  $pdf->SetXY( $pdf->GetX() + 90, $pdf->GetY() - 6 );
  $pdf->MultiCell( $cellWidth - 15, $cellHeight, "CANTIDAD POR LOTE DE", 0, 'R' );
  $pdf->SetXY( $pdf->GetX() + 118, $pdf->GetY() - 4 );
  $pdf->MultiCell( $cellWidth - 5, $cellHeight, $cant_lote, 'B', 'C' );


  $pdf->Ln( 5 );
  $pdf->SetFont( 'helvetica', 'B', 9 );
  $pdf->Cell( $cellWidth + 20, $cellHeight, 'DIMENSIONES DE LA PIEZA', 0, 1, 'C' );

  $pdf->Ln( 3 );
  $pdf->SetFont( 'dejavusans', '', 7 );
  $pdf->Cell( $cellWidth - 22, $cellHeight, 'LARGO', 0, 0, 'R' );
  $pdf->Cell( $cellWidth - 17, $cellHeight, $d_largo, 'B', 0, 'R' );
  $pdf->Cell( $cellWidth - 25, $cellHeight, 'mm', 0, 0, 'C' );

  $pdf->Cell( $cellWidth - 5, $cellHeight, 'ANCHO', 0, 0, 'R' );
  $pdf->Cell( $cellWidth - 17, $cellHeight, $d_ancho, 'B', 0, 'R' );
  $pdf->Cell( $cellWidth - 25, $cellHeight, 'mm', 0, 0, 'C' );

  $pdf->Cell( $cellWidth - 23, $cellHeight, 'ALTO', 0, 0, 'R' );
  $pdf->Cell( $cellWidth - 17, $cellHeight, $d_alto, 'B', 0, 'R' );
  $pdf->Cell( $cellWidth - 25, $cellHeight, 'mm', 0, 1, 'C' );

  $pdf->Ln( 5 );
  $pdf->SetFont( 'helvetica', 'B', 8 );
  $pdf->Cell( $cellWidth + 23, $cellHeight, 'ESPECIFICACIONES DEL MATERIAL', 0, 1, 'C' );

  $pdf->Ln( 3 );
  $pdf->SetFont( 'dejavusans', '', 7 );
  $pdf->Cell( $cellWidth - 22, $cellHeight, 'MATERIAL', 0, 0, 'R' );
  $pdf->Cell( $cellWidth, $cellHeight, $material, 'B', 0, 'C' );

  $pdf->Cell( $cellWidth - 25, $cellHeight, 'CALIBRE', 0, 0, 'R' );
  $pdf->Cell( $cellWidth - 17, $cellHeight, $calibre, 'B', 0, 'L' );

  $pdf->Cell( $cellWidth - 28, $cellHeight, 'COLOR', 0, 0, 'R' );
  $pdf->Cell( $cellWidth - 17, $cellHeight, $color, 'B', 0, 'L' );

  $pdf->Cell( $cellWidth - 14, $cellHeight, 'FRANJA DE COLOR', 0, 0, 'R' );
  $pdf->Cell( $cellWidth - 17, $cellHeight, $franja_color, 'B', 0, 'L' );

  $pdf->Ln( 5 );
  // Dibuja una línea negra encima del texto
  $pdf->SetDrawColor( 0, 0, 0 ); // Establece el color de la línea a negro
  $pdf->SetLineWidth( 0.2 ); // Establece el grosor de la línea

  $cuadroX2 = $pdf->GetX(); // Posición X del cuadro
  $cuadroY2 = $pdf->GetY() + 3; // Posición Y del cuadro
  $cuadroAncho2 = 184; // Ancho del cuadro
  $cuadroAlto2 = 5; // Alto del cuadro

  // Dibujar un cuadro con el fondo gris del sub-titulo
  $pdf->SetDrawColor( 0, 0, 0 ); // Establecer el color del contorno (negro)
  $pdf->SetFillColor( 202, 202, 202 ); // Establecer el color de relleno (blanco)
  $pdf->Rect( $cuadroX2, $cuadroY2, $cuadroAncho2, $cuadroAlto2, 'DF' );
  $pdf->SetY( $cuadroY2 - 2 );
  $pdf->SetFillColor( 0, 0, 0 );
  $pdf->SetFont( 'helvetica', 'B', 9 );
  $pdf->Cell( 0, 10, 'ESPECIFICACIONES DE EMPAQUE', 0, false, 'C', 0, '', 0 );

  $pdf->Ln( 11 );
  $pdf->SetFont( 'dejavusans', '', 7 );
  $pdf->Cell( $cellWidth, $cellHeight, "TIPO DE CORRUGADO", 0, 0, 'R' );
  switch ( $t_corrugado ) {
    case 1:
      $t_corrugadoC = "SI";
      break;
    case 2:
      $t_corrugadoC = "NO";
      break;
    case 3:
      $t_corrugadoC = "N/C";
      break;
  }
  $pdf->Cell( $cellWidth - 22, $cellHeight, $t_corrugadoC, "B", 0, 'C' );

  $pdf->Cell( 30, $cellHeight, "BOLSA DE PLÁSTICO", 0, 0, 'R' );
  switch ( $bolsa ) {
    case 1:
      $bolsaC = "SI";
      break;
    case 2:
      $bolsaC = "NO";
      break;
    case 3:
      $bolsaC = "N/C";
      break;
  }
  $pdf->Cell( $cellWidth - 22, $cellHeight, $bolsaC, "B", 0, 'C' );

  $pdf->Cell( 12, $cellHeight, "LINER", 0, 0, 'R' );
  switch ( $liner ) {
    case 1:
      $linerC = "SI";
      break;
    case 2:
      $linerC = "NO";
      break;
    case 3:
      $linerC = "N/C";
      break;
  }
  $pdf->Cell( $cellWidth - 22, $cellHeight, $linerC, "B", 0, 'C' );

  $pdf->Cell( 20, $cellHeight, "ESQUINEROS", 0, 0, 'R' );
  switch ( $esquineros ) {
    case 1:
      $esquinerosC = "SI";
      break;
    case 2:
      $esquinerosC = "NO";
      break;
    case 3:
      $esquinerosC = "N/C";
      break;
  }
  $pdf->Cell( $cellWidth - 22, $cellHeight, $esquinerosC, "B", 1, 'C' );

  $pdf->Ln( 3 );
  $pdf->Cell( $cellWidth, $cellHeight, "OTRAS ESPECIFICACIONES:", 0, 0, 'R' );
  $pdf->Cell( $cellWidth + 94, $cellHeight, $otras_specs, "B", 1, 'C' );


  // Dibuja una línea negra encima del texto
  $pdf->SetDrawColor( 0, 0, 0 ); // Establece el color de la línea a negro
  $pdf->SetLineWidth( 0.2 ); // Establece el grosor de la línea

  $cuadroX2 = $pdf->GetX(); // Posición X del cuadro
  $cuadroY2 = $pdf->GetY() + 3; // Posición Y del cuadro
  $cuadroAncho2 = 184; // Ancho del cuadro
  $cuadroAlto2 = 5; // Alto del cuadro

  // Dibujar un cuadro con el fondo gris del sub-titulo
  $pdf->SetDrawColor( 0, 0, 0 ); // Establecer el color del contorno (negro)
  $pdf->SetFillColor( 202, 202, 202 ); // Establecer el color de relleno (blanco)
  $pdf->Rect( $cuadroX2, $cuadroY2, $cuadroAncho2, $cuadroAlto2, 'DF' );
  $pdf->SetY( $cuadroY2 - 2 );
  $pdf->SetFillColor( 0, 0, 0 );

  $pdf->Ln( 7 );

  $pdf->MultiCell( $cellWidth, $cellHeight + 60, "DATOS CRÍTICOS / ESPECIALES QUE EL CLIENTE SOLICITE", 'RB', 'C', 0, 0, '', '', true, 0, false, true, 60, 'M' );
  $textoDA = $datos_criticos;
  // Verificar la longitud del texto
  if ( strlen( $textoDA ) > 600 ) {
    $textoDA = substr( $textoDA, 0, 600 ) . "...";
  }
  $pdf->SetFont( 'dejavusans', '', 6 );
  $pdf->MultiCell( 0, $cellHeight + 60, $textoDA, 'B', 'C', 0, 0, '', '', true, 0, false, true, 60, 'M' );

  $pdf->Ln( 57 );
  // Dibuja una línea negra encima del texto
  $pdf->SetDrawColor( 0, 0, 0 ); // Establece el color de la línea a negro
  $pdf->SetLineWidth( 0.2 ); // Establece el grosor de la línea

  $cuadroX2 = $pdf->GetX(); // Posición X del cuadro
  $cuadroY2 = $pdf->GetY() + 3; // Posición Y del cuadro
  $cuadroAncho2 = 184; // Ancho del cuadro
  $cuadroAlto2 = 5; // Alto del cuadro

  // Dibujar un cuadro con el fondo gris del sub-titulo
  $pdf->SetDrawColor( 0, 0, 0 ); // Establecer el color del contorno (negro)
  $pdf->SetFillColor( 202, 202, 202 ); // Establecer el color de relleno (blanco)
  $pdf->Rect( $cuadroX2, $cuadroY2, $cuadroAncho2, $cuadroAlto2, 'DF' );
  $pdf->SetY( $cuadroY2 - 2 );
  $pdf->SetFillColor( 0, 0, 0 );
  $pdf->SetFont( 'helvetica', 'B', 9 );
  $pdf->Ln( 2 );
  $pdf->Cell( 92, 5, 'Cotizacion adicional', 0, false, 'C', 0, '', 0 );
  $pdf->Cell( 92, 5, 'Informacion adicional', 'L', false, 'C', 0, '', 0 );

  $pdf->Ln( 8 );
  $pdf->SetFont( 'dejavusans', '', 8 );
  $pdf->Cell( $cellWidth, $cellHeight, "PPAP", 0, 0, 'R' );
    switch($ppap){
        case 1:
            $pdf->SetFont( 'dejavusans', '', 7 );
            $ppapC="Pzs. dim: ".$pxd."  Cotas/pza: ".$cxp;
            break;
        case 2:
            $ppapC="NO";
                break;
        case 3:
            $ppapC="N/C";
            break;            
    }
  $pdf->Cell( $cellWidth, $cellHeight, $ppapC, 'B', 0, 'C' );
$pdf->SetFont( 'dejavusans', '', 8 );
  $pdf->Cell( $cellWidth + 15, $cellHeight, "Altura Maxima de estiba", 0, 0, 'R' );
  $pdf->Cell( $cellWidth, $cellHeight, $max_estiba, 'B', 1, 'C' );

  $pdf->Ln( 3 );
  $pdf->Cell( $cellWidth, $cellHeight, "CORRIDA PILOTO", 0, 0, 'R' );
  $pdf->Cell( $cellWidth, $cellHeight, $corrida_piloto, 'B', 0, 'C' );

  $pdf->Cell( $cellWidth + 15, $cellHeight, "Peso Maxima por caja", 0, 0, 'R' );
  $pdf->Cell( $cellWidth, $cellHeight, $max_peso_caja, 'B', 1, 'C' );

  $pdf->Ln( 3 );
  $pdf->Cell( $cellWidth, $cellHeight, "HERRAMENTALES", 0, 0, 'R' );
      switch ( $herramentales) {
    case 1:
    $herramentalesC = "SI";
      break;
    case 2:
      $herramentalesC = "NO";
      break;
    case 3:
      $herramentalesC = "N/C";
      break;
  }
  $pdf->Cell( $cellWidth, $cellHeight,$herramentalesC , 'B', 0, 'C' );

  $pdf->Cell( $cellWidth + 15, $cellHeight, "Peso del componente", 0, 0, 'R' );
  $pdf->Cell( $cellWidth, $cellHeight, $peso_com, 'B', 1, 'C' );

  $pdf->Ln( 3 );
  $pdf->Cell( $cellWidth, $cellHeight, "ALMACENAJE", 0, 0, 'R' );
   switch($almacenaje){
        case 1:
            $pdf->SetFont( 'dejavusans', '', 7 );
            $almacenajeC="Tma: ".$tarimas."  Tiem: ".$tiempo;
            break;
        case 2:
            $almacenajeC="NO";
                break;
        case 3:
            $almacenajeC="N/C";
            break;            
    }
  $pdf->Cell( $cellWidth, $cellHeight, $almacenajeC, 'B', 0, 'C' );

  $pdf->Cell( $cellWidth + 15, $cellHeight, "Componentes por charola", 0, 0, 'R' );
  $pdf->Cell( $cellWidth, $cellHeight, $com_charola, 'B', 1, 'C' );

  $pdf->Ln( 3 );
  $pdf->Cell( $cellWidth, $cellHeight, "OTRO", 0, 0, 'R' );
  $pdf->Cell( $cellWidth, $cellHeight, $otro1, 'B', 0, 'C' );

  $pdf->Cell( $cellWidth + 15, $cellHeight, "Pestaña", 0, 0, 'R' );
  $pdf->Cell( $cellWidth, $cellHeight, $pestana, 'B', 1, 'C' );

  $pdf->Ln( 3 );
  $pdf->Cell( $cellWidth, $cellHeight, "OTRO", 0, 0, 'R' );
  $pdf->Cell( $cellWidth, $cellHeight, $otro2, 0, 0, 'C' );

  $pdf->Cell( $cellWidth + 15, $cellHeight, "OTRO", 0, 0, 'R' );
  $pdf->Cell( $cellWidth, $cellHeight, $otro4, 0, 1, 'C' );

  $x = 105; // Posición X de la línea
  $y1 = 230; // Posición Y de inicio de la línea
  $y2 = 273; // Posición Y de fin de la línea

  // Dibujar la línea vertical
  $pdf->Line( $x, $y1, $x, $y2 );

  $pdf->AddPage();
  $pdf->AddMarginBox();
  $pdf->SetY( 39 );
  $pdf->Ln( 5 );
  $pdf->SetFont( 'dejavusans', '', 8 );
  $pdf->Cell( $cellWidth - 5, $cellHeight, "Tipo de estiba", 0, 1, 'R' );
  $estiba0 = "";
  $estiba180 = "";
  $estibaSN = "";
  switch ( $t_estiba ) {
    case 1:
      $estiba0 = "X";
      break;
    case 2:
      $estiba180 = "X";
      break;
    case 3:
      $estibaSN = "X";
      break;
    default:
      $estibaSN = "X";
      break;
  }
  $pdf->Cell( $cellWidth + 12, $cellHeight, "cero grados", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $estiba0, 1, 1, 'C' );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "180°", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $estiba180, 1, 1, 'C' );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "sin estiba", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $estibaSN, 1, 1, 'C' );

  $pdf->Ln( 6 );
  $pdf->Cell( $cellWidth + 3, $cellHeight, "Grabados ", 0, 1, 'C' );
  $grabados1 = "";
  $grabados2 = "";
  $grabados3 = "";
  $grabados4 = "";
  if ( strpos( $string_grabados, '1' ) !== false ) {
    $grabados1 = "X";
  }
  if ( strpos( $string_grabados, '2' ) !== false ) {
    $grabados2 = "X";
  }
  if ( strpos( $string_grabados, '3' ) !== false ) {
    $grabados3 = "X";
  }
  if ( strpos( $string_grabados, '4' ) !== false ) {
    $grabados4 = "X";
  }
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Número de parte", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $grabados1, 'LTRB', 1, 'C' );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Tipo de material ", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $grabados2, 'LRB', 1, 'C' );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Logo Cliente", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $grabados3, 'LRB', 1, 'C' );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Logo INNOVET", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $grabados4, 'LRB', 1, 'C' );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Otros", 0, 0, 'R' );
  $pdf->SetFont( 'dejavusans', '', 6 );
  $pdf->Cell( $cellWidth / 2, $cellHeight + 4, $grabados_otro, 'LRB', 1, 'C' );

  $pdf->SetFont( 'dejavusans', '', 8 );
  $pdf->Ln( 6 );
  $pdf->Cell( $cellWidth - 5, $cellHeight, "Flujo de carga", 0, 1, 'R' );
  $flujo1 = "";
  $flujo2 = "";
  switch ( $flujo_carga ) {
    case 1:
      $flujo1 = "X";
      break;
    case 2:
      $flujo2 = "X";
      break;
  }
  $pdf->Cell( $cellWidth + 12, $cellHeight, "entre componentes", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $flujo1, 1, 1, 'C' );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "sobre charola", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $flujo2, 1, 1, 'C' );

  $pdf->Ln( 6 );
  $pdf->Cell( $cellWidth - 17, $cellHeight, "Pared", 0, 1, 'R' );
  $pared1 = "";
  $pared2 = "";
  $pared3 = "";
  switch ( $pared ) {
    case 1:
      $pared1 = "X";
      break;
    case 2:
      $pared2 = "X";
      break;
    case 3:
      $pared3 = "X";
      break;
    default:
      $pared3 = "X";
      break;
  }
  $pdf->Cell( $cellWidth + 12, $cellHeight, "alta", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $pared1, 1, 1, 'C' );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "media", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $pared2, 1, 1, 'C' );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "sin pared", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $pared3, 1, 1, 'C' );

  $pdf->Ln( 6 );
  $pdf->Cell( $cellWidth - 13, $cellHeight, "Sujeción", 0, 1, 'R' );
  $sujecion1 = "";
  $sujecion2 = "";
  $sujecion3 = "";
  $sujecion4 = "";
  switch ($sujecion) {
    case 1:
      $sujecion1 = "X";
      break;
    case 2:
      $sujecion2 = "X";
      break;
    case 3:
      $sujecion3 = "X";
      break;
    case 4:
      $sujecion4 = "X";
      break;
    default:
      $sujecion4 = "X";
      break;
  }
  $pdf->Cell( $cellWidth + 12, $cellHeight, "movimiento limitado en vertical", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $sujecion1, 1, 1, 'C' );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "movimiento limitado en horizontal", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $sujecion2, 1, 1, 'C' );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Broche", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $sujecion3, 1, 1, 'C' );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "sin broche", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $sujecion4, 1, 1, 'C' );

  $pdf->Ln( 6 );
  $pdf->Cell( $cellWidth + 10, $cellHeight, "Temperaturas expuestas", 0, 1, 'R' );
  $temp_exp1 = "";
  $temp_exp2 = "";
  $temp_exp3 = "";
  $temp_exp4 = "";
  switch ( $temp_exp ) {
    case 1:
      $temp_exp1 = "X";
      break;
    case 2:
      $temp_exp2 = "X";
      break;
    case 3:
      $temp_exp3 = "X";
      break;
    case 4:
      $temp_exp4 = "X";
      break;
    default:
      $temp_exp4 = "X";
      break;
  }
  $pdf->Cell( $cellWidth + 12, $cellHeight, "0ºC _40ºC", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $temp_exp1, 1, 1, 'C' );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "40ºC_50ºC", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $temp_exp2, 1, 1, 'C' );
  $pdf->Cell( $cellWidth + 12, $cellHeight, ">50ºC", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $temp_exp3, 1, 1, 'C' );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "NC", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $temp_exp4, 1, 1, 'C' );

  $pdf->Ln( 6 );
  switch ( $proceso_inocuidad ) {
    case 1:
      $proceso_inocuidad1 = "X";
      break;
    case 2:
      $proceso_inocuidad1 = "";
      break;
  }
  $pdf->Cell( $cellWidth + 5, $cellHeight, "Proceso de inocuidad", 0, 0, 'R' );
  $pdf->Cell( $cellWidth - 33, $cellHeight, "", 0, 0, 'C' );

  $pdf->Cell( $cellWidth / 2, $cellHeight, $proceso_inocuidad1, 1, 1, 'C' );

  ////Ldo derecho 
  $XD = 100;
  $pdf->Ln( 6 );
  $pdf->SetXY( 100, 44 );
  $pdf->Cell( $cellWidth + 10, $cellHeight, "Informacion Termoformado", 0, 1, 'R' );
  $info_termo1 = "";
  $info_termo2 = "";
  $info_termo3 = "";
  $info_termo4 = "";
  $info_termo5 = "";
  $info_termo6 = "";
  $info_termo7 = "";
  $info_termo8 = "";
  $info_termo9 = "";
  if ( strpos( $string_info_termo, '1' ) !== false ) {
    $info_termo1 = "X";
  }
  if ( strpos( $string_info_termo, '2' ) !== false ) {
    $info_termo2 = "X";
  }
  if ( strpos( $string_info_termo, '3' ) !== false ) {
    $info_termo3 = "X";
  }
  if ( strpos( $string_info_termo, '4' ) !== false ) {
    $info_termo4 = "X";
  }
  if ( strpos( $string_info_termo, '5' ) !== false ) {
    $info_termo5 = "X";
  }
  if ( strpos( $string_info_termo, '6' ) !== false ) {
    $info_termo6 = "X";
  }
  if ( strpos( $string_info_termo, '7' ) !== false ) {
    $info_termo7 = "X";
  }
  if ( strpos( $string_info_termo, '8' ) !== false ) {
    $info_termo8 = "X";
  }
  if ( strpos( $string_info_termo, '9' ) !== false ) {
    $info_termo9 = "X";
  }
  $pdf->SetXY( 100, 48 );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Pieza a mejorar", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $info_termo1, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Pieza Fisica a proteger", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $info_termo2, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Plano pieza termoformada", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $info_termo3, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "IGS componente", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $info_termo4, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "IGS pieza termoformada", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $info_termo5, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Contenedor", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $info_termo6, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Plano de la Pieza PDF", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $info_termo7, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "NC", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $info_termo8, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "NA", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $info_termo4, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Otros", 0, 0, 'R' );
  $pdf->SetFont( 'dejavusans', '', 6 );
  $pdf->Cell( $cellWidth / 2, $cellHeight + 4, $info_termo_otro, 'LRB', 1, 'C' );

  $pdf->SetFont( 'dejavusans', '', 8 );
  $pdf->Ln( 8 );
  $pdf->SetXY( 86, $pdf->GetY() );
  $pdf->Cell( $cellWidth, $cellHeight, "Uso Cliente", 0, 1, 'R' );
  $uso_cliente1 = "";
  $uso_cliente2 = "";
  $uso_cliente3 = "";
  $uso_cliente4 = "";
  $uso_cliente5 = "";
  $uso_cliente6 = "";
  $uso_cliente7 = "";
  $uso_cliente8 = "";
  $uso_cliente9 = "";
  if ( strpos( $string_uso_cliente, '1' ) !== false ) {
    $uso_cliente1 = "X";
  }
  if ( strpos( $string_uso_cliente, '2' ) !== false ) {
    $uso_cliente2 = "X";
  }
  if ( strpos( $string_uso_cliente, '3' ) !== false ) {
    $uso_cliente3 = "X";
  }
  if ( strpos( $string_uso_cliente, '4' ) !== false ) {
    $uso_cliente4 = "X";
  }
  if ( strpos( $string_uso_cliente, '5' ) !== false ) {
    $uso_cliente5 = "X";
  }
  if ( strpos( $string_uso_cliente, '6' ) !== false ) {
    $uso_cliente6 = "X";
  }
  if ( strpos( $string_uso_cliente, '7' ) !== false ) {
    $uso_cliente7 = "X";
  }
  if ( strpos( $string_uso_cliente, '8' ) !== false ) {
    $uso_cliente8 = "X";
  }
  if ( strpos( $string_uso_cliente, '9' ) !== false ) {
    $uso_cliente9 = "X";
  }
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Manipulación Interna", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $uso_cliente1, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Proceso Interno Manual", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $uso_cliente2, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Proceso Interno Robotizado", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $uso_cliente3, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Envió Única Cliente", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $uso_cliente4, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Envió Cliente Retornable", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $uso_cliente5, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Exhibicion", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $uso_cliente6, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Exhibicion Sello", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $uso_cliente7, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Componente INT Automotriz", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $uso_cliente8, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Componente INT Automotriz", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $uso_cliente9, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Otros", 0, 0, 'R' );
  $pdf->SetFont( 'dejavusans', '', 6 );
  $pdf->Cell( $cellWidth / 2, $cellHeight + 4, $uso_cliente_otro, 'LRB', 1, 'C' );

  $pdf->SetFont( 'dejavusans', '', 8 );
  $pdf->Ln( 12 );
  $pdf->SetXY( 108, $pdf->GetY() );
  $pdf->Cell( $cellWidth, $cellHeight, "Caja Cliente (Dimensiones interiores)", 0, 1, 'L' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "largo", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $caja_l, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Ancho", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $caja_an, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "Alto", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $caja_al, 1, 1, 'C' );

  $pdf->Ln( 8 );
  $pdf->SetXY( 98, $pdf->GetY() );
  $pdf->Cell( $cellWidth - 17, $cellHeight, "Dedales", 0, 1, 'R' );
  $dedales1 = "";
  $dedales2 = "";
  $dedales3 = "";
  switch ( $dedales ) {
    case 1:
      $dedales1 = "X";

      break;
    case 2:
      $dedales2 = "X";
      break;
    case 3:
      $dedales3 = "X";
      break;

  }
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "180°", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $dedales1, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "90°", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $dedales2, 1, 1, 'C' );
  $pdf->SetXY( $XD, $pdf->GetY() );
  $pdf->Cell( $cellWidth + 12, $cellHeight, "120°", 0, 0, 'R' );
  $pdf->Cell( $cellWidth / 2, $cellHeight, $dedales3, 1, 1, 'C' );

  $pdf->Output( $_SERVER[ 'DOCUMENT_ROOT' ] . "/saturno/$cliente/$folio.pdf", 'F' );
?>