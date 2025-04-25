<?php
    require_once("DatabaseController.php");
    require_once("StringMappingController.php");

    class DistrictController extends Globals{
        private $district;
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
            global $conn;

            $sql = "SELECT * FROM tbl_district";
            $result = $conn->query($sql);

            print_r($result);
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
    }