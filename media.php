<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$config["media_url"] = "http://http://www.spotoninteractive.com/synze/assets/uploads/media";
$config["text_url"] = "http://http://www.spotoninteractive.com/synze/assets/uploads/text";


$config["media_path"] = "assets/uploads/media/";
$config["text_path"] = "assets/uploads/text/";

$config["not_allowed_file_name"] = array("/", ".", "-");


$config["extention"]["video"] = array("mp4");
$config["extention"]["image"] = array("jpg", "gif", "png");
//$config["extention"]["text"] = array("txt");


$config["allowed_formats"] = array_merge($config["extention"]["video"], $config["extention"]["image"]);
