<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/licencias/sql_licencias.php' );
$idlicencia = $_GET[ 'id_licencias' ];


$datos_equipo = view_licencia( $conexion, $idlicencia );

if ( $access[ 'licencias' ] == 0 || empty( $idlicencia ) || empty( $datos_equipo[ "nombre_licencias" ] ) ) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}
$codigo = $datos_equipo[ "nombre_licencias" ];
$registro = "licencias";
//echo $codigoQR;
if ( $access[ 'licencias' ] != 1 ) {
    $disableInputs = true;
} else {
    $disableInputs = false;
}

?>
<div class="layoutSidenav_content">
  <div class="container-fluid"> 
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>LICENCIA DE SOFTWARE</strong></h1>
    <br>
    <form class="user" action="edit_licencias" method="post" enctype="multipart/form-data">
      <div class="card shadow mb-4"> 
        <!--card header-->
        <div class="card-header py-3"> <strong>DATOS GENERALES</strong> </div>
        
        <!--card body-->
        
        <div class="card-body">
          <input type="hidden" name="id_licencias"   value="<?php echo $idlicencia ?>">
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="nombre_licencias">Nombre de la licencia</label>
              <input class="form-control" type="text" id="nombre_licencias" name="nombre_licencias" required value="<?php echo $datos_equipo['nombre_licencias'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
            </div>
            <div class="col-sm mb-3">
              <label for="costo">Costo</label>
              <input type="text" class="form-control" id="costo" name="costo" required  onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?php echo $datos_equipo['costo'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
            </div>
            <div class="col-sm mb-3">
              <label for="fecha_inicio">Fecha de compra</label>
              <input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $datos_equipo['fecha_inicio'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
            </div>
            <div class="col-sm mb-3">
              <label for="fecha_fin">Fecha de renovación</label>
              <input type="text" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $datos_equipo['fecha_fin'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="clave">Clave(Serial)</label>
              <input type="text" class="form-control" id="clave" name="clave" required value="<?php echo $datos_equipo['clave'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
            </div>
            <div class="col-sm mb-3">
              <label for="tipo">Tipo </label>
              <select class="form-control" id="tipo" name="tipo" required <?php echo $disableInputs ? 'disabled' : ''; ?>>
                <option id="tipo" name="tipo" value="">Seleccione</option>
                <option id="tipo" name="tipo" value="1" <?php if ($datos_equipo['tipo'] == 1) echo 'selected'; ?>>Antivirus</option>
                <option id="tipo" name="tipo" value="2" <?php if ($datos_equipo['tipo'] == 2) echo 'selected'; ?>>Office</option>
                <option id="tipo" name="tipo" value="3"<?php if ($datos_equipo['tipo'] == 3) echo 'selected'; ?>>Sistema operativo</option>
                <option id="tipo" name="tipo" value="4"<?php if ($datos_equipo['tipo'] == 4) echo 'selected'; ?>>Otro</option>
              </select>
            </div>
            <div class="col-sm mb-3">
              <label for="limite_usuarios">Límite de usuarios</label>
              <input type="text" class="form-control" id="limite_usuarios" name="limite_usuarios"  maxlength="12" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?php echo $datos_equipo['limite_usuarios'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
            </div>
            <?php
            $count = view_count_licencia( $conexion, $idlicencia );
            $disponibles = $datos_equipo[ 'limite_usuarios' ] - $count;
            ?>
            <div class="col-sm mb-3">
              <label >Usuarios disponibles</label>
              <input type="text" class="form-control"  value="<?php echo $disponibles ?>" disabled>
            </div>
          </div>
        </div>
      </div>
      <?php
      include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/table_doc.php' );
      ?>
      <div  align="center"> <a class="btn btn-danger" type="button" href="licencias" >Volver </a>
        <?php
        if ( $access[ 'licencias' ] == 1 ) {
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
