<?php

class DBConnect {
	public static function get() {
		return new PDO('mysql:host=localhost;dbname=OurCloud','root','HwAlJAgstN');
	}
}