<?php
    require_once("DatabaseController.php");
    require_once("StringMappingController.php");

    class DistrictController extends Globals{
        protected $conn;
        protected $db;
        protected $mapArray;

        public function __construct(){
            parent::__construct();
            $db = new DatabaseController();
            $map = new StringMappingController();
            $this->db = $db;
            $this->conn = $db->conn;
            $this->mapArray = $map->districtMapping;
        }

        public function getAllDistrict(){
            $sql = "SELECT * FROM tbl_district";
            $result = $this->conn->query($sql);
            $data = array();

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

        public function getDistrict(){
            $k = $this->db->escapeString($this->search);
    
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
    
            while($row = $result->fetch_assoc()){
                array_push($data, $row);
            }
            parent::message(true, '0000',"No error found",$data);
        }
    }