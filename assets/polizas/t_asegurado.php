<?php
include($_SERVER['DOCUMENT_ROOT'] . '/conexion.php');

$html = "";

if (isset($_POST["elegido"])) {
    $elegido = $_POST["elegido"];

    if ($elegido == 0) {
        $html = '<option value="">Seleccione un tipo de asegurado</option>';
    } else {
        switch ($elegido) {
            case 1:
                $sql = "SELECT `id_autos`, CONCAT(codigo, ' - ', nombre, ' ', a_paterno, ' ', a_materno) as nombre_completo FROM `autos` LEFT JOIN personal ON personal_id_personal = id_personal WHERE activo = 1 AND estatus = 1
				ORDER BY nombre_completo;
				";
                $option_text = "Seleccione un auto";
                break;
            case 2:
                $sql = "SELECT `id_personal`, CONCAT(nombre, ' ', a_paterno, ' ', a_materno) AS nombre_completo FROM `personal` 
				ORDER BY nombre_completo;";
                $option_text = "Seleccione al colaborador";
                break;
            case 3:
                $sql = "SELECT `id_inmobiliario`, `name` FROM `inmobiliario` ORDER BY name; ";
                $option_text = "Seleccione el inmobiliario";
                break;
        }

        $stmt = mysqli_prepare($conexion, $sql);
        if ($stmt) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id, $nombre);
            while (mysqli_stmt_fetch($stmt)) {
                $html .= '<option value="' . $id . '">' . $nombre . '</option>';
            }
            mysqli_stmt_close($stmt);
        }
    }
}

echo $html;
?>
