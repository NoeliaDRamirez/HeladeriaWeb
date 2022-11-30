<?php


//datos de conexion con la base de dato

$host = 'localhost';
$user = 'root';
$password = '';
$db = 'utn_heladeria';

$con = mysqli_connect($host,$user,$password,$db);//cadena de conexion

if(!$con)
{
	die(mysqli_error());
}


function pre($array)
{
	echo "<pre>";
	print_r($array);
	echo "</pre>";
}