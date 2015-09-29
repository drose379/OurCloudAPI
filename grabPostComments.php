<?php

require_once 'connect.php';

class grabPostComments {

	public function run() {

		$post = json_decode(file_get_contents("php://input"));

		$postId = $post[0];

		$this->grabComments($postId);

	}

	public function grabComments($postId) {
		$comments = [];

		$con = DBConnect::get();
		$stmt = $con->prepare("SELECT 
			users.user_name,users.user_image,
			post_comments.comment_time,post_comments.comment 
			FROM post_comments
			JOIN users ON users.user_id = post_comments.user_id 
			WHERE post_id = :postId ORDER BY comment_time DESC");

		$stmt->bindParam(':postId',$postId);
		$stmt->execute();

		while ($comment = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$comments[] = $comment;
		}

		echo json_encode($comments);

	}

}