<?php

class DbClass {

    private const SERVER = '127.0.0.1';
    private const USER = 'root';
    private const PASSWORD = '';
    private const DATABASE = 'soap';

    //Conection to database to database
    public function connect() {
        $conn = mysqli_connect(self::SERVER, self::USER, self::PASSWORD, self::DATABASE);
        if (!$conn) {
            die('Cannot Connect: ' . mysqli_error());
        }
        $conn->query("SET NAMES 'utf8'");

        return $conn;
    }

    //disconnect to to database
    public function disconnect() {
        mysqli_close($this->connect());
    }

    //set file to database
    public function setFile($id, $name, $ext, $type) {
        
        $type_id = $this->findTypeFile($ext, $type); // find typeFile if not exist create and return id
        
        try {
            if (!$this->findFile($id)) { // if the file not exist insert it
                return  mysqli_query($this->connect(), "INSERT INTO files (file_id,name,type_id) VALUES  ('" . $id . "', '" . $name . "', '" . $type_id["id"] . "')");
                // insert file with the parameters
            }
        } catch (Exception $exc) {
            return "cant connect";
        }
    }

    public function findFile($id) {
        // find file by id
        $result = mysqli_query($this->connect(), 'SELECT id FROM files WHERE file_id ="' . $id . '"');
        $data = mysqli_fetch_assoc($result);
        if (!$data) {
            return false;
        }
        return true;
    }

    public function insertTypeFile($ext, $type) {
        //insert filetype
        mysqli_query($this->connect(), "INSERT INTO file_types (type,extension) VALUES ('" . $type . "', '" . $ext . "')");
        return $this->findTypeFile($ext, $type); // after insert the file find and return 
    }

    public function findTypeFile($ext, $type) {
        // find and type text if not exist insert and return it
        $result = mysqli_query($this->connect(), 'SELECT id FROM file_types WHERE extension ="' . $ext . '"');
        $data = mysqli_fetch_assoc($result);
        if (!$data) {
            return $this->insertTypeFile($ext, $type);
        }
        return $data;
    }

    public function clean($string) {
        
        //clean special characters
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^a-z0-9\_\-\.]/i', '', $string); // Removes special chars.
    }

    public function loadFilesReport() {
        
        //load reports from query
        $resultQuery = mysqli_query($this->connect(), "SELECT files.*, file_types.type, file_types.extension FROM `files` JOIN file_types ON files.type_id = file_types.id");
        $size = 0;
        if (!is_null($resultQuery)) {
            $result = '<?xml version="1.0" encoding="UTF-8"?>';
            $result .= "<files>";
            while ($row = mysqli_fetch_assoc($resultQuery)) {
                $result .= '<file>';
                foreach ($row as $key => $value) {
                    $value = $this->clean($value);
                    $result .= "<$key>$value</$key>";
                }
                $result .= '</file>';
                $size++;
            }
            $result .= "</files>";
        }
        // parse and generate xml cleaning value from special params
        
        $theme = '<xsl:stylesheet version="1.0"
                    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
                    <xsl:output 
                            method="html"
                            encoding="UTF-8" 
                            standalone="yes"
                            indent="yes"/>
                    <xsl:template match="/">
                    <div class="table-responsive-md">
                        <table  class="table table-hover table-bordered">
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Extension</th>
                                </tr>
                                <xsl:for-each select="files/file">
                                    <tr>
                                        <td>
                                            <xsl:value-of select="name"/>
                                        </td>
                                        <td>
                                            <xsl:value-of select="type"/>
                                        </td>
                                        <td>
                                            <xsl:value-of select="extension"/>
                                        </td>
                                    </tr>
                                </xsl:for-each>
                            </table>
                        </div>
                    </xsl:template>
                </xsl:stylesheet>';
        // this is the style of the files table

        $xml = new DOMDocument();
        $xml->loadXML($result); // load xml generated
        $xsl = new DOMDocument;
        $xsl->loadXML($theme); // load style generated
        $proc = new XSLTProcessor();
        $proc->importStyleSheet($xsl); // import style
        $compiled = $proc->transformToXML($xml); // compile style with xml generated
        return array("compiled" => $compiled, "size" => $size);
    }

    public function getExtensions() {
        
        //get extensions with extension and count
        $resultQuery = mysqli_query($this->connect(), "SELECT  file_types.extension , COUNT(files.id) as size FROM `files` JOIN file_types ON files.type_id = file_types.id group by file_types.id");
        $size = 0;
        if (!is_null($resultQuery)) {
            
            $result = '<?xml version="1.0" encoding="UTF-8"?>';
            $result .= "<extensions>";
            
            while ($row = mysqli_fetch_assoc($resultQuery)) {
                $result .= '<extension>';
                foreach ($row as $key => $value) {
                    $value = $this->clean($value);
                    $result .= "<$key>$value</$key>";
                }
                $result .= '</extension>';
                $size++;
            }
            $result .= "</extensions>";
        }
        // parse and generate xml cleaning value from special params

        $theme = '<xsl:stylesheet version="1.0"
         xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
         <xsl:output 
                 method="html"
                 encoding="UTF-8" 
                 standalone="yes"
                 indent="yes"/>
         <xsl:template match="/">
             <div class="table-responsive-md">
                <table  class="table table-hover table-bordered ">
                    <tr>
                        <th>Extension</th>
                        <th>Size</th>
                    </tr>
                    <xsl:for-each select="extensions/extension">
                        <tr>
                            <td>
                                <xsl:value-of select="extension"/>
                            </td>
                            <td>
                                <xsl:value-of select="size"/>
                            </td>
                        </tr>
                    </xsl:for-each>
                </table>
             </div>
         </xsl:template>
     </xsl:stylesheet>';
        // this is the style of the files types table

        $xml = new DOMDocument();
        $xml->loadXML($result); // load xml generated
        $xsl = new DOMDocument;
        $xsl->loadXML($theme); // load style generated
        $proc = new XSLTProcessor();
        $proc->importStyleSheet($xsl); // import style
        $compiled = $proc->transformToXML($xml); // compile style with xml generated
        return array("compiled" => $compiled, "size" => $size);
    }
}

?>