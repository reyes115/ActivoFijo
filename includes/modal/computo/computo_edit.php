       <!-- Modal Delete-->
        <div class="modal fade" id="delete<?php echo $id_registro;?>" tabindex="-1" role="dialog" aria-labelledby="bloquearLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header bg-principal">
                <h5 class="modal-title text-white" id="newModalLabel">Eliminar Registro</h5>
                <button type="button" class="close  text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">
                &times; 
                </span>
                </button>
              </div>
              <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                  <input type="hidden" name="id" id="id" value="<?php echo $id_registro;?>">
                  <div class="form-group mb-2">
                    <p>¿Deseas eliminar el registro seleccionado?</p>
                  </div>
                    <div class="form-group mb-2" align="center">
                    <b><?php echo $name_delete;?></b>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
                    <button type="submit" class="btn btn-primary" name="eliminar">SI</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!--END modal-->