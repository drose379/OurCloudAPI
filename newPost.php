<?php

require_once 'connect.php';
require_once 'gcmController.php';

class newPost {


	protected function updateZoneClients( $zone ) 
	{
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