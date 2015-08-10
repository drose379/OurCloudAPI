<?php

require_once 'connect.php';

class newPost {

	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$user = $post[0];
		$userPhoto = $this->formatUrl($post[1]);
		$zone = $post[2];
		$postText = $post[3];

		$this->insert($user,$userPhoto,$zone,$postText);

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

	public function insert($user,$userPhoto,$zone,$post) {
		$con  = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO zone_posts (user,user_photo,zone,postText) VALUES (:user,:user_photo,:zone,:postText)");
		$stmt->bindParam(':user',$user);
		$stmt->bindParam(':user_photo',$userPhoto);
		$stmt->bindParam(':zone',$zone);
		$stmt->bindParam(':postText',$post);
		$stmt->execute();
	}

}