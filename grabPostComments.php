<?php

require_once 'connect.php';

class grabPostComments {

	public function run() {

		$post = json_decode(file_get_contents("php://input"));

		$postId = $post[0];

	}

	public function grabComments($postId) {
		$con = DBConnect::get();
		$stmt = $con->prepare("SELECT 
			users.user_name,users.user_image,
			post_comments.comment_time,post_comments.comment 
			FROM post_comments
			JOIN users ON users.user_id = post_comments.user_id 
			WHERE post_id = :postId");


		//does WHERE get called before JOIN? 
		//If not, how does it JOIN
	}

}