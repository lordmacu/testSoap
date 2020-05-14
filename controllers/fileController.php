<?php 
 
class fileController{

    private const WSDServices = "http://test.analitica.com.co/AZDigital_Pruebas/WebServices/ServiciosAZDigital.wsdl";
    private const UrlServices = "http://test.analitica.com.co/AZDigital_Pruebas/WebServices/SOAP/index.php";


	public function loadFilesReport(){

        $helper= new Helpers();
        $file= new File();
		 
		$loadFilesReport = $file->loadFilesReport();

		$data=[];
        
        $buildXml = $helper->buildXml($loadFilesReport,"file","files");  // building xml
        $result=$buildXml["xml"];
        $size=$buildXml["size"];

        $theme=$helper->buildTheme(array("name","type","extension"),"files/file");  // building theme
       
        $getXmlResult= $helper->getXmlResult($result,$theme,$size);

		echo   json_encode($getXmlResult);
	}
 



    public function findFiles($params) {

        $WSDServices = new SoapClient(self::WSDServices);

        $WSDServices->__setLocation(self::UrlServices);
        // conection  and find function to search file
        try {
            return $result = $WSDServices->BuscarArchivo($params); // find files and return 
        } catch (SoapFault $th) {
            return $th;
        }
    }

    public function saveFiles() {
        $file= new File();

    	$files = $this->findFiles(array("Condiciones" => array("Condicion" => array("Tipo" => "FechaInicial", "Expresion" => "2019-07-01 00:00:00")))); // prepare query

        for ($i = 0; $i < count($files->Archivo); $i++) {

            $arrayTypes = array(
                "pdf" => "Portable Document Format File",
                "xml" => "XML File",
                "html" => "Hypertext Markup Language File",
                "css" => "Cascading Style Sheet",
                "js" => "JavaScript File",
                "png" => "Portable Network Graphic",
                "jpg" => "JPEG Image",
                "mp4" => "MPEG-4 Video File",
                "mpeg" => "MPEG-2 Video File",
                "mp3" => "MP3 Audio File",
                "p12" => "Personal Information Exchange File",
                "txt" => "Plain Text File",
                "docx" => "Microsoft Word Open XML Document",
                "wsdl" => "Web Services Description Language File",
                "ico" => "Icon File",
                "xslt" => "XSL Transformation File",
                "url" => "Internet Shortcut",
                "xls" => "Excel Spreadsheet"
            );
            //maping know extentions
            
            $name = $files->Archivo[$i]->Nombre;
            $id = $files->Archivo[$i]->Id;
            //get name and id

            $ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $name); // get extension 

            if (isset($arrayTypes[$ext])) {
                $type = $arrayTypes[$ext]; // get the extention type
            } else {
                $type = "unknown"; // if extension is unknown 
                $ext= "unknown";
            }

            $file->setFile($id, $name, $ext, $type); // save to database
        }
     }
}