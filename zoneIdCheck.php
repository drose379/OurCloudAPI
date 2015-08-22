<?php

require_once 'connect.php';

class zoneIdGrabber {

	private $zoneSSID;
	private $networksInRange;
	
	public function run() {

		$post = json_decode(file_get_contents("php://input"),true);

		$this->zoneSSID = $post[0];
		$this->networksInRange = json_decode($post[1],true);

		$con = DBConnect::get();

		$zoneId = $this->grabZoneId($con);

		if($zoneId == null) {
			$zoneId = $this->createNewZone($con);
		}

		echo $zoneId;
	}

	public function grabZoneId($con) {
		//make a query for zone that matches criteria, if found, return true, else return false
		$matchedZoneId;
		$matchingZones = [];

		$stmt = $con->prepare("SELECT * FROM zones WHERE SSID = :ssid");
		$stmt->bindParam(':ssid',$this->zoneSSID);
		$stmt->execute();

		while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$matchingZones[] = $result;
		}

		if (sizeof($matchingZones) > 1) {

			for($i = 0;$i<sizeOf($matchingZones);$i++) {

				$currentZone = $matchingZones[$i];
				error_log($currentZone);

			}

		} else {
			$matchedZoneId = null;
		}

		return $matchedZoneId;
	}

	public function createNewZone($con) {
		//creates a new zone record (generate a random 10 digit number as the zoneId, and return the zoneId)
		$newZoneId = rand(1,getrandmax());

		$stmt = $con->prepare("INSERT INTO zones (ID,SSID,inRange) VALUES (:id,:ssid,:inRange)");
		$stmt->bindParam(':id',$newZoneId);
		$stmt->bindParam(':ssid',$this->zoneSSID);
		$stmt->bindParam(':inRange',json_encode($this->networksInRange));
		$stmt->execute();
	}



}
 