<?php

require_once 'connect.php';

class getZonePosts {

	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$zoneId = $post[0];

		$this->grabPosts($zoneId);
	}

	public function grabPosts($zoneId) {
		$currentMillis = (date("U") * 1000);

		$posts = [];
		$finalPosts = [];

		$con = DBConnect::get();
		$stmt = $con->prepare("SELECT
			users.user_name,users.user_image,
			zone_posts.postText,zone_posts.postImage,zone_posts.postTime,zone_posts.expDate
			FROM zone_posts
			JOIN users ON users.user_id = zone_posts.user_id
			WHERE zone = :zoneId");

		$stmt->bindParam(':zoneId',$zoneId);
		$stmt->execute();
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$posts[] = $result;
		}

		//need to skip this if expiration for post is 0, a post with 0 exp time will last forever
		for($i = 0;$i<sizeof($posts);$i++) {
			$currentPost = $posts[$i];
			if ($currentPost["expDate"] == null || $currentMillis < $currentPost["expDate"]) {
				$finalPosts[] = $currentPost;
			}
		}

		//testing 
		$this->addPostType($finalPosts);

		//echo json_encode($finalPosts);

	}

	public function addPostType($grabbedPosts) {
		foreach($grabbedPosts as $post) {
			/**
			* loop over each post, check what items are null (if any)
			* type 1 = just text
			* type 2 = text and photo
			* type 3 = just photo

			* Add the type to the $post and echo out the json encoded $grabbedPosts array
			* To test, error log the json_encoded array
			*/

			if ($post["postText"] != null && $post["postImage"] != null) {
				//type 2
				$type = "2";
			} else if ($post["postText"] != null && $post["postImage"] == null) {
				//type 1
				$type = "1";
			} else {
				//should be just image
				$type = "3";
			}

			$post["postType"] = $type;
		}

		echo json_encode($grabbedPosts);

	}




}