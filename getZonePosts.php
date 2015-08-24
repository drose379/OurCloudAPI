<?php

require_once 'connect.php';

class getZonePosts {

	public function run() {
		$post = json_decode(file_get_contents("php://input"),true);

		$zoneId = $post[0];

		$this->grabPosts($zoneId);
	}

	public function grabPosts($zoneId) {
		$posts = array();

		$con = DBConnect::get();
		$stmt = $con->prepare("SELECT
			users.user_name,users.user_image,
			zone_posts.postText,zone_posts.postImage,zone_posts.postTime
			FROM zone_posts
			JOIN users ON users.user_id = zone_posts.user_id
			WHERE zone = :zoneId");

		$stmt->bindParam(':zoneId',$zoneId);
		$stmt->execute();
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$posts[] = $result;
		}

		error_log(microtime(true));
		echo json_encode($posts);
	}




}