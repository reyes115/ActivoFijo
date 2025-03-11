<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/password/sql_password.php' ); // Si se ha enviado el formulario
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    // Verifica si se hizo clic en el botón de inserción
    if ( isset( $_POST[ 'insertar' ] ) ) {
        // Recoge los valores de los campos del formulario
        // Recibo los datos de la imagen
        $tipo =  $_POST[ 'tipo' ] ;
        $usuario = trim( $_POST[ 'usuario' ] );
        $password = trim( $_POST[ 'password' ] );
        $descripcion = trim( $_POST[ 'descripcion' ] );
       

        // Llama a la función consulta2() para insertar los datos
        insert_password( $conexion, $tipo, $usuario , $password,$descripcion);
    }  
	if ( isset( $_POST[ 'editar' ] ) ) {
        // Recoge los valores de los campos del formulario
        // Recibo los datos de la imagen
        $id_pass =  $_POST[ 'id_pass' ] ;
        $tipo =  $_POST[ 'tipo' ] ;
        $usuario = trim( $_POST[ 'usuario' ] );
        $password = trim( $_POST[ 'password' ] );
        $descripcion = trim( $_POST[ 'descripcion' ] );
       

        // Llama a la función consulta2() para insertar los datos
        edit_password( $conexion, $tipo, $usuario , $password,$descripcion,$id_pass);
    }
    // Verifica si se hizo clic en el botón de eliminar
    if ( isset( $_POST[ 'eliminar' ] ) ) {
        // Recoge los valores de los campos del formulario
        $id = $_POST[ 'id' ];
        // Llama a la función consulta3() para insertar los datos
        delete_password( $conexion, $id );
    }
}
$encabezados = array( "ID", "Tipo", "Nombre / Usuario", "Contraseña", "Descripción","Editar" );

$datos_password = view_password( $conexion );

if ( $access[ 'password' ] == 0 ) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}
?>

<style>
	  .copy-button {
    cursor: pointer;
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    transition-duration: 0.4s;
    border-radius: 8px;
  }

  .copy-button:hover {
    background-color: #45a049; /* Darker Green */
  }
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
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>REGISTRO DE CONTRASEÑAS</strong></h1>
    <!--Botones-->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <button class="btn btn-dark btn-sm" type="button" data-toggle="modal" data-target="#ModalColumnas">Columnas</button>
        <?php
        if ( $access[ 'password' ] == 1 ) {
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
                if ( $access[ 'password' ] == 1 ) {
                    ?>
                <th class="bg-principal text-white" style="vertical-align: middle;">Eliminar</th>
                <?php
                }
                ?>
              </tr>
            </thead>
            <tbody>
              <?php
              while ( $row = mysqli_fetch_array( $datos_password ) ) {
				  switch ($row['tipo']){
					  case 1:
						  $tipo = "Correo";
						  break;
					  case 2:
						  $tipo = "Equipo";
						  break;
					  case 3:
						  $tipo = "Wi-Fi";
						  break;
					  case 4:
						  $tipo = "Sistemas";
						  break;
					  case 5:
						  $tipo = "Dominio";
						  break;
					  case 6:
						  $tipo = "Otros";
						  break;
					  default:
						  $tipo = "Otros";
						  break;
						
				  }

                  $id_registro = $row[ 'id_pass' ];
			
                  ?>
            <tr>
                <td class="text-dark"><?php echo $id_registro  ?></td>
                <td><?php echo $tipo ?></td>          
                <td><?php echo $row[ 'usuario-email' ] ?></td>
                <td><?php echo $row[ 'password' ] ?> </td>				
                <td><?php echo $row[ 'descripcion' ] ?></td> 
			
                <td align="center"><acronym title="Ver más "> <a class="btn btn-success  btn-sm" data-toggle="modal" data-target="#editModal<?php echo $id_registro;?>"> <i class="fas fa fa-pencil-square-o"></i></a></acronym></td>
               	 <?php

                if ( $access[ 'password' ] == 1 ) {
                    ?>
                <td align="center"><a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete<?php echo $id_registro;?>"> <i class="fas fa-trash"></i></a></td>
                <?php
					
					
                }
                ?>
              </tr>
              <?php
				  include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/password/edit_password.php' );
              include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/edit/delete.php' );
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
            </tbody>
          </table>
        </div>
      </div>
    </div>
<?php
$cell_tipo = 'password';
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/columnas.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/password/new_password.php' );

?>
<script>
    function abrirEnlace(enlace) {
        // Hacer algo con el enlace, por ejemplo, redirigir a la URL correspondiente
        window.location.href = enlace;
    }
	
</script> 
<script src="excel"></script> 
<script src="TablaPasswordJs"></script> 
<script src="aplicarConfiguraciones"></script>
<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>