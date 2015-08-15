<?php

class postImageUpload {
	
	public function run() {

		$imageTmpName = $_FILES["photo"]["tmp_name"];

		$imageResource = imagecreatefromjpeg($imageTmpName);
		$randomName = rand(1,10000000000000000).".jpg";
		$path = "http://104.236.15.47/OurCloudAPI/PostImages/" . $randomName;
		$imageFile = imagejpeg($imageResource,$path);

		echo $path;

	}


}