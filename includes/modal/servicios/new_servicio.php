
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
              <label for="no_cuenta">Número de cuenta</label>
              <input type="text" class="form-control" id="no_cuenta" name="no_cuenta"  required>
            </div>
			  <div class="col-sm mb-3">
              <label for="proveedores">Proveedor</label>
              <input type="text" class="form-control" id="proveedores" name="proveedores"  >
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">            
                  <label for="fecha_inicio">Fecha de contratación del servicio</label>
                  <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
            </div>
			  </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">        
                  <label for="fecha_renova">Fecha (Día) de ronavación</label>
                  <input type="text" class="form-control" id="fecha_renova" name="fecha_renova">
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
			  <div class="col-sm mb-3">  
             <label for="costo_renova">Costo de renovación</label>
                  <input type="text" class="form-control" id="costo_renova" name="costo_renova" required  onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
          </div> 
	
			 <div class="col-sm mb-3">        
                  <label for="ubicacion">Ubicación</label>
                  <input type="text" class="form-control" id="ubicacion" name="ubicacion">
            </div>
			</div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col mb-3">
              <label for="detalles">Características generales</label>
              <textarea class="form-control" rows="2" d="detalles" name="detalles" required></textarea>
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
