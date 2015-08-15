<?php

class postImageUpload {
	
	public function run() {
		$imageTmpName = $_FILES["photo"]["tmp_name"];

		$imageResource = imagecreatefromjpeg($imageTmpName);
		$randomName = substr(md5("text"),0,10).".jpg";
		$path = "/var/www/OurCloudAPI/PostImages/" . $randomName;
		$imageFile = imagejpeg($imageResource,$path);

		echo $randomName;

		//echo out the full image url so application can save it to zone_posts table
			//maybe just make request from this script?
	}

}