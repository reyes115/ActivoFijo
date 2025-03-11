<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/usuarios/sql.php' );
$usuarios = view_usuarios( $conexion );
?>
<style>
.hidden {
    display: none;
}
.boton {
    display: inline-block;
}
#opciones {
    display: flex;
    flex-wrap: wrap;
}
#opciones label {
    display: inline-block;
    width: 50%;
}
</style>
<div class="layoutSidenav_content">
  <div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>USUARIOS</strong></h1>
    <div class="card shadow mb-4">
      <div class="card-header py-3"> <a href="new_usuario" class="btn bg-secundario btn-icon-split btn-sm"> <span class="icon text-white-50"> <i class="fas fa-plus"></i> </span> <span class="text text-white">Nuevo Registro</span> </a> </div>
      <div class="card-body">
        <div class="table-responsive" >
          <table class=" table table-bordered display table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: 12px;">
            <thead>
              <tr style="vertical-align: middle;">
                <th class="bg-principal text-white" >ID</th>
                <th class="bg-principal text-white" >Nombre </th>
                <th class="bg-principal text-white" >Usuario </th>
                <th class="bg-principal text-white" >Contraseña </th>
                <th class="bg-principal text-white" >Ver más</th>
                <th class="bg-principal text-white" >Bloquear</th>
              </tr>
            </thead>
            <tbody>
              <?php
              while ( $mostrar = mysqli_fetch_array( $usuarios ) ) {


                  switch ( $mostrar[ 'activo' ] ) {
                      case 1:
                          $activo = '
			 <acronym title="Bloquear"> <button type="button"  class="btn btn-danger  btn-sm bloquear-usuario" data-toggle="modal" data-target="#bloquear" data-idusuario= "' . $mostrar[ 'id_usuarios' ] . '" > <i class="fas fa-ban"></i> </button></acronym>
			 

				';
                          break;
                      case 0:
                          $activo = '
			       <acronym title="Activar" >
					<button type="button"  class="btn btn-success   btn-sm activar-usuario" data-toggle="modal" data-target="#activar"  data-idusuario= "' . $mostrar[ 'id_usuarios' ] . '">
						
 							<i class="fas fa fa-check-circle"></i>
 						
 					</button></acronym>
					
					
	';
                          break;
                  }

                  if ( $mostrar[ 'email' ] == $_SESSION[ 'user_ceers' ] ) {

                      $activo = ' <acronym title="Activar" >
					<a  class="btn btn-info   btn-sm" data-toggle="modal" data-target="#error">
						
 							<i class="fas fa fa-circle-o-notch"></i>
 						
 					</a></acronym>';
                  }

                  ?>
              <tr>
                <td><?php echo $mostrar[ 'id_usuarios' ] ?></td>
                <td><?php echo $mostrar[ 'nombre' ]?></td>
                <td><?php echo $mostrar[ 'email' ]?></td>
                <td><?php echo $mostrar[ 'password' ]?></td>
                <td align="center"><acronym title="Ver mas"> <a href="ver_usuario<?php echo $mostrar[ 'id_usuarios' ] ?>" class="btn btn-success  btn-sm" > <i class="fas  fa-edit"></i></a></acronym></td>
                <td align="center"><?php echo $activo ?></td>
              </tr>
		
              <?php
					  
              }

              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
		    <!-- Modal -->
          <div class="modal fade" id="bloquear" tabindex="-1" role="dialog" aria-labelledby="bloquearLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content" >
                <div class="modal-header bg-success">
                  <h5 class="modal-title text-white" id="bloquearLabel">Bloquear usuario</h5>
                  <button type="button" class="close  text-white" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
				  <form method="post" action="c_accesos" enctype="multipart/form-data">
                <div class="modal-body" align="justify"> ¿Deseas bloquer el acceso de este usuario al sistema?
                  
					 <input type="hidden"  id="id_usuarioB" name="id_usuarioB" required value="">
					 <input type="hidden"  id="accion" name="accion" required value="bloquear">
					
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                  <button type="submit" type="button" class="btn btn-primary" >Si</button> 
				  </div>
				  </form>
              </div>
            </div>
          </div>
          
          <!-- Modal -->
          <div class="modal fade" id="activar" tabindex="-1" role="dialog" aria-labelledby="activarLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content" >
                <div class="modal-header bg-success">
                  <h5 class="modal-title text-white" id="activarLabel">Activar usuario</h5>
                  <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
				   <form method="post" action="c_accesos" enctype="multipart/form-data">
					   <input type="hidden"  id="id_usuarioA" name="id_usuarioA" required value="">
					 <input type="hidden"  id="accion" name="accion" required value="activar">
                <div class="modal-body" align="justify"> Este usuario anteriormente se le bloqueo el acceso al sistema.
                  <p>¿Desea permitirle nuevamente el acceso?</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                   <button type="submit" type="button" class="btn btn-primary" >Si</button>  </div>
					     </form>
              </div>
            </div>
          </div>
<!-- Modal -->
<div class="modal fade" id="error" tabindex="-1" role="dialog" aria-labelledby="errorLabel" aria-hidden="true">
  <div class="modal-dialog modal-3 modal-dialog-centered" >
    <div class="modal-content" >
      <div class="modal-header bg-success">
        <h5 class="modal-title text-white" id="errorLabel">TU ERES YO, Y YO SOY TU </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body" align="justify">No puedes bloquear este usuario, ya que es tu usuario </div>
      <div class="modal-footer" data-dismiss="modal"> </div>
    </div>
  </div>
</div>
<script>
	 $(document).on('click', '.bloquear-usuario', function() {
        var idusuario = $(this).data('idusuario');
      
		 
        $('#id_usuarioB').val(idusuario);
   
    });
	 $(document).on('click', '.activar-usuario', function() {
        var idusuario = $(this).data('idusuario');
      
		 
        $('#id_usuarioA').val(idusuario);
   
    });
</script>
<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>
