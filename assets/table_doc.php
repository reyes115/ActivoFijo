<!--card-->
<div class="card shadow mb-4">
  <div class="card-header py-3"><strong>DOCUMENTACIÓN (Opcional)</strong></div>
  <div class="card-body">
    <?php if ($access[$registro] == 1) : ?>
    <div class="row">
      <div class="col">
        <div class="custom-file mb-3">
          <input type="file" class="custom-file-input" id="customFile" name="archivos[]" multiple onchange="actualizarNombreArchivos()">
          <label class="custom-file-label" for="customFile">Suelta los archivos aqui. <i class="fa fa-upload"></i></label>
        </div>
      </div>
    </div>
    <?php endif; ?>
    <!-- Tabla de archivos-->
    <?php
    
    $directorio = $_SERVER[ 'DOCUMENT_ROOT' ] . "/uploads/" . $registro . '/' . $codigo;
    if ( !file_exists( $directorio ) ) {
        mkdir( $directorio, 0777 )or die( "No se puede crear el directorio de extracci&oacute;n" );
    }
    $directorio2 = "/uploads/" . $registro . '/' . $codigo . "/";
    ?>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered display table-hover">
          <thead>
            <tr align="center">
              <th scope="col">#</th>
              <th scope="col">Nombre del archivo</th>
              <th colspan="3">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $archivos = scandir($directorio);

            // Filtrar los archivos "." y ".."
            $archivos = array_diff($archivos, array());
            // Contador inicial
            $contador = 1;

            foreach ($archivos as $archivo) : 
			  if ($archivo != '.' && $archivo != '..' && $archivo != 'Thumbs.db') {?>
              <tr align="center">
                <td ><?php echo $contador ?></td>
                <td><?php echo $archivo ?></td>
                <td>
                  <?php
                  $extension = pathinfo($archivo, PATHINFO_EXTENSION);
                  $fileUrl = $directorio2 . $archivo;

                switch ($extension) {
    case 'pdf':
        $modalClass = 'open-pdf-modal btn-primary';
        $modalTarget = '#pdfModal';
						$icon="fa-eye";
						 // Añadir la condición para Android aquí
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if (strpos($userAgent, 'Android') !== false && strpos($userAgent, 'com.example.ab_forti') !== false) {
               $modalClass = 'download-file btn-info';
        $modalTarget = '';
						$icon="fa-download";
        }
        break;
    case 'jpg':
    case 'jpeg':
    case 'png':
    case 'gif':
        $modalClass = 'open-image-modal btn-primary';
        $modalTarget = '#imageModal';
						$icon="fa-eye";
        break;
    default:
        $modalClass = 'download-file btn-info';
        $modalTarget = '';
						$icon="fa-download";
        break;
}
?>
<a class="btn  <?php echo $modalClass; ?>" 
   <?php if ($modalClass === 'download-file btn-info') : ?>
       href="<?php echo $fileUrl; ?>" download="<?php echo $archivo; ?>"
   <?php else : ?>
       data-file="<?php echo $fileUrl; ?>" data-toggle="modal" data-target="<?php echo $modalTarget; ?>"
   <?php endif; ?>
   data-toggle="tooltip" data-placement="top" title="Ver archivo"> 
	
	
   <i class="fas fa <?php echo $icon; ?>"></i> 
	
	
</a>
                </td>
                <?php if ($access[$registro] == 1) : ?>
                  <td>
                    <button type="button" class="btn btn-danger delete-file" data-toggle="modal" data-target="#deleteModal<?php echo $contador ?>" data-toggle="tooltip" data-placement="top" title="Eliminar archivo <?php echo $archivo ?>"> <i class="fas fa-trash"></i> </button>
                  </td>
                <?php 
																				  
					  endif; }?>
              </tr>
              <!-- Modal de eliminación -->
              <div class="modal fade" id="deleteModal<?php echo $contador ?>" tabindex="-1" role="dialog" aria-labelledby="bloquearLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header bg-success">
                      <h5 class="modal-title text-white" id="bloquearLabel">Eliminar Archivo</h5>
                      <button type="button" class="close  text-white" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                    </div>
                    <div class="modal-body" align="justify">
                      <p>
                        ¿Deseas eliminar el archivo: <b><?php echo $archivo ?></b> ?
                      </p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                      <form method="post" action="DeleteDoc" enctype="multipart/form-data">
                        <input type="hidden" name="borrarArchivo" value="<?php echo $archivo ?>">
                        <input type="hidden" name="rutaArchivo" value="<?php echo $directorio2 ?>">
                        <input type="hidden" name="contador" value="<?php echo $contador ?>">
                        <button type="submit" class="btn btn-primary" name="deletedoc">Sí</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            <?php
              $contador++;
            endforeach;
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
