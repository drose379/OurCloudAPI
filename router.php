<?php

require 'userSignin.php';
require 'newPostText.php';
require 'newPostWithImage.php';
require 'newComment.php';
require 'updateZoneName.php';
require 'grabPostComments.php';

require 'zoneIdCheck.php';
require 'zoneIdCheck2.php';
require 'getZonePosts.php';

require 'postImageUpload.php';

require 'newMarkedZone.php';
require 'grabMarkedzones.php';
require 'grabUserPosts.php';

//live user system
require 'live_users/newUserEnter.php';
require 'live_users/userExit.php';
require 'live_users/processMessage.php';

require 'newPostView.php';

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
			"/getZoneId" => [new zoneIdCheck2,"run"],
			"/updateZoneName" => [new zoneNameUpdate,"run"],
			"/getZonePosts" => [new getZonePosts,"run"],
			"/getPostComments" => [new grabPostComments,"run"],
			"/postImageUpload" => [new postImageUpload,"run"],
			"/markZone" => [new markZone,"run"],
			"/grabMarkedZones" => [new grabMarkedZones,"run"],
			"/grabUserPosts" => [new grabUserPosts,"run"],
			"/live/newUserEnter" => [new newUserEnter,"run"],
			"/live/userExit" => [new liveUserExit, "run"],
			"/live/privateMessage" => [new processMessage,"run"],
			"/newPostView" => [new newPostView,"run"]
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