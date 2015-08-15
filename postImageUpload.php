<?php

class postImageUpload {
	
	public function run() {

		$imageTmpName = $_FILES["photo"]["tmp_name"];

		$imageResource = imagecreatefromjpeg($imageTmpName);
		$randomName = rand(1,100000000000000).".jpg";
		$path = "/var/www/OurCloudAPI/PostImages/" . $randomName;
		$imageFile = imagejpeg($imageResource,$path);

		echo $path;

	}


}