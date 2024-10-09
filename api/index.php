<?php
/**
 * DO NOT INSERT YOUR CODE HERE! THIS FILE WILL BE REWRITE IN THE NEXT UPDATE
 * USE ONLY FILES THAT BEGIN BY my_
 * @author Ruvenss G. Wilches <ruvenss@gmail.com>
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
header("PoweredBy: LightWeb 3.0.33;");
header("Content-Type: application/json; charset=UTF-8");
define("errors", json_decode(file_get_contents("errors.json"), true));
include_once("xhr_handler.php");
include_once("lightweb.php");
include_once("my_config.php");
include_once("my_functions.php");
include_once("my_init.php");

