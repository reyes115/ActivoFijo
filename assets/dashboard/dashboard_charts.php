<?php
// Preparar consultas
// Verificar si la variable de sesión 'user_ceers' está definida y no es nula
if (!isset($_SESSION['user_ceers']) || $_SESSION['user_ceers'] === null || $_SESSION['activo'] == 0) {
    session_destroy();
    // Redireccionar al usuario a la página de denegado
    header("Location: denegado");
    exit; // También puedes usar die en lugar de exit
}

  function countItemsChars($conexion, $tabla, $codigo_prefix, $condiciones = []) {
    $condiciones_str = !empty($condiciones) ? " AND " . implode(" AND ", $condiciones) : "";
    $query = "SELECT COUNT(*) FROM $tabla WHERE codigo LIKE '$codigo_prefix%' $condiciones_str";
    $stmt = mysqli_prepare($conexion, $query);

    if ($stmt && mysqli_stmt_execute($stmt)) {
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        return $count;
    }

    return false;
}

function countItemsByCategory($conexion, $tabla, $categories, $condiciones = []) {
    $counts = [];

    foreach ($categories as $key => $prefix) {
        $counts[$key] = countItemsChars($conexion, $tabla, $prefix, $condiciones);
    }

    return $counts;
}

// Definir las categorías y datos para los gráficos
$like1 = ['inno' => '___01', 'sol' => '___02', 'beex' => '___03', 'abf' => '___04', 'na' => '___0C'];
$like2 = ['inno' => 'INV', 'sol' => 'SOL', 'beex' => 'BEX', 'abf' => 'ABF'];
$like3 = ['inno' => 'INV', 'sol' => 'SOL', 'beex' => 'BEX', 'abf' => 'ABF', 'jaj' => 'JAJ'];
$like4 = ['inno' => 'INV', 'sol' => 'SOL', 'beex' => 'BEX', 'abf' => 'ABF', 'jaj' => 'JAJ', 'bhr' => 'BHR', 'apc' => 'APC', 'nrf' => 'NRF', 'vwl' => 'VWL', 'evj' => 'EVJ'];
$like5 = ['inno' => '___01', 'sol' => '___02', 'beex' => '___03','abf' => '___04', 'na' => '___0T'];
$like6 = ['inno' => '___01', 'sol' => '___02', 'beex' => '___03','abf' => '___04', 'na' => '___0M'];

$EmpresasCompus = $like1;
$PropietariosCompus = $like2;

$EmpresasMoviles = $like1;
$PropietariosMoviles = $like3;

$EmpresasAutos = $like5;
$PropietariosAutos = $like4;

$EmpresasStock = $like6;
$PropietariosStock = $like4;

$condicionesActivos = ['activo = 1'];
$condicionesEstatus = ['estatus = 1'];

// Contar equipos de cómputo
$countsEmpresasCompus = countItemsByCategory($conexion, 'computadora', $EmpresasCompus, $condicionesActivos);
$countsPropietariosCompu = countItemsByCategory($conexion, 'computadora', $PropietariosCompus, $condicionesActivos);

$countsEmpresasMoviles = countItemsByCategory($conexion, 'celular', $EmpresasMoviles, $condicionesActivos);
$countsPropietariosMoviles = countItemsByCategory($conexion, 'celular', $PropietariosMoviles, $condicionesActivos);

$countsEmpresasAutos = countItemsByCategory($conexion, 'autos', $EmpresasAutos, $condicionesEstatus, $condicionesActivos);
$countsPropietariosAutos = countItemsByCategory($conexion, 'autos', $PropietariosAutos, $condicionesEstatus, $condicionesActivos);

$countsEmpresasStock = countItemsByCategory($conexion, 'stock', $EmpresasStock, $condicionesActivos);
$countsPropietariosStock = countItemsByCategory($conexion, 'stock', $PropietariosStock, $condicionesActivos);

// Datos para los gráficos
$dataLabels = ["INNOVET", "SOLGISTIKA", "BE EX EN", "AB FORTI", "Sin asignar"];
$dataLabels2 = ["INNOVET", "SOLGISTIKA", "BE EX EN", "AB FORTI"];
$dataLabels3 = ["INNOVET", "SOLGISTIKA", "BE EX EN", "AB FORTI" ,"JOSE JIMENEZ "];
$dataLabels4 = ["INV", "SOL", "BEX", "ABF", "JAJ", "BHR", "APC", "NRF", "VWL", "EVJ"];
$dataValues1 = array_values($countsEmpresasCompus);
$dataValues2 = array_values($countsPropietariosCompu);
$dataValues3 = array_values($countsEmpresasMoviles);
$dataValues4 = array_values($countsPropietariosMoviles);
$dataValues5 = array_values($countsEmpresasAutos);
$dataValues6 = array_values($countsPropietariosAutos);
$dataValues7 = array_values($countsEmpresasStock);
$dataValues8 = array_values($countsPropietariosStock);



?>