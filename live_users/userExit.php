<?php

require_once 'connect.php';
require_once 'gcmController.php';

class liveUserExit {


	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$userId = $post[0];
		$zoneId = $post[1];

		$this->removeLiveUser( $userId );

		$remainingUsers = $this->getRemainingUsers($zoneId);
		GcmController::sendGcmUserUpdate($remainingUsers);

	}

	private function removeLiveUser( $userId ) {
		$con = DBConnect::get();
		$stmt = $con->prepare("DELETE FROM live_users WHERE user_id = :user_id");
		$stmt->bindParam(':user_id',$userId);
		$stmt->execute();
	}

	private function getRemainingUsers( $zoneId ) {
		$users = [];
		$con = DBConnect::get();
		$stmt = $con->prepare("SELECT user_id, user_gcm_id, user_name, user_photo FROM live_users WHERE user_zone_id = :zoneId");
		$stmt->bindParam(':zoneId',$zoneId);
		$stmt->execute();

		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$users[] = $result;
		}
		return json_encode($users);
	}

	private function sendGcmMessage( $users ) {

	}


}