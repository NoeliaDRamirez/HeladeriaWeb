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
			
			$sql = "INSERT INTO articulos (codigo, nombre, categoria_id, preciocompra, cantidad,
			minimo, maximo , proveedor_id , precioventa , fecha) 
			
			VALUE ('$codigo','$nombre', '$categoria_id','$preciocompra','$cantidad','$minimo',
			'$maximo','$proveedor_id','$precioventa','$fecha')";
			
			//ejecuto la consulta
			$rs = mysqli_query($con,$sql);
			
			if(mysqli_affected_rows($con) == 1)
			{
				//obtengo el ultimo registro y redirecciono
				$lastInsertedID = mysqli_insert_id($con);
				$_SESSION['success_msg'] = 'Registro ha sido agregado con éxito';
				header('location:articulos_edit.php?id='.$lastInsertedID);
				exit();
			}
			else
			{
				$error_msg[] = 'No fue posible agregar el registro' ;
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
	
	require_once( 'include/head.php');

	?>
	
	
		<div class="container wrapper">
			<h2>Agregar articulo</h2>
		
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
			<form action="" method="POST">
				<div class="form-group">
					<label for="codigo">Codigo</label>
					<input type="text" name="codigo">
				</div>
				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre">
				</div>
				<div class="form-group">
					<label for="categoria_id">categoria</label>
					<!--
					<select name="categoria_id" id="categoria_id">
						<option value="">Seleccione categoria</option>
						<option value="1">Categoria1</option>
						<option value="2">Categoria2</option>
						<option value="3">Categoria3</option>
					</select>
					-->
					<?php
					
					//consulta a listado a las categorias
					$sqlc = 'SELECT * FROM categorias ORDER BY id ASC';
					$cat = mysqli_query($con,$sqlc);
					?>
				<select name="categoria_id" id="categoria_id">
					<option value="">Seleccione categoria</option>		
					<?php
						while($row = mysqli_fetch_array($cat))
						{
							echo '<option value ="'.$row['id'].'">'.$row['nombre'].'</option>';
						}		
					?>
				</select>		

				</div>
				<div class="form-group">
					<label for="preciocompra">preciocompra</label>
					<input type="text" name="preciocompra" >
				</div>
			
				<div class="form-group">
					<label for="cantidad">cantidad</label>
					<input type="text" name="cantidad">
				</div>
				<div class="form-group">
					<label for="minimo">Minimo</label>
					<input type="text" name="minimo">
				</div>
				<div class="form-group">
					<label for="maximo">Maximo</label>
					<input type="text" name="maximo">
				</div>				
				<div class="form-group">
					<label for="proveedor_id">proveedor</label>
					<!--<select name="proveedor_id" id="proveedor_id">
						<option value="">Seleccione proveedor</option>
						<option value="1">proveedor1</option>
						<option value="2">proveedor2</option>
						<option value="3">proveedor3</option>
					</select>
					-->
					<?php
					
					//consulta a listado a las categorias
					$sqlp = 'SELECT * FROM proveedores ORDER BY id ASC';
					$prov = mysqli_query($con,$sqlp);
					?>
				<select name="proveedor_id" id="proveedor_id">
					<option value="">Seleccione proveedor</option>		
					<?php
						while($row = mysqli_fetch_array($prov))
						{
							echo '<option value ="'.$row['id'].'">'.$row['nombre'].'</option>';
						}		
					?>
				</select>		

				</div>
				<div class="form-group">
					<label for="precioventa">precioventa</label>
					<input type="text" name="precioventa">
				</div>
				<div class="form-group">
					<label for="fecha">fecha</label>
					<input type="date" name="fecha">
				</div>
				<div class="form-group">
					<button type="submit" name="submit">Guardar</button>
					<a href="articulos_index.php" class="back-link" style="float:right"><< Volver</a>
				</div>
				<!-- comentario -->
			</form>
		</div>
	</div>
	<?php
	require_once('include/foot.php');
	?>