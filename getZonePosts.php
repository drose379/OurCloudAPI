<?php

require_once 'connect.php';

class getZonePosts {

	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$zoneId = $post[0];

		$this->getZonePosts($zoneId);
	}

	public function getZonePosts($zoneId) {
		$posts = [];

		$con = DBConnect::get();
		$stmt = $con->prepare("SELECT user,user_photo,postText FROM zone_posts WHERE zone = :zoneId");
		$stmt->bindParam(':zoneId',$zoneId);
		$stmt->execute();
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$posts[] = $result;
		}

		echo json_encode($posts);
	}

}