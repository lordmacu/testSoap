<?php 
class Helpers{
	 public function clean($string) {
        //clean special characters
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^a-z0-9\_\-\.]/i', '', $string); // Removes special chars.
    }


    public function buildTheme($fields,$source){
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
                                <tr>';
                                foreach ($fields as $field) {
                                     $theme .= '<th>'.ucfirst($field).'</th>';
                                }

                                $theme .= '</tr><xsl:for-each select="'.$source.'"><tr>';

                                foreach ($fields as $field) {
                                    $theme .= '<td><xsl:value-of select="'.$field.'"/></td>';
                                }

                                $theme .= '</tr>
                                </xsl:for-each>
                            </table>
                        </div>
                    </xsl:template>
                </xsl:stylesheet>';

        return $theme;
    }

    public function buildXml($resultQuery,$singular,$plural){
        $size = 0;
        $result="";
        if (!is_null($resultQuery)) {
            $result = '<?xml version="1.0" encoding="UTF-8"?>';
                $result .= "<".$plural.">";
                while ($row = mysqli_fetch_assoc($resultQuery)) {
                    $result .= '<'.$singular.'>';
                    foreach ($row as $key => $value) {
                        $value = $this->clean($value);
                        $result .= "<$key>$value</$key>";
                    }
                    $result .= '</'.$singular.'>';
                    $size++;
                }
                $result .= "</".$plural.">";
            }
        return array("xml"=>$result,"size"=>$size);
    }

    public function getXmlResult($result,$theme,$size){
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