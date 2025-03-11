<?php
$hostname='localhost';
$database='scri';
$username='root';
$password='T3rm0Form4d0';

$conexion=new mysqli($hostname,$username,$password,$database);
if($conexion->connect_errno){
    echo "El sitio web está experimentado problemas";
}
?>