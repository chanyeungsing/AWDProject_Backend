<?php
    require_once("DatabaseController.php");
    require_once("StringMappingController.php");
    
class BranchController extends Globals{
    protected $conn;
    protected $db;
    protected $lang;
    protected $search;

    public function __construct(){
        parent::__construct();
        $db = new DatabaseController();
        $map = new StringMappingController();
        $this->db = $db;
        $this->conn = $db->getConnection();
    }

    public function handle_GET($param){
        if($param["key"] == ""){
            $this->getAllBranch();
        }else{
            $this->getBranch($param["key"]);
        }
    }

    public function handle_POST($param){
    }

    public function handle_PUT(){}
    public function handle_DELETE($param){
        $this->deleteBranch($param["key"]);
    }

    private function getAllBranch(){
        $sql = "SELECT 
                b.bank_name_en, b.bank_name_tc, b.bank_name_sc, 
                d.district_en, d.district_tc, d.district_sc, 
                br.branch_name, br.address, br.service_hours, br.latitude, br.longitude, br.`barrier-free_access`, br.`barrier-free_access_code` FROM tbl_branch br 
                JOIN tbl_bank b ON br.bank_key = b.bank_key
                JOIN tbl_district d ON br.district_key = d.district_key;";
        $result = $this->conn->query($sql);
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

    private function getBranchwithDistrictKey(){
        $k = $this->db->escapeString($this->search);

        if($k == "" || $k == null){
            parent::message(true, '0000',"No District was input");
            exit;
        }

        $sql = "SELECT 
                b.bank_name_en, b.bank_name_tc, b.bank_name_sc, 
                d.district_en, d.district_tc, d.district_sc, 
                br.branch_name, br.address, br.service_hours, br.latitude, br.longitude, br.`barrier-free_access`, br.`barrier-free_access_code` 
                FROM tbl_branch br 
                JOIN tbl_bank b ON br.bank_key = b.bank_key
                JOIN tbl_district d ON br.district_key = d.district_key
                WHERE br.district_key = ?";
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

    public function getBranchwithBankKey($k){

        if($k == "" || $k == null){
            parent::message(true, '0000',"No Bank was input");
            exit;
        }

        $sql = "SELECT 
                b.bank_name_en, b.bank_name_tc, b.bank_name_sc, 
                d.district_en, d.district_tc, d.district_sc, 
                br.branch_name, br.address, br.service_hours, br.latitude, br.longitude, br.`barrier-free_access`, br.`barrier-free_access_code` 
                FROM tbl_branch br 
                JOIN tbl_bank b ON br.bank_key = b.bank_key
                JOIN tbl_district d ON br.district_key = d.district_key
                WHERE br.bank_key = ?";
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

    private function getBranch($k){

        if($k == "" || $k == null){
            parent::message(true, '0000',"No Branch key input");
            exit;
        }

        $sql = "SELECT 
                b.bank_name_en, b.bank_name_tc, b.bank_name_sc, 
                d.district_en, d.district_tc, d.district_sc, 
                br.branch_name, br.address, br.service_hours, br.latitude, br.longitude, br.`barrier-free_access`, br.`barrier-free_access_code` 
                FROM tbl_branch br 
                JOIN tbl_bank b ON br.bank_key = b.bank_key
                JOIN tbl_district d ON br.district_key = d.district_key
                WHERE br.branch_key = ?";
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

    public function addAllBranch($data){
        $query = "INSERT INTO tbl_branch";
        $fields = array();
        $values = array();

        foreach($data as $key => $value){
            array_push( $fields, "`".$key."`" );
            array_push( $values, "'".$this->db->escapeString($value)."'" );
        }
        $query .= "(".implode(',', $fields).") VALUES (".implode(',', $values).")";

        if($this->conn->query($query)){
            return $this->conn->insert_id;
        }else{
            return $this->db->getError();
        }
    }

    private function deleteBranch($k){
        if($k == "" || $k == null){
            parent::message(true, '0000',"No Branch input");
            exit;
        }

        $sql = "SELECT * FROM tbl_branch WHERE branch_key = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $k);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 0){
            parent::message(false, '0000',"No record found",array());
            exit;
        }

        $sql = "DELETE FROM tbl_branch WHERE branch_key = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $k);
        if($stmt->execute()){
            parent::message(true, '0000',"No error found",array());
        }else{
            parent::message(false, '0000',$stmt->error,array());
        }
    }
}