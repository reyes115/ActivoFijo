<?php
header("Content-Type: application/json");
include($_SERVER['DOCUMENT_ROOT'] . '/conexion.php');

// Consulta para obtener la versión más reciente y la URL del APK
$sql = "SELECT version,`apk_url` FROM versiones_app ORDER BY `id_version` DESC LIMIT 1;";
$stmt = $conexion->prepare($sql);

if ($stmt) {
    // Ejecutar la consulta
    $stmt->execute();

    // Vincular el resultado de la consulta a variables PHP
    $stmt->bind_result($latest_version, $apk_url);

    // Obtener el resultado
    if ($stmt->fetch()) {
        // Devolver la respuesta en formato JSON
        echo json_encode(array("latest_version" => $latest_version, "apk_url" => $apk_url));
    } else {
        // Si no se encuentra ninguna versión, devolver un error
        echo json_encode(array("error" => "No se encontró ninguna versión."));
    }

    // Cerrar el statement
    $stmt->close();
} else {
    // Si la preparación de la consulta falla, devolver un error
    echo json_encode(array("error" => "Error al preparar la consulta."));
}

// Cerrar conexión a la base de datos
$conexion->close();
?>
