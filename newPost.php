<?php

require 'connect.php';

class newPost {

	public function run() {
		$post = json_decode("php://input");

		$user = $post["user"];
		$zone = $post["zone"];
		$postText = $post["postText"];

		$this->insert($user,$zone,$postText)

	}

	public function insert($user,$zone,$post) {
		$con  = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO zone_posts (user,zone,postText) VALUES (:user,:zone,:postText)");
		$stmt->bindParam(':user',$user);
		$stmt->bindParam(':zone',$zone);
		$stmt->bindParam(':postText',$post);
		$stmt->execute();
	}

}