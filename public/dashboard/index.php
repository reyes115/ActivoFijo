<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/silderbar.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/dashboard/dashboard_cards.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/dashboard/dashboard_charts.php' );
?>
<!-- Contenido de la pagina -->
<div id="layoutSide nav_content">
  <main>
    <div class="container-fluid">
      <div class="row align-items-center justify-content-center">
        <?php
        // Función para renderizar elementos de tarjeta
        function renderCardItem( $accessValue, $title, $count, $iconClass, $borderClass ) {
          if ( $accessValue == 1 || $accessValue == 2 ) {
            ?>
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card <?php echo $borderClass   ?> shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2" align="center">
                  <div class="text-xs font-weight-bold text-warning text-uppercase mb-1"><?php echo  $title ?></div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $count ?></div>
                </div>
                <div class="col-4"><i class="<?php echo $iconClass ?> fa-2x text-gray-300"></i></div>
              </div>
            </div>
          </div>
        </div>
        <?php
        }
        }
        // Parte superior
        $cardInfo = [
          [ 'computo', 'EQUIPOS DE CÓMPUTO', $COUNT_computo, 'fas fa-laptop', 'border-bottom-danger' ],
          [ 'moviles', 'EQUIPOS MÓVILES', $COUNT_moviles, 'fas fa-mobile', 'border-bottom-success' ],
          [ 'autos', 'AUTOMÓVILES', $COUNT_autos, 'fas fa-car', 'border-bottom-primary' ],
          [ 'stock', 'MOBILIARIO', $COUNT_stock, 'fas fa-archive', 'border-bottom-secondary' ],
        ];

        foreach ( $cardInfo as $info ) {
          renderCardItem( $access[ $info[ 0 ] ], $info[ 1 ], $info[ 2 ], $info[ 3 ], $info[ 4 ] );
        }

        ?>
      </div>
      <div class="row align-items-center justify-content-center">
        <?php
        // parte inferior
        $cardInfo2 = [
          [ 'computo', 'EQUIPOS DE CÓMPUTO DISPONIBLES', $COUNT_computoDisponible, 'fas fa-laptop', 'border-bottom-danger' ],
          [ 'moviles', 'EQUIPOS MÓVILES DISPONIBLES', $COUNT_movilesDisponible, 'fas fa-mobile', 'border-bottom-success' ],
          [ 'autos', 'AUTOMÓVILES DISPONIBLES', $COUNT_autosDisponible, 'fas fa-car', 'border-bottom-primary' ],
          [ 'stock', 'MOBILIARIO DISPONIBLES', $COUNT_stockDisponible, 'fas fa-archive', 'border-bottom-secondary' ],
        ];

        foreach ( $cardInfo2 as $info ) {
          renderCardItem( $access[ $info[ 0 ] ], $info[ 1 ], $info[ 2 ], $info[ 3 ], $info[ 4 ] );
        }
        ?>
      </div>
      <div class="row align-items-center justify-content-between">
        <?php


        // Función para renderizar tarjetas de gráficos
        function renderChartCard( $accessValue, $title, $title2, $COLOR_PRIMARY, $COLOR_HOVER, $chartId, $dataLabels, $dataValues ) {
          if ( $accessValue == 1 || $accessValue == 2 ) {
            ?>
        <div class="col-xl-6 col-lg-7">
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold "><?php echo $title ?></h6>
            </div>
            <div class="card-body">
              <div>
                <div class="chartjs-size-monitor">
                  <div class="chartjs-size-monitor-expand"></div>
                  <div class="chartjs-size-monitor-shrink"></div>
                </div>
                <canvas id="<?php echo $chartId ?>"></canvas>
              </div>
            </div>
          </div>
        </div>
        <script>
            var ctx = document.getElementById("<?php echo $chartId ?>");
            var data = {
                labels: <?php echo json_encode( $dataLabels ) ?>,
                datasets: [{
                    label: "<?php echo $title2 ?>",
                    backgroundColor: "<?php echo $COLOR_HOVER ?>",
                    hoverBackgroundColor: "<?php echo $COLOR_PRIMARY ?>",
                    borderColor: "<?php echo $COLOR_HOVER ?>",
                    data: <?php echo json_encode( $dataValues ) ?>,
                }],
            };
            var options = {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            };
            var <?php echo $chartId ?> = new Chart(ctx, {
                type: "bar",
                data: data,
                options: options
            });
        </script>
        <?php
        }
        }

        // Renderizar tarjetas de gráficos
        $chartInfo = [
          [ 'computo', 'EQUIPOS DE CÓMPUTO POR EMPRESAS', 'Equipos', '#e74a3b', '#c31f24', 'chart1', $dataLabels, $dataValues1 ],
          [ 'computo', 'EQUIPOS DE CÓMPUTO POR PROPIETARIOS', 'Equipos', '#e74a3b', '#c31f24', 'chart2', $dataLabels2, $dataValues2 ],
          [ 'moviles', 'EQUIPOS MÓVILES POR EMPRESAS', 'Equipos', '#1cc88a', '#009627', 'chart3', $dataLabels, $dataValues3 ],
          [ 'moviles', 'EQUIPOS MÓVILES POR PROPIETARIOS', 'Equipos', '#1cc88a', '#009627', 'chart4', $dataLabels3, $dataValues4 ],
          [ 'autos', 'AUTOMÓVILES POR EMPRESAS', 'Autos', '#0133d4', '#4e73df', 'chart5', $dataLabels, $dataValues5 ],
          [ 'autos', 'AUTOMÓVILES POR PROPIETARIOS', 'Autos', '#0133d4', '#4e73df', 'chart6', $dataLabels4, $dataValues6 ],
          [ 'stock', 'MOBILIARIO POR EMPRESAS', 'Mobiliario', '#2c3036', '#858796', 'chart7', $dataLabels, $dataValues7 ],
          [ 'stock', 'MOBILIARIO POR PROPIETARIOS', 'Mobiliario', '#2c3036', '#858796', 'chart8', $dataLabels4, $dataValues8 ],
        ];

        foreach ( $chartInfo as $info ) {
          renderChartCard( $access[ $info[ 0 ] ], $info[ 1 ], $info[ 2 ], $info[ 3 ], $info[ 4 ], $info[ 5 ], $info[ 6 ], $info[ 7 ] );
        }

        ?>
      </div>
      <!---------------------------end chart------------------------> 
 

    <!-- FullCalendar CSS -->
    
<?php if ($access['computo'] == 1 || $access['computo'] == 2 ){
    
    ?>
    <div>
      <div class="row  justify-content-center ">
        
        <div class="shadow mb-4 col">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold ">CALENDARIO DE MANTENIMIENTOS DE COMPUTO</h6>
            </div>
            <div class="col mb-4 ">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
        <style>
  #calendar {
    max-width: 1000px; /* Tamaño máximo por defecto para pantallas grandes */
    margin: 0 auto;
  }

  @media (max-width: 767px) {
    #calendar {
      max-width: 400px; /* Tamaño máximo para dispositivos móviles */
        font-size: 7px;
    }
  }
</style>
            
    <script src="calendarJs"></script>
        </div>
        <?php
    }
    ?>
        
    </div>
  </main>
</div>
<?php
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/footer.php' );
?>
