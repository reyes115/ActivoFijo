<div class="modal fade" id="relaciones" tabindex="-1" role="dialog" aria-labelledby="newModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-principal">
        <h5 class="modal-title text-white" id="newModalLabel">Asignaciones</h5>
        <button type="button" class="close  text-white" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">
    <div class="table-responsive">
        <table class="table table-bordered display table-hover">
          <thead >
            <tr align="center" bgcolor="#E1261C">
              <th scope="col">Tipo</th>
              <th scope="col">Codigo</th>
              <th colspan="3">Ver</th>
            </tr>
          </thead>
          <tbody>
			  <?php 
			  include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/personal/relaciones.php' );
			  function mostrarElementos($elementos, $tipo,$url) {
        while ($ver = mysqli_fetch_array($elementos)) {
    ?>
        <tr align="center">
            <td><?php echo $tipo ?></td>
            <td><?php echo $ver['codigo'] ?></td>
            <td align="center">
                <acronym title="Ver más">
                    <a href="ver_<?php echo $url ?><?php echo $ver['QRKey'] ?>" class="btn btn-success btn-sm">
                        <i class="fas fa fa-share"></i>
                    </a>
                </acronym>
            </td>
        </tr>
    <?php 
        }
    }

    // Mostrar computadoras
    mostrarElementos(computo($conexion, $idPersonal), "Computo", "equipo");
    // Mostrar móviles
    mostrarElementos(movil($conexion, $idPersonal), "Movil", "movil");
    // Mostrar perifericos
    mostrarElementos(perifericos($conexion, $idPersonal), "Equipo alterno", "dispositivos"); 
	// Mostrar autos
    mostrarElementos(autos($conexion, $idPersonal), "Automovil", "auto");
	// Mostrar mobiliario
    mostrarElementos(mobiliario($conexion, $idPersonal), "Mobiliario", "mobiliario");
	// Mostrar mobiliario
    mostrarElementos(polizas($conexion, $idPersonal), "Polizas", "polizas");
    ?>
</tbody>
        </table>		
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
