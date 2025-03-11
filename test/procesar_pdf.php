<?php
// Obtener el texto enviado desde el cliente
$texto = $_POST["texto"];

// Filtrar palabras o frases no deseadas
$palabrasNoDeseadas = array("palabra1", "palabra2", "frase no deseada");
$palabrasFiltradas = preg_replace('/\b(?:' . implode('|', $palabrasNoDeseadas) . ')\b/i', '', $texto);

// Mostrar las palabras filtradas
echo $palabrasFiltradas;
?>
