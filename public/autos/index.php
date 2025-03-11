<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/autos/sql_autos.php' );

$datos_cards = verCards( $conexion );
$datos_autos = view_autos( $conexion );
$datos_verificaciones = tverificaciones( $conexion );
$datos_servicios = tservicios( $conexion );
// Si se ha enviado el formulario
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
// Verifica si se hizo clic en el botón de eliminar
  if ( isset( $_POST[ 'eliminar' ] ) ) {
    // Recoge los valores de los campos del formulario
    $id = $_POST[ 'id' ];
    // Llama a la función consulta3() para insertar los datos
    delete_autos( $conexion, $id );
  }
}


$encabezados = array( "ID", "Clave Vehicular", "VIN",  "Marca", "Modelo", "Trasnmisión", "Tipo", "Placas", "Color", "Estatus del Vehiculo", "Usuario Asignado", "Ficha Tecnica", "Ver más" );

if ( $access[ 'autos' ] == 0 ) {
  // Script de redirección con JavaScript
  echo '<script type="text/javascript">window.location.href = "inicio"</script>';
  exit;
}
?>
<style>
.hidden {
    display: none;
}
.boton {
    display: inline-block;
}
#opciones {
    display: flex;
    flex-wrap: wrap;
}
#opciones label {
    display: inline-block;
    width: 50%;
}
	 .border-danger {
        border: 2px solid red;
    }
</style>
<div class="layoutSidenav_content">
  <div class="container-fluid"> 
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>EQUIPOS DE TRANSPORTE</strong></h1>
	  <!-- Cards -->
	  <div class="row" align="center">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-sm  mb-2">
                            <div class="card border-left-primary shadow ">
                                <div class="btn btn-sm" data-toggle="modal" data-target="#mantenimientos">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col ">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Próximos Mantenimientos</div>
                                            <div class=" mb-0 font-weight-bold text-gray-800"><?php echo $datos_cards['Autos_Proximo_Servicio']; ?></div>
                                        </div>
                                     
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Annual) Card Example -->
                        <div class="col-sm  mb-2">
                            <div class="card border-left-success shadowu">
                                <div class="btn btn-sm" data-toggle="modal" data-target="#verificaciones">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col ">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                VERIFICACIONES PRÓXIMAS A VENCER</div>
                                            <div class=" mb-0 font-weight-bold text-gray-800"><?php echo $datos_cards['Autos_Vencimiento_Verificacion']; ?></div>
                                        </div>
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
		  
                        </div>

                  
    <!--Botones-->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
		  <!--boton de columnas
        <button class="btn btn-dark btn-sm" type="button" data-toggle="modal" data-target="#ModalColumnas">Columnas</button>
