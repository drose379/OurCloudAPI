<?php

require_once 'connect.php';
require_once 'gcmController.php';

class processMessage {

	public function run() {

		$post = json_decode(file_get_contents("php://input"),true);

		$senderID = $post[0];
		$senderName = $post[1];
		$receiverID = $post[2]; // google id, need to grab gcm id from the db
		$message = $post[3];

		$receiverGcmId = $this->getUserGcmID($receiverID);

		GcmController::sendGcmPrivateMessage($senderID,$senderName,$receiverGcmId,"2",$message);

	}

	public function getUserGcmID( $userId ) {
		$gcmId = null;
		$con = DBConnect::get();
		$stmt = $con->prepare("SELECT user_gcm_id FROM live_users WHERE user_id = :id");
		$stmt->bindParam(':id',$userId);
		$stmt->execute();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$gcmId = $result;
		}
		return $gcmId["user_gcm_id"];
	}

}