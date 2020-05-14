<?php 
class File extends Db { 
	//set file to database
    public function setFile($id, $name, $ext, $type) {
    	
        $fileType= new FileType();
        $type_id = $fileType->findTypeFile($ext, $type); // find typeFile if not exist create and return id
        
        try {
            if (!$this->findFile($id)) { // if the file not exist insert it
                return  mysqli_query($this->connect(), "INSERT INTO files (file_id,name,type_id) VALUES  ('" . $id . "', '" . $name . "', '" . $type_id["id"] . "')");
                // insert file with the parameters
            }
        } catch (Exception $exc) {
            return "cant connect";
        }
        $this->disconnect();

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

    
    public function loadFilesReport() {

        //load reports from query
        $resultQuery = mysqli_query($this->connect(), "SELECT files.*, file_types.type, file_types.extension FROM `files` JOIN file_types ON files.type_id = file_types.id");
        $this->disconnect();

        return $resultQuery;

    }
}