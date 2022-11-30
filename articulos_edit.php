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
		header('location:articulos_index.php');
		exit();
	}
		
	if(isset($_POST['submit']))
	{
		$error_msg = [];
		
		if(isset(
			$_POST['codigo'],
			 $_POST['nombre'],
			$_POST['categoria_id'],
			 $_POST['proveedor_id'],
			 $_POST['preciocompra'],
			 $_POST['cantidad'],
			 $_POST['minimo'],
			 $_POST['maximo'],
			 $_POST['precioventa'],
			 $_POST['fecha']
			 ) 
			&& !empty($_POST['codigo']) 
			&& !empty($_POST['nombre']) 
			&& !empty($_POST['categoria_id']) 
			&& !empty($_POST['proveedor_id']) 
			&& !empty($_POST['preciocompra'])	
			&& !empty($_POST['cantidad'])
			&& !empty($_POST['minimo'])
			&& !empty($_POST['maximo'])
			&& !empty($_POST['precioventa'])
			&& !empty($_POST['fecha'])
			)
	{
			$id = intval(trim($_GET['id']));
			
			$codigo 	= filter_var(trim($_POST['codigo']),FILTER_SANITIZE_STRING);
			$nombre 	= filter_var(trim($_POST['nombre']),FILTER_SANITIZE_STRING);
			$categoria_id 	= filter_var(trim($_POST['categoria_id']),FILTER_SANITIZE_STRING);
			$proveedor_id 	= filter_var(trim($_POST['proveedor_id']),FILTER_SANITIZE_STRING);
			$preciocompra 	= filter_var(trim($_POST['preciocompra']),FILTER_SANITIZE_STRING);
			$cantidad = filter_var(trim($_POST['cantidad']),FILTER_SANITIZE_STRING);
			$minimo 	= filter_var(trim($_POST['minimo']),FILTER_SANITIZE_STRING);
			$maximo 	= filter_var(trim($_POST['maximo']),FILTER_SANITIZE_STRING);
			$precioventa 	= filter_var(trim($_POST['precioventa']),FILTER_SANITIZE_STRING);
			$fecha 	= filter_var(trim($_POST['fecha']),FILTER_SANITIZE_STRING);


			//preparo la fecha de cuando actualizo
			//$updated 	= date('Y-m-d H:i:s');
			
			//preparo la consulta con los valores enviados desde el form
			$sql = "UPDATE articulos SET 
								codigo = '".$codigo."', 
								nombre='".$nombre."', 
								categoria_id='".$categoria_id."', 
								preciocompra='".$preciocompra."', 
								cantidad='".$cantidad."',
								minimo='".$minimo."',
								maximo='".$maximo."',	
								proveedor_id= '".$proveedor_id."',							
								precioventa='".$precioventa."',
								fecha='".$fecha."'

					WHERE id = ".$id;
			
			//ejecuto la consulta
			$rs = mysqli_query($con,$sql);
			
			//segun se haya ejecutado, preparo el msq a mostrar y redirecciono
			if(mysqli_affected_rows($con) == 1)
			{
				$_SESSION['success_msg'] = 'El registro ha sido actualizado';
				header('location:articulos_edit.php?id='.$id);
				exit();
			}
			else
			{
				$error_msg[] = 'No fue posible actualizar el registro' ;
			}
			
		}
		else
		{
			if(!isset($_POST['codigo']) || empty($_POST['codigo']))
			{
				$error_msg[] = 'El codigo es requerido' ;
			}
			
			if(!isset($_POST['nombre']) || empty($_POST['nombre']))
			{
				$error_msg[] = 'El Nombre es requerido' ;
			}

			if(!isset($_POST['preciocompra']) || empty($_POST['preciocompra']))
			{
				$error_msg[] = 'El preciocompra es requerido' ;
			}
			
			if(!isset($_POST['cantidad']) || empty($_POST['cantidad']))
			{
				$error_msg[] = 'La cantidad es requerida	' ;
			}
			
			if(!isset($_POST['precioventa']) || empty($_POST['precioventa']))
			{
				$error_msg[] = 'El precioventa es requerido	' ;
			}
			
			if(!isset($_POST['minimo']) || empty($_POST['minimo']))
			{
				$error_msg[] = 'El minimo es requerido	' ;
			}
			
			if(!isset($_POST['maximo']) || empty($_POST['maximo']))
			{
				$error_msg[] = 'El maximo es requerido	' ;
			}
			
			
			if(!isset($_POST['preciocompra']) || empty($_POST['preciocompra']))
			{
				$error_msg[] = 'El preciocompra es requerido	' ;
			}
			
			
		}
	}
	
	//listar los registros
	$sql = 'SELECT * FROM articulos WHERE id = '.$_GET['id'];
	$rs = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($rs);
	
	
	require_once( 'include/head.php');
