<?php

require_once 'connect.php';
require_once 'gcmController.php';

class newUserEnter {

	private $gcmId;
	private $userId;
	private $zoneId;
	private $userName;
	private $profileImage;

	public function run() {
		/**
		 * Receives new users zone,user_id, gcm_id,user_name,user_photo
		 * adds new user to the db (make sure to correlate user_id to the gcm_id)
		 * sends a gcm message with updated users for this zone, to all other users in the zone
		 */

		$post = json_decode(file_get_contents("php://input"),true);

		$this->gcmId = $post[0];
		$this->userID = $post[1];
		$this->zoneId = $post[2];
		$this->userName = $post[3];
		$this->profileImage = $post[4];

		$this->insertLiveUser();
		$users = $this->getUsersOfZone();
		$this->sendGcmNotification($users);

	}

	private function insertLiveUser() {
		$con = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO live_users (user_gcm_id,user_id,user_zone_id,user_name,user_photo) VALUES (:gcm,:user_id,:zone_id,:name,:photo)");
		$stmt->bindParam(':gcm',$this->gcmId);
		$stmt->bindParam(':user_id',$this->userID);
		$stmt->bindParam(':zone_id',$this->zoneId);
		$stmt->bindParam(':name',$this->userName);
		$stmt->bindParam(':photo',$this->profileImage);
		$stmt->execute();

	}

	private function getUsersOfZone() {
		$users = [];
		$con = DBConnect::get();
		$stmt = $con->prepare("SELECT user_id, user_gcm_id, user_name, user_photo FROM live_users WHERE user_zone_id = :zone_id");
		$stmt->bindParam(':zone_id',$this->zoneId);
		$stmt->execute();

		while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$users[] = $result;
		}

		return json_encode($users);
	}

	private function sendGcmNotification($users) {
		$receivers = [];
		$usersArray = json_decode($users,true);
		foreach (json_decode($users,true) as $user) {
			$receivers[] = $user["user_gcm_id"];
		}

		GcmController::sendGCM($receivers,"1",$users);
	}


}