<?php
session_start();

// Verificar si la variable de sesión 'usuario' está definida y no es nula
if ( !isset( $_SESSION[ 'user_ceers' ] ) || $_SESSION[ 'user_ceers' ] === null || $_SESSION[ 'activo' ] == 0 ) {
   
	
$_SESSION['ruta_destino'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    // Redireccionar al usuario a la página de denegado
  header( "Location: denegado" );
  exit; // También puedes usar die en lugar de exit
}

include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );
include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/access.php' );
//include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/public/error_handler.php' );
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<meta name="description" content="Sistema de control de activos de AB FORTI corporativo, desarrollado por el área de informática y comunicaciones." />
<meta name="author" content="Diego Camacho Martínez" />
<title>Sistema de Control de Activos de AB FORTI</title>
<link rel="icon" type="image/png" href="logo4">
<!-- Custom fonts for this template-->
<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

<!-- Custom styles for this template-->

<link href="css/sb-admin-2.css" rel="stylesheet">

<!-- Custom styles for this page -->
<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script> 
<script rel="prefetch" src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 

<!-- Page level plugins -->
</head>

<body id="sb-nav-fixed">

<!-- Page Wrapper -->
<div id="wrapper" class="bg-principal">

<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
  
  <!-- Sidebar - Brand --> 
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="inicio"> <img src="logo17" width="100%" class="d-none d-lg-block" > <img src="logo18" width="100%" class="d-lg-none"> </a>
  <style>
       
