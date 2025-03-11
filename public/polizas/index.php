<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] .'/assets/polizas/sql_polizas.php' );
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    // Verifica si se hizo clic en el botón de inserción
    if ( isset( $_POST[ 'insertar' ] ) ) {
$asegurado = $_POST[ 'asegurado' ];
$t_asegurado = $_POST[ 't_asegurado' ];
$empresa =$_POST['empresa'];
$tipo=$_POST['tipo'];
$no_poliza=trim($_POST['no_poliza']);
$aseguradora=trim($_POST['aseguradora']);
$propietario =$_POST['propietario'];
$f_pago=$_POST['f_pago'];
$inicio_vigencia=$_POST['inicio_vigencia'];
$fin_vigencia=$_POST['fin_vigencia'];
$moneda=$_POST['moneda'];
$total=trim($_POST['total']);
$prima_neta=trim($_POST['prima_neta']);
$derecho_poliza=trim($_POST['derecho_poliza']);
$iva=trim($_POST['iva']);
$suma_asegurada=trim($_POST['suma_asegurada']);
		
		insert_polizas($conexion, $asegurado, $t_asegurado, $empresa, $tipo, $no_poliza, $aseguradora, $propietario, $f_pago, $inicio_vigencia, $fin_vigencia, $moneda, $total, $prima_neta, $derecho_poliza, $iva, $suma_asegurada);
    }
    // Verifica si se hizo clic en el botón de eliminar
    if ( isset( $_POST[ 'eliminar' ] ) ) {
        // Recoge los valores de los campos del formulario
        $id = $_POST[ 'id' ];
        // Llama a la función consulta3() para insertar los datos
        delete_polizas( $conexion, $id );
    }
}

$encabezados = array( "ID", "Asegurado", "Tipo", "Número", "Aseguradora","Inicio de vigencia","Fin de vigencia", "Estatus", "Ver más" );

$datos_polizas = view_polizas( $conexion );
$datos_cards = verCards( $conexion );
// Conteo de resultados
$totalResultados = $datos_cards->num_rows;

if ( $access[ 'polizas' ] == 0 ) {
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
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>PÓLIZAS DE SEGURO</strong></h1>
	    <div class="row" align="center">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-sm  mb-2">
                            <div class="card border-left-primary shadow ">
                                <div class="btn btn-sm" data-toggle="modal" data-target="#polizas">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col ">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                PÓLIZAS PENDIENTES</div>
                                            <div class=" mb-0 font-weight-bold text-gray-800"><?php echo $totalResultados;?></div>
                                        </div>
                                     
                                    </div>
                                </div>
                            </div>
                        </div>	  
                        </div>
    <!--Botones-->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <button class="btn btn-dark btn-sm" type="button" data-toggle="modal" data-target="#ModalColumnas">Columnas</button>
        <?php
        if ( $access[ 'polizas' ] == 1 ) {
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
                if ( $access[ 'polizas' ] == 1 ) {
                    ?>
                <th class="bg-principal text-white" style="vertical-align: middle;">Eliminar</th>
                <?php
                }
                ?>
              </tr>
            </thead>
            <tbody>
				<?php
				  while ( $row = mysqli_fetch_array( $datos_polizas ) ) {
 switch ( $row[ 'tipo' ] ) {
                      case 1:
                          $tipo = "Auto";
                          break;
                      case 2:
                          $tipo = "Vida";
                          break;
                      case 3:
                          $tipo = "Gastos medicos";
                          break;
                      case 4:
                          $tipo = "Daños";
                          break;
                  }
					  // Fecha actual
$hoy = date("Y-m-d");

// Calcula la diferencia de días
$dias_restantes = (strtotime($row[ 'fin_vigencia' ]) - strtotime($hoy)) / (60 * 60 * 24);
		
// Determina el estado según los días restantes
if ($dias_restantes >= 1 && $dias_restantes <= 30) {
    $d_restantes = "Pendiente";
} elseif ($dias_restantes > 30) {
    $d_restantes = "Activa";
} else {
    $d_restantes = "Cancelada";
}        
					  $id_registro = $row[ 'id_poliza' ];
                  $name_delete = $row[ 'codigo' ];
			
                  ?>
            <tr ondblclick="abrirEnlace('ver_polizas<?php echo $id_registro ?>')" >
                <td class="text-dark"><?php echo $row[ 'codigo' ] ?></td>        
                <td><?php echo $row[ 'auto_codigo' ]. $row[ 'nombre' ]. $row[ 'name' ] ?></td>
                <td><?php echo $tipo?></td>
                <td><?php echo $row[ 'no_poliza' ] ?></td>
                <td><?php echo $row[ 'aseguradora' ] ?></td>
<td><?php echo date('d-m-Y', strtotime($row['inicio_vigencia'])) ?></td>
<td><?php echo date('d-m-Y', strtotime($row['fin_vigencia'])) ?></td>

                <td><?php echo $d_restantes  ?></td>
                <td align="center"><acronym title="Ver más "> <a href="ver_polizas<?php echo $id_registro ?>" class="btn btn-success  btn-sm" > <i class="fas fa fa-share"></i></a></acronym></td>
                <?php

                if ( $access[ 'polizas' ] == 1 ) {
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
<!--modal-->
      <div class="modal fade" id="polizas">
        <div class="modal-dialog modal-lg">
          <div class="modal-content"> 
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">PÓLIZAS PENDIENTES</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
          
            <!-- Modal body -->
            <div class="modal-body">
              <table  class=" table table-hover" width="50%">
              <thead>
                  <tr >
                     <th >Ver</th>
                    <th >Registro</th>
                    <th>Fecha de vencimiento</th>
                    <th>Tipo</th>
                    <th>Asegurado</th>
                  </tr>
                </thead>
				   <tbody>
              <?php

              // Suponiendo que $VencVerificacion es la fecha que deseas verificar
              while ( $ver= mysqli_fetch_array( $datos_cards ) ) {
				 switch ( $ver[ 'tipo' ] ) {
                      case 1:
                          $tipov = "Auto";
                          break;
                      case 2:
                          $tipov = "Vida";
                          break;
                      case 3:
                          $tipov = "Gastos medicos";
                          break;
                      case 4:
                          $tipov = "Daños";
                          break;
                  }
               
                      ?>
              <tr ondblclick="abrirEnlace('ver_polizas<?php echo $ver[ 'id_poliza' ] ?>')">
                   <td > <a  type="button" href="ver_polizas<?php echo $ver[ 'id_poliza' ]  ?>" >
                              <i class="fas fa fa-search  ">
								 </i>
									 </a>
                                       </td>
                   <td ><?php echo $ver[ 'codigo' ] ?></td>
                   <td><?php echo $ver[ 'fecha_fin' ] ?></td>
                   <td><?php echo $tipov ?></td>
                   <td><?php echo $ver[ 'auto_codigo' ] .$ver[ 'nombre' ] . $ver[ 'name' ] ?></td>
                  </tr>
              <?php
              
              }
              ?>
  </tbody>
            

              </table>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div> 
      </div>

<?php
$cell_tipo = 'polizas';
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/columnas.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/modal/polizas/new_polizas.php' );
?>
<script>

    function abrirEnlace(enlace) {
        // Hacer algo con el enlace, por ejemplo, redirigir a la URL correspondiente
        window.location.href = enlace;
    }
</script> 
<script src="excel"></script> 
<script src="TablaPolizasJs"></script> 
<script src="aplicarConfiguraciones"></script>
<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>