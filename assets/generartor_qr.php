<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/lib/qr_generator/barcode.php');

$generator = new barcode_generator();
    

    // Definir opciones para el código QR
    $options = [
        'w' => 300,  // Ancho de la imagen (cambia este valor para ajustar el tamaño)
        'h' => 300,  // Ancho de la imagen (cambia este valor para ajustar el tamaño)
        'p' => -40  // Relleno. 
    ];
    

    // Generar el código QR en formato PNG
    $image = $generator->render_image("qr-h", $urlQr, $options);

$logo_QR= $_SERVER[ 'DOCUMENT_ROOT' ] . '/img/AB_FORTI/JPG/AB_FORTI_Logotipo-02.jpg';
    // Cargar el logo
    $logo = imagecreatefromjpeg($logo_QR); // Reemplaza con la ruta real de tu logo en formato PNG
    
    // Obtener las dimensiones del código QR y del logo redimensionado
    $qr_width = imagesx($image);
    $qr_height = imagesy($image);
    
    $width_logo= imagesx($logo);
    $height_logo= imagesy($logo);
    
    if ($width_logo == $height_logo){
        // Redimensionar el logo a un tamaño específico (por ejemplo, 100x100 píxeles)
    $logo_width =  $qr_width/3;
    $logo_height =  $qr_height/3;
        
    }else{
        // Redimensionar el logo a un tamaño específico (por ejemplo, 100x100 píxeles)
    $logo_width =  $qr_width/3;
    $logo_height =  $qr_height/7;
        
    }
    
    
    $resized_logo = imagescale($logo, $logo_width, $logo_height);
    
    $logo_width = imagesx($resized_logo);
    $logo_height = imagesy($resized_logo);

    // Calcular la posición para centrar el logo en el código QR
    $x = ($qr_width - $logo_width) / 2;
    $y = ($qr_height - $logo_height) / 2;

    // Fusionar el código QR y el logo redimensionado
    imagecopy($image, $resized_logo, $x, $y, 0, 0, $logo_width, $logo_height);


    // Establecer el encabezado para mostrar una imagen PNG en el navegador
   // header('Content-Type: image/png');

     
    // Ruta donde deseas guardar el archivo PNG
    $ruta_guardado = $_SERVER['DOCUMENT_ROOT'] . '/uploads/'. $t_qr.'/'. $codigo.'/' . $QRKey . '_qr.png'; // Generar un nombre único para el archivo
    
    // Guarda la imagen PNG en la ruta especificada
    imagepng($image, $ruta_guardado);
    //Mostrar la imagen PNG con el logo
    //imagepng($image);

    // Liberar la memoria
    imagedestroy($image);
    ?>
