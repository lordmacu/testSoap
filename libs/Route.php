<?php 

class Route{
	
	public static  function get($route,$controller){
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			if(isset($_GET["action"])){
				if($_GET["action"]==$route){
					$controllerExplode=explode("@",$controller);
					$controller= new $controllerExplode[0]();
		    		return  $controller->{$controllerExplode[1]}();
				}
			}else{
				$controllerExplode=explode("@",$controller);
				$controller= new $controllerExplode[0]();
	    		return  $controller->{$controllerExplode[1]}();
			}
	   		
		}
	}

	public static  function post($route,$controller){
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	   		if($_GET["action"]==$route){
				$controllerExplode=explode("@",$controller);
				$controller= new $controllerExplode[0]();
	    		return  $controller->{$controllerExplode[1]}();
			}
		}
	}

}