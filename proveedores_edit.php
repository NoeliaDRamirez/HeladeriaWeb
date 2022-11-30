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
		header('location:proveedores_index.php');
		exit();
	}
		
	if(isset($_POST['submit']))
	{
		$error_msg = [];
		
		if(isset(
            $_POST['nombre'],
            $_POST['direccion'],
            $_POST['codigo'],
            $_POST['ciudad'],
            $_POST['pais'],
            $_POST['telefono'],
            $_POST['cuit'],
             ) 
            && !empty($_POST['nombre']) 
            && !empty($_POST['direccion']) 
            && !empty($_POST['codigo']) 
            && !empty($_POST['ciudad']) 
            && !empty($_POST['pais'])	
            && !empty($_POST['telefono'])
            && !empty($_POST['cuit'])
            )
	{
			$id = intval(trim($_GET['id']));
			
			$nombre 	= filter_var(trim($_POST['nombre']),FILTER_SANITIZE_STRING);
			$direccion 	= filter_var(trim($_POST['direccion']),FILTER_SANITIZE_STRING);
			$codigo 	= filter_var(trim($_POST['codigo']),FILTER_SANITIZE_STRING);
			$ciudad 	= filter_var(trim($_POST['ciudad']),FILTER_SANITIZE_STRING);
			$pais 	= filter_var(trim($_POST['pais']),FILTER_SANITIZE_STRING);
			$telefono = filter_var(trim($_POST['telefono']),FILTER_SANITIZE_STRING);
			$cuit 	= filter_var(trim($_POST['cuit']),FILTER_SANITIZE_STRING);


			//preparo la fecha de cuando actualizo
			//$updated 	= date('Y-m-d H:i:s');
			
			//preparo la consulta con los valores enviados desde el form
			$sql = "UPDATE proveedores SET 
								nombre = '".$nombre."', 
								direccion='".$direccion."', 
								codigo='".$codigo."', 
								ciudad='".$ciudad."', 
								pais='".$pais."',
								telefono='".$telefono."',
								cuit='".$cuit."'

					WHERE id = ".$id;
			
			//ejecuto la consulta
			$rs = mysqli_query($con,$sql);
			
			//segun se haya ejecutado, preparo el msq a mostrar y redirecciono
			if(mysqli_affected_rows($con) == 1)
			{
				$_SESSION['success_msg'] = 'El registro ha sido actualizado';
				header('location:proveedores_edit.php?id='.$id);
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
				$error_msg[] = 'El nombre es requerido' ;
			}
			
			if(!isset($_POST['direccion']) || empty($_POST['direccion']))
			{
				$error_msg[] = 'La direccion es requerido' ;
			}

			if(!isset($_POST['codigo']) || empty($_POST['codigo']))
			{
				$error_msg[] = 'El codigo es requerido' ;
			}
			
			if(!isset($_POST['ciudad']) || empty($_POST['ciudad']))
			{
				$error_msg[] = 'La ciudad es requerida	' ;
			}
			
			if(!isset($_POST['pais']) || empty($_POST['pais']))
			{
				$error_msg[] = 'El pais es requerido	' ;
			}
			
			if(!isset($_POST['telefono']) || empty($_POST['telefono']))
			{
				$error_msg[] = 'El telefono es requerido	' ;
			}
			
			if(!isset($_POST['cuit']) || empty($_POST['cuit']))
			{
				$error_msg[] = 'El cuit es requerido	' ;
			}
			
		}
	}
	
	//listar los registros
	$sql = 'SELECT * FROM proveedores WHERE id = '.$_GET['id'];
	$rs = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($rs);
	
	
	require_once( 'include/head.php');
?>


	<div class="container wrapper">
		<h2>Editar Proveedores</h2>
		
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
					<label for="direccion">Direccion</label>
					<input type="text" name="direccion" placeholder="Ingrese la direccion" id="direccion" value="<?php echo $row['direccion'];?>">
				</div>
				
				<div class="form-group">
					<label for="codigo">codigo</label>
					<input type="text" name="codigo" placeholder="Ingrese el codigo" id="codigo" value="<?php echo $row['codigo'];?>">
				</div>
			
				<div class="form-group">
					<label for="ciudad">ciudad</label>
					<input type="text" name="ciudad" placeholder="Ingrese la ciudad" id="ciudad" value="<?php echo $row['ciudad'];?>">
				</div>
				<div class="form-group">
					<label for="pais">pais</label>
					<input type="text" name="pais" placeholder="Ingrese el pais" id="pais" value="<?php echo $row['pais'];?>">
				</div>
				<div class="form-group">
					<label for="telefono">telefono</label>
					<input type="text" name="telefono" placeholder="Ingrese el telefono" id="telefono" value="<?php echo $row['telefono'];?>">
				</div>				
				
				<div class="form-group">
					<label for="cuit">cuit</label>
					<input type="text" name="cuit" placeholder="Ingrese el cuit" id="cuit" value="<?php echo $row['cuit'];?>">
				</div>
				
				<div class="form-group">
					<button type="submit" name="submit">Procesar</button>
					<a href="proveedores_index.php" class="back-link" style="float:right"><< Volver</a>
				</div>
			</form>
		</div>
	</div>
		<?php
	require_once('include/foot.php');
	?>