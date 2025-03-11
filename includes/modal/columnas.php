<!---modal seleccionar ver modals-->

<div class="modal fade" id="ModalColumnas" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title text-white" id="modalLabel">Mostrar/Ocultar Columnas</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">
        <div id="opciones">
          <?php
          foreach ( $encabezados as $encabezado ) {
            ?>
          <label>
            <input type="checkbox" class="columna" <?php if($encabezado== 'ID' || $encabezado== 'Acciones'){ echo 'checked disabled'; }?>>
            <?php echo $encabezado ?> </label>
          <?php
          }
			if($access[$cell_tipo]==1 ){
				
				?>
			<label>
            <input type="checkbox" class="columna">
            Eliminar </label>
			<?php
			}
          ?>
          
        </div>
        <br>
        <button onclick="marcarTodos()" class="btn btn-sm btn-info">Marcar todos</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="aplicarConfiguraciones();" data-dismiss="modal">Aplicar</button>

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>