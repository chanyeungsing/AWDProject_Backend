<?php
    require_once("DatabaseController.php");
class BankController extends Globals{

    protected $conn;
    protected $db;

    public function __construct(){
        parent::__construct();
        $db = new DatabaseController();
        $this->db = $db;
        $this->conn = $db->conn;
    }
    
    function getAllBank(){
        global $conn;

        $sql = "SELECT * FROM tbl_bank";
        $result = $conn->query($sql);

        print_r($result);
    }

    public function getBankByName($lang, $name){
        $sql = "SELECT * FROM tbl_bank WHERE bank_name_".$lang." = '".$name."'";
        
        $result = $this->conn->query($sql);

        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            return $row['bank_key'];
        }else{
            return 0;
        }
    }

    public function addBank($name, $lang){
        $sql = "INSERT INTO tbl_bank (bank_name_".$lang.") VALUES ('".$name."')";      
        if($this->conn->query($sql)){
            return $this->conn->insert_id;
        }else{
            return $this->db->getError();
        }
    }
}