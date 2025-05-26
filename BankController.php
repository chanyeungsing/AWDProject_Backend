<?php
    require_once("DatabaseController.php");
    require_once("StringMappingController.php");

class BankController extends Globals{

    protected $conn;
    protected $db;

    public function __construct(){
        parent::__construct();
        $db = new DatabaseController();
        $map = new StringMappingController();
        $this->db = $db;
        $this->conn = $db->getConnection();
    }

    public function handle_GET($param){
        if($param["key"] == ""){
            $this->getAllBank();
        }else{
            $this->getBank($param["key"]);
        }
    }

    public function handle_POST($param){
    }

    public function handle_PUT(){}
    public function handle_DELETE($param){
        $this->deleteBank($param["key"]);
    }
    
    private function getAllBank(){
        $sql = "SELECT * FROM tbl_bank";
        $result = $this->conn->query($sql);
        $data = array();

        while($row = $result->fetch_assoc()){
            array_push($data, $row);
        }
        parent::message(true, '0000',"No error found",$data);
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

    public function getBank($k){

        if($k == "" || $k == null){
            parent::message(true, '0000',"No Bank input");
            exit;
        }

        $sql = "SELECT * FROM tbl_bank WHERE bank_key = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $k);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = array();

        if($result->num_rows == 0){
            parent::message(true, '0000',"No record found",$data);
            exit;
        }

        while($row = $result->fetch_assoc()){
            array_push($data, $row);
        }
        parent::message(true, '0000',"No error found",$data);
    }

    private function deleteBank($k){
        if($k == "" || $k == null){
            parent::message(true, '0000',"No Bank input");
            exit;
        }

        $sql = "SELECT * FROM tbl_bank WHERE bank_key = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $k);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 0){
            parent::message(false, '0000',"No record found",array());
            exit;
        }

        $sql = "DELETE FROM tbl_bank WHERE bank_key = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $k);
        if($stmt->execute()){
            parent::message(true, '0000',"No error found",array());
        }else{
            parent::message(false, '0000',$stmt->error,array());
        }
    }
}