<?php

class StringMappingController{
    public $districtMapping;

    public function __construct(){
        $this->districtMapping = Array(
            //Yune Long
            "YuenLong"=> "Yuen Long",
            "Yuen Long"=> "Yuen Long",
            "Yuen Long District"=> "Yuen Long",

            //Yau Tsim Mong
            "Yau Tsim Mong"=>"Yau Tsim Mong",
            "YauTsimMong"=>"Yau Tsim Mong",
            "Yau Tsui Mong District"=>"Yau Tsim Mong",
            "Yau Tsim Mong District"=> "Yau Tsim Mong",


        );

     }


}