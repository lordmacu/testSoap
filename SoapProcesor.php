<?php

class SoapProcesor {
 
    protected $db;

    function __construct($db) {
        $this->db = $db;
    }

    private const WSDServices = "http://test.analitica.com.co/AZDigital_Pruebas/WebServices/ServiciosAZDigital.wsdl";
    private const UrlServices = "http://test.analitica.com.co/AZDigital_Pruebas/WebServices/SOAP/index.php";

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

    public function saveFiles($results) {

        for ($i = 0; $i < count($results->Archivo); $i++) {

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
            
            $name = $results->Archivo[$i]->Nombre;
            $id = $results->Archivo[$i]->Id;
            //get name and id

            $ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $name); // get extension 

            if (isset($arrayTypes[$ext])) {
                $type = $arrayTypes[$ext]; // get the extention type
            } else {
                $type = "unknown"; // if extension is unknown 
                $ext= "unknown";
            }

            $this->db->setFile($id, $name, $ext, $type); // save to database
        }

        $this->db->disconnect(); // disconect to database
    }

}

?>