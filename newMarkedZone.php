<?php

require_once 'connect.php';

class markZone {
	
	public function run() {

		$post = json_decode( file_get_contents("php://input"), true );

		$userId = $post[0];
		$zoneId = $post[1];

		$this->insertMarkedZone( $userId, $zoneId );

	}

	public function insertMarkedZone( $userId, $zoneId ) {
		$stmt = $con->prepare("INSERT INTO user_marked_zones (user_id,user_zone_id) VALUES (:userId, :zoneId)");
		$stmt->bindParam(':userId',$userId);
		$stmt->bindParam(':zoneId',$zoneId);
		$stmt->execute();

		error_log("marked zone");
	}


}