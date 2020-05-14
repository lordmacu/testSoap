<?php 

class extensionController{
	public function getExtensions(){
        $helper= new Helpers();

		$fileType = new FileType();
	 	$loadFilesReport = $fileType->getExtensions();
 
 		$buildXml = $helper->buildXml($loadFilesReport,"extension","extensions"); // building xml
        $result=$buildXml["xml"];
        $size=$buildXml["size"];
  
        $theme=$helper->buildTheme(array("extension","size"),"extensions/extension"); // building theme

        $getXmlResult= $helper->getXmlResult($result,$theme,$size);

    	echo  json_encode($getXmlResult);
	}
}