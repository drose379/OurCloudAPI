<?php

class zoneIdCheck2 {

	private $zoneSSID;
	private $networksInRange;

	public function run() {

		$post = json_decode( file_get_contents("php://input"), true );

		$this->zoneSSID = $post[0];
		$this->networksInRange = $post[1];

		error_log( json_encode( $this->networksInRange ) );

		echo json_encode(["ID" => "577739225", "name" => "UNH"]);
	}

}