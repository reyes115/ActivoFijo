<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.js"></script>

<div class="modal fade" id="nuevoModal" tabindex="-1" role="dialog" aria-labelledby="newModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-principal">
        <h5 class="modal-title text-white" id="newModalLabel">Nuevo Registro</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body container-fluid">
        <form method="post" enctype="multipart/form-data">
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="propietario">Contratante</label>
              <select class="form-control" id="propietario" name="propietario" required >
                <option value="">SELECCIONE</option>
                <?php
                include($_SERVER['DOCUMENT_ROOT'] . '/assets/propietarios.php');
                $propietarios = view_propietarios($conexion);
                while ($ver_propietarios = mysqli_fetch_array($propietarios)) {
                  echo '<option value="' . $ver_propietarios["id_propietario"] . '">' . $ver_propietarios["nombre"] . '</option>';
                }
                ?>
              </select>
            </div>
          
            <div class="col-sm mb-3">
              <label for="t_asegurado">Tipo de asegurado</label>
              <select class="form-control" id="t_asegurado" name="t_asegurado" required >
                <option >Seleccione </option>
                <option value="1">Auto</option>
                <option value="2">Colaborador</option>
                <option value="3">Inmobiliario</option>
              </select>
            </div>
      
            <div class="col-sm mb-3">
              <label for="asegurado">Asegurado</label>
              <br>
              <select class="form-control" id="asegurado" name="asegurado" required >
                <option name="asegurado" id="asegurado" value="">Seleccione un tipo de asegurado</option>
              </select>
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="empresa">Empresa</label>
              <select class="form-control" id="empresa" name="empresa" required >
                <?php
                $valores = view_empresa($conexion);
                while ($ver = mysqli_fetch_array($valores)) {
                  print '<option name="empresa" id="empresa" value="' . $ver["id_empresa"] . '">' . $ver["nombre"] . '</option>';
                }
                ?>
              </select>
            </div>
         
            <div class="col-sm mb-3">
              <label for="tipo">Tipo</label>
              <select class="form-control" id="tipo" name="tipo" required >
                <option value="1">Auto</option>
                <option value="2">Vida</option>
                <option value="3">Gastos medicos</option>
                <option value="4">Daños</option>
              </select>
            </div>
        
            <div class="col-sm mb-3">
              <label for="no_poliza">Número</label>
              <input type="text" class="form-control" id="no_poliza" name="no_poliza" required>
            </div>
            <div class="col-sm mb-3">
              <label for="aseguradora">Aseguradora</label>
              <input type="text" class="form-control" id="aseguradora" name="aseguradora" required>
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="f_pago">Formato de pago</label>
              <select class="form-control" id="f_pago" name="f_pago" required >
                <option value="1">Anual</option>
                <option value="2">Semestral</option>
              </select>
            </div>
        
            <div class="col-sm mb-3">
              <label for="inicio_vigencia">Fecha de inicio de vigencia</label>
              <input type="date" id="inicio_vigencia" name="inicio_vigencia" class="form-control" required>
            </div>
            <div class="col-sm mb-3">
              <label for="fin_vigencia">Fecha de fin de vigencia</label>
              <input type="date" id="fin_vigencia" name="fin_vigencia" class="form-control">
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="moneda">Moneda</label>
              <select class="form-control" id="moneda" name="moneda" required>
                <option value="1">Nacional</option>
                <option value="2">Dolar</option>
              </select>
            </div>
            <div class="col-sm mb-3">
              <label for="total">Total a pagar</label>
              <input type="text" class="form-control" id="total" name="total" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required>
            </div>
          </div>
			    <div class="row justify-content-center justify-content-md-start">
                 <div class="col-sm mb-3">
                  <label for="prima_neta">Prima neta</label>
                  <input type="text" class="form-control" id="prima_neta" name="prima_neta" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required>
                </div>
				  <div class="col-sm mb-3">
                  <label for="derecho_poliza">Derecho de poliza</label>
                  <input type="text" class="form-control" id="derecho_poliza" name="derecho_poliza" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required>
                </div>
				  <div class="col-sm mb-3">
                  <label for="iva">IVA</label>
                  <input type="text" class="form-control" id="iva" name="iva" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required>
                </div>
				  <div class="col-sm mb-3">
                  <label for="suma_asegurada">Suma asegurada</label>
                  <input type="text" class="form-control" id="suma_asegurada" name="suma_asegurada" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required>
                </div>
				  
              </div>
          <div class="py-3">
            <strong>DOCUMENTACIÓN (Opcional)</strong>
          </div>
          <div class="row">
            <div class="col">
              <div class="custom-file mb-3">
                <input type="file" class="custom-file-input" id="customFile" name="archivos[]" multiple onchange="actualizarNombreArchivos()">
                <label class="custom-file-label" for="customFile">Suelta los archivos aquí. <i class="fa fa-upload"></i></label>
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
