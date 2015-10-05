<?php

require 'userSignin.php';
require 'newPostText.php';
require 'newPostWithImage.php';
require 'newComment.php';
require 'updateZoneName.php';
require 'grabPostComments.php';

require 'zoneIdCheck.php';
require 'getZonePosts.php';

require 'postImageUpload.php';

require 'newMarkedZone.php';

//live user system
require 'live_users/newUserEnter.php';
require 'live_users/userExit.php';
require 'live_users/processMessage.php';

class Router {

	private $routes = [];

	public function __construct() {
		$this->loadRoutes();
	}

	public function loadRoutes() {
		$this->routes = [
			"/userSignin" => [new userSignin,"run"],
			"/newPost" => [new newPostText,"run"],
			"/newPostWithImage" => [new newPostWithImage,"run"],
			"/newComment" => [new newComment,"run"],
			"/getZoneId" => [new zoneIdGrabber,"run"],
			"/updateZoneName" => [new zoneNameUpdate,"run"],
			"/getZonePosts" => [new getZonePosts,"run"],
			"/getPostComments" => [new grabPostComments,"run"],
			"/postImageUpload" => [new postImageUpload,"run"],
			"/markZone" => [new markZone,"run"],
			"/live/newUserEnter" => [new newUserEnter,"run"],
			"/live/userExit" => [new liveUserExit, "run"],
			"/live/privateMessage" => [new processMessage,"run"]
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