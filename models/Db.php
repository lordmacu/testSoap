<?php

class Db {

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

}

?>