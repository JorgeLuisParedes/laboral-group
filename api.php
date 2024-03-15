<?php
// Establecer encabezados para el tipo de contenido y permitir el acceso y métodos de control cruzado
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir el archivo de conexión a la base de datos
include_once 'db.php';

// Obtener el método HTTP usado en la petición
$method = $_SERVER['REQUEST_METHOD'];

// Manejar la petición basada en el método HTTP
switch ($method) {
	case 'GET': // Si la petición es GET
		if (isset($_GET['nId'])) { // Verificar si se ha enviado un ID específico
			$nId = $_GET['nId']; // Obtener el ID

			// Preparar y ejecutar la consulta SQL para obtener el usuario por ID
			$sql = "SELECT * FROM users WHERE nid = :nId";
			$stmt = $con->prepare($sql);
			$stmt->execute(['nId' => $nId]);
			$data = $stmt->fetch(PDO::FETCH_ASSOC);

			// Responder con los datos del usuario o un mensaje de error si no se encontró
			if ($data) {
				echo json_encode($data);
			} else {
				echo json_encode(['status' => 404, 'message' => 'Not Found']);
			}
		} else { // Si no se especificó un ID, obtener todos los usuarios
			$sql = "SELECT * FROM users";
			$stmt = $con->prepare($sql);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($data);
		}
		break;

	case 'POST': // Si la petición es POST para crear un nuevo registro
		$data = file_get_contents("php://input");
		$input = json_decode($data, TRUE);

		// Recoger los datos del cuerpo de la petición
		$sNombre = !empty($input['sNombre']) ? $input['sNombre'] : null;
		$dFechaNacimiento = !empty($input['dFechaNacimiento']) ? $input['dFechaNacimiento'] : null;
		$sTelefono = !empty($input['sTelefono']) ? $input['sTelefono'] : null;
		$sEmail = !empty($input['sEmail']) ? $input['sEmail'] : null;
		$sDni = !empty($input['sDni']) ? $input['sDni'] : null;

		// Validar los datos obligatorios y su formato
		if (($sDni === null) || ($sNombre === null) || ($dFechaNacimiento === null) || (strlen($sDni) !== 9) || (strlen($dFechaNacimiento) !== 10) || (($sTelefono !== null) && (strlen($sTelefono) !== 9))) {
			echo json_encode(['status' => 400, 'message' => 'Bad Request']);
			break;
		}

		// Preparar y ejecutar la consulta SQL para insertar el nuevo usuario
		$sql = "INSERT INTO users (
			sDni,
			sNombre,
			dFechaNacimiento,
			sTelefono,
			sEmail
			)
			VALUES (
				:sDni,
				:sNombre,
				:dFechaNacimiento,
				:sTelefono,
				:sEmail
			)";
		$stmt = $con->prepare($sql);
		$stmt->execute([
			'sDni' => $sDni,
			'sNombre' => $sNombre,
			'dFechaNacimiento' => $dFechaNacimiento,
			'sTelefono' => $sTelefono,
			'sEmail' => $sEmail
		]);

		// Responder con éxito
		echo json_encode(['status' => 200, 'message' => 'OK']);
		break;

	case 'PUT': // Si la petición es PUT para actualizar un registro existente
		$data = file_get_contents("php://input");
		$input = json_decode($data, TRUE);

		// Recoger los datos del cuerpo de la petición
		$nId = !empty($input['nId']) ? $input['nId'] : null;

		// Validar los datos obligatorios y su formato
		if (($sDni === null) || ($sNombre === null) || ($dFechaNacimiento === null) || (strlen($sDni) !== 9) || (strlen($dFechaNacimiento) !== 10) || (($sTelefono !== null) && (strlen($sTelefono) !== 9))) {
			echo json_encode(['status' => 400, 'message' => 'Bad Request']);
			break;
		}
		// Preparar y ejecutar la consulta SQL para actualizar el usuario
		// Similar al caso POST, pero con la sentencia SQL de actualización

		echo json_encode(['status' => 200, 'message' => 'OK']);
		break;

	default: // Si el método HTTP no es ninguno de los anteriores, devolver error
		header("HTTP/1.1 405 Method Not Allowed");
		break;
}
