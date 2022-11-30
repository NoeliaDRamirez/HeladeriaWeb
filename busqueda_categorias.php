<?php
	session_start();
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
	//eliminar el registro con la id
	if(isset($_GET['action'],$_GET['id']) && $_GET['action'] == 'delete')
	{
		//Obtiene el valor entero de una variable, si existe-> borrare el id
		$id = intval(trim($_GET['id']));
		
		//preparo la consulta para eliminarla
		$sql = 'DELETE FROM categorias WHERE id = '.$id;
		
		//proceso la eliminación
		$deleteRs = mysqli_query($con,$sql);
		
		//muestro el mensaje de lo que ocurrió.
		if(mysqli_affected_rows($con) == 0)
		{
			$_SESSION['error_msg'] = 'Imposible eliminar el registro';
			header('location:categorias_index.php');
			exit();
		}
		else
		{
			$_SESSION['success_msg'] = 'El registro ha sido eliminado con éxito';
			header('location:categorias_index.php');
			exit();
		}
	}
	
	//consulta a listado
	$sql = 'SELECT * FROM categorias ORDER BY id DESC';
	$rs = mysqli_query($con,$sql);//la funcion hace la conexion
	require_once( 'include/head.php');

    //busqueda
    $busqueda = $_POST['busqueda']; //obtener la variable enviada desde el form
    
$sqlBusqueda = "SELECT * 
                FROM categorias
                WHERE id LIKE '$busqueda%' OR 
                nombre LIKE '$busqueda%' ";

$rsBusqueda = mysqli_query($con,$sqlBusqueda);


?>


	<div class="container wrapper">
		<h2>Categorias</h2>
		<a href="categorias_create.php" class="add-new">Agregar una nueva categoria</a>
		
		<?php
			if(isset($_SESSION['success_msg']))
			{
				echo '<div class="success-msg">'.$_SESSION['success_msg'].'</div>';
				unset($_SESSION['success_msg']);
			}
			
			if(isset($_SESSION['error_msg']))
			{
				echo '<div class="error-msg">'.$_SESSION['error_msg'].'</div>';
				unset($_SESSION['error_msg']);
			}
			
		?>

		<form action="busqueda_categorias.php" method="POST" class= "formBusqueda" > 
	<h2> Buscador: </h1>
<input type="text" value="" name="busqueda" id= "busqueda" placeholder="Buscar">
<input type="submit" value="Buscar" class="btnBuscar">
<br>

</form>
		<table class="table">
			<tr>
				<th>#</th>
				<th>Nombre de categorias</th>
				<th>Accion</th>
			</tr>
			<?php
				while($row = mysqli_fetch_assoc($rsBusqueda))
				{
			?>
					<tr >
						<td> <?php echo $row['id']?> </td>
						<td> <?php echo $row['nombre']?> </td>
						<td>
							<a href="categorias_edit.php?id=<?php echo $row['id']?>" >Editar</a> |
							<a href="categorias_index.php?action=delete&id=<?php echo $row['id']?>" class="delete-record">Eliminar</a>
						</td>
					</tr>
			<?php
				}
			?>
		</table>
	</div>
	
	<?php
	require_once('include/foot.php');
	?>