<?php

class newPostWithImage {

	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$userId = $post[0];
		//$user = $post[1];
		//$userPhoto = $this->formatUrl($post[2]);
		$zone = $post[3];
		$postText = $post[4];
		$postImageUrl = $post[5];

		$this->insert($userId,$zone,$postText,$postImageUrl);


	}

	public function insert($userId,$zone,$post,$postImageUrl) {

		$con  = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO zone_posts (user_id,zone,postText,postImage) VALUES (:id,:zone,:postText,:postImage)");
		$stmt->bindParam(':id',$userId);
		$stmt->bindParam(':zone',$zone);
		$stmt->bindParam(':postText',$post);
		$stmt->bindParam(':postImage',$postImageUrl);
		$stmt->execute();
	}

}