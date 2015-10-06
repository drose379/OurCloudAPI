<?php

require_once 'connect.php';

class grabUserPosts {

	public function run() {
		$post = json_decode( file_get_contents("") , true );
		$userId = $post[0];

		$posts = $this->grabUserPosts( $userId );

		$this->addPostType( $posts );

		error_log($posts);
	}

	public function grabUserPosts( $userId ) {
		$con = DBConnect::get();

		$posts = [];

		$stmt = $con->prepare("SELECT zone,postText,postImage,postTime FROM zone_posts WHERE user_id = :user_id");
		$stmt->bindParam(':user_id',$userId);
		$stmt->execute();

		while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
			$posts[] = $post;
		}

		return $posts;

	}

	public function addPostType($grabbedPosts) {
		foreach($grabbedPosts as &$post) {
			/**
			* loop over each post, check what items are null (if any)
			* type 1 = just text
			* type 2 = text and photo
			* type 3 = just photo
			*
			* Add the type to the $post and echo out the json encoded $grabbedPosts array
			* To test, error log the json_encoded array
			*/

			if ($post["postText"] != null && $post["postImage"] != null) {
				//both post text and image
				$type = "2";
			} else if ($post["postText"] != null && $post["postImage"] == null) {
				//just post text
				$type = "1";
			} else {
				//just image
				$type = "3";
			}

			$post["postType"] = $type; // need to use the & sign that uses a reference to the origional array (& references orig array)
		}

		echo json_encode($grabbedPosts);

	}

}