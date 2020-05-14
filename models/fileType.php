<?php 
 
class FileType extends Db{ 
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

    public function getExtensions() {
        //get extensions with extension and count
        $resultQuery = mysqli_query($this->connect(), "SELECT  file_types.extension , COUNT(files.id) as size FROM `files` JOIN file_types ON files.type_id = file_types.id group by file_types.id");
       
        $this->disconnect();

        return $resultQuery;
    }
}