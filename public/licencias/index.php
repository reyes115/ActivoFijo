<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] .'/assets/licencias/sql_licencias.php' );
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    // Verifica si se hizo clic en el botón de inserción
    if ( isset( $_POST[ 'insertar' ] ) ) {
        // Recoge los valores de los campos del formulario
        // Recibo los datos de la imagen
        $nombre_licencias = trim( $_POST[ 'nombre_licencias' ] );
        $costo = trim( $_POST[ 'costo' ] );
        $fecha_inicio = trim( $_POST[ 'fecha_inicio' ] );
        $fecha_fin = trim( $_POST[ 'fecha_fin' ] );
        $clave = trim( $_POST[ 'clave' ] );
        $tipo = $_POST[ 'tipo' ] ;
        $limite_usuarios = trim( $_POST[ 'limite_usuarios' ] );
        $provedor= trim( $_POST[ 'provedor' ] );
        $observaciones= trim( $_POST[ 'observaciones' ] );

        // Llama a la función consulta2() para insertar los datos
        insert_licencias( $conexion, $nombre_licencias, $fecha_inicio, $fecha_fin,$clave, $limite_usuarios,$costo, $tipo, $observaciones, $provedor);
    }
    // Verifica si se hizo clic en el botón de eliminar
    if ( isset( $_POST[ 'eliminar' ] ) ) {
        // Recoge los valores de los campos del formulario
        $id = $_POST[ 'id' ];
        // Llama a la función consulta3() para insertar los datos
        delete_licencias( $conexion, $id );
    }
}

$encabezados = array( "ID", "Nombre", "Clave (Serial)", "Tipo", "Costo", "Fecha de activación","Fecha de vencimiento","Proveedor", "Ver más" );

$datos_licencias = view_licencias( $conexion );

if ( $access[ 'licencias' ] == 0 ) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}
?><style>
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
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>LICENCIAS DE SOFTWARE</strong></h1>
    <!--Botones-->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <button class="btn btn-dark btn-sm" type="button" data-toggle="modal" data-target="#ModalColumnas">Columnas</button>
        <?php
        if ( $access[ 'licencias' ] == 1 ) {
            ?>
        <a typpe="button" class="btn bg-secundario btn-icon-split btn-sm" data-toggle="modal" data-target="#nuevoModal"> <span class="icon text-white-50"> <i class="fas fa-plus"></i> </span> <span class="text text-white">Nuevo Registro</span> </a>
        <?php
        }
        ?>
        <button id="exportButton" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"> <i class="fas fa-download fa-sm text-white-50"></i> Exportar a Excel</button>
      </div>
		<div class="card-body">
        <div class="table-responsive" >
          <table class=" table table-bordered display table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: 12px;">
            <thead>
              <tr >
                <?php
                foreach ( $encabezados as $encabezado ) {
                    ?>
                <th class="bg-principal text-white" style="vertical-align: middle;"<?php if($encabezado== 'ID'){ echo 'width= "10%"'; }?> ><?php echo $encabezado ?></th>
                <?php
                }
                if ( $access[ 'licencias' ] == 1 ) {
                    ?>
                <th class="bg-principal text-white" style="vertical-align: middle;">Eliminar</th>
                <?php
                }
                ?>
              </tr>
            </thead>
            <tbody>
				<?php
				  while ( $row = mysqli_fetch_array( $datos_licencias ) ) {
 switch ( $row[ 'tipo' ] ) {
                      case 1:
                          $tipo = "Antivirus";
                          break;
                      case 2:
                          $tipo = "Office";
                          break;
                      case 3:
                          $tipo = "Sistema operativo";
                          break;
                      case 4:
                          $tipo = "Otro";
                          break;
                  }
                  $id_registro = $row[ 'id_licencias' ];
                  $name_delete = $row[ 'nombre_licencias' ];
			
                  ?>
            <tr ondblclick="abrirEnlace('ver_licencias<?php echo $id_registro ?>')" >
                <td class="text-dark"><?php echo $row[ 'id_licencias' ] ?></td>        
                <td><?php echo $row[ 'nombre_licencias' ] ?></td>
                <td><?php echo $row[ 'clave' ] ?></td>
                <td><?php echo $tipo?></td>
                <td><?php echo $row[ 'costo' ] ?></td>
                <td><?php echo $row[ 'fecha_inicio' ] ?></td>
                <td><?php echo $row[ 'fecha_fin' ] ?></td>
                <td><?php echo $row[ 'provedor' ] ?></td>
                <td align="center"><acronym title="Ver más "> <a href="ver_licencias<?php echo $id_registro ?>" class="btn btn-success  btn-sm" > <i class="fas fa fa-share"></i></a></acronym></td>
                <?php

                if ( $access[ 'licencias' ] == 1 ) {
                    ?>
                <td align="center"><a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete<?php echo $id_registro;?>"> <i class="fas fa-trash"></i></a></td>
                <?php
                }
                ?>
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
<?php
$cell_tipo = 'licencias';
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/columnas.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/licencias/new_licencias.php' );
?>
<script>

    function abrirEnlace(enlace) {
        // Hacer algo con el enlace, por ejemplo, redirigir a la URL correspondiente
        window.location.href = enlace;
    }
</script> 
<script src="excel"></script> 
<script src="TablaLicenciasJs"></script> 
<script src="aplicarConfiguraciones"></script>
<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>