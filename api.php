<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
	case 'GET':
		$sql = "SELECT * FROM users";
		$stmt = $con->prepare($sql);
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($data);
		break;

	case 'POST':
		$data = json_decode(file_get_contents("php://input"));

		$sDni = isset($data->sDni) ? $data->sDni : null;
		$sNombre = isset($data->sNombre) ? $data->sNombre : null;
		$dFechaNacimiento = isset($data->dFechaNacimiento) ? $data->dFechaNacimiento : null;
		$sTelefono =  isset($data->sTelefono) ? $data->sTelefono : null;
		$sEmail = isset($data->sEmail) ? $data->sEmail : null;

		if (($sDni === null) || ($sNombre === null) || ($dFechaNacimiento === null)) {
			echo json_encode(['status' => 502, 'message' => 'Bad Gateway']);
			break;
		}

		$sql = "INSERT INTO users (sDni, sNombre, dFechaNacimiento, sTelefono, sEmail) VALUES (:sDni, :sNombre, :dFechaNacimiento, :sTelefono, :sEmail)";
		$stmt = $con->prepare($sql);
		$stmt->execute(['sDni' => $sDni, 'sNombre' => $sNombre, 'dFechaNacimiento' => $dFechaNacimiento, 'sTelefono' => $sTelefono, 'sEmail' => $sEmail]);

		echo json_encode(['status' => 200, 'message' => 'OK']);
		break;
}
