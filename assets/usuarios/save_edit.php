<?php
session_start();
// Verificar si la variable de sesión 'usuario' está definida y no es nula
if (!isset($_SESSION['user_ceers']) || $_SESSION['user_ceers'] === null || $_SESSION['activo'] == 0) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header("Location: denegado");
    exit; // También puedes usar die en lugar de exit
}
include($_SERVER['DOCUMENT_ROOT'] . '/conexion.php');
include($_SERVER['DOCUMENT_ROOT'] . '/assets/historial.php');

$id_usuario=$_POST['id_usuario'];
$nombre = trim($_POST['nombre']);
$email = trim($_POST['usuario-email']);
$departamento = $_POST['departamento'];
$password_usuario = trim($_POST['password']); // Cambiado el nombre de la variable

$tipo = $_POST['tipo'];
if($tipo = 1){
$computo = isset($_POST['computo']) ? $_POST['computo'] : "0";
$moviles = isset($_POST['moviles']) ? $_POST['moviles'] : "0";
$dispositivos = isset($_POST['dispositivos']) ? $_POST['dispositivos'] : "0";
$personal = isset($_POST['personal']) ? $_POST['personal'] : "0";
$licencias = isset($_POST['licencias']) ? $_POST['licencias'] : "0";
$servicios = isset($_POST['servicios']) ? $_POST['servicios'] : "0";
$password_acceso = isset($_POST['password_acceso']) ? $_POST['password_acceso'] : "0"; // Cambiado el nombre de la variable
$autos = isset($_POST['autos']) ? $_POST['autos'] : "0";
$stock = isset($_POST['stock']) ? $_POST['stock'] : "0";
$maquinaria = isset($_POST['maquinaria']) ? $_POST['maquinaria'] : "0";
$polizas = isset($_POST['polizas']) ? $_POST['polizas'] : "0";
$accesos = isset($_POST['accesos']) ? $_POST['accesos'] : "0";
	}
$imagen = "";
if (!empty($_FILES['archivos']['name']) && $_FILES['archivos']['name'] != ""){
    $ruta_imagen = $_FILES['archivos']['name'];
    $imagen = ", `ruta_imagen` = '$ruta_imagen' ";
}


$stmt = $conexion->prepare("
UPDATE
    `usuarios`
SET
    `nombre` = ?,
    `email` = ?,
    `departamento` = ?,
    `password` = ?
	$imagen
WHERE	
    `id_usuarios` = ?");
$stmt->bind_param("sssss", $nombre, $email, $departamento, $password_usuario, $id_usuario);
//variables para guardar historial
$usu_usuario = $_SESSION['user_name'];
$accion = "Registro edito el  USUARIO: " . $nombre;
if ($stmt->execute()) {

    insert_history($conexion, $usu_usuario, $accion);

    // guardar archivos
    $directorio = $_SERVER['DOCUMENT_ROOT'] . "/uploads/img_perfil/$id_usuario"; //Declaramos un  variable con la ruta donde guardaremos los archivos

  // Movemos el archivo al directorio de destino
    $target_path = $directorio . '/' . $_FILES["archivos"]["name"];
    move_uploaded_file($_FILES["archivos"]["tmp_name"], $target_path);
	if($tipo = 1){
$stmt2 = $conexion->prepare("
UPDATE
    `access`
SET
    `computo` = ?,
    `moviles` = ?,
    `dispositivos` = ?,
    `personal` = ?,
    `licencias` = ?,
    `servicios` = ?,
    `password` = ?,
    `autos` = ?,
    `stock` = ?,
    `maquinaria` = ?,
    `polizas` = ?,
    `accesos` = ?
WHERE
    `id_usuario` = ?
	");
    $stmt2->bind_param("sssssssssssss",  $computo, $moviles, $dispositivos, $personal, $licencias, $servicios, $password_acceso, $autos, $stock, $maquinaria, $polizas, $accesos,$id_usuario);
    $stmt2->execute();
    $stmt2->close();
	}
    echo "<script>location.href='ver_usuario$id_usuario'</script>";
}else{
	// Manejo de errores en caso de falla en la inserción
    echo "<script>location.href='error_page'</script>";
}
// Cerrar la consulta preparada
$stmt->close();


?>