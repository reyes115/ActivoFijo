<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/personal/sql_personal.php' );
$idPersonal = $_GET[ 'id_personal' ];


$datos_equipo = view_colaborador( $conexion, $idPersonal );

if ( $access[ 'personal' ] == 0 || empty( $idPersonal ) || empty( $datos_equipo[ "nombre" ] ) ) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}
$codigo=  $datos_equipo[ "nombre" ] . ' ' . $datos_equipo[ "a_paterno" ] . ' ' . $datos_equipo[ "a_materno" ];
$registro = "personal";
//echo $codigoQR;
if ( $access[ 'personal' ] != 1 ) {
    $disableInputs = true;
} else {
    $disableInputs = false;
}

?>
<div class="layoutSidenav_content">
  <div class="container-fluid"> 
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>COLABORADOR</strong></h1>
	    <div class="row ">
      <div class="col-auto mb-3" > <button type="button" class="btn btn-success"  data-toggle="modal" data-target="#relaciones"> <i class="fa fa-id-badge"></i> Asiganaciones</button> </div>
    
	  <?php 
	  include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/personal/relaciones.php' );

	  ?>
	  
	   
      <div class="col-auto mb-3" > <button type="button" class="btn bg-secundario text-white"  onclick="generarPDF()"> <i class="fa fa-times"></i> Generar documeto de baja</button> </div>
			 </div>
   
    <br>
    <form class="user" action="edit_personal" method="post" enctype="multipart/form-data">
      <div class="card shadow mb-4">
      <!--card header-->
      <div class="card-header py-3"> <strong>DATOS GENERALES</strong> </div>
      
      <!--card body-->
      
      <div class="card-body">
        <input type="hidden" name="id_personal"   value="<?php echo $idPersonal ?>">
        <div class="row justify-content-center justify-content-md-start">
          <div class="col-sm mb-3">
            <label for="numColaborador">Número de Colaborador</label>
            <input class="form-control" type="text" id="numColaborador" name="numColaborador" value="<?php echo $datos_equipo['no_empleado'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
          </div>
          <div class="col-sm mb-3">
            <label for="nombre">Nombre(s)</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required  value="<?php echo $datos_equipo['nombre'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
          </div>
          <div class="col-sm mb-3">
            <label for="aPaterno">Apellido Paterno</label>
            <input type="text" class="form-control" id="aPaterno" name="aPaterno" value="<?php echo $datos_equipo['a_paterno'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
          </div>
          <div class="col-sm mb-3">
            <label for="aMaterno">Apellido Materno</label>
            <input type="text" class="form-control" id="aMaterno" name="aMaterno" value="<?php echo $datos_equipo['a_materno'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
          </div>
        </div>
        <div class="row justify-content-center justify-content-md-start">
          <div class="col-sm mb-3 ">
            <label for="email">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $datos_equipo['email'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
          </div>
          <div class="col-sm mb-3">
            <label for="telefono">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" maxlength="12" value="<?php echo $datos_equipo['phone'] ?>" <?php echo $disableInputs ? 'disabled' : ''; ?>>
          </div>
        </div>
        <div class="row justify-content-center justify-content-md-start">
          <div class="col mb-3">
            <label for="empresa">Empresa</label>
            <select class="form-control" id="empresa" name="empresa" required <?php echo $disableInputs ? 'disabled' : ''; ?>>
              <option value="">SELECCIONE</option>
              <?php
              $stmt = $conexion->prepare( "SELECT `id_empresa`, `nombre` FROM `empresa`" );
              $stmt->execute();
              $valores = $stmt->get_result();

              $stmt->close();
              while ( $ver = mysqli_fetch_array( $valores ) ) {
                  $selected = ( $datos_equipo[ 'id_empresa' ] == $ver[ 'id_empresa' ] ) ? 'selected' : '';
                  print '<option value="' . $ver[ "id_empresa" ] . '" ' . $selected . '>' . $ver[ "nombre" ] . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="col mb-3">
            <label for="departamento">Departamento </label>
            <select class="form-control" id="departamento" name="departamento" required <?php echo $disableInputs ? 'disabled' : ''; ?>>
              <option name="departamento" id="departamento" value="<?php  echo $datos_equipo['id_depar'] ?>"><?php echo $datos_equipo['departamentos'] ?> </option>
            </select>
          </div>
        </div>
        </div>		  
      </div>
		<?php
      include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/table_doc.php' );
      ?>
		  <div  align="center"> <a class="btn btn-danger" type="button" href="personal" >Volver </a>
		   <?php
        if ( $access[ 'personal' ] == 1 ) {
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
	  function generarPDF() {
	   $('#loading-overlay').show();
	    // Obtén el valor de la variable PHP y asigna a la variable JavaScript
        var codigoQR = "<?php echo $idPersonal; ?>";
        // Llamada AJAX al servidor para generar el PDF
        $.ajax({
            url: 'baja_equipo', // Reemplaza con la ruta correcta a tu script PHP que genera el PDF
            method: 'POST',
             data: { codigoQR: codigoQR }, // Envía la variable al script PHP
            success: function (data) {
				$('#loading-overlay').hide();
                // Abre el modal
                $('#pdfModal').modal('show');

                // Muestra el PDF en el iframe
                $('#modalPDF').attr('src', 'data:application/pdf;base64,' + data);

                // Habilita el botón de descarga
                $('#downloadBtn').attr('href', 'data:application/pdf;base64,' + data);
            },
            error: function () {
				$('#loading-overlay').hide();
                alert('Error al generar el PDF');
            }
        });
    }
  $(document).ready(function() {
    // Función para obtener los departamentos al cambiar la empresa seleccionada
    function obtenerDepartamentos(empresa_id) {
      $.ajax({
        url: 'obtener_departamentos', // URL del script PHP para obtener los departamentos
        method: 'POST',
        data: { empresa_id: empresa_id }, // Enviar el ID de la empresa al servidor
        success: function(data) {
          $('#departamento').html(data); // Actualizar el select de departamentos con las opciones recibidas
        }
      });
    }

    // Evento change al seleccionar una empresa
    $('#empresa').change(function() {
      var empresa_id = $(this).val(); // Obtener el ID de la empresa seleccionada
      obtenerDepartamentos(empresa_id); // Obtener y mostrar los departamentos de esa empresa
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
