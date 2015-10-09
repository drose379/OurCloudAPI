<?php

require_once 'connect.php';
require_once 'gcmController.php';

class newComment {

	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$userId = $post[0];
		$postId = $post[1];
		$commentTimeMillis = $post[2];
		$comment = $post[3];

		$this->addComment($userId,$postId,$commentTimeMillis,$comment);

		$this->updatePostOP( $userId,$postId );
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

	/**
	 *send a GCM message to the OP of the post giving them a notification that someone commented on their post
	 *grab the userId who is the OP of this post, via the PostID
	 *check if the userID appears in the live_users table (thats where their gcm id will be found)
	 *Issue, if user is online, they cannot get notifications? Need to save GCM id in the users table also
	*/

	public function updatePostOP( $userID, $postId ) {

		$gcmId = null;

		$con = DBConnect::get();
		//$stmt = $con->prepare("SELECT user_gcm_id FROM users WHERE user_id = :user_id"); // this is the user id for the person making the comment, need to get the id for the OP of the post (2 queries)
		
		$stmt = $con->prepare("SELECT users.user_gcm_id FROM users JOIN users.user_id ON zone_posts.user_id WHERE zone_posts.user_id = :userId");
		$stmt->bindParam(':userId',$userID);
		$stmt->execute();

		while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
			$gcmId = $row["user_gcm_id"];
			error_log( $gcmId );
		}

		GcmController::sendGCM( $gcmId, "4", $postId );

	}
}