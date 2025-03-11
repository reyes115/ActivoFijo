<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );
?>

<!-- Contenido de la pagina -->
<div id="layoutSide nav_content">
  <main>
    <div class="container-fluid">

      <div class="row align-items-center justify-content-center">
        <?php

        function renderMenuItem( $accessValue, $href, $iconClass, $text ) {
          if ( $accessValue == 1 || $accessValue == 2 ) {
            ?>
        <div class="col-lg-6 mb-4 align-items-center"> <a href="<?php echo $href ?>">
          <div class="card border-left-primary shadow-lg h-100">
            <div class="card-body">
              <div class="container">
                <div class="row no-gutters align-items-center ">
                  <div class="col mr-2" align="center"> <i class="<?php echo $iconClass ?>"></i> </div>
                  <div class="col">
                    <div class="text-black-50 text-lg font-weight-bold"><?php echo $text ?></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </a> </div>
        <?php
        }
        }

        // Uso de la función renderMenuItem con los valores de acceso
        renderMenuItem( $access[ 'computo' ], 'computo', 'fas fa-laptop fa-2x', 'Equipos de cómputo' );
        renderMenuItem( $access[ 'moviles' ], 'moviles', 'fas fa-mobile fa-2x', 'Equipos telefónicos' );
        renderMenuItem( $access[ 'dispositivos' ], 'dispositivos', 'fas fa-microchip fa-2x', 'Equipos alternos' );
        renderMenuItem( $access[ 'personal' ], 'personal', 'fas fa-users fa-2x', 'Colaboradores' );
        renderMenuItem( $access[ 'licencias' ], 'licencias', 'fas fa-certificate fa-2x', 'Licencias de software' );
        renderMenuItem( $access[ 'servicios' ], 'servicios', 'fas fa-globe fa-2x', 'Servicios de comunicaciónes' );
        renderMenuItem( $access[ 'password' ], 'password', 'fas fa-lock fa-2x', 'Contraseñas' );
        renderMenuItem( $access[ 'autos' ], 'autos', 'fas fa-car fa-2x', 'Automóviles' );
        renderMenuItem( $access[ 'stock' ], 'stock', 'fas fa-archive fa-2x', 'Mobiliario' );
        renderMenuItem( $access[ 'maquinaria' ], 'maquinaria', 'fas fa-cogs fa-2x', 'Maquinaria' );
        renderMenuItem( $access[ 'polizas' ], 'polizas', 'fa fa-shield fa-2x', 'Pólizas de seguro' );
        renderMenuItem( $access[ 'accesos' ], 'accesos', 'fas fa-key fa-2x', 'Accesos' );

        ?>
        
        <!-------------------END configuraciones--------------------> 
      </div>
    </div>
  </main>
</div>
 
<?php

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );
?>