@media (max-width: 768ppx) {
  #ocultar_movil{
    display: none;
  }
    
       #qrButtonContainer {
        text-align: center;
        display: none;
    }
	@media screen and (max-width: 768px) {
  * {
    font-size: 2vw;
  }
	
        </style>
  <hr class="sidebar-divider my-0">
  <li class="nav-item "> <a class="nav-link" href="dashboard"> <i class="fas fa-fw fa fa-tachometer" aria-hidden="true"></i>
    <span class="text-white">Dashboard</span>
    </a> </li>
  <?php

  function renderNavItem( $accessValue, $iconClass, $link, $text ) {
    if ( $accessValue == 1 || $accessValue == 2 ) {
      echo '
      <li class="nav-item "> 
          <a class="nav-link" href="' . $link . '"> 
              <i class="' . $iconClass . '">
              </i>
                <span>' . $text . '</span>
          </a>
      </li>';
    }
  }

  function renderCollapseItem( $accessValue, $link, $text ) {
    if ( $accessValue == 1 || $accessValue == 2 ) {
      echo '
      <a class="collapse-item" href="' . $link . '" style="font-size: 90%">' . $text . '</a>';
    }
  }

  // Verificación general de acceso a la sección de informática
  if ( $access[ 'computo' ] > 0 || $access[ 'moviles' ] > 0 || $access[ 'dispositivos' ] > 0 || $access[ 'licencias' ] > 0 || $access[ 'servicios' ] > 0 || $access[ 'password' ] > 0 ) {
    echo '
    <hr class="sidebar-divider">
    <div class="sidebar-heading sb-sidenav-menu-heading"> informática 
    </div>';
  }

  // Sección de Equipos
  if ( $access[ 'computo' ] > 0 || $access[ 'moviles' ] > 0 || $access[ 'dispositivos' ] > 0 ) {
    ?>
  <li class="nav-item "> <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo"> <i class="fas fa-fw fa-folder"></i>
    <span>Equipos</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <?php

        // Renderizar elementos de la sección de Equipos
        renderCollapseItem( $access[ 'computo' ], 'computo', 'Equipos de cómputo' );
        renderCollapseItem( $access[ 'moviles' ], 'moviles', 'Equipos móviles' );
        renderCollapseItem( $access[ 'dispositivos' ], 'dispositivos', 'Equipos alternos' );

        ?>
      </div>
    </div>
  </li>
  <?php
  }

  // Renderizar otros elementos del menú de informatica
  renderNavItem( $access[ 'licencias' ], 'fas fa-fw fa-certificate', 'licencias', 'Licencias de software' );
  renderNavItem( $access[ 'servicios' ], 'fa fa-fw fa-globe', 'servicios', 'Servicios' );
  renderNavItem( $access[ 'password' ], 'fas fa-fw fa-lock', 'password', 'Contraseñas' );


  // Verificación general de acceso a la sección de control
  if ( $access[ 'personal' ] > 0 || $access[ 'autos' ] > 0 || $access[ 'stock' ] > 0 || $access[ 'maquinaria' ] > 0 || $access[ 'polizas' ] > 0 ) {
    echo '
    <hr class="sidebar-divider">
    <div class="sidebar-heading sb-sidenav-menu-heading"> Control 
    </div>';
  }


  // Renderizar elementos del menú de Control
  renderNavItem( $access[ 'personal' ], 'fas fa-fw fa-group', 'personal', 'Colaboradores' );
  renderNavItem( $access[ 'autos' ], 'fas fa-fw fa-car', 'autos', 'Autos' );
  renderNavItem( $access[ 'stock' ], 'fas fa-fw fa-archive', 'stock', 'Mobiliario' );
  renderNavItem( $access[ 'maquinaria' ], 'fas fa-fw fa-cogs', 'maquinaria', 'Maquinaria' );
  renderNavItem( $access[ 'polizas' ], 'fas fa-fw fa fa-shield', 'polizas', 'Pólizas' );

  // Verificación general de acceso a la sección de ajustes
  if ( $access[ 'accesos' ] > 0 ) {
    echo '
    <hr class="sidebar-divider">
    <div class="sidebar-heading sb-sidenav-menu-heading"> AJUSTES
    </div>';
  }

  renderNavItem( $access[ 'accesos' ], 'fas fa-fw fa-key', 'accesos', 'Accesos' );
  renderNavItem( $access[ 'android' ], 'fa fa-fw fa-android', 'android', 'APK´S' );
  ?>
  
  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">
  
  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>
	
</ul>
<!-- End of Sidebar --> 

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

<!-- Main Content -->
<div id="content">

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"> 
  
  <!-- Sidebar Toggle (Topbar) -->
  <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"> <i class="fa fa-bars"></i> </button>
	<?php 
					 // Añadir la condición para Android aquí
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if (strpos($userAgent, 'Android') !== false ) {
         
        ?>
        <!-- Botón para abrir el modal -->
    <button type="button" class="btn btn-primary btn-sm" onclick="redirectToApp()">
        Escanear QR
    </button>
	<?php }else{?>
	
	
	 <button class=" btn btn-primary " data-toggle="modal" data-target="#download"> <i class="fa fa-android"></i> Descarga la App </button> 
	
	
	
	    <!-- Modal para mostrar el resultado del escaneo -->
    <div class="modal fade" id="download" tabindex="-1" role="dialog" aria-labelledby="escanearModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="escanearModalLabel">Descarga la aplicación </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="col-auto mb-4" >

                            <!-- Illustrations -->
                            
                             
                                    <div class="text-center">
                                         <h4>Escanea el código QR para descargar la APK</h4>
    <?php
        // URL de tu página donde se encuentra la APK
        $url = 'https://ceers.innovet.com.mx/apk';
        echo "<img src='https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$url' alt='Código QR' class='img-fluid px-3 px-sm-4 mt-3 mb-4'>";
    ?>
                                    </div>
                                    <p>Escanea el código QR para descargar la aplicación. Si la descarga no inicia, también puedes descargar la aplicación haciendo clic en el siguiente enlace</p>
                                    <a target="_blank" rel="nofollow" href="https://ceers.innovet.com.mx/apk">Descarga la app →</a>
                               
                          
                    </div>
                    </div>
           
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>    
			     </div>
		

			
		<?php } ?>
  <!-- Topbar Navbar -->
  <ul class="navbar-nav ml-auto" >
    <?php
    if ( !isset( $_SESSION[ 'img_profile' ] ) ) {
      $perfil = "img/AB_FORTI/JPG/AB_FORTI_Logotipo-02.jpg";

    } else {
      $perfil = "uploads/img_perfil/" . $_SESSION[ 'id_ceers' ] . "/" . $_SESSION[ 'img_profile' ];
    }
    ?>
    <li class="nav-item dropdown no-arrow show"> <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <span class="mr-2 d-none d-lg-inline text-gray-600 small" style="font-size: 16px; "><?php echo $_SESSION[ 'user_name' ]?></span>
      <img class="img-profile rounded-circle" src="<?php echo $perfil?>"> </a> 
      <!-- Dropdown - User Information -->
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown"> 
		<a class="dropdown-item" href="ver_usuario<?php echo $_SESSION[ 'id_ceers' ]?>"> <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Perfil </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" data-toggle="modal" data-target="#logoutModal"> <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Cerrar sesión </a> </div>
    </li>
  </ul>
</nav>
<!-- End of Topbar --> 

<!-- start body--> 

     <!-- Modal para mostrar el resultado del escaneo -->
    <div class="modal fade" id="escanearModal" tabindex="-1" role="dialog" aria-labelledby="escanearModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="escanearModalLabel">Escanear QR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <video id="video" width="100%" height="auto" autoplay></video>
                    <canvas id="canvas" style="display:none;"></canvas>
                    <div id="qrButtonContainer" style="display:none;" align="center">
                        <button id="qrButton" class="btn btn-success">Abrir</button>
                    </div> 
					<div id="qrnovalido" style="display:none;" align="center">
                        <p id="qrinvalido" >El codigo no es valido</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- End of Topbar -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-axu-2">
            <h5 class="modal-title text-white" id="logoutModalLabel">Advertencia </h5>
            <button type="button" class="close  text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">
            &times; 
            </span>
            </button>
          </div>
          <div class="modal-body">
            <p>Antes de cerrar la sesión, recuerda guardar cualquier información importante, ya que podría perderse si no se guarda correctamente</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <a type="button" class="btn btn-primary" href="logout">Continuar</a> </div>
        </div>
      </div>
    </div>
 

    
<!-- Bootstrap core JavaScript--> 
<script src="vendor/jquery/jquery.min.js"></script> 
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script> 

<!-- Core plugin JavaScript--> 
<script src="vendor/jquery-easing/jquery.easing.min.js"></script> 

<!-- Custom scripts for all pages--> 
<script src="js/sb-admin-2.min.js"></script> 

   <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script> 
    
 

  <!-- Page level plugins --> 
    <script src="vendor/chart.js/Chart.min.js" ></script> 
 
    <!-- Incluir la librería jsQR desde un CDN -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
   <script>
        var videoStream;

       // Función para abrir la cámara y detectar el QR
function abrirCamara() {
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(function(stream) {
            videoStream = stream; // Guardar el stream de video
            var video = document.getElementById('video');
            video.srcObject = stream;
            video.play();

            var canvas = document.getElementById('canvas');
            var context = canvas.getContext('2d');

            var scanInterval = setInterval(function() {
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    var imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    var code = jsQR(imageData.data, imageData.width, imageData.height);
                    if (code) {
                        clearInterval(scanInterval); // Detener el escaneo
                        var qrResult = code.data;
                        if (qrResult.startsWith("https://ceers.innovet.com.mx/")) {
                            document.getElementById('qrButtonContainer').style.display = 'block';
                            var qrButton = document.getElementById('qrButton');
                            qrButton.onclick = function() {
                                window.location.href = qrResult;
                            };
                        } else {
                            // Mostrar un mensaje de error si la URL no es válida
                           
                            document.getElementById('qrnovalido').style.display = 'block';
                        }
                    }
                }
            }, 1000);
        })
        .catch(function(error) {
            console.error('Error al acceder a la cámara:', error);
        });
    } else {
        console.error('La API de captura de medios no está soportada en este navegador.');
    }
}

     
      // Redirigir si es un dispositivo con la app específica
        function redirectToApp() {
            var userAgent = navigator.userAgent || navigator.vendor || window.opera;
           if (/android/i.test(userAgent) && userAgent.includes("com.example.ab_forti")) {
                window.location.href = 'https://ceers.innovet.com.mx/mostrar_modal.php';
            } else {
                // Abrir el modal para escanear el QR
                $('#escanearModal').modal('show');
                abrirCamara();
            }
        }
        
        // Detener la cámara al cerrar el modal
        $('#escanearModal').on('hidden.bs.modal', function () {
            var video = document.getElementById('video');
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
            }
            document.getElementById('qrButtonContainer').style.display = 'none'; // Ocultar el botón
            document.getElementById('qrnovalido').style.display = 'none'; // Ocultar el botón
        });
    </script>

</body>
</html>
