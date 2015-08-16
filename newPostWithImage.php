<?php

class newPostWithImage {

	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$userId = $post[0];
		$user = $post[1];
		$userPhoto = $this->formatUrl($post[2]);
		$zone = $post[3];
		$postText = $post[4];
		$postImageUrl = $post[5];

		if ($this->userExists($userId)) {
			$this->insert($userId,$zone,$postText,$postImageUrl);
		} else {
			$this->createUser($userId,$user,$userPhoto);
			$this->insert($userId,$zone,$postText,$postImageUrl);
		}

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

	public function userExists($userId) {
		$con = DBConnect::get();
		$stmt = $con->prepare("SELECT user_id FROM users WHERE user_id = :userId");
		$stmt->bindParam(':userId',$userId);
		$stmt->execute();
		$result = $stmt->fetch();

		$exists = $result[0] == null ? false : true;

		return $exists;
	}

	public function createUser($userId,$userName,$userPhoto) {
		$con = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO users (user_id,user_name,user_image) VALUES (:id,:name,:photo)");
		$stmt->bindParam(':id',$userId);
		$stmt->bindParam(':name',$userName);
		$stmt->bindParam(':photo',$userPhoto);
		$stmt->execute();
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