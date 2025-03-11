<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/servicios/sql_servicios.php' );
$id_servicios = $_GET[ 'id_servicios' ];


$datos_equipo = view_servicio( $conexion, $id_servicios );

if ( $access[ 'servicios' ] == 0 || empty( $id_servicios ) || empty( $datos_equipo[ "no_cuenta" ] ) ) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}
$codigo = $datos_equipo[ "no_cuenta" ];
$registro = "servicios";
//echo $codigoQR;
if ( $access[ 'servicios' ] != 1 ) {
    $disableInputs = true;
} else {
    $disableInputs = false;
}

?>
<div class="layoutSidenav_content">
  <div class="container-fluid"> 
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>SERVICIO DE COMUNICACIÓN</strong></h1>
    <br>
    <form class="user" action="edit_servicios" method="post" enctype="multipart/form-data">
      <div class="card shadow mb-4"> 
        <!--card header-->
        <div class="card-header py-3"> <strong>DATOS GENERALES</strong> </div>
        
        <!--card body-->
        
        <div class="card-body">
          <input type="hidden" name="id_servicios"   value="<?php echo $id_servicios ?>">
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="no_cuenta">Número de cuenta</label>
              <input type="text" class="form-control" id="no_cuenta" name="no_cuenta"  required value="<?php echo $datos_equipo['no_cuenta'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
            </div>
            <div class="col-sm mb-3">
              <label for="proveedores">Proveedor</label>
              <input type="text" class="form-control" id="proveedores" name="proveedores"  value="<?php echo $datos_equipo['proveedores'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
            </div>
            <div class="col-sm mb-3">
              <label for="fecha_inicio">Fecha de contratación del servicio</label>
              <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $datos_equipo['fecha_inicio'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="fecha_renova">Fecha (Día) de ronavación</label>
              <input type="text" class="form-control" id="fecha_renova" name="fecha_renova" value="<?php echo $datos_equipo['fecha_renova'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
            </div>
            <div class="col-sm mb-3">
              <label for="costo_renova">Costo de renovación</label>
              <input type="text" class="form-control" id="costo_renova" name="costo_renova" required  onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?php echo $datos_equipo['costo_renova'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
            </div>
            <div class="col-sm mb-3">
              <label for="ubicacion">Ubicación</label>
              <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="<?php echo $datos_equipo['ubicacion'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col mb-3">
              <label for="detalles">Características generales</label>
              <textarea class="form-control" rows="2" d="detalles" name="detalles" required  <?php echo $disableInputs ? 'disabled' : ''; ?>><?php echo $datos_equipo['detalles'] ?> </textarea>
            </div>
          </div>
        </div>
      </div>
      <?php
      include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/table_doc.php' );
      ?>
      <div  align="center"> <a class="btn btn-danger" type="button" href="servicios" >Volver </a>
        <?php
        if ( $access[ 'servicios' ] == 1 ) {
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
	 function actualizarNombreArchivos() {
    var inputArchivos = document.getElementById('customFile');
    var labelArchivos = document.querySelector('.custom-file-label');
    var nombresArchivos = Array.from(inputArchivos.files).map(file => file.name);
    labelArchivos.innerHTML = nombresArchivos.join(', ') || 'Selecciona archivos';
  }
</script>
<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/table_doc.html' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>
