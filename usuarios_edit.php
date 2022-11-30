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

	//denegacion de acceso para visitante
	
	if($datosUsuario['rol_id'] == 3 ){
		header('Location: articulos_index.php');
	}
	//
	if(!isset($_GET['id']))
	{
		header('location:usuarios_index.php');
		exit();
	}
		
	if(isset($_POST['submit']))
	{
		$error_msg = [];
		
		if(isset(
			$_POST['usuario'],
			$_POST['email'],
			$_POST['password'],
			$_POST['rol_id'],
			 
			 ) 
			&& !empty($_POST['usuario']) 
			&& !empty($_POST['email']) 
			&& !empty($_POST['password']) 
			&& !empty($_POST['rol_id']) 
			
			)
	{
			$id = intval(trim($_GET['id']));
			
			$usuario 	= filter_var(trim($_POST['usuario']),FILTER_SANITIZE_STRING);
			$email 	= filter_var(trim($_POST['email']),FILTER_SANITIZE_STRING);
			//$password 	= password_hash($_POST['password'], PASSWORD_DEFAULT);
			$password 	= filter_var(trim($_POST['password']),FILTER_SANITIZE_STRING);//no hay que encriptarla
			$hash = password_hash($password, PASSWORD_DEFAULT);
			$rol_id 	= filter_var(trim($_POST['rol_id']),FILTER_SANITIZE_STRING);
			
			//preparo la fecha de cuando actualizo
			//$updated 	= date('Y-m-d H:i:s');
			
			//preparo la consulta con los valores enviados desde el form
			$sql = "UPDATE usuarios SET 
								usuario = '".$usuario."', 
								email='".$email."', 
								password='".$hash."', 
								rol_id='".$rol_id."'

					WHERE id = ".$id;
			
			//ejecuto la consulta
			$rs = mysqli_query($con,$sql);
			
			//segun se haya ejecutado, preparo el msq a mostrar y redirecciono
			if(mysqli_affected_rows($con) == 1)
			{
				$_SESSION['success_msg'] = 'El registro ha sido actualizado';
				header('location:usuarios_edit.php?id='.$id);
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
			
			if(!isset($_POST['rol_id']) || empty($_POST['rol_id']))
			{
				$error_msg[] = 'La rol_id es requerida	' ;
			}
			
			
		}
	}
	
	//listar los registros
	$sql = 'SELECT * FROM usuarios WHERE id = '.$_GET['id'];
	$rs = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($rs);
	
	
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
					<input type="text" name="email" placeholder="Ingrese el email" id="email" value="<?php echo $row['email'];?>">
				</div>
                <div class="form-group">
					<label for="password">password</label>
					<input type="" name="password" placeholder="Ingrese el password" id="password" value="<?php echo $row['password'];?>">
				</div>
				<div class="form-group">
					<label for="rol_id">Rol : (<?php echo $row['rol_id']; ?>)</label>
					
					<?php
										
					//consulta a listado de roles
					$sqlr = 'SELECT * FROM roles ORDER BY id ASC';
					$roles = mysqli_query($con,$sqlr);
					
					//la categoria del registro
					$rolesl = $row['rol_id'];
															
					?>
					<select name="rol_id" id="rol_id">	
					<?php
						while($row1 = mysqli_fetch_array($roles))
						{
							if($rolesl == $row1['id']) {
								echo '<option value ="'.$row1['id'].'" selected>'.$row1['nombre'].'</option>';
							} else {
								echo '<option value ="'.$row1['id'].'">'.$row1['nombre'].'</option>';
							}
						}		
					?>
					</select>	
				
				<div class="form-group">
					<button type="submit" name="submit">Procesar</button>
					<a href="usuarios_index.php" class="back-link" style="float:right"><< Volver</a>
				</div>
			</form>
		</div>
	</div>
		<?php
	require_once('include/foot.php');
	?>