<?php 
session_start();
require 'config.php';	 //conexion a db
require 'alta_usuario.php';  //procesos de alta

// verifico si el usuario tiene creada la sesion previamente
if(isset($_SESSION['user_email'])){
	header('Location: index.php');
	exit;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registro de usuario - ABM de Usuarios PHP y MYSQLi</title>
<link rel="stylesheet" href="css/login_style.css" media="all" type="text/css">
</head>

<body>

<form action="" method="POST">
	<h1>Crear una cuenta</h1>

	<div class="container">
		<label for="username"><b>Usuario</b></label>
		<input type="text" placeholder="Ingrese el nombre de usuario" id="username" name="form_usuario" required>

		<label for="email"><b>Email</b></label>
		<input type="email" placeholder="Ingrese el email" id="email" name="form_email" required>

		<label for="password"><b>Clave</b></label>
		<input type="password" placeholder="Ingrese la clave" id="password" name="form_password" required>

		<button type="submit">Registrar</button>
	</div>
	<?php

	//mensajes de alerta

	//en caso de exito mostrar mensaje exitoso
	if(isset($success_message)){
		echo '<div class="success_message">'.$success_message.'</div>'; 
	}
	//en caso de error mostrar mensaje con error
	if(isset($error_message)){
		echo '<div class="error_message">'.$error_message.'</div>'; 
	}	
	?>

	<div class="container">
		<a href="index.php"><button type="button" class="Regbtn">Acceder</button></a>
	</div>
</form>
</body>
</html>