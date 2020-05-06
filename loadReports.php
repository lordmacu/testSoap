<?php

include("DbConection.php");
include("SoapProcesor.php");

$db = new DbClass();

if ($_GET["action"] == "getrAllfiles") {
    //get request to show all files by query
    $loadFilesReport = $db->loadFilesReport();
    $db->disconnect();
    echo json_encode($loadFilesReport);
}



if ($_GET["action"] == "getAllExtensions") {
    //get request to show all extensions by query
    $loadFilesReport = $db->getExtensions();
    $db->disconnect();
    echo json_encode($loadFilesReport);
}


if ($_GET["action"] == "saveFiles") {
    // get files from Soap and save to database
    $SoapProcesor = new SoapProcesor($db);
    $files = $SoapProcesor->findFiles(array("Condiciones" => array("Condicion" => array("Tipo" => "FechaInicial", "Expresion" => "2019-07-01 00:00:00")))); // prepare query
    
    $SoapProcesor->saveFiles($files); // save files
}