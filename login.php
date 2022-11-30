<?php

//Archivo con las consultas para ingreso al panel


//verifico si ingreso email y clave para consultar a mi tabla en la db
if(isset($_POST['form_email']) && isset($_POST['form_password'])){

	// Quito espacios en blanco y verifico que no esten vacios
	if(!empty(trim($_POST['form_email'])) && !empty(trim($_POST['form_password']))){

		// Escapo caracteres especiales en el email ingresado para evitar hacking SQL injection
		$form_email = mysqli_real_escape_string($con, htmlspecialchars(trim($_POST['form_email'])));
	
		// realizo la consulta para ver si existe el email ingresado	
		$query = mysqli_query($con, "SELECT * FROM `usuarios` WHERE email = '$form_email'");

		//si la consulta tiene valores, existe ese email, entonces procedo a consultar por la clave
		if(mysqli_num_rows($query) > 0){

				$row = mysqli_fetch_assoc($query);
				
				//asigno el valor de la clave ingresada en el formulario de login a un variable para mejor vista
				$usuario_db_pass = $row['password'];

				// Verifico que la clave ingresada sea igual a la almacenada en la tabla de la db.
				$verifico_password = password_verify($_POST['form_password'], $usuario_db_pass);

				
				// si la verificación es cierta 
				if($verifico_password === TRUE){

					//Actualizo el id de sesión actual con uno generado más reciente 
					session_regenerate_id(true);

					//coloco el email del usuario en una variable de sesión para poder acceder en otras páginas				
					$_SESSION['email'] = $form_email;

					// direcciono al panel de administración o pagina del logueo exitoso.
					header('Location: home.php');
					exit;

				}
				else{
					// Configuro mensaje de error
					$error_message = "Clave o email incorrectos.";

				}
		}
		else{
			// Si el email no existe, no esta registrado, mando error
			$error_message = "Password o email incorrectos.";
		}
	}
		else{

			// En caso que no haya completado los campos del formulario
			$error_message = "Por favor complete los campos vacios.";
		}

}
?>