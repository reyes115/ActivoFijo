<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );


if ( $access[ 'autos' ] == 0 ) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}
?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.js"></script>

<div class="layoutSidenav_content">
  <div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>NUEVO EQUIPO DE TRANSPORTE</strong></h1>
    <form class="user" action="save_auto" method="post" enctype="multipart/form-data">
      <!--card-->
      <div class="card shadow mb-4"> 
        <!--card header-->
        <div class="card-header py-3"><strong>DATOS GENERALES</strong></div>
        
        <!--card body-->
        
        <div class="card-body">
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
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
              <label for="claveVehicular">Clave Vehicular</label>
              <input type="text" class="form-control" id="claveVehicular" name="claveVehicular" required>
            </div>
            <div class="col-sm mb-3">
              <label for="factura">Factura</label>
              <input class="form-control" type="text" id="factura"  name="factura">
              </input>
            </div>
            <div class="col-sm mb-3">
              <label for="vin">VIN</label>
              <input type="text" class="form-control" id="vin" name="vin" required>
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="marca">Marca</label>
              <input type="text" class="form-control" id="marca" name="marca" required>
            </div>
            <div class="col-sm mb-3">
              <label for="modelo">Modelo</label>
              <input type="text" class="form-control" id="modelo" name="modelo" required>
            </div>
            <div class="col-sm mb-3">
              <label for="tipo">Tipo</label>
              <input type="text" class="form-control" id="tipo" name="tipo" required>
            </div>
            <div class="col-sm mb-3">
              <label for="transmision">Transmisión</label>
              <select class="form-control" id="transmision" name="transmision" required>
                <option id="transmision" name="transmision" >Seleccione</option>
                <option id="transmision" name="transmision" value="1">Automática</option>
                <option id="transmision" name="transmision" value="2">Manual</option>
              </select>
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="color">Color</label>
              <input type="text" class="form-control" id="color" name="color">
            </div>
            <div class="col-sm mb-3">
              <label for="combustible">Combustible</label>
              <input type="text" class="form-control" id="combustible" name="combustible">
            </div>
            <div class="col-sm mb-3">
              <label for="numMotor">Número de Motor</label>
              <input type="text" class="form-control" id="numMotor" name="numMotor">
            </div>
            <div class="col-sm mb-3">
              <label for="placas">Placas</label>
              <input type="text" class="form-control" id="placas"name="placas" >
            </div>
            <div class="col-sm mb-3">
              <label for="color_engomado">Color de engomado</label>
              <select class="form-control" id="color_engomado" name="color_engomado" required>
                <option >Seleccione</option>
                <option value="1">Amarillo</option>
                <option value="2">Rosa</option>
                <option value="3">Rojo</option>
                <option value="4">Verde</option>
                <option value="5">Azul</option>
              </select>
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="tarjetaCirculacion">Tarjeta de Circulación</label>
              <input type="text" class="form-control" id="tarjetaCirculacion" name="tarjetaCirculacion">
            </div>
            <div class="col-sm mb-3">
              <label for="vencimientoTarjeta">Vencimiento Tarjeta de Circulación</label>
              <input type="date" class="form-control" id="vencimientoTarjeta" name="vencimientoTarjeta">
            </div>
            <div class="col-sm mb-3">
              <label for="estadoCirculacion">Estado de Circulación</label>
              <input type="text" class="form-control" id="estadoCirculacion" name="estadoCirculacion">
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="estatus">Estatus del Vehiculo</label>
              <select class="form-control" id="estatus" name="estatus" required>
                <option id="estatus" name="estatus">Seleccione</option>
                <option id="estatus" name="estatus" value="1">Alta</option>
                <option id="estatus" name="estatus" value="2">Baja</option>
              </select>
            </div>
            <div class="col-sm mb-3">
              <label for="estatusVerificacion">Estatus de Verificación</label>
              <select class="form-control" id="estatusVerificacion" name="estatusVerificacion">
                <option id="estatus" name="estatus">Seleccione</option>
                <option id="estatus" name="estatus" value="1">Realizado</option>
                <option id="estatus" name="estatus" value="2">Pendiente</option>
              </select>
            </div>
            <div class="col-sm mb-3">
              <label for="vencimientoVerificacion">Vencimiento de Verificación</label>
              <input type="date" class="form-control" id="vencimientoVerificacion" name="vencimientoVerificacion">
            </div> 
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3" style="color:#858796">
              <label for="usuarioAsignado">Colaborador Asignado</label>
              <select class="js-example-basic-single js-states form-control" id="usuarioAsignado" name="usuarioAsignado" required>
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
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">Documento</th>
                <th scope="col">Seleccionar Archivo</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td scope="row">Imagen del Vehículo</td>
                <td><div class="custom-file mb-3">
                    <input type="file" class="custom-file-input" id="imagen" name="imagen" onchange="actualizarNombreArchivo('imagen')">
                    <label class="custom-file-label" for="imagen">Selecciona un archivo <i class="fa fa-upload"></i></label>
                  </div></td>
              </tr>
              <tr>
                <td scope="row">Tarjeta de Circulación</td>
                <td><div class="custom-file mb-3">
                    <input type="file" class="custom-file-input" id="tarjeta" name="tarjeta" onchange="actualizarNombreArchivo('tarjeta')">
                    <label class="custom-file-label" for="tarjeta">Selecciona un archivo <i class="fa fa-upload"></i></label>
                  </div></td>
              </tr>
			 <tr>
      <td scope="row">Factura</td>
      <td>
        <div class="custom-file mb-3">
          <input type="file" class="custom-file-input" id="facturas" name="facturas" onchange="actualizarNombreArchivo('facturas')">
          <label class="custom-file-label" for="facturas">Selecciona un archivo <i class="fa fa-upload"></i></label>
        </div>
      </td>
    </tr>
    <tr>
      <td scope="row">Identificación</td>
      <td>
        <div class="custom-file mb-3">
          <input type="file" class="custom-file-input" id="identificacion" name="identificacion" onchange="actualizarNombreArchivo('identificacion')">
          <label class="custom-file-label" for="identificacion">Selecciona un archivo <i class="fa fa-upload"></i></label>
        </div>
      </td>
    </tr>
    <tr>
      <td scope="row">Pagos de Tenencia</td>
      <td>
        <div class="custom-file mb-3">
          <input type="file" class="custom-file-input" id="tenencia" name="tenencia" onchange="actualizarNombreArchivo('tenencia')">
          <label class="custom-file-label" for="tenencia">Selecciona un archivo <i class="fa fa-upload"></i></label>
        </div>
      </td>
    </tr>
    <tr>
      <td scope="row">Certificado de Verificación</td>
      <td>
        <div class="custom-file mb-3">
          <input type="file" class="custom-file-input" id="verificacion" name="verificacion" onchange="actualizarNombreArchivo('verificacion')">
          <label class="custom-file-label" for="verificacion">Selecciona un archivo <i class="fa fa-upload"></i></label>
        </div>
      </td>
    </tr>
    <tr>
      <td scope="row">Licencia de Conducir</td>
      <td>
        <div class="custom-file mb-3">
          <input type="file" class="custom-file-input" id="licencia" name="licencia" onchange="actualizarNombreArchivo('licencia')">
          <label class="custom-file-label" for="licencia">Selecciona un archivo <i class="fa fa-upload"></i></label>
        </div>
      </td>
    </tr>
  
    <tr>
      <td scope="row">Políticas de Uso</td>
      <td>
        <div class="custom-file mb-3">
          <input type="file" class="custom-file-input" id="politicas" name="politicas" onchange="actualizarNombreArchivo('politicas')">
          <label class="custom-file-label" for="politicas">Selecciona un archivo <i class="fa fa-upload"></i></label>
        </div>
      </td>
    </tr>
    <tr>
              <!-- Repite este bloque para cada tipo de documento -->
            </tbody>
          </table>
          
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
    $('.js-example-basic-single').select2();U
});
  function actualizarNombreArchivo(idInput) {
    var inputArchivo = document.getElementById(idInput);
    var labelArchivo = document.querySelector('label[for="' + idInput + '"]');
    var nombreArchivo = inputArchivo.files[0].name;
    labelArchivo.innerHTML = nombreArchivo || 'Selecciona un archivo <i class="fa fa-upload"></i>';
  }

</script>
<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>
