<?php
session_start();
if ( isset( $_SESSION[ 'user_ceers' ] ) && $_SESSION[ 'user_ceers' ] ) {
  header( "Location: inicio" );
  exit();
}
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
<link rel="icon" type="image/png" href="logo1">

<!-- Custom styles for this template-->
<link href="css/sb-admin-2.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>
  <script>
        function redirectToApp() {
            var userAgent = navigator.userAgent || navigator.vendor || window.opera;
            
            if (/android/i.test(userAgent)) {
                var intentUrl = "intent://#Intent;scheme=ab_forti;package=com.example.ab_forti;end";
                window.location.href = intentUrl;
                
            } 
        }

        window.onload = redirectToApp;
    </script>
<body class="fondo-inicio blur-inicio">

<!-- Navigation -->

<div class="col">
  <div class="container" >
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
      <div class="col-lg-5f">
        <div class="card shadow-lg border-0 rounded-lg mt-5f">
          <div class="card-header bg-principal">
            <h3 class="text-center text-white my-2"><b>INICIAR SESIÓN</b></h3>
          </div>
          <br>
          <?php
          if ( isset( $_GET[ 'NS' ] ) ) {
            ?>
          <p class="alert alert-warning " align="center"> Debe iniciar sesión para acceder a esta página. </p>
          <?php
          };
          ?>
          <?php
          if ( isset( $_GET[ 'in' ] ) ) {
            ?>
          <p class="alert alert-warning " align="center"> Usuario o contraseña incorrectos. </p>
          <?php
          };
          ?>
			
          <div align="center"><img class"rounded-circle img-profile" style="width: 180px;
 height: 180px;" src="logo4"></div>
          <div class="card-body">
            <form action="validar.php" method="POST">
              <div class="form-floating mb-2">
                <input class="form-control " id="username" name="username" type="text" placeholder="Coloca tu usuario" />
                <label >Usuario</label>
              </div>
              <div class="input-group  form-floating mb-2">
                <input class="form-control" id="password" name="password" type="password" placeholder="Coloca tu contraseña" />
                <label >Contraseña</label>
                <div class="input-group-append">
                  <button id="show_password" class="btn btn-primary" type="button" onclick="mostrarPassword()">
                  <span class="fa fa-eye-slash icon"></span>
                  </button>
                </div>
              </div>
              <div align="center">
                <input type="submit" class="btn btn-primary" name="Entrar" value="Iniciar sesión">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
	
<script type="text/javascript">

    function mostrarPassword(){
            var cambio = document.getElementById("password");
            if(cambio.type == "password"){
                cambio.type = "text";
                $('.icon').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
            }else{
                cambio.type = "password";
                $('.icon').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
            }
        } 
    </script> 

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>