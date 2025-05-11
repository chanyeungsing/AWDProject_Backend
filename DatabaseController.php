<?php
class DatabaseController extends Globals{
    public $conn;
    private $globals;

    public function __construct(){ 
        parent::__construct();

        $username = 'awd';
        $password = 'xNT03nfFq7((VzLz';
        $database = 'awd';
        $servername = 'localhost';

        // Create connection
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            $status = false;
            $err_code = "100";
            $err_msg = "Connection failed: " . $conn->connect_error;
            parent::message($status, $err_code, $err_msg);
            exit;
        }
        $this->conn = $conn;
    }

    public function getConnection(){
        return $this->conn;
    }

    public function getError(){
        return $this->conn->error;
    }

    public function escapeString($str){
        if($str != "" || $str != null){
            return $this->conn->real_escape_string($str);
        }
        return $str;
    }



}



