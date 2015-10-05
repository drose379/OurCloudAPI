 <?php

require_once 'connect.php';
require_once 'gcmController.php';

class newPost {

	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$userId = $post[0];
		$zone = $post[1];
		$postText = $post[2];
		$timeMillis = $post[3];
		$expDateMillis = $post[4];

		if($expDateMillis > 0) {
			$this->insertWithExp($userId,$zone,$postText,$timeMillis,$expDateMillis);
		} else {
			$this->insertWithoutExp($userId,$zone,$postText,$timeMillis);
		}

		$this->updateZoneClients( $zone );

		
	}

	public function insertWithExp($userId,$zone,$post,$postTime,$expDate) {
		$con  = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO zone_posts (user_id,zone,postText,postTime,expDate) VALUES (:user_id,:zone,:postText,:time,:expDate)");
		$stmt->bindParam(':user_id',$userId);
		$stmt->bindParam(':zone',$zone);
		$stmt->bindParam(':postText',$post);
		$stmt->bindParam(':time',$postTime);
		$stmt->bindParam(':expDate',$expDate);
		$stmt->execute();
	}

	public function insertWithoutExp($userId,$zone,$post,$postTime) {
		$con  = DBConnect::get();
		$stmt = $con->prepare("INSERT INTO zone_posts (user_id,zone,postText,postTime) VALUES (:user_id,:zone,:postText,:time)");
		$stmt->bindParam(':user_id',$userId);
		$stmt->bindParam(':zone',$zone);
		$stmt->bindParam(':postText',$post);
		$stmt->bindParam(':time',$postTime);
		$stmt->execute();
	}

	/**
	 * Grab all receivers for given zone
	 * Use GCM controller to send them a type 3 message, telling clients there is a new post to be viewed
	 */
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