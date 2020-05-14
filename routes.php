<?php
 
include (dirname(__FILE__)."/libs/Loader.php");

Route::get("/","homeController@home");
Route::get("getAllfiles","fileController@loadFilesReport");
Route::get("saveFiles","fileController@saveFiles");
Route::get("getAllExtensions","extensionController@getExtensions");
