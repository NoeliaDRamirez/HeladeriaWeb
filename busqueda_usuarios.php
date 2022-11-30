<?php
	session_start();
	require_once('config.php');
    $busqueda = $_POST['busqueda']; //obtener la variable enviada desde el form

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

	//denegacion de acceso para visitante y operador
	if($datosUsuario['rol_id'] == 3 or $datosUsuario['rol_id'] == 2 ){
		header('Location: articulos_index.php');
	}

	//eliminar el registro con la id
	if(isset($_GET['action'],$_GET['id']) && $_GET['action'] == 'delete')
	{
		//Obtiene el valor entero de una variable, si existe-> borrare el id
		$id = intval(trim($_GET['id']));
		
		//preparo la consulta para eliminarla
		$sql = 'DELETE FROM usuarios WHERE id = '.$id;	
			
		//proceso la eliminación
		$deleteRs = mysqli_query($con,$sql);
		
		//muestro el mensaje de lo que ocurrió.
		if(mysqli_affected_rows($con) == 0)
		{
			$_SESSION['error_msg'] = 'Imposible eliminar el registro';
			header('location:usuarios_index.php');
			exit();
		}
		else
		{
			$_SESSION['success_msg'] = 'El registro ha sido eliminado con éxito';
			header('location:usuarios_index.php');
			exit();
		}
	}
	/*
	//consulta a listado
	$sql = 'SELECT * FROM usuarios ORDER BY id DESC';
	$rs = mysqli_query($con,$sql);//la funcion hace la conexion
	require_once( 'include/head.php');
*/
//consulta a listado
$sql = 'SELECT usuarios.*, roles.nombre as nombrerol
FROM usuarios
JOIN roles ON usuarios.rol_id = roles.id
ORDER BY usuarios.id DESC';

$rs = mysqli_query($con,$sql);//la funcion hace la conexion
require_once( 'include/head.php');

//busqueda
$sqlBusqueda = "SELECT usuarios.*, roles.nombre as nombrerol
                FROM usuarios
                JOIN roles ON usuarios.rol_id = roles.id
		        WHERE usuarios.id LIKE '$busqueda%' OR 
                usuarios.usuario LIKE '$busqueda%' OR 
                roles.nombre LIKE '$busqueda%' OR 
                usuarios.email LIKE  '$busqueda%'";

$rsBusqueda = mysqli_query($con,$sqlBusqueda);
		

?>

<link rel="stylesheet" href="css/usuario_style.css" media="all" type="text/css">

<div class="containerb wrapper">
<form action="busqueda_usuarios.php" method="POST" class= "formBusqueda" > 
	<h2> Buscador: </h1>
<input type="text" value="" name="busqueda" id= "busqueda" placeholder="Buscar">
<input type="submit" value="Buscar" class="btnBuscar">
<br>
</div>
	<div class="containerb wrapper">
		
		<h2>Usuarios</h2>
		
		
		<table class="table">
			<tr>
				<th>Id</th>
				<th>Usuario</th>
                <th>Email</th>
                <th>Rol</th>
				<th>Accion</th>
			</tr>
			<?php
				while($row = mysqli_fetch_assoc($rsBusqueda))
				{
			?>
					<tr >
						<td> <?php echo $row['id']?> </td>
						<td> <?php echo $row['usuario']?> </td>
                        <td> <?php echo $row['email']?> </td>
						<td> <?php echo $row['nombrerol']?> </td>
						<td>
							<a href="usuarios_edit.php?id=<?php echo $row['id']?>" >Editar</a> |
							<a href="usuarios_index.php?action=delete&id=<?php echo $row['id']?>" class="delete-record">Borrar</a>
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