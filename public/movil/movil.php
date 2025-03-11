<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/movil/sql_movil.php' );
$codigoQR = $_GET[ 'codigoQR' ];


$datos_equipo = view_equipo( $conexion, $codigoQR );

if ( $access[ 'moviles' ] == 0 || empty($codigoQR)|| empty($datos_equipo[ 'id_celular' ])) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}
$id_movil = $datos_equipo[ 'id_celular' ];
$codigo= $datos_equipo[ 'codigo' ];
$registro = "moviles";
//echo $codigoQR;
 if ( $access[ 'moviles' ] != 1 ) {    
	 $disableInputs = true;
} else {
    $disableInputs = false;
}
    
?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.js"></script>
<div class="layoutSidenav_content">
  <div class="container-fluid"> 
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>EQUIPO DE MÓVIL</strong></h1>
	      <div class="row justify-content-center justify-content-md-start">
      <div class="col" > <button type="button" class="btn btn-success  btn-sm" onclick="generarPDF()"> <i class="fa fa-file-text-o "></i> Generar Responsiva </button> </div>
    </div>
    <br>
  <!--card--> 
	  <form class="user" action="edit_movil" method="post" enctype="multipart/form-data">
    <div class="card shadow mb-4">
    <!--card header-->
    <div class="card-header py-3"> <strong>DATOS GENERALES DE: <?php echo $datos_equipo['codigo']?></strong> </div>
    
    <!--card body-->
    
    <div class="card-body">

		 <input type="hidden" name="id_movil"   value="<?php echo $id_movil ?>">   
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
				$selected = ($datos_equipo['id_propietario'] == $ver_propietarios['id_propietario']) ? 'selected' : '';
                // Utiliza comillas simples para las cadenas HTML
                echo '<option value="' . $ver_propietarios[ "id_propietario" ] . '" ' . $selected . '>' . $ver_propietarios[ "nombre" ] . '</option>';
            }
            ?>
          </select>
        </div>
		  <div class="col-sm mb-3">
              <label for="no_telefono">Número telefónico</label>
              <input type="tel" class="form-control" id="no_telefono" name="no_telefono" pattern="[0-9]{10}" inputmode="tel" title="Introduce un número telefónico válido de 10 dígitos (solo números)" maxlength="12"  <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['numero_tel'] ?>">
            </div>
            <div class="col-sm mb-3">
              <label for="region">Región</label>
              <input type="text" class="form-control" id="region" name="region"  <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['region'] ?>">
            </div>
          </div>
		<div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="marca">Marca</label>
              <input type="text" class="form-control" id="marca" name="marca" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['marca'] ?>">
            </div>
            <div class="col-sm mb-3">
              <label for="modelo">Modelo</label>
              <input type="text" class="form-control" id="modelo" name="modelo"  <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['modelo'] ?>">
            </div>
            <div class="col-sm mb-3">
              <label for="imei">IMEI</label>
              <input type="text" class="form-control" id="imei" name="imei" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['imei'] ?>">
            </div>
            <div class="col-sm mb-3">
              <label for="color">Color</label>
              <input type="text" class="form-control" id="color" name="color"  <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['color'] ?>">
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="no_serie">Número de serie</label>
              <input type="text" class="form-control" id="no_serie" name="no_serie" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['no_serie'] ?>">
            </div>
            <div class="col-sm mb-3">
              <label for="disponible">Disponibilidad</label>
              <select class="form-control" id="disponible" name="disponible" required  <?php echo $disableInputs ? 'disabled' : ''; ?>>
                <option value="">Seleccione</option>
                <option value="1" <?php if ($datos_equipo['disponible'] == 1) echo 'selected'; ?>>Disponible</option>
                <option value="2" <?php if ($datos_equipo['disponible'] == 2) echo 'selected'; ?>>No disponible</option>
              </select>
            </div>
            <div class="col-sm mb-3">
              <label for="no_cargador">Número de serie del cargador</label>
              <input type="text" class="form-control" id="no_cargador" name="no_cargador"  <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['no_cargador'] ?>">
            </div>
          </div>
		     <div class="row justify-content-center justify-content-md-start">
              <div class="col-sm mb-3" style="color:#858796">
          <label for="usuarioAsignado">Colaborador Asignado</label>
          <select class="js-example-basic-single js-states form-control" id="usuarioAsignado" name="usuarioAsignado" <?php echo $disableInputs ? 'disabled' : ''; ?> required>
            <option  value="<?php echo $datos_equipo['personal_id'] ?>"><?php echo strtoupper($datos_equipo['nombre'].' '.$datos_equipo['a_paterno'].' '.$datos_equipo['a_materno']) ?></option>
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
              <textarea class="form-control" id="observaciones" name="observaciones" rows="3" <?php echo $disableInputs ? 'disabled' : ''; ?>><?php echo $datos_equipo['observaciones'] ?></textarea>
            </div>
          </div>
		</div>
		</div>
		 <?php
      include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/table_doc.php' );
      ?>
      <div  align="center"> <a class="btn btn-danger" type="button" href="moviles" >Volver </a>
		   <?php
        if ( $access[ 'moviles' ] == 1 ) {
          ?>
        <input type="Submit" class="btn bg-principal text-white" name="Submit" value="Guardar "  />
		   <?php
		}
          ?>
		  
      </div>
		
		</form>
		</div>  
		</div>
	  <script>// In your Javascript (external .js resource or <script> tag)
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});
 function actualizarNombreArchivos() {
    var inputArchivos = document.getElementById('customFile');
    var labelArchivos = document.querySelector('.custom-file-label');
    var nombresArchivos = Array.from(inputArchivos.files).map(file => file.name);
    labelArchivos.innerHTML = nombresArchivos.join(', ') || 'Selecciona archivos';
  }
  function generarPDF() {
	  $('#loading-overlay').show();
	    // Obtén el valor de la variable PHP y asigna a la variable JavaScript
        var codigoQR = "<?php echo $codigoQR; ?>";
        // Llamada AJAX al servidor para generar el PDF
        $.ajax({
            url: 'responsiva_movil', // Reemplaza con la ruta correcta a tu script PHP que genera el PDF
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
<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/table_doc.html' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>
	  