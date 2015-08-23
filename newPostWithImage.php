<?php

class newPostWithImage {

	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$userId = $post[0];
		$zone = $post[1];
		$postText = $post[2];
		$postImageUrl = $post[3];
		$postTimeMillis = $post[4];

		$this->insertTextWithPhoto($userId,$zone,$postText,$postImageUrl,$postTimeMillis);

		


	}

	public function insertTextWithPhoto($userId,$zone,$post,$postImageUrl,$postTimeMillis) {

		$con  = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO zone_posts (user_id,zone,postText,postImage,postTime) VALUES (:id,:zone,:postText,:postImage,:postTime)");
		$stmt->bindParam(':id',$userId);
		$stmt->bindParam(':zone',$zone);
		$stmt->bindParam(':postText',$post);
		$stmt->bindParam(':postImage',$postImageUrl);
		$stmt->bindParam(':postTime',$postTimeMillis);
		$stmt->execute();
	}

	public function insertPhoto($userId,$zone,$postImageUrl,$postTimeMillis) {
		$con = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO zone_posts (user_id,zone,postImage,postTime) VALUES (:id,:zone,:postImage,:postTime)");
		$stmt->bindParam(':id',$userId);
		$stmt->bindParam(':zone',$zone);
		$stmt->bindParam(':postImage',$postImageUrl);
		$stmt->bindParam(':postTime',$postTimeMillis);
	}

}