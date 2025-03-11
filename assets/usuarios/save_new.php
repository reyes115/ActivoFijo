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

$nombre = trim($_POST['nombre']);
$email = trim($_POST['usuario-email']);
$departamento = $_POST['departamento'];
$password_usuario = trim($_POST['password']); // Cambiado el nombre de la variable

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
$ruta_imagen = isset($_FILES['archivos']['name']) ? $_FILES['archivos']['name'] : "";

$stmt = $conexion->prepare("INSERT INTO `usuarios`(
    `nombre`,
    `email`,
    `departamento`,
    `password`,
    `ruta_imagen`
)
VALUES(
    ?,
    ?,
    ?,
    ?,
    ?
)");
$stmt->bind_param("sssss", $nombre, $email, $departamento, $password_usuario, $ruta_imagen);

//variables para guardar historial
$usu_usuario = $_SESSION['user_name'];
$accion = "Registro nuevo USUARIO: " . $nombre;
if ($stmt->execute()) {

    $id_usuario = $conexion->insert_id;
    insert_history($conexion, $usu_usuario, $accion);

    // guardar archivos
    $directorio = $_SERVER['DOCUMENT_ROOT'] . "/uploads/img_perfil/$id_usuario"; //Declaramos un  variable con la ruta donde guardaremos los archivos

    //Validamos si la ruta de destino existe, en caso de no existir la creamos
    if (!file_exists($directorio)) {
        mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");
    }

  // Movemos el archivo al directorio de destino
    $target_path = $directorio . '/' . $_FILES["archivos"]["name"];
    move_uploaded_file($_FILES["archivos"]["tmp_name"], $target_path);

    $stmt2 = $conexion->prepare("INSERT INTO `access`(
    `id_usuario`,
    `computo`,
    `moviles`,
    `dispositivos`,
    `personal`,
    `licencias`,
    `servicios`,
    `password`,
    `autos`,
    `stock`,
    `maquinaria`,
    `polizas`,
    `accesos`
)
VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt2->bind_param("sssssssssssss", $id_usuario, $computo, $moviles, $dispositivos, $personal, $licencias, $servicios, $password_acceso, $autos, $stock, $maquinaria, $polizas, $accesos);
    $stmt2->execute();
    $stmt2->close();
    echo "<script>location.href='accesos'</script>";
}else{
	// Manejo de errores en caso de falla en la inserción
    echo "<script>location.href='error_page'</script>";
}
// Cerrar la consulta preparada
$stmt->close();

?>
