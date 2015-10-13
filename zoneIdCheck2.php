<?php

require 'connect.php';

class zoneIdCheck2 {

	private $zoneSSID;

	public function run() {

		$post = json_decode( file_get_contents("php://input"), true );

		$this->zoneSSID = $post[0];
		$networksInRange = array_unique($post[1]);

		$networksInRange = $this->removeNetworkBlanks( $networksInRange );  //untested

		$zoneInfo = $this->getZoneInfo( $networksInRange );

		echo $zoneInfo;
	}

	public function removeNetworkBlanks( $networks ) {

		//remove blank items
		foreach ( $networks as $network ) {
			if ($network == null) {
				unser($networks[$network]);
			}
		}

		return $networks;
	}

	public function getZoneInfo( $networks ) {
		$con = DBConnect::get();
		$stmt = $con->prepare("SELECT ID,name FROM zones WHERE SSID = :ssid");
		$stmt->bindParam(':ssid',$this->zoneSSID);
		$stmt->execute();

		$row = $stmt->fetch( PDO::FETCH_ASSOC );

		return ["ID" => $row["ID"], "name" => $row["name"]];
	}


}