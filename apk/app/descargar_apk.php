<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Descargar App</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .card {
      margin-top: 100px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header" >
            Descargar App
          </div>
          <div class="card-body text-center">
            <p>Haz clic en el botón para comenzar con la descargar de la aplicación:</p>
            <a href="?download=true" class="btn btn-primary">Descargar</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
    // Verificar si se ha solicitado la descarga
    if(isset($_GET['download']) && $_GET['download'] == 'true') {
      // Servir la APK
    // Redirigir al usuario a la ubicación del archivo APK
$apk_file = 'https://ceers.innovet.com.mx/apk/app/app-AB_FORTI_2.0.0.BETA.apk'; // Ruta de tu APK
header('Location: ' . $apk_file);
exit;
    }
  ?>

  <!-- Bootstrap JS (opcional, solo si necesitas funcionalidades como el dropdown o el modal) -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>