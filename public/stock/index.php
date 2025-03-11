<?php 

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/stock/sql_stock.php' );
// Si se ha enviado el formulario
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
	  if ( isset( $_POST[ 'insertar' ] ) ) {
        // Recoge los valores de los campos del formulario
	
	
$propietario =$_POST['propietario'];
$tipo =trim($_POST['tipo']);
$caracteristicas=trim($_POST['caracteristicas']);
$estado=$_POST['estado'];
$cantidad=$_POST['cantidad'];
$almacenamiento =$_POST['opt'];
$usuarioAsignado =$_POST['usuarioAsignado'];
	
		  
 // Llama a la función consulta2() para insertar los datos
 insert_stock( $conexion,$propietario, $tipo,$caracteristicas,$estado,$cantidad,$almacenamiento,$usuarioAsignado);
    }
// Verifica si se hizo clic en el botón de eliminar
  if ( isset( $_POST[ 'eliminar' ] ) ) {
    // Recoge los valores de los campos del formulario
    $id = $_POST[ 'id' ];
    // Llama a la función consulta3() para insertar los datos
    delete_stock( $conexion, $id );
  }
}
$datos_stock = view_stock( $conexion );

$encabezados = array( "ID", "Tipo", "Estado", "Cantidad", "Almacenado", "Departamento", "Usuario Asignado", "Ver más" );

if ( $access[ 'stock' ] == 0 ) {
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
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>MOBILIARIO</strong></h1>
    <!--Botones-->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <button class="btn btn-dark btn-sm" type="button" data-toggle="modal" data-target="#ModalColumnas">Columnas</button>
        <?php
        if ( $access[ 'stock' ] == 1 ) {
          ?>
         <a type="button" class="btn bg-secundario btn-icon-split btn-sm" data-toggle="modal" data-target="#nuevoModal"> <span class="icon text-white-50"> <i class="fas fa-plus"></i> </span> <span class="text text-white">Nuevo Registro</span> </a>
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
                if ( $access[ 'stock' ] == 1 ) {
                  ?>
                <th class="bg-principal text-white" style="vertical-align: middle;">Eliminar</th>
                <?php
                }
                ?>
              </tr>
            </thead>
            <tbody>
         <?php
              while ( $row = mysqli_fetch_array( $datos_stock ) ) {
				  switch($row['estado']){
					  case 1:
						  $estado= "Excelente";
						  break;
					  case 2:
						  $estado= "Regular";
						  break;
					  case 3:
						  $estado="Deteriorado";
						  break;
					  case 4:
						  $estado="Mal estado";
						  break;
				  }
		    switch($row['almacenado']){
					  case 1:
						  $almacen= "Almacenado";
						  break;
					  case 2:
						  $almacen= "No almacenado";
						  break;
					  case 3:
						  $almacen="Baja";
						  break;
				  }
              
				 $codigoQR=$row[ 'QRKey' ];
				  $id_registro=$row[ 'id_stock' ];
				  $name_delete = $row[ 'codigo' ];
                ?>
              <tr ondblclick="abrirEnlace('ver_mobiliario<?php echo $codigoQR ?>')">
                <td class="text-dark"><?php echo $row[ 'codigo' ] ?></td>
                <td><?php echo $row[ 'tipo' ] ?></td>
                <td><?php echo $estado ?></td>
                <td><?php echo $row[ 'cantidad' ] ?></td>
                <td><?php echo $almacen?></td>
                <td><?php echo $row[ 'nombre_departamento' ] ?></td>
                <td><?php $nombre_completo = $row[ "nombre" ] . ' ' . $row[ "a_paterno" ] . ' ' . $row[ "a_materno" ];
                $nombre_completo_en_mayusculas = mb_strtoupper( $nombre_completo, 'UTF-8' );
                echo $nombre_completo_en_mayusculas;?></td>
                <td align="center"><acronym title="Ver más "> <a href="ver_mobiliario<?php echo $codigoQR ?>" class="btn btn-success  btn-sm" > <i class="fas fa fa-share"></i></a></acronym></td>
                <?php

                if ( $access[ 'stock' ] == 1 ) {
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
$cell_tipo = 'stock';
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/columnas.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/stock/new_stock.php' );

?>
<script>
    function abrirEnlace(enlace) {
        // Hacer algo con el enlace, por ejemplo, redirigir a la URL correspondiente
        window.location.href = enlace;
    }
</script>

<script src="excel"></script> 
<script src="TablaStockJs"></script> 
<script src="aplicarConfiguraciones"></script>
<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>