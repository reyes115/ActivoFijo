<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/usuarios/sql.php' );
$v_android = view_android( $conexion );
if ( $access[ 'android' ] == 0 ) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    // Verifica si se hizo clic en el botón de eliminar
    if ( isset( $_POST[ 'eliminar' ] ) ) {
        // Recoge los valores de los campos del formulario
        $id = $_POST[ 'id' ];
        // Llama a la función consulta3() para insertar los datos
        delete_app( $conexion, $id );
    } 
	else if ( isset( $_POST[ 'insert' ] ) ) {
        // Recoge los valores de los campos del formulario
        $version = $_POST[ 'version' ];
        $id = $_POST[ 'versionCode' ];
        $id = $_POST[ 'versionCode' ];
        // Llama a la función consulta3() para insertar los datos
        delete_app( $conexion, $id );
    }
}

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
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>REGISTRO DE COLABORADORES</strong></h1>
    <!--Botones-->
    <div class="card shadow mb-4">
      <div class="card-header py-3"> <a type="button" class="btn bg-secundario btn-icon-split btn-sm" data-toggle="modal" data-target="#nuevoModal"> <span class="icon text-white-50"> <i class="fas fa-plus"></i> </span> <span class="text text-white">Nuevo Registro</span> </a> </div>
      <div class="card-body">
        <div class="table-responsive" >
          <table class=" table table-bordered display table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: 12px;">
            <thead>
              <tr >
                <th class="bg-principal text-white" style="vertical-align: middle;" >ID</th>
                <th class="bg-principal text-white" style="vertical-align: middle;" >Nombre del archivo</th>
                <th class="bg-principal text-white" style="vertical-align: middle;" >Version Name</th>
                <th class="bg-principal text-white" style="vertical-align: middle;" >Version Code</th>
                <th class="bg-principal text-white" style="vertical-align: middle;" >Release Notes</th>
                <th class="bg-principal text-white" style="vertical-align: middle;" >Fecha de Lazamiento</th>
                <th class="bg-principal text-white" style="vertical-align: middle;" >Fecha de Terminacion</th>
                <th class="bg-principal text-white" style="vertical-align: middle;">Eliminar</th>
              </tr>
            </thead>
            <tbody>
              <?php
              while ( $row = mysqli_fetch_array( $v_android ) ) {
                  $id_registro = $row[ 'id_version' ];
                  if ( $row[ 'fecha_terminacion' ] == NULL ) {
                      $f_t = 'En linea';
                  } else {
                      $f_t = $row[ 'fecha_terminacion' ];
                  }
                  // Dividir el texto en una lista usando el guion como delimitador
                  $items = explode( "-", $row[ 'releaseNotes' ] );
                  $items = array_filter( array_map( 'trim', $items ) );
                  ?>
              <tr>
                <td class="text-dark"><?php echo $row[ 'id_version' ] ?></td>
                <td><?php echo $row[ 'apk_url' ]?></td>
                <td><?php echo $row[ 'version' ] ?></td>
                <td><?php echo $row[ 'versionCode' ] ?></td>
                <td><ul>
                    <?php foreach ($items as $item): ?>
                    <li><?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                  </ul></td>
                <td><?php echo $row[ 'fecha_lazamiento' ] ?></td>
                <td><?php echo $f_t ?></td>
                <td align="center"><a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete<?php echo $id_registro;?>"> <i class="fas fa-trash"></i></a></td>
              </tr>
              <?php
              include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/edit/delete.php' );
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!--Modal new-->

<div class="modal fade" id="nuevoModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header bg-principal">
        <h5 class="modal-title text-white" id="newModalLabel">Subir nueva APK</h5>
        <button type="button" class="close  text-white" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="observaciones">Archivo de actualización</label>
              <div class="custom-file mb-3">
                <input type="file" class="custom-file-input" id="apk" name="apk" accept=".apk" onchange="actualizarNombreArchivo('apk')">
                <label class="custom-file-label" for="apk">Suelta el achivo .apk aqui. <i class="fa fa-upload"></i></label>
              </div>
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="version">Version Name</label>
              <input type="text" class="form-control" id="version" name="version">
            </div>
            <div class="col-sm mb-3">
              <label for="versionCode">Version Code</label>
              <input type="text" class="form-control" id="versionCode" name="versionCode">
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="notas">Introduce tus notas de release (separadas por guiones '-' y terminado con '.')</label>
              <br>
              <textarea id="notas" name="notas" rows="5" cols="50" class="form-control"></textarea>
            </div>
          </div>
         
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="fecha_lazamiento">Fecha de lazamiento</label>
              <input type="date" class="form-control" id="fecha_lazamiento" name="fecha_lazamiento">
            </div>
         
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" name="insert" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
 function actualizarNombreArchivo(idInput) {
    var inputArchivo = document.getElementById(idInput);
    var labelArchivo = document.querySelector('label[for="' + idInput + '"]');
    var nombreArchivo = inputArchivo.files[0].name;
    labelArchivo.innerHTML = nombreArchivo || 'Selecciona un archivo <i class="fa fa-upload"></i>';
  }
</script>
<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>
