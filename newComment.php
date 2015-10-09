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

	public function updatePostOP( $commenterID, $postId ) {
		$OPUserID = null;
		$gcmId = null;

		$con = DBConnect::get();

		$stmt = $con->prepare( "SELECT user_id FROM zone_posts WHERE ID = :postId" );


		$stmt->bindParam(':postId',$postId);
		$stmt->execute();

		while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
			$OPUserID = $row["user_id"];
		}

		$stmt = $con->prepare("SELECT user_gcm_id FROM users WHERE user_id = :userId");
		$stmt->bindParam(':userId',$OPUserID);
		$stmt->execute();
		
		while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
			$gcmId = $row["user_gcm_id"];
		}

		error_log( $gcmId );

		GcmController::sendGCM( [$gcmId], "4", $postId );

	}
}