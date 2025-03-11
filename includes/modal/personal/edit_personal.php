
<div class="modal fade" id="edit<?php echo $id_registro ?>" tabindex="-1" role="dialog" aria-labelledby="newModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header bg-principal">
        <h5 class="modal-title text-white" id="newModalLabel">Nuevo Registro</h5>
        <button type="button" class="close  text-white" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm">
              <label for="numColaborador">Número de Colaborador</label>
                  <input class="form-control" type="text" id="numColaborador" name="numColaborador">
                  </input>
               </div>
         
                <div class="col-sm">
                  <label for="nombre">Nombre(s)</label>
                  <input type="text" class="form-control" id="nombre" name="nombre" required>
              </div>
              </div>
		  <br>
          <div class="row justify-content-center justify-content-md-start">
                <div class="col-sm">
                  <label for="aPaterno">Apellido Paterno</label>
                  <input type="text" class="form-control" id="aPaterno" name="aPaterno" >
                </div>
                <div class="col-sm">
                  <label for="aMaterno">Apellido Materno</label>
                  <input type="text" class="form-control" id="aMaterno" name="aMaterno" >
                </div>
          </div>
		  <br>
        <div class="row justify-content-center justify-content-md-start">
                <div class="col-sm">
                  <label for="email">Correo Electrónico</label>
                  <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="col-sm">
                  <label for="telefono">Teléfono</label>
                  <input type="text" class="form-control" id="telefono" name="telefono" maxlength="12" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                </div>

              </div>
		  <br>
		   <div class="row justify-content-center justify-content-md-start">
                <div class="col">
                  <label for="empresa">Empresa</label>
                  <select class="form-control" id="empresa" name="empresa" required>
                    <option name="empresa" id="empresa" value="">SELECCIONE</option>
                    <?php
$stmt = $conexion->prepare( "SELECT `id_empresa`, `nombre` FROM `empresa`" );
$stmt->execute();
$valores = $stmt->get_result();

$stmt->close();
                    while ( $ver = mysqli_fetch_array( $valores ) ) {
                        print '
            <option  name="empresa" id="empresa" value="' . $ver[ "id_empresa" ] . '">' . $ver[ "nombre" ] . '</option>
            ';
                    }
                    ?>
                  </select>
                </div>
                <div class="col">
                  <label for="departamento">Departamento </label>
                  <select class="form-control" id="departamento" name="departamento" required>
                    <option name="departamento" id="departamento" value="" >SELECCIONE </option>
                  </select>
                </div>
              </div>
		  <br>
		  
           
			<div class=" py-3"><strong>DOCUMENTACIÓN (Opcional)</strong></div>
			
			<div class="row">
                  <div class="col" >
                  <div class="custom-file mb-3">
      <input type="file" class="custom-file-input" id="customFile" name="archivos[]" multiple onchange="actualizarNombreArchivos()">
      <label class="custom-file-label" for="customFile">Suelta los archivos aqui. <i class="fa fa-upload"></i></label>
    </div>
                  </div>
                </div>
				
          
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" name="insertar" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#empresa').change(function() {
      var empresa_id = $(this).val(); // Obtener el ID de la empresa seleccionada
      $.ajax({
        url: 'obtener_departamentos', // URL del script PHP para obtener los departamentos
        method: 'POST',
        data: { empresa_id: empresa_id }, // Enviar el ID de la empresa al servidor
        success: function(data) {
          $('#departamento').html(data); // Actualizar el select de departamentos con las opciones recibidas
        }
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
