<?php

require_once 'connect.php';

class liveUserExit {


	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$userId = $post[0];
		$zoneName = $post[1];

		$this->removeLiveUser( $userId );
	}

	public function removeLiveUser( $userId ) {
		$con = DBConnect::get();
		$stmt = $con->prepare("DELETE FROM live_users WHERE user_id = :user_id");
		$stmt->bindParam(':user_id',$userId);
		$stmt->execute();
	}

	public function updateUsers() {
		//pull all users in the zoneName
	}


}