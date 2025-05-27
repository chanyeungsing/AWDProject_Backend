<?php
    require_once("DatabaseController.php");
    require_once("StringMappingController.php");

    class DistrictController extends Globals{
        protected $conn;
        protected $db;
        protected $mapArray;
        protected $district_key;

        public function __construct($district_key = null){
            parent::__construct();

            $this->db = new DatabaseController();
            $map = new StringMappingController();

            $this->conn = $this->db->getConnection();
            $this->mapArray = $map->districtMapping;


            $this->district_key = $district_key;
        }

        public function handle_GET($param){

            if(!array_key_exists("key",$param)){
                $this->getAllDistrict();
            }else{
                $this->getDistrict($param["key"]);
            }
        }

        public function handle_POST($param){
            $this->addSingleDistrict();
        }

        public function handle_PUT(){
            $this->updateDistrict();
        }
        public function handle_DELETE($param){
            $this->deleteDistrict($param["key"]);
        }

        public function getAllDistrict(){
            $sql = "SELECT * FROM tbl_district";
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

        public function getDistrictByName($lang, $name){
            //Mapping the value
            $value = $this->mapArray[$name];
            $sql = "SELECT * FROM tbl_district WHERE district_".$lang." = '".$value."'";
            
            $result = $this->conn->query($sql);

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                return $row['district_key'];
            }else{
                return 0;
            }
        }

        public function addSingleDistrict(){
            $name = $_POST['district_name'];
            $lang = "en";
    
            $sql = "INSERT INTO tbl_district (district_".$lang.") VALUES (?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $name);
    
            if($stmt->execute()){
                parent::message(true, '0000',"No error found",array());
            }else{
                parent::message(false, '0000',$stmt->error,array());
            }
        }

        public function addDistrict($name, $lang){
            //Mapping the value
            $value = $this->mapArray[$name];
            $sql = "INSERT INTO tbl_district (district_".$lang.") VALUES ('".$value."')";

            if($this->conn->query($sql)){
                return $this->conn->insert_id;
            }else{
                return $this->db->getError();
            }
        }

        public function getDistrict($k){
    
            if($k == "" || $k == null){
                parent::message(true, '0000',"No District input");
                exit;
            }
    
            $sql = "SELECT * FROM tbl_district WHERE district_key = ?";
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

        public function updateDistrict(){
            parse_str(file_get_contents('php://input'), $_PUT);
            $k = $_PUT["key"];
            $district_en = $_PUT["district_en"];

            $sql = "SELECT * FROM tbl_district WHERE district_key = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $k);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows == 0){
                parent::message(false, '0000',"No record found",array());
                exit;
            }

            $sql = "UPDATE tbl_district SET district_en = ? WHERE district_key = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $district_en, $k);
            
            if($stmt->execute()){
                parent::message(true, '0000',"No error found",array());
            }else{
                parent::message(false, '0000',$stmt->error,array());
            }
            $stmt->close();
        }

        public function deleteDistrict($k){
            if($k == "" || $k == null){
                parent::message(true, '0000',"No District input");
                exit;
            }

            $sql = "SELECT * FROM tbl_district WHERE district_key = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $k);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows == 0){
                parent::message(false, '0000',"No record found",array());
                exit;
            }
    
            $sql = "DELETE FROM tbl_district WHERE district_key = ?";
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