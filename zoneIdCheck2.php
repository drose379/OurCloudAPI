<?php

class zoneIdCheck2 {

	private $zoneSSID;

	public function run() {

		$post = json_decode( file_get_contents("php://input"), true );

		$this->zoneSSID = $post[0];
		$this->networksInRange = $post[1];

		echo "577739225";

	}

}