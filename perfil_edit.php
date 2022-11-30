<?php
	session_start();
	//conexión con la DB
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
	
	//
	if(!isset($_GET['id']))
	{
		header('location:usuarios_index.php');
		exit();
	}
	//listar los registros
	$sql = 'SELECT * FROM usuarios WHERE id = '.$_GET['id'];
	$rs = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($rs);

	//asigno el valor de la clave ingresada en el formulario de login a un variable para mejor vista
	$usuario_db_pass = $row['password'];
	//echo $usuario_db_pass;
	
	
	
	if(isset($_POST['submit'])){
		$error_msg = [];
		
		if(isset(
			$_POST['usuario'],
			$_POST['email'],
			$_POST['password'],
	 
			 ) 
			&& !empty($_POST['usuario']) 
			&& !empty($_POST['email']) 
			&& !empty($_POST['password']) 
				
			){
			$id = intval(trim($_GET['id']));
			echo $_POST['usuario'];
			$usuario 	= filter_var(trim($_POST['usuario']),FILTER_SANITIZE_STRING);
			$email 	= filter_var(trim($_POST['email']),FILTER_SANITIZE_STRING);
			$password 	= filter_var(trim($_POST['password']),FILTER_SANITIZE_STRING);
			//$password 	= password_hash($_POST['password'], PASSWORD_DEFAULT);
			//$rol_id 	= filter_var(trim($_POST['rol_id']),FILTER_SANITIZE_STRING);
			$hash = password_hash($password, PASSWORD_DEFAULT);
										
			//preparo la consulta con los valores enviados desde el form
			$sql = "UPDATE usuarios SET 
								usuario = '".$usuario."', 
								email='".$email."', 
								password='".$hash."'

					WHERE id = ".$id;
			
			//ejecuto la consulta
			$rs = mysqli_query($con,$sql);
			
			//segun se haya ejecutado, preparo el msq a mostrar y redirecciono
			if(mysqli_affected_rows($con) == 1)
			{
				$_SESSION['success_msg'] = 'El registro ha sido actualizado';
				header('location:perfil_edit.php?id='.$id);
				exit();
			}
			else
			{
				$error_msg[] = 'No fue posible actualizar el registro' ;
			}
			
		}
		else
		{
			if(!isset($_POST['usuario']) || empty($_POST['usuario']))
			{
				$error_msg[] = 'El usuario es requerido' ;
			}
			
			if(!isset($_POST['email']) || empty($_POST['email']))
			{
				$error_msg[] = 'El email es requerido' ;
			}

			if(!isset($_POST['password']) || empty($_POST['password']))
			{
				$error_msg[] = 'El password es requerido' ;
			}
						
		}
	
	}
	
	require_once( 'include/head.php');
?>


	<div class="container wrapper">
		<h2>Editar usuario</h2>
		
		<?php 
			if(isset($_SESSION['success_msg']))
			{
				echo '<div class="success-msg">'.$_SESSION['success_msg'].'</div>';
				unset($_SESSION['success_msg']);
			}
			
			if(isset($error_msg) && !empty($error_msg))
			{
				foreach($error_msg as $error)
				{
					echo '<div class="error-msg">'.$error.'</div>';
				}
			}
			
		?>
		<div class="container">
			<form action="" method="POST">
				<div class="form-group">
					<label for="usuario">Usuario</label>
					<input type="text" name="usuario" placeholder="Ingrese el usuario" id="usuario" value="<?php echo $row['usuario'];?>">
				</div>
				<div class="form-group">
					<label for="email">email</label>
					<input type="text" name="email" placeholder="Ingrese el email" id="email" readonly value="<?php echo $row['email'];?>">
				</div>
                <div class="form-group">
					<label for="password">password</label>
					<input type="" name="password" placeholder="Ingrese el password" id="password" value="<?php echo $row['password'];?>">
				</div>
			 				
				<div class="form-group">
					<button type="submit" name="submit">Procesar</button>
					
				</div>
			</form>
		</div>
	</div>
		<?php
			require_once('include/foot.php');
	?>