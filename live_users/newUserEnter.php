<?php

require_once 'connect.php';

class newUserEnter {

	//ADD THIS ITEM TO ROUTER

	public function run() {
		/**
		 * Receives new users zone,user_id, gcm_id,user_name,user_photo
		 * adds new user to the db (make sure to correlate user_id to the gcm_id)
		 * sends a gcm message with updated users for this zone, to all other users in the zone
		 */
	}

	private function insertLiveUser() {
		//add user to table live_users
	}

	private function getUsersOfZone() {
		//returns a json array of all users in the same zone as the user who just entered
	}

	private function sendGcmNotification() {
		//uses cURL to send a notification of type "1" to any client in the same zone as user who just entered
		//in the "to" section of the POST data to GCM, have an array of all gcm_ids of users in zone who need update
	}


}