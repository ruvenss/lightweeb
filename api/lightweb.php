<?php
/**
 * LightWeb API Handler 
 * This file won't be copied to production
 * @author Ruvenss G. Wilches <ruvenss@gmail.com>
 */
if (file_exists("../lightweb/config.php")) {
    include_once("../lightweb/config.php");
    if (file_exists("../lightweb/pages/tree.json")) {
        define("tree", json_decode(file_get_contents("../lightweb/pages/tree.json")));
    } else {
        define("tree", []);
    }
    $DataInputRaw = file_get_contents("php://input");
    if (json_validator($DataInputRaw)) {
        $Bearer = getAuthorizationHeader();
        define("DataInput", json_decode($DataInputRaw, TRUE));
        define("request_type", $_SERVER['REQUEST_METHOD']);
        if (isset(DataInput['a'])) {
            $ThisFunction = DataInput['a'];
            if (function_exists($ThisFunction)) {
                $ThisFunction();
            }
        }
    }
} else {
    die('{"answer":false,"error":"LightWeb API Key missing in the server"}');
}
function create_page()
{
    if (isset(DataInput['tree']) && isset(DataInput['id'])) {
        foreach (tree as $page) {
            if ($page == DataInput['id']) {
                response(false, [], 0, "Page already exist.");
                break;
            }
        }
        response(true, []);
    } else {
        response(false, [], 0, "Tree is not defined");
    }
}
function getAuthorizationHeader()
{
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    if (!empty($headers)) {
        $Bearer = trim(str_replace("Bearer", "", $headers));
        return($Bearer);
    } else {
        return null;
    }
}
function json_validator($data)
{
    if (!empty($data)) {
        return is_string($data) &&
            is_array(json_decode($data, true)) ? true : false;
    }
    return false;
}