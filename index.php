<?php
    require_once("Globals.php");

    //[GET] http://localhost/index.php?controller=(controller)
    //[GET] http://localhost/index.php?controller=(controller)&key=(key)


    $globals = new Globals();

    $lang = (isset($_REQUEST['lang'])? $_REQUEST['lang']:"en");
    $controller = $_REQUEST["controller"];
    $search = (isset($_REQUEST['search'])? $_REQUEST['search']:null);

    $method = $_SERVER['REQUEST_METHOD'];
    $param = $_SERVER['QUERY_STRING'];

    if($controller == ""){
        $status = false;
        $err_code = "404";
        $err_msg = "No Controller input";
        $globals->message($status, $err_code, $err_msg);
        exit;
    }

    if($method == ""){
        $status = false;
        $err_code = "404";
        $err_msg = "No Method input";
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
    $action = "handle_".$method;

    if(method_exists($service, $action)){
        parse_str($param, $queryArray);
        
        $service->$action($queryArray);
    }else{
        $status = false;
        $err_code = "404";
        $err_msg = "Action (".$action.") not found!";
        $globals->message($status, $err_code, $err_msg);
        exit;
    }