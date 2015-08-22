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

		error_log($this->zoneSSID);

		if ($this->isZoneFound($con)) {
			//run grabZoneId and echo the result
		} else {
			//run createNewZone and echo the result
		}

	}

	public function isZoneFound($con) {
		//make a query for zone that matches criteria, if found, return true, else return false
		$matchingZones = [];

		$stmt = $con->prepare("SELECT * FROM zones WHERE SSID = :ssid");
		$stmt->bindParam(':ssid',$this->zoneSSID);
		$stmt->execute();

		while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$matchingZones[] = $result;
		}

		if (sizeof($matchingZones) > 1) {
			error_log("Item found");
		} else {
			error_log("Item not found");
		}

	}

	public function createNewZone() {
		//creates a new zone record AND returns the new zoneId
	}

	public function grabZoneId() {
		//called if zone record already exists, returns the zoneId of matching criteria
	}

}
 