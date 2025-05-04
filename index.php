<?php
    require_once("Globals.php");

    $globals = new Globals();

    $lang = (isset($_REQUEST['lang'])? $_REQUEST['lang']:"en");
    $controller = $_REQUEST["controller"];
    $action = $_REQUEST["action"];
    $search = (isset($_REQUEST['search'])? $_REQUEST['search']:null);


    if($controller == ""){
        $status = false;
        $err_code = "404";
        $err_msg = "No Controller input";
        $globals->message($status, $err_code, $err_msg);
        exit;
    }

    if($action == ""){
        $status = false;
        $err_code = "404";
        $err_msg = "No Action input";
        $globals->message($status, $err_code, $err_msg);
        exit;
    }

    $serviceName = ucfirst($controller."Controller");
    $fileName = $serviceName.".php";
    
    if(!file_exists($fileName)){  
        $status = false;
        $err_code = "404";
        $err_msg = "Controller(".$serviceName."Controller) not found!";
        $globals->message($status, $err_code, $err_msg);
        exit;
    }

    require_once($fileName);

    $service = new $serviceName;

    $service->$action();