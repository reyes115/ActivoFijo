<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );


if ( $access[ 'moviles' ] == 0 ) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}
?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.js"></script>

<div class="layoutSidenav_content">
  <div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>NUEVO EQUIPO DE MÓVIL</strong></h1>
    <form class="user" action="save_movil" method="post" enctype="multipart/form-data">
      <!--card-->
      <div class="card shadow mb-4"> 
        <!--card header-->
        <div class="card-header py-3"><strong>DATOS GENERALES</strong></div>
        
        <!--card body-->
        
        <div class="card-body">
          <div class="row justify-content-center justify-content-md-start">
            <div class="col mb-3">
              <label for="propietario">Propietario</label>
              <select class="form-control" id="propietario" name="propietario" required>
                <option value="">SELECCIONE</option>
                <?php
                include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/propietarios.php' );
                $propietarios = view_propietarios( $conexion );

                while ( $ver_propietarios = mysqli_fetch_array( $propietarios ) ) {
                    // Utiliza comillas simples para las cadenas HTML
                    echo '<option value="' . $ver_propietarios[ "id_propietario" ] . '">' . $ver_propietarios[ "nombre" ] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="col-sm mb-3">
              <label for="no_telefono">Número telefónico</label>
              <input type="tel" class="form-control" id="no_telefono" name="no_telefono" pattern="[0-9]{10}" inputmode="tel" title="Introduce un número telefónico válido de 10 dígitos (solo números)" maxlength="12">
            </div>
            <div class="col-sm mb-3">
              <label for="region">Región</label>
              <input type="text" class="form-control" id="region" name="region"  >
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="marca">Marca</label>
              <input type="text" class="form-control" id="marca" name="marca"  >
            </div>
            <div class="col-sm mb-3">
              <label for="modelo">Modelo</label>
              <input type="text" class="form-control" id="modelo" name="modelo"  >
            </div>
            <div class="col-sm mb-3">
              <label for="imei">IMEI</label>
              <input type="text" class="form-control" id="imei" name="imei"  >
            </div>
            <div class="col-sm mb-3">
              <label for="color">Color</label>
              <input type="text" class="form-control" id="color" name="color"  >
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="no_serie">Número de serie</label>
              <input type="text" class="form-control" id="no_serie" name="no_serie"  >
            </div>
            <div class="col-sm mb-3">
              <label for="disponible">Disponibilidad</label>
              <select class="form-control" id="disponible" name="disponible" required>
                <option id="disponible" name="disponible" value="">Seleccione</option>
                <option id="disponible" name="disponible" value="1">Disponible</option>
                <option id="disponible" name="disponible" value="2">No disponible</option>
              </select>
            </div>
            <div class="col-sm mb-3">
              <label for="no_cargador">Número de serie del cargador</label>
              <input type="text" class="form-control" id="no_cargador" name="no_cargador"  >
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3" style="color:#858796">
              <label for="usuarioAsignado">Colaborador Asignado</label>
              <select class="js-example-basic-single js-states form-control" id="usuarioAsignado" name="usuarioAsignado" >
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
              <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
            </div>
          </div>
        </div>
      </div>
      <!--card-->
      <div class="card shadow mb-4">
        <div class="card-header py-3"><strong>DOCUMENTACIÓN (Opcional)</strong></div>
        <div class="card-body">
          <div class="row">
            <div class="col" >
              <div class="custom-file mb-3">
                <input type="file" class="custom-file-input" id="customFile" name="archivos[]" multiple onchange="actualizarNombreArchivos()">
                <label class="custom-file-label" for="customFile">Suelta los archivos aqui. <i class="fa fa-upload"></i></label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div  align="center"> <a class="btn btn-danger" type="button" href="computo" >Volver </a>
        <input type="Submit" class="btn bg-principal text-white" name="Submit" value="Guardar "  >
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
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>
