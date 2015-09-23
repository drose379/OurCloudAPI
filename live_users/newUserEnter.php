<?php

require_once 'connect.php';

class newUserEnter {

	private $gcmId;
	private $userId;
	private $zoneName;
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
		$this->zoneName = $post[2];
		$this->userName = $post[3];
		$this->profileImage = $post[4];

		$this->insertLiveUser();

	}

	private function insertLiveUser() {
		$con = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO live_users (user_gcm_id,user_id,user_zone,user_name,user_photo) VALUES (:gcm,:user_id,:zone,:name,:photo)");
		$stmt->bindParam(':gcm',$this->gcmId);
		$stmt->bindParam(':user_id',$this->userID);
		$stmt->bindParam(':zone',$this->zoneName);
		$stmt->bindParam(':name',$this->userName);
		$stmt->bindParam(':photo',$this->profileImage);
		$stmt->execute();

	}

	private function getUsersOfZone() {
		//returns a json array of all users in the same zone as the user who just entered
	}

	private function sendGcmNotification() {
		//uses cURL to send a notification of type "1" to any client in the same zone as user who just entered
		//in the "to" section of the POST data to GCM, have an array of all gcm_ids of users in zone who need update
	}


}