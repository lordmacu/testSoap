<?php 

class Route{
	
	public static  function get($route,$controller){
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			if(isset($_GET["action"])){
				if($_GET["action"]==$route){
				return (new self)->processRoute($controller);
				}
			}else{
				return (new self)->processRoute($controller);
			}
	   		
		}
	}

	public static  function post($route,$controller){
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	   		if($_GET["action"]==$route){
				return (new self)->processRoute($controller);
			}
		}
	}

	public static function processRoute($controller){
		$controllerExplode=explode("@",$controller);
		$controller= new $controllerExplode[0]();
		return  $controller->{$controllerExplode[1]}();
	}

}