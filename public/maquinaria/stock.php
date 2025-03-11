<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/stock/sql_stock.php' );
// Si se ha enviado el formulario
$codigoQR = $_GET[ 'codigoQR' ];

$datos_equipo = view_equipo( $conexion, $codigoQR );


if ( $access[ 'stock' ] == 0 || empty( $codigoQR ) || empty( $datos_equipo[ 'id_stock' ] ) ) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}
$id_stock = $datos_equipo[ 'id_stock' ];
$codigo = $datos_equipo[ 'codigo' ];
$registro = "stock";
//echo $codigoQR;
if ( $access[ 'stock' ] != 1 ) {
    $disableInputs = true;
} else {
    $disableInputs = false;
}
?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.js"></script>

<div class="layoutSidenav_content">
<div class="container-fluid"> 
  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800" align="center"><strong>MOBILIARIO</strong></h1>
  <div class="row justify-content-center justify-content-md-start mb-2">
    <div class="col-sm mb-2" >
      <button type="button" class="btn bg-principal text-white btn-sm mb-2" data-toggle="modal" data-target="#asig"> <i class="fa fa-clock-o"></i> Historial de asignaciones </button>
    </div>
  </div>
  <!--card-->
  <form class="user" action="edit_stock" method="post" enctype="multipart/form-data">
    <div class="card shadow mb-4"> 
      <!--card header-->
      <div class="card-header py-3"> <strong>DATOS GENERALES DE: <?php echo $datos_equipo['codigo']?></strong> </div>
      
      <!--card body-->
      
      <div class="card-body">
        <input type="hidden" name="id_stock"   value="<?php echo $id_stock ?>">
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
            <label for="tipo">Tipo</label>
            <input type="text" class="form-control" id="tipo" name="tipo" required <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['tipo'] ?>">
          </div>
          <div class="col-sm mb-3">
            <label for="estado">Estado</label>
            <select  class="form-control" id="estado" name="estado" required <?php echo $disableInputs ? 'disabled' : ''; ?>>
              <option id="estado" name="estado" value="1" <?php if ($datos_equipo['estado'] == 1) echo 'selected'; ?>>Exelente</option>
              <option id="estado" name="estado" value="2" <?php if ($datos_equipo['estado'] == 2) echo 'selected'; ?>>Regular</option>
              <option id="estado" name="estado" value="3" <?php if ($datos_equipo['estado'] == 3) echo 'selected'; ?>>Deteriorado</option>
              <option id="estado" name="estado" value="4" <?php if ($datos_equipo['estado'] == 4) echo 'selected'; ?>>Mal estado</option>
            </select>
          </div>
          <div class="col-sm mb-3">
            <label for="cantidad">Cantidad</label>
            <input type="text" class="form-control" id="cantidad" name="cantidad"  maxlength="12" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['cantidad'] ?>">
          </div>
        </div>
        <div class="row justify-content-center justify-content-md-start">
          <div class="col-sm mb-3" style="color:#858796">
            <label for="usuarioAsignado">Colaborador Asignado</label>
            <select class="form-control" id="usuarioAsignado" name="usuarioAsignado" <?php echo $disableInputs ? 'disabled' : ''; ?> required>
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
            <fieldset  <?php echo $disableInputs ? 'disabled' : ''; ?>>
              Estado de almacenamiento
              <div>
                <input required name="opt" id="opt1" type="radio" value="1" <?php if ($datos_equipo['almacenado'] == 1) echo 'checked'; ?>>
                <label for="opt1">Almacenado</label>
              </div>
              <div>
                <input name="opt" id="opt2" type="radio" value="2"  <?php if ($datos_equipo['almacenado'] == 2) echo 'checked';?>>
                <label for="opt2">No almacenado</label>
              </div>
              <div>
                <input name="opt" id="opt3" type="radio" value="3"  <?php if ($datos_equipo['almacenado'] == 3) echo 'checked';?>>
                <label for="opt3">Baja</label>
              </div>
            </fieldset>
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
    <div  align="center"> <a class="btn btn-danger" type="button" href="stock" >Volver </a>
      <?php
      if ( $access[ 'stock' ] == 1 ) {
          ?>
      <input type="Submit" class="btn bg-principal text-white" name="Submit" value="Guardar "  />
      <?php
      }
      ?>
    </div>
  </form>
  <br>
  <!-- Modal de historial -->
  <div class="modal fade" id="asig" tabindex="-1" role="dialog" aria-labelledby="bloquearLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h5 class="modal-title text-white" id="bloquearLabel">Historial de asignaciones</h5>
          <button type="button" class="close  text-white" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        </div>
        <div class="modal-body" align="justify">
          <div class="table-responsive">
            <table class="table table-bordered display table-hover" >
              <thead>
                <tr align="center">
                  <th scope="col">Nombre </th>
                  <th scope="col">Fecha de retiro del mobiliario</th>
                  <th scope="col">Ver más </th>
              </thead>
              <tbody>
           <?php
$hist = view_mob_asig($conexion, $datos_equipo['id_stock']);
if(mysqli_num_rows($hist) > 0) {
    while ($row = mysqli_fetch_array($hist)) {
        $personal = view_colaborador($conexion, $row['id_before']);
        ?>
        <tr align="center">
          <td><?php echo $personal['nombre'] . ' ' . $personal['a_paterno'] . ' ' . $personal['a_materno']; ?></td>
          <td><?php echo $row['fecha_formateada']; ?></td>
          <td align="center"><a class="btn btn-primary" href="ver_personal<?php echo $personal['id_personal']; ?>"> <i class="fas fa fa-share"> </i> </a></td>
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
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
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