-->
        <?php
        if ( $access[ 'autos' ] == 1 ) {
            ?>
        <a href="new_autos" class="btn bg-secundario btn-icon-split btn-sm"> <span class="icon text-white-50"> <i class="fas fa-plus"></i> </span> <span class="text text-white">Nuevo Registro</span> </a>
        <?php
        }
        ?>
        <button id="exportButton" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"> <i class="fas fa-download fa-sm text-white-50"></i> Exportar a Excel</button>
      </div>
		<div class="card-body">
        <div class="table-responsive" >
          <table class=" table table-bordered display table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: 12px;">
            <thead>
              <tr >
                <?php
                foreach ( $encabezados as $encabezado ) {
                  ?>
                <th class="bg-principal text-white" style="vertical-align: middle;"<?php if($encabezado== 'ID'){ echo 'width= "10%"'; }?> ><?php echo $encabezado ?></th>
                <?php
                }
                if ( $access[ 'autos' ] == 1 ) {
                  ?>
                <th class="bg-principal text-white" style="vertical-align: middle;">Eliminar</th>
                <?php
                }
                ?>
              </tr>
            </thead>
            <tbody>
				 <?php
              while ( $row = mysqli_fetch_array( $datos_autos ) ) {
              switch($row[ 'transmision' ]){
				  case 1 :
					  $transmision = "Automatica";
					  break;
				  case 2:
					  $transmision = "Manual";
					  break;
			  }
				 $codigoQR=$row[ 'QRKey' ];
				  $id_registro=$row[ 'id_autos' ];
				  $name_delete = $row[ 'codigo' ];
				  
				 $ifRuta = view_autos_rutas( $conexion, $row[ 'codigo' ] );
				   
				    $ico = '';

    // Verificamos si alguno de los campos de la consulta `view_autos_rutas` está vacío
    if (empty($ifRuta['r_imagen']) || empty($ifRuta['r_tarjeta']) || 
        empty($ifRuta['r_factura']) || empty($ifRuta['r_identificacion']) || 
        empty($ifRuta['r_tenencia']) || empty($ifRuta['r_verificacion']) || 
        empty($ifRuta['r_licencia']) ||empty($ifRuta['r_politicas']) ) {  // Clase de Bootstrap para fondo rojo
		$ico='<br><i class="text-danger fas fa-exclamation-triangle" title="Verifica la documentación"></i>';
    }         ?>
              <tr  ondblclick="abrirEnlace('ver_auto<?php echo $codigoQR ?>')" >
                <td align="center"><?php echo $row['codigo']; ?> <?php echo $ico;?></td>
                <td><?php echo $row[ 'claveVehicular' ] ?></td>
                <td><?php echo $row[ 'vin' ] ?></td>
                <td><?php echo $row[ 'marca' ] ?></td>
                <td><?php echo $row[ 'modelo' ] ?></td>
				<td><?php echo $transmision ?></td>
                <td><?php echo $row[ 'tipo' ] ?></td>
                <td><?php echo $row[ 'placas' ] ?></td>
                <td><?php echo $row[ 'color' ] ?></td>
                <td><?php echo $row[ 'est' ] ?></td>
                <td><?php $nombre_completo = $row[ "nombre" ] . ' ' . $row[ "a_paterno" ] . ' ' . $row[ "a_materno" ];
                $nombre_completo_en_mayusculas = mb_strtoupper( $nombre_completo, 'UTF-8' );
                echo $nombre_completo_en_mayusculas;?></td>
				  <td align="center"><a onclick="generarPDF('<?php echo $codigoQR; ?>')"
 class="btn btn-primary" > <i class="fas  fa-eye"> </i> </a> 
				  
				  </td>
				<td align="center"><acronym title="Editar ">
                                        <a class="btn btn-success" type="button" href="ver_auto<?php echo $codigoQR ?>" >
                                        <i class="fas fa fa-share"></i></a></acronym>
                                       </td>
				    <?php

                if ( $access[ 'autos' ] == 1 ) {
                  ?>
                <td align="center"><a href="#" class="btn btn-danger" data-toggle="modal" data-target="#delete<?php echo $id_registro;?>"> <i class="fas fa-trash"></i></a></td>
                <?php
					include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/edit/delete.php' );
                }
                ?>
				</tr>
                <?php
			  }

?>
            </tbody>
			</table>
			</div>
			</div>
		
		
		
<?php
		$cell_tipo = 'autos';
//include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/columnas.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/autos/cards_autos.php' );

?>

<script>
    function abrirEnlace(enlace) {
        // Hacer algo con el enlace, por ejemplo, redirigir a la URL correspondiente
        window.location.href = enlace;
    }
	function generarPDF(qr) {
	   $('#loading-overlay').show();
	    // Obtén el valor de la variable PHP y asigna a la variable JavaScript
        var codigoQR = qr;
        // Llamada AJAX al servidor para generar el PDF
        $.ajax({
            url: 'ficha_tecnica ', // Reemplaza con la ruta correcta a tu script PHP que genera el PDF
            method: 'POST',
             data: { codigoQR: codigoQR }, // Envía la variable al script PHP
            success: function (data) {
				$('#loading-overlay').hide();
   var isAndroid = /(android)/i.test(navigator.userAgent);

            if (isAndroid) {
                // Descargar automáticamente el PDF en dispositivos Android
                var byteCharacters = atob(data);
                var byteNumbers = new Array(byteCharacters.length);
                for (var i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                var byteArray = new Uint8Array(byteNumbers);
                var blob = new Blob([byteArray], {type: 'application/pdf'});

                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'Responsiva.pdf';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                // Mostrar el PDF en un visor o permitir al usuario descargarlo manualmente
                $('#pdfModal').modal('show');
                $('#modalPDF').attr('src', 'data:application/pdf;base64,' + data);
                $('#downloadBtn').attr('href', 'data:application/pdf;base64,' + data);
            }
            },
            error: function () {
				$('#loading-overlay').hide();
                alert('Error al generar el PDF');
            }
        });
    }

</script> 
<script src="excel"></script> 
<script src="TablaAutosJs"></script> 
<script src="aplicarConfiguraciones"></script>
<?php
		
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/table_doc.html' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>