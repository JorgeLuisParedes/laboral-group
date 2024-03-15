<?php

$host = 'localhost';
$db_name = 'laboral_group';
$username = 'root';
$password = '';

try {
	$con = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
	echo "Error de conexión: " . $exception->getMessage();
}
