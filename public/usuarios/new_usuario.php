<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/usuarios/sql.php' );

if ( $access[ 'accesos' ] == 0 ) {
    // Script de redirección con JavaScript
    echo '<script type="text/javascript">window.location.href = "inicio"</script>';
    exit;
}
?>
<script>
        function checkPassword() {
            var password = document.getElementById("password").value;
            var message = document.getElementById("password-message");
            var strength = 0;

            // Verificar si la contraseña tiene al menos 8 caracteres
            if (password.length >= 8) {
                strength++;
            }

            // Verificar si la contraseña tiene al menos un número
            if (/\d/.test(password)) {
                strength++;
            }

            // Verificar si la contraseña tiene al menos una letra mayúscula
            if (/[A-Z]/.test(password)) {
                strength++;
            }

            // Verificar si la contraseña tiene al menos una letra minúscula
            if (/[a-z]/.test(password)) {
                strength++;
            }

            // Verificar si la contraseña tiene al menos un caracter especial
            if (/[!@#$%^&*()\-_=+{};:,<.>]/.test(password)) {
                strength++;
            }

            // Verificar si la contraseña contiene una palabra familiar
            var commonPasswords = ["la familia", "jesus", "jesucristo", "dios"];
            var containsCommonPassword = false;
            for (var i = 0; i < commonPasswords.length; i++) {
                if (password.toLowerCase().includes(commonPasswords[i])) {
                    containsCommonPassword = true;
                    break;
                }
            }
            if (containsCommonPassword) {
                strength = 4;
            }

            // Mostrar un mensaje que indique la fortaleza de la contraseña
            switch (strength) {
                case 0:
                    message.innerHTML = "Muy débil.";
                    message.style.color = "red";
                    break;
                case 1:
                    message.innerHTML = "Débil.";
                    message.style.color = "orange";
                    break;
                case 2:
                    message.innerHTML = "Media.";
                    message.style.color = "yellow";
                    break;
                case 3:
                    message.innerHTML = "Fuerte.";
                    message.style.color = "green";
                    break;
                case 4:
                case 5:
                    message.innerHTML = "Muy fuerte.";
                    message.style.color = "blue";
                    break;
            }
        }
    </script>
<div class="layoutSidenav_content">
  <div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800" align="center"><strong>REGISTRO DE USUARIO</strong></h1>
    <form class="user" action="save_usuario" method="post" enctype="multipart/form-data">
      <!--card-->
      <div class="card shadow mb-4"> 
        <!--card header-->
        <div class="card-header py-3"><strong>DATOS GENERALES</strong></div>
        
        <!--card body-->
        
        <div class="card-body">   
			<div class="row justify-content-center justify-content-md-start">
				   <div class="col-sm mb-3">
                  <label for="nombre">Nombre completo</label>
                  <input type="text" class="form-control" id="nombre" name="nombre">
                </div>
				    <div class="col-sm mb-3">
                  <label for="departamento">Departamento </label>
                  <select class="form-control" id="departamento" name="departamento" required>
                    <option name="departamento" id="departamento" value="" >SELECCIONE </option>
 <?php
                $valores = view_departamentos($conexion);
                while ($ver = mysqli_fetch_array($valores)) {
                  print '<option value="' . $ver["id_depa"] . '">' . $ver["nombre"] . '</option>';
                }
                ?>
                  </select>
                </div>
           
            
                
              </div>
           
              <div class="row justify-content-center justify-content-md-start">
               <div class="col-sm mb-3">
                  <label for="usuario-email">Usuario</label>
                  <input type="text" class="form-control" id="usuario-email	" name="usuario-email">
                </div>
				  <div class="col-sm mb-3">
          <label for="password">Contraseña</label>
          <input type="text" class="form-control" id="password" name="password" onkeyup="checkPassword()" required >
          <p id="password-message"></p>
        </div>
        <div class="col-sm mb-3">
          <label for="rpass">Confirmar contraseña</label>
          <input type="text" class="form-control" id="rpass" name="rpass" required autocomplete="off">
        </div>
              </div>
		  </div>
		  </div>
		 <!--card-->
<div class="card shadow mb-4">
  <div class="card-header py-3"><strong>Foto de perfíl (Opcional)</strong></div>
  <div class="card-body">
    <div class="row">
      <div class="col">
        <div class="custom-file mb-3">
          <input type="file" class="custom-file-input" id="customFile" name="archivos" accept="image/*" onchange="actualizarNombreArchivos()">
          <label class="custom-file-label" for="customFile">Suelta tu imagen aqui. <i class="fa fa-upload"></i></label>
        </div>
      </div>
    </div>
	  </div>
		  </div>
        <!--card body-->
         <!--card-->
  <div class="card shadow mb-4">
  <div class="card-header py-3"><strong>PERMISOS</strong></div>
  <div class="card-body">
  <div class="table-responsive" >
	     
    <a class="btn btn-sm bg-principal text-white mb-2" onclick="marcarTodos()">Marcar Todos los Permisos</a>
 
      <table class=" table table-bordered display table-hover"  width="100%" cellspacing="0" style="font-size: 13px;">
      <thead>
        <tr>
          <th class="col-5">Módulo</th>
          <th>Control total</th>
          <th>Visualizador</th>
          <th>Bloquear</th>
        </tr>
      </thead>
  <tbody>
  <tr align="center">
    <td>Equipos de Cómputo</td>
    <td><input type="radio" name="computo" value="1" required></td>
    <td><input type="radio" name="computo" value="2"></td>
    <td><input type="radio" name="computo" value="0"></td>
  </tr>
  <tr align="center">
    <td>Equipos Móviles</td>
    <td><input type="radio" name="moviles" value="1" required></td>
    <td><input type="radio" name="moviles" value="2"></td>
    <td><input type="radio" name="moviles" value="0"></td>
  </tr>
  <tr align="center">
    <td>Equipos Alternos</td>
    <td><input type="radio" name="dispositivos" value="1" required></td>
    <td><input type="radio" name="dispositivos" value="2"></td>
    <td><input type="radio" name="dispositivos" value="0"></td>
  </tr>
  <tr align="center">
    <td>Colaboradores</td>
    <td><input type="radio" name="personal" value="1" required></td>
    <td><input type="radio" name="personal" value="2"></td>
    <td><input type="radio" name="personal" value="0"></td>
  </tr>
  <tr align="center">
    <td>Licencias de software</td>
    <td><input type="radio" name="licencias" value="1" required></td>
    <td><input type="radio" name="licencias" value="2"></td>
    <td><input type="radio" name="licencias" value="0"></td>
  </tr>
  <tr align="center">
    <td>Servicios de comunicación</td>
    <td><input type="radio" name="servicios" value="1" required></td>
    <td><input type="radio" name="servicios" value="2"></td>
    <td><input type="radio" name="servicios" value="0"></td>
  </tr>
  <tr align="center">
    <td>Contraseñas</td>
    <td><input type="radio" name="password_acceso" value="1" required></td>
    <td><input type="radio" name="password_acceso" value="2"></td>
    <td><input type="radio" name="password_acceso" value="0"></td>
  </tr>
  <tr align="center">
    <td>Automóviles</td>
    <td><input type="radio" name="autos" value="1" required></td>
    <td><input type="radio" name="autos" value="2"></td>
    <td><input type="radio" name="autos" value="0"></td>
  </tr>
  <tr align="center">
    <td>Mobiliario</td>
    <td><input type="radio" name="stock" value="1" required></td>
    <td><input type="radio" name="stock" value="2"></td>
    <td><input type="radio" name="stock" value="0"></td>
  </tr>
  <tr align="center">
    <td>Maquinaria</td>
    <td><input type="radio" name="maquinaria" value="1" required></td>
    <td><input type="radio" name="maquinaria" value="2"></td>
    <td><input type="radio" name="maquinaria" value="0"></td>
  </tr>
  <tr align="center">
    <td>Pólizas</td>
    <td><input type="radio" name="polizas" value="1" required></td>
    <td><input type="radio" name="polizas" value="2"></td>
    <td><input type="radio" name="polizas" value="0"></td>
  </tr>
  <tr align="center">
    <td>Accesos</td>
    <td><input type="radio" name="accesos" value="1" required></td>
    <td></td>
    <td><input type="radio" name="accesos" value="0"></td>
  </tr>
</tbody>

    </table>
  </div>
  </div>
  </div>
	<div  align="center">
			   <a class="btn bg-principal text-white" type="button" href="accesos" >Volver </a>
            <input type="Submit" class="btn bg-secundario text-white" name="Submit" value="Guardar "  >
          </div>
    </form>
  </div>
</div>
<!-- Modal de error -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title text-white" id="errorModalLabel">Error de confirmación de contraseña</h5>
        <button type="button" class="btn-close  text-white" data-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p>Las contraseñas no coinciden. Por favor, inténtalo de nuevo.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<script>
function marcarTodos() {
  var radios = document.querySelectorAll('input[type="radio"]');
  var allChecked = true;
  radios.forEach(function(radio) {
    if (radio.value === "1" && !radio.checked) {
      allChecked = false;
    }
  });
  radios.forEach(function(radio) {
    if (allChecked) {
      radio.checked = false;
    } else {
      if (radio.value === "1") {
        radio.checked = true;
      }
    }
  });
}
	function validarContraseñas() {
        var password = document.getElementById("password").value;
        var confirm_password = document.getElementById("rpass").value;
        if (password != confirm_password) {
            $('#errorModal').modal('show');
            return false;
        }
        return true;
    }
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