<?php

require_once 'connect.php';

class getZonePosts {

	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$zoneId = $post[0];

		$this->grabPosts();
	}

	public function grabPosts() {
		echo "grabbing!";
	}



	


}