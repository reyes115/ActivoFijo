
<div class="modal fade" id="editModal<?php echo $id_registro;?>" tabindex="-1" role="dialog" aria-labelledby="newModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header bg-principal">
        <h5 class="modal-title text-white" id="newModalLabel">Editar Registro</h5>
        <button type="button" class="close  text-white" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">
          <div class="row justify-content-center justify-content-md-start">
			   <input type="hidden" class="form-control" id="id_pass" name="id_pass" required value="<?php echo $row['id_pass'] ?>">
            <div class="col-sm mb-3">
              <label for="tipo">Tipo</label>
              <select class="form-control" id="tipo" name="tipo" required>
                <option id="tipo" name="tipo" value="">Seleccione</option>
                <option id="tipo" name="tipo" value="1" <?php if ($row['tipo'] == 1) echo 'selected'; ?>>Correo</option>
                <option id="tipo" name="tipo" value="2" <?php if ($row['tipo'] == 2) echo 'selected'; ?>>Equipo</option>
                <option id="tipo" name="tipo" value="3" <?php if ($row['tipo'] == 3) echo 'selected'; ?>>Wi-Fi</option>
                <option id="tipo" name="tipo" value="4" <?php if ($row['tipo'] == 4) echo 'selected'; ?>>Sistemas</option>
                <option id="tipo" name="tipo" value="5" <?php if ($row['tipo'] == 5) echo 'selected'; ?>>Dominio</option>
                <option id="tipo" name="tipo" value="6" <?php if ($row['tipo'] == 6) echo 'selected'; ?>>Otros</option>
              </select>
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="usuario">Nombre / Usuario</label>
              <input type="text" class="form-control" id="usuario" name="usuario" required value="<?php echo $row['usuario-email'] ?>">
            </div>
            <div class="col-sm mb-3">
              <label for="password">Contraseña</label>
              <input type="text" class="form-control" id="password" name="password" required value="<?php echo $row['password'] ?>">
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col mb-3">
              <label for="descripcion">Descripción generales</label>
              <textarea class="form-control" rows="2" d="descripcion" name="descripcion" ><?php echo $row['descripcion'] ?></textarea>
            </div>
          </div>u
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" name="editar" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
