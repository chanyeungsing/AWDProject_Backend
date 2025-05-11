<?php
require_once("DatabaseController.php");
require_once("BankController.php");
require_once("BranchController.php");
require_once("DistrictController.php");

class ImportController extends Globals{
    protected $url;
    protected $db;
    protected $conn;
    protected $lang;

    public function __construct(){ 
        parent::__construct();
        $db = new DatabaseController();
        $this->conn = $db->getConnection();
    }

    public function handle_POST(){
        $this->importData();
    }

    public function importData(){
        $url = "https://api.hkma.gov.hk/public/bank-svf-info/banks-branch-locator?pagesize=1000&lang=".$this->lang;

        $result = file_get_contents($url);
        
        $data = json_decode($result, true);

        $header = $data["header"];
        
        if($header["err_code"] == "0000"){
            $result = $data["result"];

            $datasize = $result["datasize"];
            $records = $result["records"];

            for($i = 0; $i < $datasize; $i++){
                $record = $records[$i];
                $branchData = array();
                foreach($record as $k=>$r){
                    switch($k){
                        case "district":
                            //District
                            $distrct = new DistrictController();
                            $district_key = $distrct->getDistrictByName($this->lang, $r);

                            if($district_key == 0){
                                $district_key = $distrct->addDistrict($r, $this->lang);
                            }
                            $branchData["district_key"] = $district_key;
                            break;
                        case "bank_name":
                            $bank = new BankController();
                            $bank_key = $bank->getBankByName($this->lang, $r);
                            if($bank_key == 0){
                                $bank_key = $bank->addBank($r, $this->lang);
                            }
                            $branchData["bank_key"] = $bank_key;
                            break;
                        default:
                            $branchData[$k] = $r;
                    }
                }
                $branch = new BranchController();
                $branch->addAllBranch($branchData);
            }
            parent::message(true, "200", "Import success");
        }else{
            if ($this->conn->connect_error) {
                $status = false;
                $err_code = "100";
                $err_msg = "Connection failed: " . $header["err_msg"];;
                parent::message($status, $err_code, $err_msg);
                exit;
            }
        }
    }

}