?>


	<div class="container wrapper">
		<h2>Editar Articulos</h2>
		
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
					<label for="codigo">Codigo</label>
					<input type="text" name="codigo" placeholder="Ingrese el codigo" id="codigo" value="<?php echo $row['codigo'];?>">
				</div>
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" placeholder="Ingrese el nombre" id="nombre" value="<?php echo $row['nombre'];?>">
				</div>
				<div class="form-group">
					<label for="categoria_id">Categoria : (<?php echo $row['categoria_id']; ?>)</label>
					<!--
					
					-->
					
					<?php
					
					
					//consulta a listado a las categorias
					$sqlc = 'SELECT * FROM categorias ORDER BY id ASC';
					$cat = mysqli_query($con,$sqlc);
					
					//la categoria del registro
					$categ = $row['categoria_id'];
					
					
					
					?>
					<select name="categoria_id" id="categoria_id">	
					<?php
						while($row1 = mysqli_fetch_array($cat))
						{
							if($categ == $row1['id']) {
								echo '<option value ="'.$row1['id'].'" selected>'.$row1['nombre'].'</option>';
							} else {
								echo '<option value ="'.$row1['id'].'">'.$row1['nombre'].'</option>';
							}
						}		
					?>
					</select>	
				</div>
				<div class="form-group">
					<label for="preciocompra">preciocompra</label>
					<input type="text" name="preciocompra" placeholder="Ingrese el preciocompra" id="preciocompra" value="<?php echo $row['preciocompra'];?>">
				</div>
			
				<div class="form-group">
					<label for="cantidad">cantidad</label>
					<input type="text" name="cantidad" placeholder="Ingrese la cantidad" id="cantidad" value="<?php echo $row['cantidad'];?>">
				</div>
				<div class="form-group">
					<label for="minimo">Minimo</label>
					<input type="text" name="minimo" placeholder="Ingrese el minimo" id="minimo" value="<?php echo $row['minimo'];?>">
				</div>
				<div class="form-group">
					<label for="maximo">Maximo</label>
					<input type="text" name="maximo" placeholder="Ingrese el maximo" id="maximo" value="<?php echo $row['maximo'];?>">
				</div>				
				<div class="form-group">
					<label for="proveedor_id">Proveedor</label>
					
					
					<?php
					
					
					//consulta a listado a las categorias
					$sqlp = 'SELECT * FROM proveedores ORDER BY id ASC';
					$prov = mysqli_query($con,$sqlp);
					
					//la categoria del registro
					$proveed = $row['proveedor_id'];
					
					
					
					?>
					<select name="proveedor_id" id="proveedor_id">	
					<?php
						while($row1 = mysqli_fetch_array($prov))
						{
							if($proveed == $row1['id']) {
								echo '<option value ="'.$row1['id'].'" selected>'.$row1['nombre'].'</option>';
							} else {
								echo '<option value ="'.$row1['id'].'">'.$row1['nombre'].'</option>';
							}
						}		
					?>
					</select>	
				</div>
				<div class="form-group">
					<label for="precioventa">precioventa</label>
					<input type="text" name="precioventa" placeholder="Ingrese el precioventa" id="precioventa" value="<?php echo $row['precioventa'];?>">
				</div>
				<div class="form-group">
					<label for="fecha">fecha</label>
					<input type="date" name="fecha" id="fecha" value="<?php echo $row['fecha'];?>">
				</div>
				<div class="form-group">
					<button type="submit" name="submit">Procesar</button>
					<a href="articulos_index.php" class="back-link" style="float:right"><< Volver</a>
				</div>
			</form>
		</div>
	</div>
		<?php
	require_once('include/foot.php');
	?>