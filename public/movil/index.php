<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/movil/sql_movil.php' );

// Si se ha enviado el formulario
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
// Verifica si se hizo clic en el botón de eliminar
  if ( isset( $_POST[ 'eliminar' ] ) ) {
    // Recoge los valores de los campos del formulario
    $id = $_POST[ 'id' ];
    // Llama a la función consulta3() para insertar los datos
    delete_moviles( $conexion, $id );
  }
}

$encabezados = array( "ID", "Marca", "Modelo", "No. Serie", "IMEI", "No. Telefónico", "Región",  "Color", "Cargador","Estado", "Usuario Asignado", "Ver más" );

$datos_cel = view_moviles( $conexion );

if ( $access[ 'moviles' ] == 0 ) {
  // Script de redirección con JavaScript
  echo '<script type="text/javascript">window.location.href = "inicio"</script>';
  exit;
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
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>EQUIPOS MÓVILES</strong></h1>
    <!--Botones-->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <button class="btn btn-dark btn-sm" type="button" data-toggle="modal" data-target="#ModalColumnas">Columnas</button>
        <?php
        if ( $access[ 'moviles' ] == 1 ) {
          ?>
        <a href="new_moviles" class="btn bg-secundario btn-icon-split btn-sm"> <span class="icon text-white-50"> <i class="fas fa-plus"></i> </span> <span class="text text-white">Nuevo Registro</span> </a>
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
                if ( $access[ 'moviles' ] == 1 ) {
                  ?>
                <th class="bg-principal text-white" style="vertical-align: middle;">Eliminar</th>
                <?php
                }
                ?>
              </tr>
            </thead>
            <tbody>
              <?php
              while ( $row = mysqli_fetch_array( $datos_cel ) ) {
              
				 $codigoQR=$row[ 'QRKey' ];
				  $id_registro=$row[ 'id_celular' ];
				  $name_delete = $row[ 'codigo' ];
                ?>
              <tr ondblclick="abrirEnlace('ver_movil<?php echo $codigoQR ?>')">
                <td class="text-dark"><?php echo $row[ 'codigo' ] ?></td>
                <td><?php echo $row[ 'marca' ] ?></td>
                <td><?php echo $row[ 'modelo' ] ?></td>
                <td><?php echo $row[ 'no_serie' ] ?></td>
                <td><?php echo $row[ 'imei' ] ?></td>
                <td><?php echo $row[ 'numero_tel' ] ?></td>
                <td><?php echo $row[ 'region' ] ?></td>
                <td><?php echo $row[ 'color' ] ?></td>
                <td><?php echo $row[ 'no_cargador' ] ?></td>
                <td><?php echo $row[ 'estado' ] ?></td>
                <td><?php $nombre_completo = $row[ "nombre" ] . ' ' . $row[ "a_paterno" ] . ' ' . $row[ "a_materno" ];
                $nombre_completo_en_mayusculas = mb_strtoupper( $nombre_completo, 'UTF-8' );
                echo $nombre_completo_en_mayusculas;?></td>
                <td align="center"><acronym title="Ver más "> <a href="ver_movil<?php echo $codigoQR ?>" class="btn btn-success  btn-sm" > <i class="fas fa fa-share"></i></a></acronym></td>
                <?php

                if ( $access[ 'moviles' ] == 1 ) {
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
$cell_tipo = 'moviles';
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/columnas.php' );

?>
<script>
    function abrirEnlace(enlace) {
        // Hacer algo con el enlace, por ejemplo, redirigir a la URL correspondiente
        window.location.href = enlace;
    }
</script>

<script src="excel"></script> 
<script src="TablaMovilJs"></script> 
<script src="aplicarConfiguraciones"></script>
<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>