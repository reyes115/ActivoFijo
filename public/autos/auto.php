<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/autos/sql_autos.php' );
$codigoQR = $_GET[ 'codigoQR' ];


$datos_equipo = view_equipo( $conexion, $codigoQR );

if ( $access[ 'autos' ] == 0 || empty( $codigoQR ) || empty( $datos_equipo[ 'id_autos' ] ) ) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}
$id_auto = $datos_equipo[ 'id_autos' ];
$codigo = $datos_equipo[ 'codigo' ];
$registro = "autos";
//echo $codigoQR;
if ( $access[ 'autos' ] != 1 ) {
    $disableInputs = true;
} else {
    $disableInputs = false;
}

?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="layoutSidenav_content">
  <div class="container-fluid"> 
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>EQUIPO DE TRANSPORTE</strong></h1>
    <div class="row justify-content-center justify-content-md-start mb-2">
      <div class="col-sm mb-2" >
        <button type="button" class="btn bg-secundario text-white btn-sm mb-2" onclick="generarDocumento('responsiva_auto', '<?php echo $codigoQR; ?>')"> <i class="fa fa-file-text-o"></i> Generar Responsiva </button>
        <button type="button" class="btn bg-principal text-white btn-sm mb-2" onclick="generarDocumento('ficha_tecnica', '<?php echo $codigoQR; ?>')"> <i class="fa fa-car"></i> Generar Ficha Técnica </button>
      </div>
      <div class="card border-left-success mb-2 col-auto" >
        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1"> PRÓXIMO MANTENIMIENTO </div>
        <div class=" mb-0 font-weight-bold text-gray-800" align="center"><?php echo $datos_equipo['prox_servicio']?></div>
      </div>
    </div>
    
    <!--card-->
    <form class="user" action="edit_auto" method="post" enctype="multipart/form-data">
      <div class="card shadow mb-4">
      <a href="#datosGenerales" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="datosGenerales"> <strong class="text-gray-800">DATOS GENERALES DE: <?php echo $datos_equipo['codigo']?></strong></a>
      <div class="collapse show" id="datosGenerales" style="">
      <!--card body-->
      <div class="card-body">
        <input type="hidden" name="id_auto"   value="<?php echo $id_auto ?>">
        <input type="hidden" name="qrcode"   value="<?php echo $codigoQR ?>">
        <div class="row justify-content-center justify-content-md-start">
          <div class="col-sm mb-3">
            <label for="propietario">Propietario</label>
            <select class="form-control" id="propietario" name="propietario" required <?php echo $disableInputs ? 'disabled' : ''; ?>>
              <option name="propietario" id="propietario"  value="">SELECCIONE</option>
              <?php
              include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/propietarios.php' );
              $propietarios = view_propietarios( $conexion );

              while ( $ver_propietarios = mysqli_fetch_array( $propietarios ) ) {
                  $selected = ( $datos_equipo[ 'id_propietario' ] == $ver_propietarios[ 'id_propietario' ] ) ? 'selected' : '';
                  // Utiliza comillas simples para las cadenas HTML
                  echo '<option value="' . $ver_propietarios[ "id_propietario" ] . '" ' . $selected . '>' . $ver_propietarios[ "nombre" ] . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="col-sm mb-3">
            <label for="claveVehicular">Clave Vehicular</label>
            <input type="text" class="form-control" id="claveVehicular" name="claveVehicular" required  <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['claveVehicular'] ?>">
          </div>
          <div class="col-sm mb-3">
            <label for="factura">Factura</label>
            <input class="form-control" type="text" id="factura"  name="factura" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['factura'] ?>" >
            </input>
          </div>
          <div class="col-sm mb-3">
            <label for="vin">VIN</label>
            <input type="text" class="form-control" id="vin" name="vin" required <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['vin'] ?>">
          </div>
        </div>
        <div class="row justify-content-center justify-content-md-start">
          <div class="col-sm mb-3">
            <label for="marca">Marca</label>
            <input type="text" class="form-control" id="marca" name="marca" required <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['marca'] ?>">
          </div>
          <div class="col-sm mb-3">
            <label for="modelo">Modelo</label>
            <input type="text" class="form-control" id="modelo" name="modelo" required <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['modelo'] ?>">
          </div>
          <div class="col-sm mb-3">
            <label for="tipo">Tipo</label>
            <input type="text" class="form-control" id="tipo" name="tipo" required <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['tipo'] ?>">
          </div>
          <div class="col-sm mb-3">
            <label for="transmision">Transmisión</label>
            <select class="form-control" id="transmision" name="transmision" required <?php echo $disableInputs ? 'disabled' : ''; ?>>
              <option id="transmision" name="transmision" value="1" <?php if ($datos_equipo['transmision'] == 1) echo 'selected'; ?>>Automática</option>
              <option id="transmision" name="transmision" value="2" <?php if ($datos_equipo['transmision'] == 2) echo 'selected'; ?>>Manual</option>
            </select>
          </div>
        </div>
        <div class="row justify-content-center justify-content-md-start">
          <div class="col-sm mb-3">
            <label for="color">Color</label>
            <input type="text" class="form-control" id="color" name="color" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['color'] ?>">
          </div>
          <div class="col-sm mb-3">
            <label for="combustible">Combustible</label>
            <input type="text" class="form-control" id="combustible" name="combustible" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['combustible'] ?>">
          </div>
          <div class="col-sm mb-3">
            <label for="numMotor">Número de Motor</label>
            <input type="text" class="form-control" id="numMotor" name="numMotor" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['no_motor'] ?>">
          </div>
          <div class="col-sm mb-3">
            <label for="placas">Placas</label>
            <input type="text" class="form-control" id="placas"name="placas" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['placas'] ?>">
          </div>
			   <div class="col-sm mb-3">
              <label for="color_engomado">Color de engomado</label>
              <select class="form-control" id="color_engomado" name="color_engomado" required  <?php echo $disableInputs ? 'disabled' : ''; ?>>
                <option  <?php if ($datos_equipo['color_engomado'] == NULL) echo 'selected'; ?>>Seleccione</option>
                <option <?php if ($datos_equipo['color_engomado'] == 1) echo 'selected'; ?> value="1">Amarillo</option>
                <option  <?php if ($datos_equipo['color_engomado'] == 2) echo 'selected'; ?> value="2">Rosa</option>
                <option  <?php if ($datos_equipo['color_engomado'] == 3) echo 'selected'; ?> value="3">Rojo</option>
                <option  <?php if ($datos_equipo['color_engomado'] == 4) echo 'selected'; ?> value="4">Verde</option>
                <option  <?php if ($datos_equipo['color_engomado'] == 5) echo 'selected'; ?> value="5">Azul</option>
              </select>
            </div>
        </div>
        <div class="row justify-content-center justify-content-md-start">
          <div class="col-sm mb-3">
            <label for="tarjetaCirculacion">Tarjeta de Circulación</label>
            <input type="text" class="form-control" id="tarjetaCirculacion" name="tarjetaCirculacion" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['tarjeta'] ?>">
          </div>
          <div class="col-sm mb-3">
            <label for="vencimientoTarjeta">Vencimiento Tarjeta de Circulación</label>
            <input type="date" class="form-control" id="vencimientoTarjeta" name="vencimientoTarjeta" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['fin_tarjeta'] ?>">
          </div>
          <div class="col-sm mb-3">
            <label for="estadoCirculacion">Estado de Circulación</label>
            <input type="text" class="form-control" id="estadoCirculacion" name="estadoCirculacion" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['estado_placa'] ?>">
          </div>
        </div>
        <div class="row justify-content-center justify-content-md-start">
          <div class="col-sm mb-3">
            <label for="estatus">Estatus del Vehiculo</label>
            <select class="form-control" id="estatus" name="estatus" required <?php echo $disableInputs ? 'disabled' : ''; ?>>
              <option id="estatus" name="estatus" value="1" <?php if ($datos_equipo['estatus'] == 1) echo 'selected'; ?>>Alta</option>
              <option id="estatus" name="estatus" value="2" <?php if ($datos_equipo['estatus'] == 2) echo 'selected'; ?>>Baja</option>
            </select>
          </div>
          <div class="col-sm mb-3">
            <label for="estatusVerificacion">Estatus de Verificación</label>
            <select class="form-control" id="estatusVerificacion" name="estatusVerificacion" <?php echo $disableInputs ? 'disabled' : ''; ?>>
              <option id="estatus" name="estatus">Seleccione</option>
              <option id="estatus" name="estatus" value="1" <?php if ($datos_equipo['EstatusVerificacion'] == 1) echo 'selected'; ?>>Realizado</option>
              <option id="estatus" name="estatus" value="2" <?php if ($datos_equipo['EstatusVerificacion'] == 2) echo 'selected'; ?>>Pendiente</option>
            </select>
          </div>
          <div class="col-sm mb-3">
            <label for="vencimientoVerificacion">Vencimiento de Verificación</label>
            <input type="date" class="form-control" id="vencimientoVerificacion" name="vencimientoVerificacion" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['VencVerificacion'] ?>">
          </div>
        </div>
        <div class="row justify-content-center justify-content-md-start">
          <div class="col-sm mb-3" style="color:#858796">
            <label for="usuarioAsignado">Colaborador Asignado</label>
            <select class="form-control js-example-responsive  " id="usuarioAsignado" name="usuarioAsignado" <?php echo $disableInputs ? 'disabled' : ''; ?> required>
              <option  value="<?php echo $datos_equipo['personal_id_personal'] ?>"><?php echo strtoupper($datos_equipo['nombre'].' '.$datos_equipo['a_paterno'].' '.$datos_equipo['a_materno']) ?></option>
              <?php
              include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/personal/sql_personal.php' );
              $datos_personal = select_personal( $conexion );
              while ( $mostrar = mysqli_fetch_array( $datos_personal ) ) {
                  print '
    <option value="' . strtoupper( $mostrar[ "id_personal" ] ) . '">
        ' . strtoupper( $mostrar[ "nombre" ] . ' ' . $mostrar[ "a_paterno" ] . ' ' . $mostrar[ "a_materno" ] ) . '
    </option>
';

              }
              ?>
            </select>
          </div>
          <div class="col-sm mb-3">
            <label for="observaciones">Observaciones</label>
            <textarea class="form-control" id="observaciones" name="observaciones" rows="3" <?php echo $disableInputs ? 'disabled' : ''; ?>><?php echo $datos_equipo['obs'] ?></textarea>
          </div>
        </div>
      </div>
      <?php
      if ( $access[ 'autos' ] == 1 ) {
          ?>
      <div class="col-sm mb-3" align="center">
        <input type="Submit" class="btn bg-principal text-white" name="Submit" value="Guardar Cambios "  />
      </div>
      <?php
      }
      ?>
    </form>
  </div>
</div>
<!--card-->
<div class="card shadow mb-4"> <a href="#documentos" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="documentos"><strong class="text-gray-800">DOCUMENTACIÓN </strong></a>
  <div class="collapse " id="documentos" style="">
    <?php

    $directorio = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/" . $registro . '/' . $codigo;
    if ( !file_exists( $directorio ) ) {
        mkdir( $directorio, 0777 )or die( "No se puede crear el directorio de extracci&oacute;n" );
    }
	  
	  $directorio_S = $_SERVER['DOCUMENT_ROOT'] . "/uploads/autos/$codigo/servicio_evidencia";

if (!file_exists($directorio_S)) {
    mkdir($directorio_S, 0777, true);
}
    $directorio2 = "/uploads/" . $registro . '/' . $codigo . "/";
    ?>
    <div class="card-body">
      <div class="table-responsive">
        <form method="post" action="guardar_archivo_autos" enctype="multipart/form-data">
          <input type="hidden" name="codigo" value="<?php echo $codigo?>">
          <input type="hidden" name="accion" value="edit">
          <table class="table table-bordered display table-hover">
            <thead>
              <tr align="center">
                <th scope="col">Documento</th>
                <th scope="col">Archivo actual</th>
                <?php
                $colapan = "";
                if ( $access[ $registro ] == 1 ):
                    $colapan = "2";
                ?>
                <th scope="col">Remplazar archivo</th>
                <?php
                endif;
                ?>
                <th colspan="3">Acciones</th>
              </tr>
            </thead>
            <tr>
              <th scope="row">Codigo QR</th>
              <td colspan="<?php echo $colapan?>" align="center"><?php echo $codigoQR.'_qr.png' ?></td>
              <td colspan="2" align="center"><a class="btn open-image-modal btn-primary"  data-file="<?php echo $directorio2.$codigoQR.'_qr.png'; ?>" data-toggle="modal" data-target="#imageModal"  data-toggle="tooltip" data-placement="top" title="Ver archivo"> <i class="fas fa fa-eye"></i> </a></td>
            </tr>
            <?php
            // Función para generar una fila con el archivo y sus controles
            function generarFila( $nombreCampo, $nombreArchivo, $datos_equipo, $directorio2, $access, $registro, $codigoA ) {
                $archivo = $datos_equipo[ $nombreArchivo ];
                $extension = strtolower( pathinfo( $archivo, PATHINFO_EXTENSION ) );
                $fileUrl = $directorio2 . $archivo;

                            $userAgent = $_SERVER[ 'HTTP_USER_AGENT' ] ?? '';
                $modalClass = '';
                $modalTarget = '';
                $icon = '';

                if ( !empty( $archivo ) ) {
                    switch ( $extension ) {
                        case 'pdf':
                            $modalClass = 'open-pdf-modal btn-primary';
                            $modalTarget = '#pdfModal';
                            $icon = "fa-eye";
                            // Añadir la condición para Android aquí
                            if ( strpos( $userAgent, 'Android' ) !== false && strpos( $userAgent, 'com.example.ab_forti' ) !== false ) {
                                $modalClass = 'download-file btn-info';
                                $modalTarget = '';
                                $icon = "fa-download";
                            }
                            break;
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                        case 'gif':
                            $modalClass = 'open-image-modal btn-primary';
                            $modalTarget = '#imageModal';
                            $icon = "fa-eye";
                            break;
                        default:
                            $modalClass = 'download-file btn-info';
                            $icon = "fa-download";
                            break;
                    }
                }
				 $ico = '';

				if(empty($datos_equipo[$nombreArchivo] )){
					
		$ico='class="border-bottom-danger bg-gray-300"';
				}
                ?>
            <tr <?php echo $ico;?>>
              <th scope="row"><?php echo $nombreCampo ?></th>
              <td><?php echo $datos_equipo[$nombreArchivo] ?></td>
              <?php
              if ( $access[ $registro ] == 1 ):
                  ?>
              <td ><div class="custom-file ">
                  <input type="file" class="custom-file-input" id="<?php echo $nombreArchivo ?>" name="<?php echo $nombreArchivo ?>" onchange="actualizarNombreArchivo('<?php echo $nombreArchivo ?>')">
                  <label class="<?php if ( strpos( $userAgent, 'Android' ) == false) { ?> custom-file-label <?php }?>" for="<?php echo $nombreArchivo ?>">Selecciona un archivo <i class="fa fa-upload"></i></label>
                </div></td>
              <?php

              endif;
              ?>
              <td align="center"><a class="btn <?php echo $modalClass; ?>" <?php if ($modalClass === 'download-file btn-info') : ?> href="<?php echo $fileUrl; ?>" download="<?php echo $archivo; ?>" <?php else : ?> data-file="<?php echo $fileUrl; ?>" data-toggle="modal" data-target="<?php echo $modalTarget; ?>" <?php endif; ?> data-toggle="tooltip" data-placement="top" > <i class="fas fa <?php echo $icon; ?>"></i> </a></td>
              <?php
              if ( $access[ $registro ] == 1 ):
                  ?>
              <td><?php
              if ( !empty( $archivo ) ) {
                  ?>
                <button type="button" class="btn btn-danger delete-file" 
						  data-toggle="modal" 
						  data-target="#deleteModal" 
						  data-toggle="tooltip" 
						  data-placement="top" 
						  data-archivo="<?php echo $datos_equipo[$nombreArchivo]?>"
						  data-registro="<?php echo $codigoA ?>"
						  data-tipo="<?php echo $nombreArchivo?>"
						  data-campo="<?php echo $nombreCampo?>"
						  title="Eliminar archivo <?php echo $nombreCampo?>">
                <i class="fas fa-trash"></i>
                </button>
                <?php
                }
                ?></td>
              <?php

              endif;
              ?>
            </tr>
            <?php
            }
            ?>
            <tbody>
              <?php generarFila("Imagen del Vehiculo", "r_imagen", $datos_equipo, $directorio2, $access, $registro, $codigo); ?>
              <?php generarFila("Tarjeta de Circulación", "r_tarjeta", $datos_equipo, $directorio2, $access, $registro, $codigo); ?>
              <?php generarFila("Factura", "r_factura", $datos_equipo, $directorio2, $access, $registro, $codigo); ?>
              <?php generarFila("Identificación", "r_identificacion", $datos_equipo, $directorio2, $access, $registro, $codigo); ?>
              <?php generarFila("Pagos de tenencia", "r_tenencia", $datos_equipo, $directorio2, $access, $registro, $codigo); ?>
              <?php generarFila("Certificado de verificación", "r_verificacion", $datos_equipo, $directorio2, $access, $registro, $codigo); ?>
              <?php generarFila("Licencia de Conducir", "r_licencia", $datos_equipo, $directorio2, $access, $registro, $codigo); ?>
              <?php generarFila("Políticas de uso", "r_politicas", $datos_equipo, $directorio2, $access, $registro, $codigo); ?>
              <?php generarFila("Evidencia de servicio", "r_servcicio", $datos_equipo, $directorio2, $access, $registro, $codigo); ?>
              <?php generarFila("Responsiva Firmada", "r_responsiva", $datos_equipo, $directorio2, $access, $registro, $codigo); ?>
            </tbody>
          </table>
          <?php
          if ( $access[ 'autos' ] == 1 ) {
              ?>
          <div align="center">
            <button  type="submit" class="btn btn-primary">Guardar Archivos</button>
          </div>
          <?php
          }
          ?>
        </form>
      </div>
    </div>
  </div>
</div>

<!--card-->
<div class="card shadow mb-4"> <a href="#polizas" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="polizas"><strong class="text-gray-800">PÓLIZAS </strong></a>
  <div class="collapse " id="polizas" style="">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered display table-hover" >
          <thead>
            <tr align="center">
              <th scope="col">Código</th>
              <th scope="col">Número</th>
              <th scope="col">Aseguradora</th>
              <th scope="col">Inicio de vigencia</th>
              <th scope="col">Fin de vigencia</th>
              <?php
              if ( $access[ 'polizas' ] != 0 ) {
                  ?>
              <th scope="col">Ver Más</th>
              <?php
              }
              ?>
          </thead>
          <tbody>
            <?php
            $poliza = view_autos_poliza( $conexion, $datos_equipo[ 'id_autos' ] );
			if ( mysqli_num_rows( $poliza ) > 0 ) {  
            while ( $mostrar4 = mysqli_fetch_array( $poliza ) ) {
                ?>
            <tr align="center">
              <td><?php echo $mostrar4[ 'codigo' ] ?></td>
              <td><?php echo $mostrar4[ 'no_poliza' ] ?></td>
              <td><?php echo $mostrar4[ 'aseguradora' ] ?></td>
              <td><?php echo $mostrar4[ 'inicio_vigencia' ] ?></td>
              <td><?php echo $mostrar4[ 'fin_vigencia' ] ?></td>
              <?php
              if ( $access[ 'polizas' ] != 0 ) {
                  ?>
              <td align="center"><a class="btn btn-primary" href="ver_polizas<?php echo $mostrar4[ 'id_poliza' ] ?>"> <i class="fas fa  fa-shield"> </i> </a></td>
              <?php
              }
              ?>
            </tr>
             <?php
            }
            } else {
              if ( $access[ 'polizas' ] != 0 ) {
                  ?>
              <tr>
              <td colspan="6" align="center">No se encontraron registros.</td>
            </tr>
              <?php
              }else{
              ?>
            <tr>
              <td colspan="5" align="center">No se encontraron registros.</td>
            </tr>
            <?php
            }}
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!--card-->
<div class="card shadow  mb-4"> <a href="#servicio_auto" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="servicio_auto"><strong class="text-gray-800">SERVICIO DE MANTENIMIENTO</strong></a>
  <div class="collapse " id="servicio_auto" style="">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered display table-hover">
          <thead>
            <tr align="center">
              <th scope="col">Kilometraje</th>
              <th scope="col">Fecha</th>
              <th scope="col">Evidencia de servicio</th>
              <?php
              if ( $access[ 'autos' ] == 1 ) {
                  ?>
              <th scope="col">Eliminar</th>
              <?php
              }
              ?>
          </thead>
          <tbody>
            <?php
            $servicio = view_autos_servicio( $conexion, $datos_equipo[ 'id_autos' ] );
            $max_km_row = array();
            $max_km = 10000;
			    $url_evidencia= "/uploads/" . $registro . '/' . $codigo . "/servicio_evidencia/";
			if ( mysqli_num_rows( $servicio ) > 0 ) {  
            	while ( $row = mysqli_fetch_array( $servicio ) ) {
					$archivo = $row[ 'evidencia_servicio' ];
                $extension = strtolower( pathinfo( $archivo, PATHINFO_EXTENSION ) );
					  $fileUrl = $url_evidencia . $archivo;

                $modalClass = '';
                $modalTarget = '';
                      if ( !empty( $archivo ) ) {
                    switch ( $extension ) {
                            case 'pdf':
                                $modalClass = 'open-pdf-modal btn-primary';
                                $modalTarget = '#pdfModal';
                                $icon = "fa-eye";
                                // Añadir la condición para Android aquí
                                $userAgent = $_SERVER[ 'HTTP_USER_AGENT' ] ?? '';
                                if ( strpos( $userAgent, 'Android' ) !== false && strpos( $userAgent, 'com.example.ab_forti' ) !== false ) {
                                    $modalClass = 'download-file btn-info';
                                    $modalTarget = '';
                                    $icon = "fa-download";
                                }
                                break;
                            case 'jpg':
                            case 'jpeg':
                            case 'png':
                            case 'gif':
                            $modalClass = 'open-image-modal btn-primary';
                            $modalTarget = '#imageModal';
                            $icon = "fa-eye";
                            break;
							
                        default:
                            $modalClass = 'download-file btn-info';
                            $icon = "fa-download";
                            break;
                    }
                }else{
						
        // Manejar el caso en que la extensión esté vacía
        $modalClass = ''; // Clase CSS que quieras aplicar
        $modalTarget = ''; // Opcional: define un modalTarget específico si es necesario
        $icon = "fa-times-circle"; // Cambia esto por el icono que desees
        
					  }
                ?>
            <tr align="center">
              <td><?php echo $row[ 'km' ] ?> Km</td>
              <td><?php echo  $row[ 'dia' ] . '-' . $row[ 'mes' ] . '-' . $row[ 'año' ] ?></td>
				<td align="center"><a class="btn <?php echo $modalClass; ?>" <?php if ($modalClass === 'download-file btn-info') : ?> href="<?php echo $fileUrl; ?>" download="<?php echo $archivo; ?>" <?php else : ?> data-file="<?php echo $fileUrl; ?>" data-toggle="modal" data-target="<?php echo $modalTarget; ?>" <?php endif; ?> data-toggle="tooltip" data-placement="top" > <i class="fas fa <?php echo $icon; ?>"></i> </a></td>
              <?php
              if ( $access[ 'autos' ] == 1 ) {
                  ?>
              <td align="center"><form method="post" action="guardar_archivo_autos" enctype="multipart/form-data">
                  <input type="hidden" name="accion" value="delete_servicio">
                  <input type="hidden" name="id_servicio" value="<?php echo $row['id_servicio']?>">
                  <input type="hidden" name="id_auto" value="<?php echo $datos_equipo[ 'id_autos' ]?>">
				  
                <input type="hidden" name="codigo" value="<?php echo $datos_equipo[ 'codigo' ]?>">
                  <input type="hidden"  name="fecha" value="<?php echo $row['ultimo_servicio']?>">
                  <input type="hidden"  name="evidencia_servicio" value="<?php echo $row['evidencia_servicio']?>">
                  <input type="hidden" id="f_servicio" name="f_servicio" value="<?php echo $datos_equipo['prox_servicio']?>">
                  <button  type="submit" class="btn btn-danger delete-file"> <i class="fas fa  fa-trash"> </i> </button >
                </form></td>
              <?php
              }
              ?>
            </tr>
            <?php
            // Verificamos si el kilometraje actual es mayor que el máximo
            if ( $row[ 'km' ] >= $max_km ) {
                // Si es así, actualizamos el máximo y guardamos los datos de esta fila
                $max_km = $row[ 'km' ] + 10000;
                $max_km_row = $row;
            }
          
            }
            } else {
                ?>
            <tr>
              <td colspan="4" align="center">No se encontraron registros.</td>
            </tr>
            <?php
            }
            ?>
          </tbody>
          <?php
          if ( $access[ 'autos' ] == 1 ) {
              ?>
          <tfoot>
            <tr>
              <td colspan="4" align="center" class="text-dark"><strong>Nuevo registro:</strong></td>
            </tr>
            <tr>
              <form method="post" action="guardar_archivo_autos" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="new_servicio">
                <input type="hidden" name="id_auto" value="<?php echo $datos_equipo[ 'id_autos' ]?>">
                <input type="hidden" name="codigo" value="<?php echo $datos_equipo[ 'codigo' ]?>">
                <td><input type="text" class="form-control" id="km" name="km" value="<?php echo $max_km ?>" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required></td>
                <td><input type="date" class="form-control" id="fecha" name="fecha" required></td>
                <td><div class="custom-file mb-3">
                  <input type="file" class="custom-file-input" id="evidencia_servicio" name="evidencia_servicio" onchange="actualizarNombreArchivo('evidencia_servicio')" required>
                  <label class="custom-file-label" for="evidencia_servicio">Coloca tu evidencia. <i class="fa fa-upload"></i></label>
                </div></td>
                <td align="center"><input type="Submit" class="btn btn-primary" name="Submit" value="Agregar"  /></td>
              </form>
            </tr>
          </tfoot>
          <?php
          }
          ?>
        </table>
      </div>
    </div>
  </div>
</div>

<!--card-->
<div class="card shadow  mb-4"> <a href="#kilometraje" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="kilometraje"><strong class="text-gray-800">KILOMETRAJE UTILIZADO PARA ACTIVIDADES EMPRESARIALES</strong></a>
  <div class="collapse" id="kilometraje" style="">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered display table-hover">
          <thead>
            <tr align="center">
              <th scope="col">Año</th>
              <th scope="col">Quincena</th>
              <th scope="col">Kilometro</th>
              <?php
              if ( $access[ 'autos' ] == 1 ) {
                  ?>
              <th scope="col">Eliminar</th>
              <?php
              }
              ?>
          </thead>
          <tbody>
            <?php
            $km = view_autos_km( $conexion, $datos_equipo[ 'id_autos' ] );

            if ( mysqli_num_rows( $km ) > 0 ) {
                while ( $row = mysqli_fetch_array( $km ) ) {
                    ?>
            <tr align="center">
              <td><?php echo $row[ 'año' ] ?></td>
              <td><?php echo $row[ 'quincena' ] ?></td>
              <td><?php echo $row[ 'km' ] ?> Km</td>
              <?php
              if ( $access[ 'autos' ] == 1 ) {
                  ?>
              <td align="center"><form method="post" action="guardar_archivo_autos" enctype="multipart/form-data">
                  <input type="hidden" name="accion" value="delete_km">
                  <input type="hidden" name="id_km" value="<?php echo $row['id_km']?>">
                  <button  type="submit" class="btn btn-danger delete-file"> <i class="fas fa  fa-trash"> </i> </button >
                </form></td>
              <?php
              }
              ?>
            </tr>
            <?php
            }
            } else {
                ?>
            <tr>
              <td colspan="4" align="center">No se encontraron registros.</td>
            </tr>
            <?php
            }
            ?>
          </tbody>
          <?php
          if ( $access[ 'autos' ] == 1 ) {
              ?>
          <tfoot>
            <tr>
              <td colspan="4" align="center" class="text-dark"><strong>Nuevo registro:</strong></td>
            </tr>
            <tr>
              <form method="post" action="guardar_archivo_autos" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="new_km">
                <input type="hidden" name="id_auto" value="<?php echo $datos_equipo[ 'id_autos' ]?>">
                <td><input type="number" class="form-control" id="year" name="year" required min="2000" max="2100" ></td>
                <td><input type="text" class="form-control" id="quincena" name="quincena" required autocomplete="off"></td>
                <td><input type="text" class="form-control" id="km" name="km"  onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required autocomplete="off"></td>
                <td align="center"><input type="Submit" class="btn btn-primary" name="Submit" value="Agregar"  /></td>
              </form>
            </tr>
          </tfoot>
          <?php
          }
          ?>
        </table>
      </div>
    </div>
  </div>
</div>

<!--card-->
<div class="card shadow mb-4"> <a href="#historial" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="historial"><strong class="text-gray-800">HISTORIAL DE ASIGNACIONES</strong></a>
  <div class="collapse " id="historial" style="">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered display table-hover" >
          <thead>
            <tr align="center">
              <th scope="col">Nombre </th>
              <th scope="col">Fecha de retiro del auto</th>
              <th scope="col">Ver más </th>
          </thead>
          <tbody>
            <?php
            $hist = view_autos_asig( $conexion, $datos_equipo[ 'id_autos' ] );
            if ( mysqli_num_rows( $hist ) > 0 ) {
                while ( $row = mysqli_fetch_array( $hist ) ) {
                    $personal = view_colaborador( $conexion, $row[ 'id_before' ] );
                    ?>
            <tr align="center">
              <td><?php echo $personal[ 'nombre' ].' '. $personal['a_paterno'].' '.$personal['a_materno']; ?></td>
              <td><?php echo $row[ 'fecha_formateada' ]; ?></td>
              <td align="center"><a class="btn btn-primary" href="ver_personal<?php echo $personal[ 'id_personal' ];?>"> <i class="fas fa  fa-share"> </i> </a></td>
            </tr>
            <?php
            }
            } else {
                ?>
            <tr>
              <td colspan="3" align="center">No se encontraron registros.</td>
            </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div  align="center"> <a class="btn btn-danger" type="button" href="autos" >Volver </a> </div>
</div>
</div>

<!-- Modal de eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="bloquearLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title text-white" id="bloquearLabel">Eliminar Archivo</h5>
        <button type="button" class="close  text-white" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body" align="justify">
        <p> ¿Deseas eliminar el archivo del campo de <b id="textoE"></b> ? </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        <form method="post" action="guardar_archivo_autos" enctype="multipart/form-data">
          <input type="hidden" name="accion" value="delete">
          <input type="hidden" name="borrarArchivo" id="archivoD" value="">
          <input type="hidden" name="rutaArchivo" id="tipoD" value="">
          <input type="hidden" name="codigo" id="registroD" value="">
          <button type="submit" class="btn btn-primary" name="deletedoc">Sí</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>// In your Javascript (external .js resource or <script> tag)
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});
function actualizarNombreArchivo(idInput) {
    var inputArchivo = document.getElementById(idInput);
    var labelArchivo = document.querySelector('label[for="' + idInput + '"]');
    var nombreArchivo = inputArchivo.files[0] ? inputArchivo.files[0].name : null;
    labelArchivo.innerHTML = nombreArchivo || 'Selecciona un archivo <i class="fa fa-upload"></i>';
}


   function generarDocumento(url, qr) {
        $('#loading-overlay').show();
        // Desactivar los botones durante la carga
        $('button').prop('disabled', true);

        var codigoQR = qr;
        $.ajax({
            url: url,
            method: 'POST',
            data: { codigoQR: codigoQR },
            success: function(data) {
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
            error: function() {
                $('#loading-overlay').hide();
                alert('Error al generar el PDF');
            },
            complete: function() {
                // Volver a activar los botones después de la carga
                $('button').prop('disabled', false);
            }
        });
    }
	
		 $(document).on('click', '.delete-file', function() {
        var archivo = $(this).data('archivo');
        var tipo = $(this).data('tipo');
        var registroD = $(this).data('registro');
        var campo = $(this).data('campo');
      
		 
        $('#archivoD').val(archivo);
        $('#tipoD').val(tipo);
        $('#registroD').val(registroD);
        $('#campoD').val(campo);
     
		 // Imprimir contenido en un elemento <p>
    $('#textoE').text(campo);
   
    });
	  // Obtener el año actual
  var year = new Date().getFullYear();

  // Establecer el año actual como valor predeterminado
  document.getElementById("year").value = year;

</script>
<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/table_doc.html' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>
