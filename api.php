<?php
// Configura los headers para devolver respuestas en formato JSON y permitir peticiones de cualquier origen (CORS policy)
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluye el archivo de conexión a la base de datos
include_once 'db.php';

// Obtiene el método de la petición HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Estructura de control para manejar las peticiones basado en el método HTTP
switch ($method) {
	case 'GET': // Caso para el método GET
		// Verifica si se ha enviado un ID como parte de la URL
		if (isset($_GET['nId'])) {
			$nId = $_GET['nId'];

			// SQL para seleccionar un usuario específico
			$sql = "SELECT * FROM users WHERE nid = :nId";
			$stmt = $con->prepare($sql);
			$stmt->execute(['nId' => $nId]);
			$data = $stmt->fetch(PDO::FETCH_ASSOC);

			// Verifica si se encontraron datos y los devuelve
			if ($data) {
				echo json_encode($data);
			} else {
				// Si no se encontraron datos, devuelve un mensaje de error
				echo json_encode(['status' => 404, 'message' => 'Not Found']);
			}
		} else {
			// SQL para seleccionar todos los usuarios
			$sql = "SELECT * FROM users";
			$stmt = $con->prepare($sql);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

			// Devuelve todos los usuarios encontrados
			echo json_encode($data);
		}
		break;

	case 'POST': // Caso para el método POST
		// Obtiene el cuerpo de la petición
		$data = file_get_contents("php://input");
		$input = json_decode($data, TRUE);

		// Extrae y asigna los valores enviados a variables
		$sNombre = !empty($input['sNombre']) ? $input['sNombre'] : null;
		$dFechaNacimiento = !empty($input['dFechaNacimiento']) ? $input['dFechaNacimiento'] : null;
		$sTelefono = !empty($input['sTelefono']) ? $input['sTelefono'] : null;
		$sEmail = !empty($input['sEmail']) ? $input['sEmail'] : null;
		$sDni = !empty($input['sDni']) ? $input['sDni'] : null;

		// Validaciones básicas para los datos requeridos
		if (($sDni === null) || ($sNombre === null) || ($dFechaNacimiento === null)) {
			echo json_encode(['status' => 400, 'message' => 'Bad Request']);
			break;
		}

		// Valida la longitud de algunos campos para asegurar que cumplen con los requisitos
		if ((strlen($sDni) !== 9) || (strlen($dFechaNacimiento) !== 10) || (($sTelefono !== null) && (strlen($sTelefono) !== 9))) {
			echo json_encode(['status' => 400, 'message' => 'Bad Request']);
			break;
		}

		// SQL para insertar un nuevo usuario con los datos proporcionados
		$sql = "INSERT INTO users (sDni, sNombre, dFechaNacimiento, sTelefono, sEmail)
                VALUES (:sDni, :sNombre, :dFechaNacimiento, :sTelefono, :sEmail)";
		$stmt = $con->prepare($sql);
		$stmt->execute([
			'sDni' => $sDni,
			'sNombre' => $sNombre,
			'dFechaNacimiento' => $dFechaNacimiento,
			'sTelefono' => $sTelefono,
			'sEmail' => $sEmail
		]);

		// Devuelve un mensaje de éxito
		echo json_encode(['status' => 200, 'message' => 'OK']);
		break;

	case 'PUT': // Caso para el método PUT
		// Obtiene el cuerpo de la petición
		$data = file_get_contents("php://input");
		$input = json_decode($data, TRUE);

		// Extrae y asigna los valores enviados a variables, incluido el ID del usuario a actualizar
		$nId = !empty($input['nId']) ? $input['nId'] : null;
		$sNombre = !empty($input['sNombre']) ? $input['sNombre'] : null;
		$dFechaNacimiento = !empty($input['dFechaNacimiento']) ? $input['dFechaNacimiento'] : null;
		$sTelefono = !empty($input['sTelefono']) ? $input['sTelefono'] : null;
		$sEmail = !empty($input['sEmail']) ? $input['sEmail'] : null;
		$sDni = !empty($input['sDni']) ? $input['sDni'] : null;

		// Validaciones básicas para los datos requeridos
		if (($sDni === null) || ($sNombre === null) || ($dFechaNacimiento === null)) {
			echo json_encode(['status' => 400, 'message' => 'Bad Request']);
			break;
		}

		// Valida la longitud de algunos campos para asegurar que cumplen con los requisitos
		if ((strlen($sDni) !== 9) || (strlen($dFechaNacimiento) !== 10) || (($sTelefono !== null) && (strlen($sTelefono) !== 9))) {
			echo json_encode(['status' => 400, 'message' => 'Bad Request']);
			break;
		}

		// SQL para actualizar un usuario existente con los datos proporcionados
		$sql = "UPDATE users SET sDni = :sDni, sNombre = :sNombre, dFechaNacimiento = :dFechaNacimiento, sTelefono = :sTelefono, sEmail = :sEmail WHERE nId = :nId";
		$stmt = $con->prepare($sql);
		$stmt->execute([
			':sDni' => $sDni,
			':sNombre' => $sNombre,
			':dFechaNacimiento' => $dFechaNacimiento,
			':sTelefono' => $sTelefono,
			':sEmail' => $sEmail,
			':nId' => $nId
		]);

		// Devuelve un mensaje de éxito
		echo json_encode(['status' => 200, 'message' => 'OK']);
		break;

	default: // Caso por defecto si el método HTTP no es soportado
		// Devuelve un mensaje de error indicando que el método no es permitido
		header("HTTP/1.1 405 Method Not Allowed");
		break;
}
