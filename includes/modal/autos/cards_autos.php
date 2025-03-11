<!--modal verificacion-->
      <div class="modal fade" id="verificaciones">
        <div class="modal-dialog modal-lg">
          <div class="modal-content"> 
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">VERIFICACIONES PRÓXIMAS A VENCER Y VENCIDAS</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
          
            <!-- Modal body -->
            <div class="modal-body">
				 <div class="table-responsive" >
              <table  class=" table table-hover" width="50%">
              <thead>
                  <tr >
                    <th >Ver</th>
					<th>ID autos</th>
                    <th >Registro</th>
                    <th>Fecha de vencimiento</th>
                    <th>Notificado</th>
                    <th>Usuario Asignado</th>
					<th>Email del usuario</th>
					<th>Envio</th>
                  </tr>
                </thead>
				   <tbody>
              <?php
              // Obtener la fecha actual
              $fecha_actual = date( 'Y-m-d' );

              // Obtener la fecha dentro de 30 días
              $fecha_30_dias = date( 'Y-m-d', strtotime( '+30 days' ) );

              // Suponiendo que $VencVerificacion es la fecha que deseas verificar
              while ( $verVon = mysqli_fetch_array( $datos_verificaciones ) ) {
				
                  $VencVerificacion = $verVon[ 'VencVerificacion' ];
  switch ($verVon[ 'note' ]) {
					  case 1 :
						  $note= "Si";
						  break;
					  case 0 :
						  $note = "No";
						  break;
				  }

                      ?>
              <tr >
                  <td ><a type="button" href="ver_auto<?php echo $verVon[ 'QRKey' ] ?>" > <i class="fas fa-search "> </i> </a></td>
				  <td><?php echo $verVon['id_autos']?></td>
                  <td ><?php echo $verVon[ 'codigo' ] ?></td>
                  <td><?php echo $verVon[ 'VencVerificacion' ] ?></td>
                  <td><?php echo $note ?></td>
                  <td><?php $nombre_completo = $verVon[ "nombre" ] . ' ' . $verVon[ "a_paterno" ] . ' ' . $verVon[ "a_materno" ];
                $nombre_completo_en_mayusculas = mb_strtoupper( $nombre_completo, 'UTF-8' );
                echo $nombre_completo_en_mayusculas;?></td>
				  <td><?php echo $verVon['email']; ?></td>
				  
				  <td>
                  <button 
                    type="button" 
                    class="btn btn-primary btn-sm" 
                    style="background-color: #007bff; border-color: #007bff; color: white; font-size: 14px; padding: 8px 12px; border-radius: 5px;" 
                    onclick="enviarVerificacion('<?php echo $verVon['email']; ?>', '<?php echo $verVon['id_autos']; ?>')">
                    <i class="fas fa-paper-plane"></i> Enviar
                  </button>
                </td>
				  

                  </tr>
				
              <?php
             
              }
              ?>
  </tbody>
            

              </table>
            </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div> 
      </div>

	   <!-- Modal de Próximos Mantenimientos -->
<div class="modal fade" id="mantenimientos">
  <div class="modal-dialog modal-lg">
    <div class="modal-content"> 
      <!-- Encabezado del Modal -->
      <div class="modal-header">
        <h4 class="modal-title">PRÓXIMOS MANTENIMIENTOS</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Cuerpo del Modal -->
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover" width="50%">
            <thead>
              <tr>
                <th>Ver</th>
                <th>ID Servicio</th>
                <th>Registro</th>
                <th>Último Servicio</th>
                <th>Próximo Servicio</th>
                <th>Notificado</th>
                <th>Usuario Asignado</th>
                <th>Email del Usuario</th>
                <th>Envío</th>
              </tr>
            </thead>              
            <tbody>
              <?php 
              while ($verSer = mysqli_fetch_array($datos_servicios)) {
                $prox_servicio = $verSer['prox_servicio'];
                $noteS = ($verSer['Notificacion'] == 1) ? "Si" : "No";
                $nombre_completo = strtoupper($verSer["nombre"] . ' ' . $verSer["a_paterno"] . ' ' . $verSer["a_materno"]);
              ?>
              <tr>
                <td><a href="ver_auto<?php echo $verSer['QRKey']; ?>"><i class="fas fa-search"></i></a></td>
                <td><?php echo $verSer['id_servicio']; ?></td>
                <td><?php echo $verSer['codigo']; ?></td>
                <td><?php echo $verSer['ultimo_servicio']; ?></td>
                <td><?php echo empty($prox_servicio) ? '<span style="color: red;"><b>Verificar registro</b></span>' : $prox_servicio; ?></td>
                <td><?php echo $noteS; ?></td>
                <td><?php echo $nombre_completo; ?></td>
                <td><?php echo $verSer['email']; ?></td>
                <td>
                  <button 
                    type="button" 
                    class="btn btn-primary btn-sm" 
                    style="background-color: #007bff; border-color: #007bff; color: white; font-size: 14px; padding: 8px 12px; border-radius: 5px;" 
                    onclick="enviarCorreo('<?php echo $verSer['email']; ?>', '<?php echo $verSer['id_servicio']; ?>')">
                    <i class="fas fa-paper-plane"></i> Enviar
                  </button>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- Pie del Modal -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Notificación de Envío -->
<div class="modal fade" id="modalNotificacion" tabindex="-1" role="dialog" aria-labelledby="modalNotificacionLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNotificacionLabel">Estado del Envío</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalMensaje">
        <!-- Aquí se mostrará el mensaje de respuesta -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
  function enviarCorreo(email, id_servicio) {
    const formData = new FormData();
    formData.append('email', email);
    formData.append('id_servicio', id_servicio);

    // Realiza la solicitud POST al archivo enviarCorreo.php
    fetch('../../../assets/emails/enviarCorreo.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())

    .then(data => {
      // Configura el mensaje en el modal según la respuesta
      const modalMensaje = document.getElementById('modalMensaje');
      if (data.status === "success") {
        modalMensaje.innerHTML = `<p style="color: green;">${data.message}</p>`;
      } else {
        modalMensaje.innerHTML = `<p style="color: red;">${data.message}</p>`;
      }

      // Abre el modal
      $('#modalNotificacion').modal('show');
    })
    .catch(error => {
      console.error('Error:', error);
      document.getElementById('modalMensaje').innerHTML = '<p style="color: red;">Ocurrió un error al enviar el correo.</p>';
      $('#modalNotificacion').modal('show');
    });
  }
</script>



<script>
  function enviarVerificacion(email, id_autos) {
    const formData = new FormData();
    formData.append('email', email);
    formData.append('id_autos', id_autos);

    // Realiza la solicitud POST al archivo enviarCorreo.php
    fetch('../../../assets/emails/enviar_verificacion.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())

    .then(data => {
      // Configura el mensaje en el modal según la respuesta
      const modalMensaje = document.getElementById('modalMensaje');
      if (data.status === "success") {
        modalMensaje.innerHTML = `<p style="color: green;">${data.message}</p>`;
      } else {
        modalMensaje.innerHTML = `<p style="color: red;">${data.message}</p>`;
      }

      // Abre el modal
      $('#modalNotificacion').modal('show');
    })
    .catch(error => {
      console.error('Error:', error);
      document.getElementById('modalMensaje').innerHTML = '<p style="color: red;">Ocurrió un error al enviar el correo.</p>';
      $('#modalNotificacion').modal('show');
    });
  }
</script>
