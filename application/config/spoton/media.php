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

$config["defaultTextSize"] = "14";

$config["textSize"] = array("12","14","16","18","20","22","24","32","36","42","48","60","72");

$config["defaultPlaySpeed"] = "400";

$config["playSpeed"] = array(
                            "0.5x" => "1000"
                            ,"1x" => "400"
                            ,"2x" => "300"
                            ,"4x" => "200"
                            ,"8x" => "100"
                            ,"16x" => "10"
                            );
