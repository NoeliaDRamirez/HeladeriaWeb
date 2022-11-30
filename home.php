<?php
require_once( 'include/head.php');
session_start();
require 'config.php';

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
?>
<body>
<div class= "wrapper"><h4 class= "bienvenida" >Bienvenid@ <?php echo $datosUsuario['usuario'];?></h4></div>
        <div class="row ">
        
            <div class="col-1">
            
                <h2>Freskitos<br>Heladeria</h2>
                <h3>El mejor precio y calidad</h3>
                <p>***</p>
                
                
            </div>
            <div class="col-2">
                <img src="Images/helado3.png" class="helado">
                <div class="color-box"></div>
                
            </div>
        </div>
   
    </div>

</body>
</html>
<?php
	require_once('include/foot.php');
	?>