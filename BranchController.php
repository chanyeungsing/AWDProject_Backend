<?php
    require_once("DatabaseController.php");
class BranchController extends Globals{
    protected $conn;
    protected $db;

    public function __construct(){
        parent::__construct();
        $db = new DatabaseController();
        $map = new StringMappingController();
        $this->db = $db;
        $this->conn = $db->conn;
    }

    public function getAllBranch(){
        global $conn;

        $sql = "SELECT * FROM tbl_branch";
        $result = $conn->query($sql);

        print_r($result);
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
}