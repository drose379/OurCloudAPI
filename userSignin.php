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
		$gcmId = $post[1];
		$userName = $post[2];
		$userPhoto = $this->formatUrl($post[3]);

		if(!$this->userExists($userId)) {$this->newUser($userId,$gcmId,$userName,$userPhoto);}

	}

	public function userExists($userId) {
		$con = DBConnect::get();
		$stmt = $con->prepare("SELECT COUNT(`user_id`) FROM users WHERE user_id = :userid");
		$stmt->bindParam(':userid',$userId);
		$stmt->execute();

		$exists = $stmt->fetchColumn() == 0 ? false : true;
		return $exists;
	}

	/**
	 * Used to format url to change photo size to 65 instead of 50
	 * Google API returns a url of photo and sz=X at the end of URL for image size
	 * Grab everything after = and change it to correct size
	 */
	public function formatUrl($imageUrl) {
		$urlArray = preg_split("/\=/",$imageUrl);
		$urlArray[1] = "=65";
		return implode($urlArray);
	}


	public function newUser($userId,$gcmId,$userName,$userPhoto) {
		$con = DBCOnnect::get();
		$stmt = $con->prepare("INSERT INTO users (user_id,user_gcm_id,user_name,user_image) VALUES (:userid,:gcm,:username,:userimage)");
		$stmt->bindParam(':userid',$userId);
		$stmt->bindParam(':gcm',$gcmId);
		$stmt->bindParam(':username',$userName);
		$stmt->bindParam(':userimage',$userPhoto);
		$stmt->execute();
	}

}
