<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/personal/sql_personal.php' ); // Si se ha enviado el formulario
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    // Verifica si se hizo clic en el botón de inserción
    if ( isset( $_POST[ 'insertar' ] ) ) {
        // Recoge los valores de los campos del formulario
        // Recibo los datos de la imagen
        $nombre = trim( $_POST[ 'nombre' ] );
        $apaterno = trim( $_POST[ 'aPaterno' ] );
        $amaterno = trim( $_POST[ 'aMaterno' ] );
        $telefono = trim( $_POST[ 'telefono' ] );
        $email = trim( $_POST[ 'email' ] );
        $numColaborador = trim( $_POST[ 'numColaborador' ] );
        $depart = $_POST[ 'departamento' ] ;

        // Llama a la función consulta2() para insertar los datos
        insert_personal( $conexion, $nombre, $apaterno, $amaterno, $telefono, $email,$numColaborador, $depart);
    }
    // Verifica si se hizo clic en el botón de eliminar
    if ( isset( $_POST[ 'eliminar' ] ) ) {
        // Recoge los valores de los campos del formulario
        $id = $_POST[ 'id' ];
        // Llama a la función consulta3() para insertar los datos
        delete_personal( $conexion, $id );
    }
}

$encabezados = array( "ID", "Colaborador", "Correo electrónico", "Telefóno", "Empresa", "Departamento", "Ver más" );

$datos_personal = view_personal( $conexion );

if ( $access[ 'personal' ] == 0 ) {
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
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>REGISTRO DE COLABORADORES</strong></h1>
    <!--Botones-->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <button class="btn btn-dark btn-sm" type="button" data-toggle="modal" data-target="#ModalColumnas">Columnas</button>
        <?php
        if ( $access[ 'personal' ] == 1 ) {
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
                 
                    ?>
                <th class="bg-principal text-white" style="vertical-align: middle;">Eliminar</th>
                <?php
               
                ?>
              </tr>
            </thead>
            <tbody>
              <?php
              while ( $row = mysqli_fetch_array( $datos_personal ) ) {

                  $id_registro = $row[ 'id_personal' ];
			
                  ?>
            <tr ondblclick="abrirEnlace('ver_personal<?php echo $id_registro ?>')" >
                <td class="text-dark"><?php echo $row[ 'no_empleado' ] ?></td>
                <td><?php
                $nombre_completo = $row[ "nombre" ] . ' ' . $row[ "a_paterno" ] . ' ' . $row[ "a_materno" ];
                $nombre_completo_en_mayusculas = mb_strtoupper( $nombre_completo, 'UTF-8' );
                echo $nombre_completo_en_mayusculas;
				  
                  $name_delete = $nombre_completo_en_mayusculas;
                ?></td>
                <td><?php echo $row[ 'email' ] ?></td>
                <td><?php echo $row[ 'phone' ] ?></td>
                <td><?php echo $row[ 'empresa' ] ?></td>
                <td><?php echo $row[ 'departamentos' ] ?></td>
                <td align="center"><acronym title="Ver más "> <a href="ver_personal<?php echo $id_registro ?>" class="btn btn-success  btn-sm" > <i class="fas fa fa-share"></i></a></acronym></td>
                <?php

                if ( $access[ 'personal' ] == 1 ) {
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
$cell_tipo = 'personal';
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/columnas.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/personal/new_personal.php' );

?>
<script>
    function abrirEnlace(enlace) {
        // Hacer algo con el enlace, por ejemplo, redirigir a la URL correspondiente
        window.location.href = enlace;
    }
	
</script> 
<script src="excel"></script> 
<script src="TablaPersonalJs"></script> 
<script src="aplicarConfiguraciones"></script>
<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>
