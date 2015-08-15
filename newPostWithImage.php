<?php

class newPostWithImage {

	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$user = $post[0];
		$userPhoto = $this->formatUrl($post[1]);
		$zone = $post[2];
		$postText = $post[3];
		$postImageUrl = $post[4];

		$this->insert($user,$userPhoto,$zone,$postText,$postImageUrl);

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

	public function insert($user,$userPhoto,$zone,$post,$postImageUrl) {
		error_log($postImageUrl);
		$con  = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO zone_posts (user,user_photo,zone,postText,postImage) VALUES (:user,:user_photo,:zone,:postText,:postImage)");
		$stmt->bindParam(':user',$user);
		$stmt->bindParam(':user_photo',$userPhoto);
		$stmt->bindParam(':zone',$zone);
		$stmt->bindParam(':postText',$post);
		$stmt->bindParam(':postImage',$postImageUrl);
		$stmt->execute();
	}

}