<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//16:9
//640×360, 854×480, 960×540, 1024×576, 1280×720, 1366×768, 1600×900, 1920×1080, 2048×1152, 2560×1440, 2880x1620, 3840×2160 and 4096×2304

                                    //width : height
//$config["media_url"] = "http://www.spotoninteractive.com/synzes/assets/uploads/media";
//$config["media_url"] = "http://www.spotoninteractive.com/synzes/assets/uploads/media";
//$config["text_url"] = "http://www.spotoninteractive.com/synzes/assets/uploads/text";

$config["media_url"] = "http://localhost/synze/assets/uploads/media";
$config["text_url"] = "http://localhost/synze/assets/uploads/text";

$config["media_path"] = "assets/uploads/media/";
$config["text_path"] = "assets/uploads/text/";

$config["not_allowed_file_name"] = array("/", ".", "-");


$config["extention"]["map"] = array("mpeg" => "mpg");

$config["extention"]["video"] = array("mp4", "mpg", "mpeg");
$config["extention"]["image"] = array("jpg", "gif", "png");
//$config["extention"]["text"] = array("txt");


$config["allowed_formats"] = array_merge($config["extention"]["video"], $config["extention"]["image"]);


//case sensitive นะครับ
$config["media_types"] = array(
    "video"
    , "image"
    , "scrolling text"
    , "Web page"
    , "RSS feed"
    , "Streaming"
    );


                          //"value" 
$config["defaultTextSize"] = "14";

//"display" => คือเอาไว้แสดงผลกับหน้าจอ 
//"value" => ค่าที่เอาไปใช้หรือเก็บลงไฟล์

                      //"display"=>"value"
$config["textSize"] = array("12px" => "12" ,
                            "14px" => "14" ,
                            "16px" => "16" ,
                            "24px" => "18" ,
                            "20px" => "20" ,
                            "22px" => "22" ,
                            "24px" => "24" ,
                            "32px" => "32" ,
                            "36px" => "36" ,
                            "42px" => "42" ,
                            "48px" => "48" ,
                            "60px" => "60" ,
                            "72px" => "72" );

$config["defaultPlaySpeed"] = "400";

$config["playSpeed"] = array(
                            "0x" => "100"
                            ,"0.5x" => "100"
                            ,"1x" => "50"
                            ,"2x" => "30"
                            ,"4x" => "20"
                            ,"8x" => "10"
                            ,"16x" => "1"
                            );
$config["defaultDirection"] = "right";

$config["direction"] = array("up" => "Up", "right" => "Right", "down" => "Down", "left" => "Left");

