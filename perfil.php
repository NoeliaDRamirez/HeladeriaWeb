<?php
require_once( 'include/head.php');
session_start();
require 'config.php';

	// verifico si el usuario tiene creada la sesion previamente, si el email esta en la variable de sesion.
	if(isset($_SESSION['email']) && !empty($_SESSION['email'])){

		$email = $_SESSION['email'];
		
		// Traigo los datos del email correspondiente, por ej. nombre de usuario, apellido, nombre ,etc
		$get_datos_usuario = mysqli_query($con, "SELECT * FROM `usuarios` WHERE email = '$email'");
		$datosUsuario =  mysqli_fetch_assoc($get_datos_usuario);

	}else{
		//si no esta es porque no pasó por el formulario de login, asi que afuera
		header('Location: salir.php');
	exit;
    }

	//listar los registros
	$sql = 'SELECT * FROM usuarios WHERE id = '.$datosUsuario['id'];
	$rs = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($rs);
?>
<body>
<div class= "wrapper"><h4 class= "bienvenida" >Perfil: <?php echo $datosUsuario['usuario'];?></h4>
<?php //oculto boton para visitante y para operador
				if($datosUsuario['rol_id'] == 1   ){
			 ?>
<a href="usuarios_index.php" class="versuarios">Ver lista de usuarios</a>
<?php } ?>
</div>


<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Perfil de usuario - ABM de Usuarios PHP y MYSQLi</title>
<link rel="stylesheet" href="css/perfil_style.css" media="all" type="text/css">
</head>
<body>

<form action="" method="POST">
	<h1>Mis datos de perfil</h1>

	<div class="container">
		<label for="username"><b>Usuario</b></label>
		<input type="text" placeholder="Ingrese el nombre de usuario" id="username" name="form_usuario" required readonly value="<?php echo $row['usuario'];?>">

		<label for="email"><b>Email</b></label>
		<input type="email" placeholder="Ingrese el email" id="email" name="form_email" required readonly value="<?php echo $row['email'];?>" >

		<label for="password"><b>Clave</b></label>
		<input type="password" placeholder="Ingrese la clave" id="password" name="form_password" required readonly value="<?php echo $row['password'];?>">

		<a href="perfil_edit.php?id=<?php echo $row['id']?>" class= "editar" >Editar</a>
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

	
</form>
</body>
</html>
    </div>

</body>
</html>
<?php
	require_once('include/foot.php');
	?>