<?php

/**
 * This script is called whenever a new user signs into the android application
 * Uses: Check to make sure the user id passed is saved in the database, if not, save a record of it
 * Maybe update profile photo
*/

require_once 'connect.php';

class userSignIn {
	
	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);
		$userId = $post[0];
		$userName = $post[1];
		$userPhoto = $post[2];

		if(!$this->userExists($userId)) {
			error_log("user does not exist");
		}
	}

	public function userExists($userId) {
		$con = DBConnect::get();
		$stmt = $con->prepare("SELECT COUNT(`user_id`) FROM users WHERE user_id = :userid");
		$stmt->bindParam(':userid',$userId);
		$stmt->execute();

		$exists = $stmt->fetch()[0] == 0 ? false : true;
		return $exists;
	}

}
