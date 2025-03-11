
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
           <label for="nombre_licencias">Nombre de la licencia</label>
                  <input type="text" class="form-control" id="nombre_licencias" name="nombre_licencias" required>
               </div>         
                <div class="col-sm mb-3">
                  <label for="costo">Costo</label>
                  <input type="text" class="form-control" id="costo" name="costo" required  onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
              </div>
              </div>
        <div class="row justify-content-center justify-content-md-start">
                <div class="col-sm mb-3">
                   <label for="fecha_inicio">Fecha de compra</label>
                  <input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio">
                </div>
                <div class="col-sm mb-3">
                 <label for="fecha_fin">Fecha de renovación</label>
                  <input type="text" class="form-control" id="fecha_fin" name="fecha_fin">
                </div>
          </div>
        <div class="row justify-content-center justify-content-md-start">
                <div class="col-sm mb-3">
                  <label for="clave">Clave(Serial)</label>
                  <input type="text" class="form-control" id="clave" name="clave" >
                </div>
                <div class="col-sm mb-3">
                    <label for="tipo">Tipo </label>
                  <select class="form-control" id="tipo" name="tipo" required>
                    <option id="tipo" name="tipo" value="">Seleccione</option>
                    <option id="tipo" name="tipo" value="1">Antivirus</option>
                    <option id="tipo" name="tipo" value="2">Office</option>
                    <option id="tipo" name="tipo" value="3">Sistema operativo</option>
                    <option id="tipo" name="tipo" value="4">Otro</option>
                  </select>
                </div>

              </div>	
        <div class="row justify-content-center justify-content-md-start">
                <div class="col-sm mb-3">
                   <label for="limite_usuarios">Límite de usuarios</label>
                  <input type="text" class="form-control" id="limite_usuarios" name="limite_usuarios"  maxlength="12" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                </div>
                <div class="col-sm mb-3">
                   <label for="provedor">Proveedor</label>
					 <input type="text" class="form-control" id="provedor" name="provedor" maxlength="50">
                </div>

              </div>
        <div class="row justify-content-center justify-content-md-start">
                <div class="col-sm mb-3">
                   <label for="observaciones">Observaciones</label>
                  <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
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
