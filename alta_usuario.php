<?php

//Archivo con las consultas para el alta del usuario

//verifico que haya enviado los 3 campos
if(isset($_POST['form_usuario']) && isset($_POST['form_email']) && isset($_POST['form_password'])){

// Verifico que los campos enviados desde el formulario no esten vacios y quito espacios en blanco
	if(!empty(trim($_POST['form_usuario'])) && !empty(trim($_POST['form_email'])) && !empty($_POST['form_password'])){

		// Escapo posibles caracteres especiales que haya ingresado
		$form_usuario = mysqli_real_escape_string($con, htmlspecialchars($_POST['form_usuario']));
		$form_email = mysqli_real_escape_string($con, htmlspecialchars($_POST['form_email']));
		$rol= 3;

				//Verifico que el email sea valido, que tenga el formato correcto
				if (filter_var($form_email, FILTER_VALIDATE_EMAIL)) { 
						// Verifico que email no exista previamente en la tabla
						$verifico_email = mysqli_query($con, "SELECT `email` FROM `usuarios` WHERE email = '$form_email'");

								if(mysqli_num_rows($verifico_email) > 0){    
									$error_message = "El email ingresado ya se encuentra utilizado, utilice otro correo para registrarse.";
								}
								else{
										// En caso que no exista, procedo a preparar el campo clave para guardarlo
										// Utilizaremos la funcion password_hash ( http://php.net/manual/en/function.password-hash.php )

										// Encripto la clave ingresada desde el formulario
										$usuario_hash_password = password_hash($_POST['form_password'], PASSWORD_DEFAULT);
										//$password 	= filter_var(trim($_POST['form_password']),FILTER_SANITIZE_STRING);

										// Ahora ingreso los valores previamente preparados

										$inserto_usuario = mysqli_query($con, "INSERT INTO `usuarios` (usuario, email, password, rol_id) VALUES ('$form_usuario', '$form_email', '$usuario_hash_password', '3')");
										//$inserto_usuario = mysqli_query($con, "INSERT INTO `usuarios` (usuario, email, password, rol_id) VALUES ('$form_usuario', '$form_email', '$password', '3')");

											// Verifico errores y preparo mensajes
											if($inserto_usuario === TRUE){
												$success_message = "Registro exitoso, ahora puede ingresar.";
											}
											else{
												$error_message = "¡Epa! Algo no salió como esperabamos, error.";
											}
								}
						}
				else {
					// Si el email no es correcto, notifico en el mensaje
					$error_message = "La dirección de email ingresada no es válida.";
				}
		}
	else{
		// Si los campos estan vacios, notifico en el mensaje
		$error_message = "Por favor complete los campos vacios.";
	}

}

?>