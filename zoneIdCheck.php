<?php

class zoneIdGrabber {

	private $zoneSSID;
	private $networksInRange;
	
	public function run() {

		$post = json_decode(file_get_contents("php://input"),true);

		$this->zoneSSID = $post[0];
		$this->networksInRange = json_decode($post[1],true);

		error_log(implode(",",$this->networksInRange));

	}

	public function isZoneFound() {
		//make a query for zone that matches criteria, if found, return true, else return false
	}

	public function createNewZone() {
		//creates a new zone record AND returns the new zoneId
	}

	public function grabZoneId() {
		//called if zone record already exists, returns the zoneId of matching criteria
	}

}
 