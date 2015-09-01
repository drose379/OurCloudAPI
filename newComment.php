<?php

require_once 'connect.php';

class newComment {

	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$userId = $post[0];
		$postId = $post[1];
		$commentTimeMillis = $post[2];
		$comment = $post[3];

		$this->addComment($userId,$postId,$commentTimeMillis,$comment);
	}

	public function addComment($userId,$postId,$commentTimeMillis,$comment) {
		$con = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO post_comments (post_id,user_id,comment_time,comment) VALUES (:postId,:userId,:comment_time,:comment)");
		$stmt->bindParam(':postId',$postId);
		$stmt->bindParam(':userId',$userId);
		$stmt->bindParam(':comment_time',$commentTimeMillis);
		$stmt->bindParam(':comment',$comment);
		$stmt->execute();
	}

}