<?php

require_once 'connect.php';
require_once 'gcmController.php';

class newPostWithImage {

	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$userId = $post[0];
		$zone = $post[1];
		$postText = $post[2];
		$postImageUrl = $post[3];
		$postTimeMillis = $post[4];
		$expDate = $post[5];

		if($expDate > 0) {
			$this->insertTextWithPhotoExp($userId,$zone,$postText,$postImageUrl,$postTimeMillis,$expDate);
		} else {
			$this->insertTextWithPhoto($userId,$zone,$postText,$postImageUrl,$postTimeMillis);
		}
		
		$this->updateZoneClients( $zone );
		


	}

	public function insertTextWithPhoto($userId,$zone,$post,$postImageUrl,$postTimeMillis) {
		$con  = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO zone_posts (user_id,zone,postText,postImage,postTime) VALUES (:id,:zone,:postText,:postImage,:postTime)");
		$stmt->bindParam(':id',$userId);
		$stmt->bindParam(':zone',$zone);
		$stmt->bindParam(':postText',$post);
		$stmt->bindParam(':postImage',$postImageUrl);
		$stmt->bindParam(':postTime',$postTimeMillis);
		$stmt->execute();
	}


	public function insertTextWithPhotoExp($userId,$zone,$post,$postImageUrl,$postTimeMillis,$expDate) {
		$con  = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO zone_posts (user_id,zone,postText,postImage,postTime,expDate) VALUES (:id,:zone,:postText,:postImage,:postTime,:expDate)");
		$stmt->bindParam(':id',$userId);
		$stmt->bindParam(':zone',$zone);
		$stmt->bindParam(':postText',$post);
		$stmt->bindParam(':postImage',$postImageUrl);
		$stmt->bindParam(':postTime',$postTimeMillis);
		$stmt->bindParam(':expDate',$expDate);
		$stmt->execute();
	}


	public function insertPhoto($userId,$zone,$postImageUrl,$postTimeMillis) {
		$con = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO zone_posts (user_id,zone,postImage,postTime) VALUES (:id,:zone,:postImage,:postTime)");
		$stmt->bindParam(':id',$userId);
		$stmt->bindParam(':zone',$zone);
		$stmt->bindParam(':postImage',$postImageUrl);
		$stmt->bindParam(':postTime',$postTimeMillis);
	}

	/** this is repeated in newPost.php and here, need to fix that, maybe add to gcmController instead? **/
	public function updateZoneClients( $zone ) {
		$con = DBConnect::get();
		$stmt = $con->prepare("SELECT user_gcm_id FROM live_users WHERE user_zone_id = :zone");
		$stmt->bindParam( ':zone', $zone );
		$stmt->execute();

		$receivers = [];

		while ( $result = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
			$receivers[] = $result["user_gcm_id"];
		}

		GcmController::sendGcm( $receivers, "3", "New" );

	}



}