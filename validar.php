<?php
session_start();
include 'conexion.php';
include( 'public/error_handler.php');
$usu_usuario = $_POST['username'] ?? '';
$usu_password = $_POST['password'] ?? '';

if (empty($usu_usuario) || empty($usu_password)) {
    // Manejar el caso en el que el usuario o la contraseña estén vacíos
    echo "<script>location.href='incorrecto'</script>";
    exit();
}

date_default_timezone_set('America/Mexico_City');
$fecha = date("Y-m-d H:i:s");
$accion = "login";

$sentencia = $conexion->prepare("SELECT * FROM usuarios WHERE email=? AND password=? AND activo=1");
$sentencia->bind_param('ss', $usu_usuario, $usu_password);
$sentencia->execute();

$resultado = $sentencia->get_result();

if ($validar = $resultado->fetch_assoc()) {
    $_SESSION[ 'id_ceers' ] = $validar[ 'id_usuarios' ];
    $_SESSION[ 'user_name' ] = $validar[ 'nombre' ];
    $_SESSION[ 'user_ceers' ] = $validar[ 'email' ];
    $_SESSION[ 'rol_ceers' ] = $validar[ 'tipo' ];
    $_SESSION[ 'password' ] = $validar[ 'password' ];
    $_SESSION[ 'activo' ] = $validar[ 'activo' ];
    $_SESSION[ 'img_profile' ] = $validar[ 'ruta_imagen' ];

    $sql = "INSERT INTO movimientos (usuario, accion, fecha) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $usu_usuario, $accion, $fecha);
    $stmt->execute();

 // Si hay una ruta destino, redirige a esa ruta, de lo contrario, redirige a inicio
    if (isset($_SESSION['ruta_destino'])) {
        $ruta_destino = $_SESSION['ruta_destino'];
        unset($_SESSION['ruta_destino']); // Limpia la variable de sesión después de usarla
        header("Location: $ruta_destino");
    } else {
        header('Location: inicio');
    }
    exit();
} else {
    header('Location: incorrecto');
    exit();
}

$sentencia->close();
$conexion->close();
?>
