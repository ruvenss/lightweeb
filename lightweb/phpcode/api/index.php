<?php
header("PoweredBy: LightWeb 3.0;");
header("Content-Type: application/json; charset=UTF-8");
define("errors", json_decode(file_get_contents("errors.json"), true));
include_once("config.php");
include_once("db.php");
include_once("init.php");
include_once("v1/index.php");
include_once("xhr_handler.php");
include_once("my_config.php");
include_once("my_functions.php");
include_once("my_init.php");
