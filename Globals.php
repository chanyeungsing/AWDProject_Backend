<?php
header("Content-Type: application/json");

class Globals{
    protected $output;
    protected $lang;
    protected $controller;
    protected $search;

    public function __construct(){ 
        $this->lang = (isset($_REQUEST['lang'])? $_REQUEST['lang']:"en");
        $this->controller = $_REQUEST["controller"];
        $this->search = (isset($_REQUEST['search'])? $_REQUEST['search']:null);
    }

    /**
    {
        "header": {
            "success": false,
            "err_code": "1002",
            "err_msg": "Invalid input value: Parameter lang is missing or invalid input (en|tc|sc)"
        }
    }
    */
    public function message($status, $code, $message, $result = "{}"){
        //setting the JSON header
        $header["success"] = $status;
        $header["err_code"] = $code;
        $header["err_msg"] = $message;
        $header["result"] = $result;

        $this->output["header"] = $header;
        
        echo json_encode($this->output);
    }
}