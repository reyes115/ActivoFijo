
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
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">    
				  <label for="descripcion">Descripción</label>
              <input type="text" class="form-control" id="descripcion" name="descripcion" >
            </div>
			  </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">   
			          <label for="marca">Marca</label>
              <input type="text" class="form-control" id="marca" name="marca" >
            </div>
            <div class="col-sm mb-3">   
				 <label for="serie">Serie</label>
              <input type="text" class="form-control" id="serie" name="serie" >
            </div>
			  </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">    
			  <label for="modelo">Modelo</label>
              <input type="text" class="form-control" id="modelo" name="modelo" >
          
            </div>
			  </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">    
				   <label for="no_factura">Número Factura</label>
              <input type="text" class="form-control" id="no_factura" name="no_factura" >					
            </div>
            <div class="col-sm mb-3">    
				   <label for="val_factura">Valor factura</label>
              <input type="text" class="form-control" id="val_factura" name="val_factura" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">		
            </div>
			  </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">    
				 <label for="empresa">Empresa</label>
              <select class="form-control" id="empresa" name="empresa" required>
				  <?php           
                $valores = view_empresa( $conexion );
                while ( $ver = mysqli_fetch_array( $valores ) ) {
                    print '
            <option  name="empresa" id="empresa" value="' . $ver[ "id_empresa" ] . '">' . $ver[ "nombre" ] . '</option>
            ';
                }
                ?>
              </select>				
            </div>
            <div class="col-sm mb-3">    
				   <label for="area">Área responsable </label>
              <select class="form-control" id="area" name="area" required>
                <option id="area" name="area" value="1">Mantenimiento</option>
                <option id="area" name="area" value="2">Maquinados</option>
                <option id="area" name="area" value="3">Producción</option>
              </select>		
            </div>
			  </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">       
				   <label for="estado">Estado</label>
              <select  class="form-control" id="estado" name="estado"  >
                <option id="estado" name="estado" value="1">En operación</option>
                <option id="estado" name="estado" value="2">Fuera de operación</option>
                <option id="estado" name="estado" value="3">En reparación</option>
                <option id="estado" name="estado" value="4">Inservible</option>
              </select>
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
