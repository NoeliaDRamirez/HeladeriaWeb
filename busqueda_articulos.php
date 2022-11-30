<?php
	session_start();
	require_once('config.php'); // conecto con la bbdd

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

	//eliminar el registro con la id 
	if(isset($_GET['action'],$_GET['id']) && $_GET['action'] == 'delete')
	{
		//Obtiene el valor entero de una variable, si existe-> borrare el id
		$id = intval(trim($_GET['id']));
		
		//preparo la consulta para eliminarla
		$sql = 'DELETE FROM articulos WHERE id = '.$id;
		
		//proceso la eliminación
		$deleteRs = mysqli_query($con,$sql);
		
		//muestro el mensaje de lo que ocurrió.
		if(mysqli_affected_rows($con) == 0)
		{
			$_SESSION['error_msg'] = 'Imposible eliminar el registro';
			header('location:articulos_index.php');
			exit();
		}
		else
		{
			$_SESSION['success_msg'] = 'El registro ha sido eliminado con éxito';
			header('location:articulos_index.php');
			exit();
		}
	}
	
	//consulta a listado
	/*$sql = 'SELECT * FROM articulos ORDER BY id DESC';
	$rs = mysqli_query($con,$sql);//la funcion hace la conexion*/

	$sql = "SELECT articulos.*, categorias.nombre as nombrecategoria, proveedores.nombre as nombreproveedor
		FROM articulos 
		JOIN categorias ON categorias.id = articulos.categoria_id 
		JOIN proveedores ON proveedores.id= articulos.proveedor_id
		ORDER BY articulos.id DESC";
	
	$rs = mysqli_query($con,$sql);
	require_once( 'include/head.php');

?>
 <link rel="stylesheet" href="css/style.css">

	<div class="container wrapper">
		<h2>Articulos</h2>
		<?php //oculto boton para visitante
				if($datosUsuario['rol_id'] == 1 or $datosUsuario['rol_id'] == 2  ){
			 ?>
		<a href="articulos_create.php" class="add-new">Agregar un nuevo articulo</a>
		<?php } ?>
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
			
/*realizar la busqueda en la tabla correspondiente

//SELECT * FROM `articulos` WHERE `nombre` LIKE 'Helado de Vainilla' 

$sql = "SELECT * FROM articulos WHERE nombre LIKE '%".$mibusqueda."%'";


//SELECT nombre, id FROM articulos WHERE codigo > 0 and nombre LIKE '%Juan%'

//echo 'mi consulta seria: <br>'.$sql;
		
//- si tiene datos informar en tabla
$rs = mysqli_query($con,$sql);

while($row = mysqli_fetch_assoc($rs))
				{
					//con datos - > mostrar la tabla
				} else
				{
					// sin datos
				}
 */
/*$sqlBusqueda = "SELECT * 
                FROM articulos 
                WHERE id LIKE '%$busqueda%' OR 
                codigo LIKE '%$busqueda%' OR 
                nombre LIKE '%$busqueda%' OR 
                categoria_id LIKE '%$busqueda%' OR
                preciocompra LIKE '%$busqueda%' OR 
                cantidad LIKE '%$busqueda%' OR 
                proveedor_id LIKE '%$busqueda%' OR 
                fecha LIKE  '%$busqueda%'";
*/
$sqlBusqueda = "SELECT articulos.*, categorias.nombre as nombrecategoria, proveedores.nombre as nombreproveedor 
                FROM articulos 
		JOIN categorias ON categorias.id = articulos.categoria_id 
		JOIN proveedores ON proveedores.id= articulos.proveedor_id
		WHERE articulos.id LIKE '%$busqueda%' OR 
                articulos.codigo LIKE '%$busqueda%' OR 
                articulos.nombre LIKE '%$busqueda%' OR 
                categorias.nombre LIKE '%$busqueda%' OR
                articulos.preciocompra LIKE '$busqueda%' OR 
                articulos.cantidad = '$busqueda%' OR 
                proveedores.nombre LIKE '%$busqueda%' OR 
                articulos.fecha LIKE  '$busqueda%'";

$rsBusqueda = mysqli_query($con,$sqlBusqueda);
		?>


		

<form action="busqueda_articulos.php" method="POST" class= "formBusqueda" > 
	<h2> Buscador: </h1>
<input type="text" value="" name="busqueda" id= "busqueda" placeholder="Buscar">
<input type="submit" value="Buscar" class="btnBuscar">
<br>

</form>
		<table class="table">
			<tr>
				<th>#</th>
				<th>Codigo</th>
				<th>Nombre de Articulo</th>
				<th>Cantidad</th>
				<th>Precio de Venta</th>
				<th>Categoria</th>
				<th>Proveedor</th>
				<th>Fecha</th>

				<th>Accion</th>
			</tr>
			<?php 
				while( $row = mysqli_fetch_assoc($rsBusqueda))
				{
			?>
					<tr>
						<td> <?php echo $row['id']?> </td>
						<td> <?php echo $row['codigo']?> </td>
						<td> <?php echo $row['nombre']?> </td>
						<td> <?php echo $row['cantidad']?> </td>
						<td> <?php echo $row['precioventa']?> </td>
						<td> <?php echo $row['nombrecategoria']?> </td>
						<td> <?php echo $row['nombreproveedor']?> </td>
						<td> <?php echo $row['fecha']?> </td>
						<?php //oculto acciones para visitante
				if($datosUsuario['rol_id'] == 1 || $datosUsuario['rol_id'] == 2  ){
			 ?>
						<td>  
							<a href="articulos_edit.php?id=<?php echo $row['id']?>" >Editar</a> | 
							<a href="articulos_index.php?action=delete&id=<?php echo $row['id']?>" class="delete-record">Eliminar</a>
						</td>
						<?php } ?>
					</tr>
			<?php 
				}
			?>
		</table>
	</div>
	
	<?php
	require_once('include/foot.php');
	?>