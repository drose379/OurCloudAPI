<?php

require_once 'connect.php';

class grabUserPosts {

	/**
	 * Need to add functionality to get the amount of views for each post, and the userID of each view
	 * Attach an array of userIDs who have viewed the post to each post array, as $post["views"] = array
	 * 
	 */

	public function run() {
		$post = json_decode( file_get_contents("php://input") , true );
		$userId = $post[0];

		$posts = $this->grabPosts( $userId );

		$posts = $this->addPostType( $posts );
		$posts = $this->addPostViews( $posts );

		echo json_encode( $posts );

	}

	public function grabPosts( $userId ) {
		$con = DBConnect::get();

		$posts = [];

		$stmt = $con->prepare("SELECT ID,zone,postText,postImage,postTime FROM zone_posts WHERE user_id = :user_id");
		$stmt->bindParam(':user_id',$userId);
		$stmt->execute();

		while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
			$posts[] = $row;
		}

		return $posts;

	}

	public function addPostType( $grabbedPosts ) {
		
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

		return $grabbedPosts;

	}

	public function addPostViews( $posts ) {
		$con = DBConnect::get();

		$stmt = $con->prepare("SELECT post_views.user_id, users.user_name FROM post_views JOIN users ON post_views.user_id = users.user_id user WHERE post_id = :post_id"); 

		foreach( $posts as &$post ) {
			$views = [];
			$stmt->bindParam(':post_id',$post["ID"]);
			$stmt->execute();

			while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
				$views[] = $row["user_name"];
			}

			$post["views"] = $views;
		}

		return $posts;

	}

}