<?php 

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/maquinaria/sql_maquinaria.php' );
$datos_maquinaria = view_maquinaria( $conexion );

// Si se ha enviado el formulario
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
	  if ( isset( $_POST[ 'insertar' ] ) ) {
        // Recoge los valores de los campos del formulario
$propietario =$_POST['propietario'];
$desc =trim($_POST['descripcion']);
$marca =trim($_POST['marca']);
$modelo =trim($_POST['modelo']);
$serie =trim($_POST['serie']);
$estado=$_POST['estado'];
$no_factura =trim($_POST['no_factura']);
$val_factura =trim($_POST['val_factura']);
$empresa =$_POST['empresa'];
$area =$_POST['area'];
$observaciones =trim($_POST['observaciones']);

// Llama a la función consulta2() para insertar los datos
 insert_maquinaria( $conexion, $propietario, $desc, $marca, $modelo, $serie, $estado, $no_factura, $val_factura, $empresa, $area, $observaciones);
    }
// Verifica si se hizo clic en el botón de eliminar
  if ( isset( $_POST[ 'eliminar' ] ) ) {
    // Recoge los valores de los campos del formulario
    $id = $_POST[ 'id' ];
    // Llama a la función consulta3() para insertar los datos
    delete_maquinaria( $conexion, $id );
  }
}

$encabezados = array( "ID", "Descripción", "Marca", "Modelo", "Serie", "No. Factura", "Estado","Empresa", "Área responsable", "Ver más" );

if ( $access[ 'maquinaria' ] == 0 ) {
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
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>Maquinaria</strong></h1>
    <!--Botones-->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <button class="btn btn-dark btn-sm" type="button" data-toggle="modal" data-target="#ModalColumnas">Columnas</button>
        <?php
        if ( $access[ 'maquinaria' ] == 1 ) {
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
                if ( $access[ 'maquinaria' ] == 1 ) {
                  ?>
                <th class="bg-principal text-white" style="vertical-align: middle;">Eliminar</th>
                <?php
                }
                ?>
              </tr>
            </thead>
            <tbody>
         <?php
              while ( $row = mysqli_fetch_array( $datos_maquinaria ) ) {
				 switch($row['estado']){
					  case 1:
						  $estado= "Operación";
						  break;
					  case 2:
						  $estado= "Fuera de operación";
						  break;
					  case 3:
						  $estado="En reparación";
						  break;
					  case 4:
						  $estado="Inservible";
						  break;
				  }
                 switch($row['area_resp']){
					  case 1:
						  $area= "Mantenimiento";
						  break;
					  case 2:
						  $area= "Maquinados";
						  break;
					  case 3:
						  $area="Producción";
						  break;
				  }
				$codigoQR=$row[ 'QRKey' ];
				  $id_registro=$row[ 'id_cogs' ];
				  $name_delete = $row[ 'codigo' ];
                ?>
              <tr ondblclick="abrirEnlace('ver_maquinaria<?php echo $codigoQR ?>')">
                <td class="text-dark"><?php echo $row[ 'codigo' ] ?></td>
                <td><?php echo $row[ 'descripcion' ] ?></td>
                <td><?php echo $row['marca'] ?></td>
                <td><?php echo $row[ 'modelo' ] ?></td>
                <td><?php echo $row[ 'serie' ] ?></td>
                <td><?php echo $row[ 'no_factura' ] ?></td>
                <td><?php echo $estado ?></td>
                <td><?php echo $row['nombre'] ?></td>
                <td><?php echo $area ?></td>
                <td align="center"><acronym title="Ver más "> <a href="ver_maquinaria<?php echo $codigoQR ?>" class="btn btn-success  btn-sm" > <i class="fas fa fa-share"></i></a></acronym></td>
                <?php

                if ( $access[ 'maquinaria' ] == 1 ) {
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
$cell_tipo = 'maquinaria';
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/columnas.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/maquinaria/new_maquinaria.php' );

?>
<script>
    function abrirEnlace(enlace) {
        // Hacer algo con el enlace, por ejemplo, redirigir a la URL correspondiente
        window.location.href = enlace;
    }
</script>

<script src="excel"></script> 
<script src="TablaMaquinariaJs"></script> 
<script src="aplicarConfiguraciones"></script>
<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>