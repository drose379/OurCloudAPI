<?php

require 'userSignin.php';
require 'newPost.php';
require 'newPostWithImage.php';

require 'zoneIdCheck.php';
require 'getZonePosts.php';

require 'postImageUpload.php';

class Router {

	private $routes = [];

	public function __construct() {
		$this->loadRoutes();
	}

	public function loadRoutes() {
		$this->routes = [
			"/userSignin" => [new userSignin,"run"],
			"/newPost" => [new newPost,"run"],
			"/newPostWithImage" => [new newPostWithImage,"run"],
			"/getZoneId" => [new zoneIdGrabber,"run"],
			"/getZonePosts" => [new getZonePosts,"run"],
			"/postImageUpload" => [new postImageUpload,"run"]
		];
	}

	public function match($path) {
		foreach ($this->routes as $route => $action) {
			if (preg_match("#^$route/?$#i",$path,$params)) {
      			return [$action,$params];
    		}
		}
	}

	public function run($path) {
		list($action,$params) = $this->match($path);
    	$action($params);
	}

}