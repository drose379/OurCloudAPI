<?php

require_once 'connect.php';

class grabMarkedZones {

	public function run() {

		$post = json_decode(  file_get_contents("php://input"), true );
		$userId = $post[0];

		// Grab all zone Ids where userId = :useriD
		// Then, need to grab all info about each zone and create an array of zone objects

		$markedZoneIds = $this->getMarkedZoneIds( $userId );

		if ( count( $markedZoneIds ) == 0 ) {
			//respond with blank array, user has not marked zones
			echo json_encode([]);
		} else {
			//get info about each zone
		}

	}

	public function getMarkedZoneIds( $userId ) {

		$markedZoneIds = [];

		$con = DBConnect::get();
		$stmt = $con->prepare("SELECT (user_zone_id) FROM user_marked_zones WHERE user_id = :userid");
		$stmt->bindParam(':userid',$userId);
		$stmt->execute();

		while( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
			$markedZoneIds[] = $row;
		}

		return $markedZoneIds;
	}



}