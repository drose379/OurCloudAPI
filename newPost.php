<?php

require_once 'connect.php';

class newPost {

	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$user = $post[0];
		$userPhoto = $post[1];
		$zone = $post[2];
		$postText = $post[3];

		$this->insert($user,$userPhoto,$zone,$postText);

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