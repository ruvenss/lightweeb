<?php
/**
 * DO NOT INSERT YOUR CODE HERE! THIS FILE WILL BE REWRITE IN THE NEXT UPDATE
 * USE ONLY FILES THAT BEGIN BY my_
 * 
 * @author Ruvenss G. Wilches <ruvenss@gmail.com>
 */

$cli = false;
define("publishing", false);
include_once('lightweb/config.php');
if (LIGHTWEB_ENVIRONMENT == 'development') {
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}
include_once('lightweb/functions.php');
include_once('lightweb/render.php');
include_once('lightweb/db.php');
include_once('lightweb/publish.php');
include_once('lightweb/init.php');