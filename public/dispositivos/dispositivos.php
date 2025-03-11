<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/dispositivos/sql_dispositivos.php' );
$codigoQR = $_GET[ 'codigoQR' ];


$datos_equipo = view_equipo( $conexion, $codigoQR );

if ( $access[ 'dispositivos' ] == 0 || empty( $codigoQR )|| empty( $datos_equipo[ 'id_perifericos' ] ) ) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}
$id_dispositivos = $datos_equipo[ 'id_perifericos' ];
$codigo = $datos_equipo[ 'codigo' ];
$registro = "dispositivos";
//echo $codigoQR;
if ( $access[ 'dispositivos' ] != 1 ) {
    $disableInputs = true;
} else {
    $disableInputs = false;
}

?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.js"></script>

<div class="layoutSidenav_content">
  <div class="container-fluid"> 
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>EQUIPO ALTERNO</strong></h1>
    <br>
    <!--card-->
    <form class="user" action="edit_dispositivos" method="post" enctype="multipart/form-data">
      <div class="card shadow mb-4"> 
        <!--card header-->
        <div class="card-header py-3"> <strong>DATOS GENERALES DE: <?php echo $datos_equipo['codigo']?></strong> </div>
        
        <!--card body-->
        
        <div class="card-body">
          <input type="hidden" name="id_dispositivos"   value="<?php echo $id_dispositivos ?>">
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
              <label for="no_serie">Número de serie</label>
              <input type="text" class="form-control" id="no_serie" name="no_serie"   <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['no_serie'] ?>">
            </div>
            <div class="col-sm mb-3">
              <label for="estado">Estado</label>
              <select  class="form-control" id="estado" name="estado" <?php echo $disableInputs ? 'disabled' : ''; ?>>
                <option id="estado" name="estado" value="1"<?php if ($datos_equipo['Estado'] == 1) echo 'selected'; ?>>Nuevo</option>
                <option id="estado" name="estado" value="2"<?php if ($datos_equipo['Estado'] == 2) echo 'selected'; ?>>Usado</option>
                <option id="estado" name="estado" value="3"<?php if ($datos_equipo['Estado'] == 3) echo 'selected'; ?>>Con fallas</option>
                <option id="estado" name="estado" value="4"<?php if ($datos_equipo['Estado'] == 4) echo 'selected'; ?>>Inservible</option>
              </select>
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="costo">Costo</label>
              <input type="text" class="form-control" id="costo" name="costo"   onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?php echo $datos_equipo['costo'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
            </div>
            <div class="col-sm mb-3">
              <label for="fecha">Fecha de asignación</label>
              <input type="date" class="form-control" id="fecha" name="fecha" required <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['fecha'] ?>">
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
              <label for="observaciones">Características generales</label>
              <textarea class="form-control" id="caracteristicas" name="caracteristicas" rows="3" <?php echo $disableInputs ? 'disabled' : ''; ?>><?php echo $datos_equipo['caracteristicas'] ?></textarea>
            </div>
          </div>
        </div>
      </div>
      <?php
      include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/table_doc.php' );
      ?>
      <div  align="center"> <a class="btn btn-danger" type="button" href="dispositivos" >Volver </a>
        <?php
        if ( $access[ 'dispositivos' ] == 1 ) {
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
  

</script>
<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/table_doc.html' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>
