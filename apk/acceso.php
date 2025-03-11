<?php
session_start();
include 'conexion.php';
$usu_usuario=$_GET['usuario'];
$usu_password=$_GET['password'];
date_default_timezone_set('America/Mexico_City');
$DateAndTime = date('m-d-Y h:i:s a', time());  
//$usu_usuario="camachoorlando0@gmail.com";
//$usu_password="qwerty";

$sentencia=$conexion->prepare("SELECT * FROM usuarios WHERE email=? and password=? ");
$sentencia->bind_param('ss',$usu_usuario,$usu_password);
$sentencia->execute();

$resultado = $sentencia->get_result();
if ($validar = $resultado->fetch_assoc()) {
	
             $_SESSION[ 'id' ] = $validar[ 'id_usuarios' ];
    $_SESSION[ 'user' ] = $validar[ 'nombre' ];
    $_SESSION[ 'email' ] = $validar[ 'email' ];
    $_SESSION[ 'rol' ] = $validar[ 'tipo' ];
	$_SESSION[ 'pass' ] = $validar[ 'password' ];
    $name=$validar[ 'nombre' ];
 $login=$conexion->prepare("INSERT INTO `login`(`id_login`, `user`, `date`) VALUES (null,'$name','$DateAndTime');");
	$login->execute();
    $tipo = $validar[ 'tipo' ];
  switch ( $tipo ) {
        case 1:
            echo "<script>location.href='/scriinn/admin/'</script>";
            break;
        case 2:
            echo "<script>location.href='/scriinn/usuario2/'</script>";
            break;
        case 3:
            echo "<script>location.href='/scriinn/usuario3/'</script>";
            break;
        case 4:
            echo "<script>location.href='/scriinn/usuario4/'</script>";
            break;
    }  
}
$sentencia->close();
$conexion->close();
?>