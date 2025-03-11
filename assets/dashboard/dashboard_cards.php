<?php
// Preparar consultas
// Verificar si la variable de sesión 'user_ceers' está definida y no es nula
if (!isset($_SESSION['user_ceers']) || $_SESSION['user_ceers'] === null || $_SESSION['activo'] == 0) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header("Location: denegado");
    exit; // También puedes usar die en lugar de exit
}

function countItems($conexion, $tabla, $condiciones = []) {
    $condiciones_str = "";
    if (!empty($condiciones)) {
        $condiciones_str = " WHERE " . implode(" AND ", $condiciones);
    }

    $query = "SELECT COUNT(*) FROM $tabla $condiciones_str";
    $stmt = mysqli_prepare($conexion, $query);
    
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        return $count;
    } else {
        return false;
    }
}


// Contar equipos de computo activos
$COUNT_computo = countItems($conexion, 'computadora', ['activo = 1']);

// Contar equipos telefónicos activos
$COUNT_moviles = countItems($conexion, 'celular', ['activo = 1']);

// Contar autos con estatus 1 y activos
$COUNT_autos = countItems($conexion, 'autos', ['estatus = 1', 'activo = 1']);

// Contar elementos de stock activos
$COUNT_stock = countItems($conexion, 'stock', ['activo = 1']);

// Contar equipos disponibles de computo activos
$COUNT_computoDisponible = countItems($conexion, 'computadora', ['`personal_id` = 0 or `personal_id` IS NULL', 'activo = 1']);

// Contar equipos disponibles telefónicos activos
$COUNT_movilesDisponible = countItems($conexion, 'celular', ['`personal_id` = 0 or`personal_id` IS NULL', 'activo = 1', 'disponible = 1']);

// Contar autos disponibles con estatus 1 y activos
$COUNT_autosDisponible = countItems($conexion, 'autos', ['`personal_id_personal`= 1 OR `personal_id_personal` IS NULL','estatus = 1', 'activo = 1']);

// Contar elementos disponibles de stock activos
$COUNT_stockDisponible = countItems($conexion, 'stock', ['`personal_id` = 0 OR `personal_id` IS NULL','activo = 1']);
?>
