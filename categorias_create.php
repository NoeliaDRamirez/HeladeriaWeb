<?php
	session_start();

	// conexión con la db
	require_once('config.php');
	
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

	//denegacion de acceso para visitante
	if($datosUsuario['rol_id'] == 3 ){
		header('Location: articulos_index.php');
	}

	if(isset($_POST['submit']))
	{
		$error_msg = [];
		
		if(isset(
				 $_POST['nombre'],
				 ) 
				&& !empty($_POST['nombre']) 
				)
		{
			$nombre 	= filter_var(trim($_POST['nombre']),FILTER_SANITIZE_STRING);
			
			$sql = "INSERT INTO categorias (nombre) 
			
			VALUE ('$nombre')";
			
			//ejecuto la consulta
			$rs = mysqli_query($con,$sql);
			
			if(mysqli_affected_rows($con) == 1)
			{
				//obtengo el ultimo registro y redirecciono
				$lastInsertedID = mysqli_insert_id($con);
				$_SESSION['success_msg'] = 'Registro ha sido agregado con éxito';
				header('location:categorias_index.php?id='.$lastInsertedID);
				exit();
			}
			else
			{
				$error_msg[] = 'No fue posible agregar el registro' ;
			}
			
		}
		else
		{
			
			if(!isset($_POST['nombre']) || empty($_POST['nombre']))
			{
				$error_msg[] = 'El Nombre es requerido' ;
			}

		}
	}
	
	require_once( 'include/head.php');

	?>
	
	
		<div class="container wrapper">
			<h2>Agregar categoria</h2>
		
		<?php 
		
			// muestro si fue exito
			if(isset($_SESSION['success_msg']))
			{
				echo '<div class="success-msg">'.$_SESSION['success_msg'].'</div>';
				unset($_SESSION['success_msg']);
			}
			
			//muestro los errores en caso que tenga
			
			if(isset($error_msg) && !empty($error_msg))
			{
				foreach($error_msg as $error)
				{
					echo '<div class="error-msg">'.$error.'</div>';
				}
			}
			
		?>
		<div class="align-center">
			<form action=" " method="POST">

				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre">
				</div>
				
				<div class="form-group">
					<button type="submit" name="submit">Guardar</button>
					<a href="categorias_index.php" class="back-link" style="float:right"><< Volver</a>
				</div>
				<!-- comentario -->
			</form>
		</div>
	</div>
	<?php
	require_once('include/foot.php');
	?>