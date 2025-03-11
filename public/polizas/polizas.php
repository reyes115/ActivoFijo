<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/polizas/sql_polizas.php' );
$idpoliza = $_GET[ 'id_polizas' ];


$datos_equipo = view_poliza( $conexion, $idpoliza );

if ( $access[ 'polizas' ] == 0 || empty( $idpoliza ) || empty( $datos_equipo[ "codigo" ] ) ) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}
$codigo = $datos_equipo[ "codigo" ];
$registro = "polizas";
//echo $codigoQR;
if ( $access[ 'polizas' ] != 1 ) {
    $disableInputs = true;
} else {
    $disableInputs = false;
}

if ( isset($datos_equipo[ 'asegurado_auto' ] ) ) {
   $id_asegurado=$datos_equipo[ 'asegurado_auto' ];
    $asegurado ="Auto: ".$datos_equipo[ 'auto_codigo' ];
}
if ( isset($datos_equipo[ 'asegurado_col' ] ) ) {
	$id_asegurado=$datos_equipo[ 'asegurado_col' ];
    $asegurado =$datos_equipo[ 'nombre' ];

}
if ( isset($datos_equipo[ 'asegurado_inm' ] ) ) {
	$id_asegurado=$datos_equipo[ 'asegurado_inm' ];
    $asegurado =$datos_equipo[ 'name' ];

}

