<?php

require_once 'connect.php';

class newPostView {

	public function run() {

		$post = json_decode( file_get_contents("php://input"),true );

		$postID = $post[0];
		$userID = $post[1];

		$isNewView = $this->hasUserViewed( $postID, $userID );

		if( $isNewView ) {
			$this->addNewView( $postID, $userID );
		} 

	}

	public function hasUserViewed( $postID, $userID ) {
		$newView = false;

		$con = DBConnect::get();
		$stmt = $con->prepare( "SELECT * FROM post_views WHERE post_id = :post_id AND user_id = :user_id" );
		$stmt->bindParam(':post_id',$postID);
		$stmt->bindParam(':user_id',$userID);
		$stmt->execute();

		if ( $stmt->fetch( ) == false ) {
			$newView = true;
		}

		return $newView;
	}

	public function addNewView( $postID, $userID ) {
		$con = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO post_views (post_id,user_id) VALUES (:post_id,:user_id)");
		$stmt->bindParam(':post_id',$postID);
		$stmt->bindParam(':user_id',$userID);
		$stmt->execute();
	}

}