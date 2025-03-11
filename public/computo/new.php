<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );



if ( $access[ 'computo' ] == 0 ) {
  // Script de redirección con JavaScript
  echo '<script type="text/javascript">window.location.href = "inicio"</script>';
  exit;
}
?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.js"></script>
<div class="layoutSidenav_content">
  <div class="container-fluid"> 
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>NUEVO EQUIPO DE CÓMPUTO</strong></h1>
   
        <form class="user" action="save_compu" method="post" enctype="multipart/form-data">
			 <!--card-->
    <div class="card shadow mb-4"> 
      <!--card header-->
      <div class="card-header py-3"><strong>DATOS GENERALES</strong></div>
      
      <!--card body-->
      
		<div class="card-body">
          <div class="row justify-content-center justify-content-md-start">
            <div class="col mb-3">
              <label for="propietario">Propietario</label>
              <select class="form-control" id="propietario" name="propietario" required>
                <option value="">SELECCIONE</option>
                <?php
                include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/propietarios.php' );
                $propietarios = view_propietarios( $conexion );

                while ( $ver_propietarios = mysqli_fetch_array( $propietarios ) ) {
                  // Utiliza comillas simples para las cadenas HTML
                  echo '<option value="' . $ver_propietarios[ "id_propietario" ] . '">' . $ver_propietarios[ "nombre" ] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="col-sm mb-3">
              <label for="tipo">Tipo </label>
              <select class="form-control" id="tipo" name="tipo" required>
                <option id="tipo" name="tipo" value="">SELECCIONE</option>
                <option id="tipo" name="tipo" value="1">Escritorio</option>
                <option id="tipo" name="tipo" value="2">Laptop</option>
                <option id="tipo" name="tipo" value="4">Otro</option>
              </select>
            </div>
            <div class="col-sm mb-3">
              <label for="costo">Costo</label>
              <input type="text" class="form-control" id="costo" name="costo"   onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
            </div>
            <div class="col-sm mb-3">
              <label for="fecha_compra">Fecha de Registro</label>
              <input type="date" class="form-control" id="fecha_compra" name="fecha_compra" required>
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="cpu">Procesador</label>
              <input type="text" class="form-control" id="cpu" name="cpu"  >
            </div>
            <div class="col-sm mb-3">
              <label for="ram">Memoria RAM </label>
              <input type="text" class="form-control" id="ram" name="ram"  >
            </div>
            <div class="col-sm mb-3">
              <label for="almacenamiento">Almacenamiento</label>
              <input type="text" class="form-control" id="almacenamiento" name="almacenamiento"  >
            </div>
            <div class="col-sm mb-3">
              <label for="estado">Estado</label>
              <select  class="form-control" id="estado" name="estado"  >
                <option id="estado" name="estado" value="0">SELECCIONE</option>
                <option id="estado" name="estado" value="1">Nuevo</option>
                <option id="estado" name="estado" value="2">Usado</option>
                <option id="estado" name="estado" value="3">Con fallas</option>
                <option id="estado" name="estado" value="4">Inservible</option>
              </select>
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="marca">Marca</label>
              <input type="text" class="form-control" id="marca" name="marca"  >
            </div>
            <div class="col-sm mb-3">
              <label for="modelo">Modelo</label>
              <input type="text" class="form-control" id="modelo" name="modelo"  >
            </div>
            <div class="col-sm mb-3">
              <label for="color">Color</label>
              <input type="text" class="form-control" id="color" name="color"  >
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="no_serie">Número de serie</label>
              <input type="text" class="form-control" id="no_serie" name="no_serie"  >
            </div>
            <div class="col-sm mb-3">
              <label for="no_serie">Cargador</label>
              <input type="text" class="form-control" id="cargador" name="cargador"  >
            </div>
            <div class="col-sm mb-3" style="color:#858796">
              <label for="usuarioAsignado">Colaborador Asignado</label>
              <select class="js-example-basic-single js-states form-control" id="usuarioAsignado" name="usuarioAsignado" >
                <?php
             include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/personal/sql_personal.php' );
                $datos_personal = select_personal( $conexion );
                while ( $mostrar = mysqli_fetch_array( $datos_personal) ) {
               print '
    <option value="' . strtoupper($mostrar["id_personal"]) . '">
        ' . strtoupper($mostrar["nombre"] . ' ' . $mostrar["a_paterno"] . ' ' . $mostrar["a_materno"]) . '
    </option>
';

                }
                ?>
              </select>
            </div>
          </div>
          <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="accesorios">Accesorios</label>
              <textarea type="text" class="form-control" id="accesorios" name="accesorios" rows="3" style="max-height: 100px;"></textarea>
            </div>
            <div class="col-sm mb-3">
              <label for="observaciones">Observaciones</label>
              <textarea class="form-control" id="observaciones" name="observaciones" rows="3" style="max-height: 100px;"></textarea>
            </div>
          </div>
          <br>
       
      </div>
	</div>
		 <!--card-->
    <div class="card shadow mb-4"> 
		<div class="card-header py-3"><strong>LICENCIAS</strong></div>
			<div class="card-body">
				 <div class="row justify-content-center justify-content-md-start">
            <div class="col-sm mb-3">
              <label for="so">Sistema Operativo</label>
              <select type="text" class="form-control" id="so" name="so" >
                <option name="so" id="so" value="999">SELECCIONE</option>
                <?php
                  include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/licencias/sql_licencias.php' );
                $datos_SO = view_SO( $conexion );
                while ( $mostrarSO = mysqli_fetch_array( $datos_SO ) ) {
                    print '
                <option  name="so" id="so" value="' . $mostrarSO[ "id_licencias" ] . '">' . $mostrarSO[ "nombre_licencias" ] . '</option>
                ';
                }
                ?>
              </select>
            </div>
            <div class="col-sm mb-3">
              <label for="office">Office </label>
              <select type="text" class="form-control" id="office" name="office"  >
                <option name="office" id="office" value="999">SELECCIONE</option>
                <?php
                $datos_office = view_office( $conexion );
                while ( $mostraroffice = mysqli_fetch_array( $datos_office ) ) {
                    print '
                <option  name="office" id="office" value="' . $mostraroffice[ "id_licencias" ] . '">' . $mostraroffice[ "nombre_licencias" ] . '</option>
                ';
                }
                ?>
              </select>
            </div>
            <div class="col-sm mb-3">
              <label for="antivirus">Antivirus</label>
              <select type="text" class="form-control" id="antivirus" name="antivirus"  >
                <option name="antivirus" id="antivirus" value="999">SELECCIONE</option>
                <?php
                 $datos_antivirus = view_antivirus( $conexion );

                while ( $mostrarantivirus = mysqli_fetch_array( $datos_antivirus ) ) {
                    print '
                <option  name="antivirus" id="antivirus" value="' . $mostrarantivirus[ "id_licencias" ] . '">' . $mostrarantivirus[ "nombre_licencias" ] . '</option>
                ';
                }
                ?>
              </select>
            </div>
			<div class="col-sm mb-3">
              <label for="adicional">Software adicional</label>
              <textarea class="form-control" id="adicional" name="adicional" rows="1"></textarea>
            </div>
			</div>
			</div>
			</div>
			 <!--card-->
    <div class="card shadow mb-4"> 
			<div class="card-header py-3"><strong>DOCUMENTACIÓN (Opcional)</strong></div>
			<div class="card-body">
			<div class="row">
                  <div class="col" >
                  <div class="custom-file mb-3">
      <input type="file" class="custom-file-input" id="customFile" name="archivos[]" multiple onchange="actualizarNombreArchivos()">
      <label class="custom-file-label" for="customFile">Suelta los archivos aqui. <i class="fa fa-upload"></i></label>
    </div>
                  </div>
                </div>
				
           </div>
			</div>
		<div  align="center">
			   <a class="btn btn-danger" type="button" href="computo" >Volver </a>
            <input type="Submit" class="btn bg-principal text-white" name="Submit" value="Guardar "  >
          </div>
			
		</form>
    
  </div>
</div>
<script>// In your Javascript (external .js resource or <script> tag)
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});
 function actualizarNombreArchivos() {
    var inputArchivos = document.getElementById('customFile');
    var labelArchivos = document.querySelector('.custom-file-label');
    var nombresArchivos = Array.from(inputArchivos.files).map(file => file.name);
    labelArchivos.innerHTML = nombresArchivos.join(', ') || 'Selecciona archivos';
  }
</script>
<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );

?>