?>
<div class="layoutSidenav_content">
  <div class="container-fluid"> 
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>PÓLIZA DE SEGURO</strong></h1>
    <br>
    <form class="user" action="edit_polizas" method="post" enctype="multipart/form-data">
      <div class="card shadow mb-4"> 
        <!--card header-->
        <div class="card-header py-3"> <strong>DATOS GENERALES DE: <?php echo $datos_equipo['codigo']?></strong> </div>
        
        <input type="hidden" name="id_poliza"   value="<?php echo $idpoliza ?>">
        <!--card body-->
        <div class="card-body">
			<div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="propietario">Contratante</label>
              <select class="form-control" id="propietario" name="propietario" required <?php echo $disableInputs ? 'disabled' : ''; ?>>
              <option name="propietario" id="propietario"  value="">SELECCIONE:</option>
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
              <label for="t_asegurado">Tipo de asegurado</label>
              <select class="form-control" id="t_asegurado" name="t_asegurado" required <?php echo $disableInputs ? 'disabled' : ''; ?> >
                <option value="1" <?php if ($datos_equipo['t_asegurado'] == 1) echo 'selected'; ?>>Auto</option>
                <option value="2" <?php if ($datos_equipo['t_asegurado'] == 2) echo 'selected'; ?>>Colaborador</option>
                <option value="3" <?php if ($datos_equipo['t_asegurado'] == 3) echo 'selected'; ?>>Inmobiliario</option>
              </select>
            </div>
      
            <div class="col-sm mb-3">
              <label for="asegurado">Asegurado</label>
              <br>
              <select class="form-control" id="asegurado" name="asegurado" required <?php echo $disableInputs ? 'disabled' : ''; ?>>
             <option name="asegurado" id="asegurado" value="<?php  echo $id_asegurado?>" ><?php echo $asegurado ?></option>
              </select>  
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="empresa">Empresa</label>
              <select class="form-control" id="empresa" name="empresa" required <?php echo $disableInputs ? 'disabled' : ''; ?>>
                <?php
                $valores = view_empresa($conexion);
                while ($verE = mysqli_fetch_array($valores)) {
					$selected = ( $datos_equipo[ 'id_empresa' ] == $verE[ 'id_empresa' ] ) ? 'selected' : '';
                  print '<option name="empresa" id="empresa" value="' . $verE["id_empresa"] . '"' . $selected . '>' . $verE["nombre"] .'</option>';
                }
                ?>
              </select>
            </div>
         
            <div class="col-sm mb-3">
              <label for="tipo">Tipo</label>
              <select class="form-control" id="tipo" name="tipo" required <?php echo $disableInputs ? 'disabled' : ''; ?>>
                <option value="1" <?php if ($datos_equipo['tipo'] == 1) echo 'selected'; ?>>Auto</option>
                <option value="2" <?php if ($datos_equipo['tipo'] == 2) echo 'selected'; ?>>Vida</option>
                <option value="3" <?php if ($datos_equipo['tipo'] == 3) echo 'selected'; ?>>Gastos medicos</option>
                <option value="4" <?php if ($datos_equipo['tipo'] == 4) echo 'selected'; ?>>Daños</option>
              </select>
            </div>
        
            <div class="col-sm mb-3">
              <label for="no_poliza">Número</label>
              <input type="text" class="form-control" id="no_poliza" name="no_poliza" required <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['no_poliza'] ?>">
            </div>
            <div class="col-sm mb-3">
              <label for="aseguradora">Aseguradora</label>
              <input type="text" class="form-control" id="aseguradora" name="aseguradora" required <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['aseguradora'] ?>">
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="f_pago">Formato de pago</label>
              <select class="form-control" id="f_pago" name="f_pago" required <?php echo $disableInputs ? 'disabled' : ''; ?>>
                <option value="1" <?php if ($datos_equipo['f_pago'] == 1) echo 'selected'; ?>>Anual</option>
                <option value="2" <?php if ($datos_equipo['f_pago'] == 2) echo 'selected'; ?>>Semestral</option>
              </select>
            </div>
        
            <div class="col-sm mb-3">
              <label for="inicio_vigencia">Fecha de inicio de vigencia</label>
              <input type="date" id="inicio_vigencia" name="inicio_vigencia" class="form-control" required <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['inicio_vigencia'] ?>">
            </div>
            <div class="col-sm mb-3">
              <label for="fin_vigencia">Fecha de fin de vigencia</label>
              <input type="date" id="fin_vigencia" name="fin_vigencia" class="form-control" <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['fin_vigencia'] ?>">
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="moneda">Moneda</label>
              <select class="form-control" id="moneda" name="moneda" required  <?php echo $disableInputs ? 'disabled' : ''; ?>>
                <option value="1" <?php if ($datos_equipo['moneda'] == 1) echo 'selected'; ?>>Nacional</option>
                <option value="2" <?php if ($datos_equipo['moneda'] == 2) echo 'selected'; ?>>Dolar</option>
              </select>
            </div>
            <div class="col-sm mb-3">
              <label for="total">Total a pagar</label>
              <input type="text" class="form-control" id="total" name="total" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['total'] ?>">
            </div>
          </div>
			    <div class="row justify-content-center justify-content-md-start">
                 <div class="col-sm mb-3">
                  <label for="prima_neta">Prima neta</label>
                  <input type="text" class="form-control" id="prima_neta" name="prima_neta" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['prima_neta'] ?>">
                </div>
				  <div class="col-sm mb-3">
                  <label for="derecho_poliza">Derecho de poliza</label>
                  <input type="text" class="form-control" id="derecho_poliza" name="derecho_poliza" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['derecho_poliza'] ?>">
                </div>
				  <div class="col-sm mb-3">
                  <label for="iva">IVA</label>
                  <input type="text" class="form-control" id="iva" name="iva" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['iva'] ?>">
                </div>
				  <div class="col-sm mb-3">
                  <label for="suma_asegurada">Suma asegurada</label>
                  <input type="text" class="form-control" id="suma_asegurada" name="suma_asegurada" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required <?php echo $disableInputs ? 'disabled' : ''; ?> value="<?php echo $datos_equipo['suma_asegurada'] ?>">
                </div>
				  
              </div>
		  </div>
     
      </div>
      <?php
      include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/table_doc.php' );
      ?>
      <div  align="center"> <a class="btn btn-danger" type="button" href="polizas" >Volver </a>
        <?php
        if ( $access[ 'polizas' ] == 1 ) {
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
    $("#t_asegurado").on('change', function() {
      var elegido = $(this).val();
      $.post("t_asegurado", {
        elegido: elegido
      }, function(data) {
        $("#asegurado").html(data);
      });
    });
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
