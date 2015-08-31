<?php

require_once 'connect.php';

class zoneNameUpdate {

	public function run() {
		//needs to grab by zoneId (passed with post) and UPDATE the ` name ` column, 
		//also must echo back the received name to the application

		$post = json_decode(file_get_contents("php://input"),true);

		$zoneId = $post[0];
		$zoneName = $post[1];

		$this->updateZoneName($zoneId,$zoneName);

		echo $zoneName;

	}

	public function updateZoneName($id,$name) {
		$con = DBConnect::get();
		$stmt = $con->prepare("UPDATE zones SET name = :name WHERE ID = :id");
		$stmt->bindParam(':name',$name);
		$stmt->bindParam(':id',$id);
		$stmt->execute();
	}

}