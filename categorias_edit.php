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

	if(!isset($_GET['id']))
	{
		header('location:categorias_index.php');
		exit();
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
			$id = intval(trim($_GET['id']));
			
			$nombre 	= filter_var(trim($_POST['nombre']),FILTER_SANITIZE_STRING);
			//preparo la fecha de cuando actualizo
			//$updated 	= date('Y-m-d H:i:s');
			
			//preparo la consulta con los valores enviados desde el form
			$sql = "UPDATE categorias SET 
								nombre='".$nombre."'

					WHERE id = ".$id;
			
			//ejecuto la consulta
			$rs = mysqli_query($con,$sql);
			
			//segun se haya ejecutado, preparo el msq a mostrar y redirecciono
			if(mysqli_affected_rows($con) == 1)
			{
				$_SESSION['success_msg'] = 'El registro ha sido actualizado';
				header('location:categorias_edit.php?id='.$id);
				exit();
			}
			else
			{
				$error_msg[] = 'No fue posible actualizar el registro' ;
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
	
	//listar los registros
	$sql = 'SELECT * FROM categorias WHERE id = '.$_GET['id'];
	$rs = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($rs);
	
	
	require_once( 'include/head.php');
?>


	<div class="container wrapper">
		<h2>Editar categorias</h2>
		
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
		<div class="align-center">
			<form action="" method="POST">
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" placeholder="Ingrese el nombre" id="nombre" value="<?php echo $row['nombre'];?>">
				</div>
				<div class="form-group">
					<button type="submit" name="submit">Procesar</button>
					<a href="categorias_index.php" class="back-link" style="float:right"><< Volver</a>
				</div>
			</form>
		</div>
	</div>
		<?php
	require_once('include/foot.php');
	?>