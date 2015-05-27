<?php 
/*
 * Todo: 读取json配表
 * Author: Linwencai
 */

function object_array($array)
{
   if(is_object($array))
   {
    $array = (array)$array;
   }
   if(is_array($array))
   {
    foreach($array as $key=>$value)
    {
     $array[$key] = object_array($value);
    }
   }
   return $array;
}

if (!isset($config_array))
{
	$filename = "./config.json";
	$config_string = file_get_contents($filename);
	$config_array = object_array(json_decode($config_string));
}
//print_r($config_array);
//echo json_encode($config_array);
?>