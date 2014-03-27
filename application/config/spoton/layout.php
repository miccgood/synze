<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//16:9
//640×360, 854×480, 960×540, 1024×576, 1280×720, 1366×768, 1600×900, 1920×1080, 2048×1152, 2560×1440, 2880x1620, 3840×2160 and 4096×2304

                                    //width : height
$config["resolution"][0]["16:9"] = array("width" => "1920", 
                                      "height" => "1080");
$config["resolution"][1]["16:9"] = array("width" => "1280", 
                                      "height" => "720");

//4:3
//640x480, 800x600, 1024x768, 1152x864, 1280x960, 1400x1050, 1600x1200, 2048x1536, 3200x2400, 4000x3000, 6400x4800 
$config["resolution"][2]["4:3"] = array("width" => "1600", 
                                      "height" => "1200");


//16:10
//1440x900, 1680x1050, 1920x1200, 2560x1600, 3840x2400, 7680x4800
$config["resolution"][3]["16:10"] = array("width" => "1920", 
                                      "height" => "1200");