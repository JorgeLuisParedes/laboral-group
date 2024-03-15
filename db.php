<?php

// Definir variables para los detalles de conexión a la base de datos
$host = 'localhost'; // El servidor donde se encuentra la base de datos
$db_name = 'laboral_group'; // Nombre de la base de datos a la que queremos conectarnos
$username = 'root'; // Nombre de usuario para la autenticación en la base de datos
$password = ''; // Contraseña para la autenticación en la base de datos

try {
	// Intentar establecer una conexión con la base de datos usando PDO
	$con = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);

	// Establecer el modo de error de PDO a excepción para una mejor gestión de errores
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
	// Capturar cualquier excepción que ocurra durante la conexión y mostrar el mensaje de error
	echo "Error de conexión: " . $exception->getMessage();
}
