<?php


include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/conexion.php' );
// Realiza la consulta SQL
$sql = "
SELECT
    id_compu,
	QRKey,
    codigo,
    fecha_sym,
    CONCAT(
        personal.nombre,
        ' ',
        personal.a_paterno
    ) AS nombre
FROM
    `computadora`
LEFT JOIN personal ON personal_id = id_personal
WHERE
    fecha_sym IS NOT NULL AND fecha_sym != '0000-00-00' AND `personal_id` IS NOT NULL;
";

// Preparar la consulta
$stmt = $conexion->prepare($sql);

// Ejecutar la consulta
$stmt->execute();

// Obtener resultados
$result = $stmt->get_result();

// Verifica si hay resultados
if ($result->num_rows > 0) {
    // Inicializa un array para almacenar los eventos
    $events = array();

    // Recorre los resultados y agrega cada fila como un evento al array
    while ($row = $result->fetch_assoc()) {
        $event = array(
            'id' => $row['QRKey'],
            'title' => $row['nombre'],
            'start' => $row['fecha_sym'],
            // Puedes agregar más propiedades aquí según las necesidades de FullCalendar
        );
        array_push($events, $event);
    }

    // Convierte el array a formato JSON y lo imprime
    echo json_encode($events);
} else {
    // No hay resultados
    echo json_encode(array());
}

// Cierra la conexión
$conexion->close();
?>