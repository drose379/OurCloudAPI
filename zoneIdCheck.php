<?php

require_once 'connect.php';

class zoneIdGrabber {

	//clean up!

	private $zoneSSID;
	private $networksInRange;
	
	public function run() {

		$post = json_decode(file_get_contents("php://input"),true);

		$this->zoneSSID = $post[0];
		$this->networksInRange = array_unique(json_decode($post[1],true));

		$con = DBConnect::get();

		$zoneId = $this->grabExistingZone($con);

		if($zoneId == null) {
			$zoneId = $this->createNewZone($con);
		}

		echo $zoneId;
	}

	public function grabExistingZone($con) {
		//make a query for zone that matches criteria, if found, return true, else return false
		$matchedZoneId;
		$matchingZones = [];

		$stmt = $con->prepare("SELECT * FROM zones WHERE SSID = :ssid");
		$stmt->bindParam(':ssid',$this->zoneSSID);
		$stmt->execute();

		while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$matchingSSIDZones[] = $result;
		}

		if (sizeof($matchingSSIDZones) > 0) {
			$matchedZoneId = $this->validateMatcingSSIDs($matchingSSIDZones);
		} else {
			$matchedZoneId = null;
		}

		return $matchedZoneId;
	}

	public function validateMatcingSSIDs($matchingZones) {

		$finalMatches = null;

		for($i = 0; $i<sizeof($matchingZones);$i++) { //foreach zone with a matching SSID

			$matches = 0;
			$currentZone = $matchingZones[$i];
			$zoneNetworks = json_decode($currentZone['inRange'],true);

			foreach($zoneNetworks as $network) { // for each network in the inRange array for a matching zone
				if (in_array($network, $this->networksInRange)) { //if the network matches one in the current range, add a match point
					$matches++;
				}
			}

			if($matches >= 1) { // if the zone has one or more matches, add it to the final array
				$finalMatches[] = [$matches,$currentZone];
			}
			
		}

		$maxMatches = 0;
		$validZone = null;

		foreach($finalMatches as $info) { // for each zone, assign the one with the highest matches count to the current zone, and grab the ID
			if($info[0] > $maxMatches) {
				$maxMatches = $info[0];
				$validZone = $info[1];
			}
		}

		//just return the $validZone array (it will include the name)
		return json_encode($validZone);

		//grab zone from finalMatches with the highest number of $matches, then grab its zoneId and return it, if nothing in final matches, return null

	}


	public function createNewZone($con) {
		//creates a new zone record (generate a random 10 digit number as the zoneId, and return the zoneId)
		$newZoneId = rand(1,getrandmax());

		$stmt = $con->prepare("INSERT INTO zones (ID,SSID,inRange) VALUES (:id,:ssid,:inRange)");
		$stmt->bindParam(':id',$newZoneId);
		$stmt->bindParam(':ssid',$this->zoneSSID);
		$stmt->bindParam(':inRange',json_encode($this->networksInRange));
		$stmt->execute();

		error_log(json_encode(["ID" => $newZoneId , "name" => "" ])); //return this

		return $newZoneId; //need to trim this, for some reason space before the zone id in the Db
	}





}
 