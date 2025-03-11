<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/computo/computo_sql.php' );
$codigoQR = $_GET[ 'codigoQR' ];


$datos_equipo = view_equipo( $conexion, $codigoQR );

if ( $access[ 'computo' ] == 0 || empty($codigoQR) || empty( $datos_equipo[ 'id_compu' ])) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}
$id_compu = $datos_equipo[ 'id_compu' ];
$codigo= $datos_equipo[ 'codigo' ];
$registro = "computo";
//echo $codigoQR;
 if ( $access[ 'computo' ] != 1 ) {    
	 $disableInputs = true;
} else {
    $disableInputs = false;
}
    
?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.js"></script>

<div class="layoutSidenav_content">
  <div class="container-fluid"> 
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>EQUIPO DE CÓMPUTO</strong></h1>
	      <div class="row justify-content-center justify-content-md-start">
      <div class="col" > <button type="button" class="btn btn-success  btn-sm" onclick="generarPDF()"> <i class="fa fa-file-text-o "></i> Generar Responsiva </button> </div>
    </div>
    <br>


    <!--card--> 
	  <form class="user" action="edit_compu" method="post" enctype="multipart/form-data">
    <div class="card shadow mb-4">
    <!--card header-->
    <div class="card-header py-3"> <strong>DATOS GENERALES DE: <?php echo $datos_equipo['codigo']?></strong> </div>
    
    <!--card body-->
    
    <div class="card-body">

		 <input type="hidden" name="id_compu"   value="<?php echo $id_compu ?>">   
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
          <label for="tipo">Tipo </label>
          <select class="form-control" id="tipo" name="tipo" required <?php echo $disableInputs ? 'disabled' : ''; ?>>
            <option id="tipo" name="tipo" value="1" <?php if ($datos_equipo['tipo'] == 1) echo 'selected'; ?>>Escritorio</option>
            <option id="tipo" name="tipo" value="2" <?php if ($datos_equipo['tipo'] == 2) echo 'selected'; ?>>Laptop</option>
            <option id="tipo" name="tipo" value="4" <?php if ($datos_equipo['tipo'] == 4) echo 'selected'; ?>>Otro</option>
          </select>
        </div>
        <div class="col-sm mb-3">
          <label for="costo">Costo</label>
          <input type="text" class="form-control" id="costo" name="costo"   onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?php echo $datos_equipo['costo'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
        </div>
        <div class="col-sm mb-3">
          <label for="fecha_compra">Fecha de Registro</label>
          <input type="date" class="form-control" id="fecha_compra" name="fecha_compra" required <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['fecha_compra'] ?>">
        </div>
      </div>
      <div class="row justify-content-center justify-content-md-start">
        <div class="col-sm mb-3">
          <label for="cpu">Procesador</label>
          <input type="text" class="form-control" id="cpu" name="cpu" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['cpu'] ?>">
        </div>
        <div class="col-sm mb-3">
          <label for="ram">Memoria RAM </label>
          <input type="text" class="form-control" id="ram" name="ram" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['ram'] ?>">
        </div>
        <div class="col-sm mb-3">
          <label for="almacenamiento">Almacenamiento</label>
          <input type="text" class="form-control" id="almacenamiento" <?php echo $disableInputs ? 'disabled' : ''; ?> name="almacenamiento" value="<?php echo $datos_equipo['almacenamiento'] ?>">
        </div>
        <div class="col-sm mb-3">
          <label for="estado">Estado</label>
          <select  class="form-control" id="estado" name="estado" <?php echo $disableInputs ? 'disabled' : ''; ?>>
            <option id="estado" name="estado" value="1"<?php if ($datos_equipo['estado'] == 1) echo 'selected'; ?>>Nuevo</option>
            <option id="estado" name="estado" value="2"<?php if ($datos_equipo['estado'] == 2) echo 'selected'; ?>>Usado</option>
            <option id="estado" name="estado" value="3"<?php if ($datos_equipo['estado'] == 3) echo 'selected'; ?>>Con fallas</option>
            <option id="estado" name="estado" value="4"<?php if ($datos_equipo['estado'] == 4) echo 'selected'; ?>>Inservible</option>
          </select>
        </div>
      </div>
      <div class="row justify-content-center justify-content-md-start">
        <div class="col-sm mb-3">
          <label for="marca">Marca</label>
          <input type="text" class="form-control" id="marca" name="marca" <?php echo $disableInputs ? 'disabled' : ''; ?>  value="<?php echo $datos_equipo['marca'] ?>">
        </div>
        <div class="col-sm mb-3">
          <label for="modelo">Modelo</label>
          <input type="text" class="form-control" id="modelo" name="modelo" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['modelo'] ?>" >
        </div>
        <div class="col-sm">
          <label for="color">Color</label>
          <input type="text" class="form-control" id="color" name="color" <?php echo $disableInputs ? 'disabled' : ''; ?>  value="<?php echo $datos_equipo['color'] ?>">
        </div>
      </div>
      <div class="row justify-content-center justify-content-md-start">
        <div class="col-sm mb-3">
          <label for="no_serie">Número de serie</label>
          <input type="text" class="form-control" id="no_serie" name="no_serie" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['no_serie'] ?>"  >
        </div>
        <div class="col-sm mb-3">
          <label for="no_serie">Cargador</label>
          <input type="text" class="form-control" id="cargador" name="cargador" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['cargador'] ?>" >
        </div>
        <div class="col-sm-auto mb-3">
          <label for="fecha_sym">Fecha de mantenimiento </label>
          <input type="date" class="form-control" id="fecha_sym" name="fecha_sym" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['fecha_sym'] ?>">
        </div>
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
      </div>
      <div class="row justify-content-center justify-content-md-start">
        <div class="col-sm mb-3">
          <label for="accesorios">Accesorios</label>
          <textarea type="text" class="form-control" id="accesorios" name="accesorios" rows="3" style="max-height: 100px;" <?php echo $disableInputs ? 'disabled' : ''; ?>><?php echo $datos_equipo['accesorios'] ?></textarea>
        </div>
        <div class="col-sm mb-3">
          <label for="observaciones">Observaciones</label>
          <textarea class="form-control" id="observaciones" name="observaciones" rows="3" style="max-height: 100px;" <?php echo $disableInputs ? 'disabled' : ''; ?>><?php echo $datos_equipo['observaciones'] ?></textarea>
        </div>
      </div>
      </div>
      </div>
      <!--card-->
      <div class="card shadow mb-4">
        <div class="card-header py-3"><strong>LICENCIAS</strong></div>
        <div class="card-body">
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="so">Sistema Operativo</label>
              <select type="text" class="form-control" id="so" name="so" <?php echo $disableInputs ? 'disabled' : ''; ?>>
                <?php
                include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/licencias/sql_licencias.php' );
                generarOpciones( $conexion, 3, $id_compu );
                ?>
              </select>
            </div>
            <div class="col-sm mb-3">
              <label for="office">Office </label>
              <select type="text" class="form-control" id="office" name="office"  <?php echo $disableInputs ? 'disabled' : ''; ?>>
                <?php
                generarOpciones( $conexion, 2, $id_compu );
                ?>
              </select>
            </div>
            <div class="col-sm mb-3">
              <label for="antivirus">Antivirus</label>
              <select type="text" class="form-control" id="antivirus" name="antivirus"  <?php echo $disableInputs ? 'disabled' : ''; ?>>
                <?php
                generarOpciones( $conexion, 1, $id_compu );
                ?>
              </select>
            </div>
            <div class="col-sm mb-3">
              <label for="adicional">Software adicional</label>
              <textarea class="form-control" id="adicional" name="adicional" rows="1" <?php echo $disableInputs ? 'disabled' : ''; ?>><?php echo $datos_equipo['adicional'] ?></textarea>
            </div>
          </div>
        </div>
      </div>
      <?php
      include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/table_doc.php' );
      ?>
      <div  align="center"> <a class="btn btn-danger" type="button" href="computo" >Volver </a>
		   <?php
        if ( $access[ 'computo' ] == 1 ) {
          ?>
        <input type="Submit" class="btn bg-principal text-white" name="Submit" value="Guardar "  />
		   <?php
		}
          ?>
		  
      </div>
    </form>
  </div>
</div>
<script>
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
    var codigoQR = "<?php echo $codigoQR; ?>";

    $('#loading-overlay').show();

    $.ajax({
        url: 'responsiva_computo',  // Asegúrate de que la URL sea correcta y que apunte al script PHP adecuado
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
        }
    });
}

</script>

<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/table_doc.html' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>
