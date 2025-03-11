
<div class="modal fade" id="nuevoModal" tabindex="-1" role="dialog" aria-labelledby="newModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header bg-principal">
        <h5 class="modal-title text-white" id="newModalLabel">Nuevo Registro</h5>
        <button type="button" class="close  text-white" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">
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
                <label for="no_serie">Número de serie</label>
              <input type="text" class="form-control" id="no_serie" name="no_serie"  >
              </div>
              </div>
          <div class="row justify-content-center justify-content-md-start">
                <div class="col-sm mb-3">
                  <label for="estado">Estado</label>
              <select  class="form-control" id="estado" name="estado"  >
                <option id="estado" name="estado" value="0">Seleccione</option>
                <option id="estado" name="estado" value="1">Nuevo</option>
                <option id="estado" name="estado" value="2">Usado</option>
                <option id="estado" name="estado" value="3">Con fallas</option>
                <option id="estado" name="estado" value="4">Inservible</option>
              </select>
                </div>
                <div class="col-sm mb-3">
                 <label for="costo">Costo</label>
              <input type="text" class="form-control" id="costo" name="costo"   onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                </div>
          </div>
        <div class="row justify-content-center justify-content-md-start">
                <div class="col-sm">
                <label for="fecha_compra">Fecha de asignación</label>
              <input type="date" class="form-control" id="fecha" name="fecha">
                </div>
                </div>
			<br>
			<div class="row justify-content-center justify-content-md-start">
                <div class="col-sm mb-3">
                   <label for="usuarioAsignado">Colaborador Asignado</label>
              <select class="form-control" id="usuarioAsignado" name="usuarioAsignado" >
                <?php
             include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/personal/sql_personal.php' );
                $datos_personal = select_personal( $conexion );
                while ( $mostrar = mysqli_fetch_array( $datos_personal) ) {
               print '
    <option value="' . strtoupper($mostrar["id_personal"]) . '">
        ' . strtoupper($mostrar["nombre"] . ' ' . $mostrar["a_paterno"] . ' ' . $mostrar["a_materno"]) . '
    </option>
';

                }
                ?>
              </select>
                </div>

              </div>
	<div class="row justify-content-center justify-content-md-start">
            <div class="col mb-3">
              <label for="descripcion">Características generales</label>
              <textarea class="form-control" rows="2" d="caracteristicas" name="caracteristicas">
					</textarea>
            </div>
            </div>
           
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

	 function actualizarNombreArchivos() {
    var inputArchivos = document.getElementById('customFile');
    var labelArchivos = document.querySelector('.custom-file-label');
    var nombresArchivos = Array.from(inputArchivos.files).map(file => file.name);
    labelArchivos.innerHTML = nombresArchivos.join(', ') || 'Selecciona archivos';
  }
</script>
