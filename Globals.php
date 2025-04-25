<?php

class Globals{
    protected $output;
    
    public function __construct(){ 
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
    public function message($status, $code, $message){
        global $output;

        header('Content-Type: application/json; charset=utf-8');
        //setting the JSON header
        $header["success"] = $status;
        $header["err_code"] = $code;
        $header["err_msg"] = $message;

        $output["header"] = $header;
        
        echo json_encode($output);
    }
}