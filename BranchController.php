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
        if(array_key_exists("key",$param)){
            $this->getBranch($param["key"]);
        }elseif(array_key_exists("bank_key",$param) && array_key_exists("district_key",$param)){
            $this->getBranchwithDistrictKeyAndBankKey($param);
        }elseif(array_key_exists("bank_key",$param)){
            $this->getBranchwithBankKey($param["bank_key"]);
        }elseif(array_key_exists("district_key",$param)){
            $this->getBranchwithDistrictKey($param["district_key"]);
        }else{
            $this->getAllBranch();
        }
    }

    public function handle_POST(){
        $this->addBranch();
    }

    public function handle_PUT(){
        $this->updateBranch();
    }
    public function handle_DELETE($param){
        $this->deleteBranch($param["key"]);
    }

    private function getAllBranch(){
        $sql = "SELECT 
                br.branch_key, b.bank_key, b.bank_name_en, b.bank_name_tc, b.bank_name_sc, 
                d.district_key, d.district_en, d.district_tc, d.district_sc, 
                br.branch_name, br.address, br.service_hours, br.latitude, br.longitude, br.`barrier-free_access`, br.`barrier-free_access_code`, br.is_active FROM tbl_branch br 
                JOIN tbl_bank b ON br.bank_key = b.bank_key
                JOIN tbl_district d ON br.district_key = d.district_key
                ORDER BY br.branch_key;";

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

    private function getBranchwithDistrictKey($k){
        if($k == "" || $k == null){
            parent::message(true, '0000',"No District was input");
            exit;
        }

        $sql = "SELECT 
                br.branch_key, b.bank_key, b.bank_name_en, b.bank_name_tc, b.bank_name_sc, 
                d.district_key, d.district_en, d.district_tc, d.district_sc, 
                br.branch_name, br.address, br.service_hours, br.latitude, br.longitude, br.`barrier-free_access`, br.`barrier-free_access_code`, br.is_active 
                FROM tbl_branch br 
                JOIN tbl_bank b ON br.bank_key = b.bank_key
                JOIN tbl_district d ON br.district_key = d.district_key
                WHERE br.district_key = ?
                ORDER BY br.branch_key;";

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
        $stmt->close();
    }

    public function getBranchwithBankKey($k){
        if($k == "" || $k == null){
            parent::message(true, '0000',"No Bank was input");
            exit;
        }

        $sql = "SELECT 
                br.branch_key, b.bank_key, b.bank_name_en, b.bank_name_tc, b.bank_name_sc, 
                d.district_key, d.district_en, d.district_tc, d.district_sc, 
                br.branch_name, br.address, br.service_hours, br.latitude, br.longitude, br.`barrier-free_access`, br.`barrier-free_access_code`, br.is_active 
                FROM tbl_branch br 
                JOIN tbl_bank b ON br.bank_key = b.bank_key
                JOIN tbl_district d ON br.district_key = d.district_key
                WHERE br.bank_key = ?
                ORDER BY br.branch_key";

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

        $stmt->close();
    }

    public function getBranchwithDistrictKeyAndBankKey($param){
        $bank_key = $param["bank_key"];
        $district_key = $param["district_key"];

        $sql = "SELECT 
                br.branch_key, b.bank_key, b.bank_name_en, b.bank_name_tc, b.bank_name_sc, 
                d.district_key, d.district_en, d.district_tc, d.district_sc, 
                br.branch_name, br.address, br.service_hours, br.latitude, br.longitude, br.`barrier-free_access`, br.`barrier-free_access_code`, br.is_active 
                FROM tbl_branch br 
                JOIN tbl_bank b ON br.bank_key = b.bank_key
                JOIN tbl_district d ON br.district_key = d.district_key
                WHERE br.bank_key = ? AND d.district_key = ?
                ORDER BY br.branch_key;";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $bank_key, $district_key);
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

        $stmt->close();
    }

    private function getBranch($k){

        if($k == "" || $k == null){
            parent::message(true, '0000',"No Branch key input");
            exit;
        }

        $sql = "SELECT 
                br.branch_key, b.bank_key, b.bank_name_en, b.bank_name_tc, b.bank_name_sc, 
                d.district_key, d.district_en, d.district_tc, d.district_sc, 
                br.branch_name, br.address, br.service_hours, br.latitude, br.longitude, br.`barrier-free_access`, br.`barrier-free_access_code`, br.is_active 
                FROM tbl_branch br 
                JOIN tbl_bank b ON br.bank_key = b.bank_key
                JOIN tbl_district d ON br.district_key = d.district_key
                WHERE br.branch_key = ?
                ORDER BY br.branch_key;";
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

        $stmt->close();
    }

    public function addBranch(){
        parse_str(file_get_contents('php://input'), $_POST);
		
        $data = array();

        foreach($_POST as $k=>$v){
            if($k == 'barrier_free_access'){
                $k = 'barrier-free_access';
            }
            $data[$k] = $v;
        }

        $result = $this->addAllBranch($data);

        if(is_int($result)){
            parent::message(true, '0000',"No error found",array());
        }else{
            parent::message(false, '0000',$result,array());
        }
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

    public function updateBranch(){
        parse_str(file_get_contents('php://input'), $_PUT);

        $fields = array();
        $k = "";

        foreach($_PUT as $key => $value){
            if($key == 'branch_key'){
                $k = $value;
            }else{
                if($key == 'barrier_free_access'){
                    $key = 'barrier-free_access';
                }
                array_push( $fields, "`".$key."` = '".$this->db->escapeString($value)."'" );
            }
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

        $sql = "UPDATE tbl_branch SET ".implode(',', $fields)." WHERE branch_key = ?";
        echo $sql;
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $k);

        if($stmt->execute()){
            parent::message(true, '0000',"No error found",array());
        }else{
            parent::message(false, '0000',$stmt->error,array());
        }
        $stmt->close();
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
        $stmt->close();
    }
}