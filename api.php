<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
	case 'GET':
		if (isset($_GET['nId'])) {
			$nId = $_GET['nId'];

			$sql = "SELECT * FROM users WHERE nid = :nId";
			$stmt = $con->prepare($sql);
			$stmt->execute(['nId' => $nId]);
			$data = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($data) {
				echo json_encode($data);
			} else {
				echo json_encode(['status' => 404, 'message' => 'Not Found']);
			}
		} else {
			$sql = "SELECT * FROM users";
			$stmt = $con->prepare($sql);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($data);
		}
		break;

	case 'POST':
		$data = file_get_contents("php://input");
		$input = json_decode($data, TRUE);

		$sNombre = !empty($input['sNombre']) ? $input['sNombre'] : null;
		$dFechaNacimiento = !empty($input['dFechaNacimiento']) ? $input['dFechaNacimiento'] : null;
		$sTelefono = !empty($input['sTelefono']) ? $input['sTelefono'] : null;
		$sEmail = !empty($input['sEmail']) ? $input['sEmail'] : null;
		$sDni = !empty($input['sDni']) ? $input['sDni'] : null;

		if (($sDni === null) || ($sNombre === null) || ($dFechaNacimiento === null)) {
			echo json_encode(['status' => 400, 'message' => 'Bad Request']);
			break;
		}

		if ((strlen($sDni) !== 9) || (strlen($dFechaNacimiento) !== 10) || (($sTelefono !== null) && (strlen($sTelefono) !== 9))) {
			echo json_encode(['status' => 400, 'message' => 'Bad Request']);
			break;
		}

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

		echo json_encode(['status' => 200, 'message' => 'OK']);
		break;

	case 'PUT':
		$data = file_get_contents("php://input");
		$input = json_decode($data, TRUE);

		$nId = !empty($input['nId']) ? $input['nId'] : null;
		$sNombre = !empty($input['sNombre']) ? $input['sNombre'] : null;
		$dFechaNacimiento = !empty($input['dFechaNacimiento']) ? $input['dFechaNacimiento'] : null;
		$sTelefono = !empty($input['sTelefono']) ? $input['sTelefono'] : null;
		$sEmail = !empty($input['sEmail']) ? $input['sEmail'] : null;
		$sDni = !empty($input['sDni']) ? $input['sDni'] : null;

		if (($nId === null) || ($sDni === null) || ($sNombre === null) || ($dFechaNacimiento === null)) {
			echo json_encode(['status' => 400, 'message' => 'Bad Request']);
			break;
		}

		if ((strlen($sDni) !== 9) || (strlen($dFechaNacimiento) !== 10) || (($sTelefono !== null) && (strlen($sTelefono) !== 9))) {
			echo json_encode(['status' => 400, 'message' => 'Bad Request']);
			break;
		}

		$sql = "UPDATE users
			SET
				sDni = :sDni,
				sNombre = :sNombre,
				dFechaNacimiento = :dFechaNacimiento,
				sTelefono = :sTelefono,
				sEmail = :sEmail
			WHERE
				nId = :nId";

		$stmt = $con->prepare($sql);

		$stmt->execute([
			':sDni' => $sDni,
			':sNombre' => $sNombre,
			':dFechaNacimiento' => $dFechaNacimiento,
			':sTelefono' => $sTelefono,
			':sEmail' => $sEmail,
			':nId' => $nId
		]);

		echo json_encode(['status' => 200, 'message' => 'OK']);
		break;

	default:
		header("HTTP/1.1 405 Method Not Allowed");
		break;
}
