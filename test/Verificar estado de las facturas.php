<?php

function consultarCFDI( $rfc_emisor, $rfc_receptor, $total_facturado, $uuid_timbrado ) {
    // Crear cliente SOAP
    $wsdlUrl = 'https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc?wsdl';
    $soapClient = new SoapClient( $wsdlUrl );

    // Construir la expresión impresa
    $expresionImpresa = "?re={$rfc_emisor}&rr={$rfc_receptor}&tt={$total_facturado}&id={$uuid_timbrado}";

    // Realizar la consulta SOAP
    $response = $soapClient->Consulta( array( 'expresionImpresa' => $expresionImpresa ) );

    // Verificar si la respuesta es válida
    if ( $response && isset( $response->ConsultaResult ) ) {
        $resultado = $response->ConsultaResult;
        return array(
            'CodigoEstatus' => ( string )$resultado->CodigoEstatus,
            'Estado' => ( string )$resultado->Estado
        );
    } else {
        // Si la respuesta no es válida, devolver valores por defecto
        return array(
            'CodigoEstatus' => 'Desconocido',
            'Estado' => 'Desconocido'
        );
    }
}

function validarXMLCargar( $archivos ) {
    $xmls = array();


    foreach ( $archivos[ 'tmp_name' ] as $key => $tmp_name ) {
        $xmlActual = array();

        // Leer el archivo XML
        $xmlString = file_get_contents( $tmp_name );
        $xml = new SimpleXMLElement( $xmlString );

        // Registrar los espacios de nombres
        $xml->registerXPathNamespace( 'cfdi', 'http://www.sat.gob.mx/cfd/3' );
        $xml->registerXPathNamespace( 'tfd', 'http://www.sat.gob.mx/TimbreFiscalDigital' );

        // Obtener los datos relevantes del XML
        $xmlActual[ 'RFC_EMISOR' ] = ( string )$xml->xpath( '//cfdi:Emisor' )[ 0 ][ 'Rfc' ];
        $xmlActual[ 'RFC_RECEPTOR' ] = ( string )$xml->xpath( '//cfdi:Receptor' )[ 0 ][ 'Rfc' ];
        $xmlActual[ 'TOTAL' ] = ( string )$xml[ 'Total' ];
		$xmlActual[ 'SELLO' ] = ( string )$xml[ 'Sello' ];
        // Obtener el UUID del nodo tfd:TimbreFiscalDigital
        $uuidNode = $xml->xpath( '//tfd:TimbreFiscalDigital' );
        if ( !empty( $uuidNode ) ) {
            $xmlActual[ 'UUID' ] = ( string )$uuidNode[ 0 ][ 'UUID' ];
        } else {
            $xmlActual[ 'UUID' ] = 'No encontrado';
        }      
        // Consultar el CFDI en el servicio web SOAP
        $resultadoConsulta = consultarCFDI( $xmlActual[ 'RFC_EMISOR' ], $xmlActual[ 'RFC_RECEPTOR' ], $xmlActual[ 'TOTAL' ], $xmlActual[ 'UUID' ] );
        $xmlActual[ 'CODIGO_ESTATUS' ] = $resultadoConsulta[ 'CodigoEstatus' ];
        $xmlActual[ 'ESTADO' ] = $resultadoConsulta[ 'Estado' ];
        // Agregar los datos del XML al arreglo de resultados
        $xmls[] = $xmlActual;
    }

    return $xmls;
}

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' && isset( $_FILES[ 'archivos' ] ) ) {
    $resultados = validarXMLCargar( $_FILES[ 'archivos' ] );
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Validar Facturas</title>
<style>
table {
    border-collapse: collapse;
    width: 100%;
}
th, td {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}
th {
    background-color: #f2f2f2;
}
</style>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
  <input type="file" name="archivos[]" id="inputSubirArchivo" multiple accept=".xml">
  <button type="submit" name="btnComprobar">Comprobar</button>
</form>
<?php if (!empty($resultados)): ?>
<h2>Resultados</h2>
<table>
  <thead>
    <tr>
      <th>RFC Emisor</th>
      <th>RFC Receptor</th>
      <th>Total</th>
      <th>UUID</th>
      <th>Código Estatus</th>
      <th>Estado</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($resultados as $resultado): ?>
    <tr>
      <td><?php echo $resultado['RFC_EMISOR']; ?></td>
      <td><?php echo $resultado['RFC_RECEPTOR']; ?></td>
      <td><?php echo $resultado['TOTAL']; ?></td>
      <td><?php echo $resultado['UUID']; ?></td>
      <td><?php echo $resultado['CODIGO_ESTATUS']; ?></td>
      <td><?php echo $resultado['ESTADO']; ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
</body>
</html>
