<?php

class postImageUpload {
	
	public function run() {

		ini_set('upload_max_filesize','20M');

		$imageTmpName = $_FILES["photo"]["tmp_name"];

		$imageResource = imagecreatefromjpeg($imageTmpName);
		$randomName = rand(1,10000000000000000).".jpg";
		$path = "/var/www/OurCloudAPI/PostImages/" . $randomName;
		$imageFile = imagejpeg($imageResource,$path);

		echo "http://104.236.15.47/OurCloudAPI/PostImages/" . $randomName;

		//space is being added somewhere before http://
	}


}