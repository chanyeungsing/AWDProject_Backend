<?php
    require_once("Globals.php");

    $globals = new Globals();

    $lang = $_REQUEST['lang'];
    $controller = $_REQUEST["controller"];
    //$action = $_REQUEST["action"];
    //$data = $_REQUEST["data"];

    if($lang == ""){
        $lang = "en";
    }

    if($controller == ""){
        $status = false;
        $err_code = "404";
        $err_msg = "Not Controller input";
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
    $service->importData("https://api.hkma.gov.hk/public/bank-svf-info/banks-branch-locator?lang=".$lang, $lang);
