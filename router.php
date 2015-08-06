<?php

require 'newPost.php';

class Router {

	private $routes = [];

	public function __construct() {
		$this->loadRoutes();
	}

	public function loadRoutes() {
		$this->routes = [
			"/newPost", [new newPost,"run"]
		]
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