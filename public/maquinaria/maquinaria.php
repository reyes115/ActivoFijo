<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/maquinaria/sql_maquinaria.php' );
// Si se ha enviado el formulario
$codigoQR = $_GET[ 'codigoQR' ];

$datos_equipo = view_equipo( $conexion, $codigoQR );


if ( $access[ 'maquinaria' ] == 0 || empty( $codigoQR ) || empty( $datos_equipo[ 'id_cogs' ] ) ) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}
$id_maquinaria = $datos_equipo[ 'id_cogs' ];
$codigo = $datos_equipo[ 'codigo' ];
$registro = "maquinaria";
//echo $codigoQR;
if ( $access[ 'maquinaria' ] != 1 ) {
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

  <!--card-->
  <form class="user" action="edit_maquinaria" method="post" enctype="multipart/form-data">
    <div class="card shadow mb-4"> 
      <!--card header-->
      <div class="card-header py-3"> <strong>DATOS GENERALES DE: <?php echo $datos_equipo['codigo']?></strong> </div>
      
      <!--card body-->
      
      <div class="card-body">
        <input type="hidden" name="id_maquinaria"   value="<?php echo $id_maquinaria ?>">
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
                  $selected = ( $datos_equipo[ 'propietario_id' ] == $ver_propietarios[ 'id_propietario' ] ) ? 'selected' : '';
                  // Utiliza comillas simples para las cadenas HTML
                  echo '<option value="' . $ver_propietarios[ "id_propietario" ] . '" ' . $selected . '>' . $ver_propietarios[ "nombre" ] . '</option>';
              }
              ?>
            </select>
          </div>
			<div class="col-sm mb-3">
          <label for="descripcion">Descripción</label>
              <input type="text" class="form-control" id="descripcion" name="descripcion" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['descripcion'] ?>">
          </div>
			<div class="col-sm mb-3">
            <label for="marca">Marca</label>
              <input type="text" class="form-control" id="marca" name="marca" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['marca'] ?>">
          </div>
			<div class="col-sm mb-3">
            <label for="serie">Serie</label>
              <input type="text" class="form-control" id="serie" name="serie" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['marca'] ?>">
          </div>         
        </div>
       
        <div class="row justify-content-center justify-content-md-start">
          <div class="col-sm mb-3">
			  			  <label for="modelo">Modelo</label>
              <input type="text" class="form-control" id="modelo" name="modelo" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['modelo'] ?>" >
			    </div>    
          <div class="col-sm mb-3">
			  			   <label for="no_factura">Número Factura</label>
              <input type="text" class="form-control" id="no_factura" name="no_factura"  <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['no_factura'] ?>" >
			    </div>      
          <div class="col-sm mb-3">
			  			   	   <label for="val_factura">Valor factura</label>
              <input type="text" class="form-control" id="val_factura" name="val_factura" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['valor_factura'] ?>" >
			    </div>         
        </div>
			   
        <div class="row justify-content-center justify-content-md-start">
          <div class="col-sm mb-3">
			   <label for="empresa">Empresa</label>
              <select class="form-control" id="empresa" name="empresa" required <?php echo $disableInputs ? 'disabled' : ''; ?>>
               <?php             
                $valores = view_empresa( $conexion );
              while (  $ver = mysqli_fetch_array( $valores )  ) {
                  $selected = ( $datos_equipo[ 'empresa_id' ] == $ver[ 'id_empresa' ] ) ? 'selected' : '';
                  // Utiliza comillas simples para las cadenas HTML
                  echo '<option value="' . $ver[ "id_empresa" ] . '" ' . $selected . '>' . $ver[ "nombre" ] . '</option>';
              }
              ?>
			  </select>
          </div>
          <div class="col-sm mb-3">
				   <label for="area">Área responsable </label>
              <select class="form-control" id="area" name="area" required <?php echo $disableInputs ? 'disabled' : ''; ?>>
                <option value="1" <?php if ($datos_equipo['area'] == 1) echo 'selected'; ?>>Mantenimiento</option>
                <option value="2" <?php if ($datos_equipo['area'] == 2) echo 'selected'; ?>>Maquinados</option>
                <option value="3" <?php if ($datos_equipo['area'] == 3) echo 'selected'; ?>>Producción</option>
              </select>	
          </div>
          <div class="col-sm mb-3">				         
				   <label for="estado">Estado</label>
              <select  class="form-control" id="estado" name="estado" <?php echo $disableInputs ? 'disabled' : ''; ?>>
                <option value="1" <?php if ($datos_equipo['estado'] == 1) echo 'selected'; ?>>En operación</option>
                <option value="2" <?php if ($datos_equipo['estado'] == 2) echo 'selected'; ?>>Fuera de operación</option>
                <option value="3" <?php if ($datos_equipo['estado'] == 3) echo 'selected'; ?>>En reparación</option>
                <option value="4" <?php if ($datos_equipo['estado'] == 4) echo 'selected'; ?>>Inservible</option>
              </select>
          </div>
        </div>
        <div class="row justify-content-center justify-content-md-start">
          <div class="col-sm mb-3">
            <label for="observaciones">Observaciones</label>
            <textarea class="form-control" id="observaciones" name="observaciones" rows="3" <?php echo $disableInputs ? 'disabled' : ''; ?>><?php echo $datos_equipo['obs'] ?></textarea>
          </div>
        </div>
      </div>
    </div>
    <?php
    include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/table_doc.php' );
    ?>
    <div  align="center"> <a class="btn btn-danger" type="button" href="maquinaria" >Volver </a>
      <?php
      if ( $access[ 'maquinaria' ] == 1 ) {
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
