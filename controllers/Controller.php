<?php 
class Controller{
	public static function view($file){

 		include($_SERVER['DOCUMENT_ROOT']."/views/".$file.".php");
		
		die();
	}
}